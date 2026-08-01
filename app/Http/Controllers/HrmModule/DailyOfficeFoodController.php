<?php

namespace App\Http\Controllers\HrmModule;

use App\Http\Controllers\Controller;
use App\Models\DailyOfficeFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Laravel port of DAILLY_OOFICE_FOOD.fmb ("Daily Food Update").
 *
 * Trigger -> method map:
 *   WHEN-NEW-FORM-INSTANCE   -> index()      (page load)
 *   WHEN-BUTTON-PRESSED PB_LOAD -> load()    (AJAX)
 *   WHEN-BUTTON-PRESSED PB_SAVE -> save()    (AJAX, mirrors commit_form)
 *   POST-FORMS-COMMIT       -> save() response message ("Record(s) successfully Saved.")
 *   WHEN-BUTTON-PRESSED PB_EXIT -> handled client-side (redirect back / close tab)
 *
 * Note: WHEN-MOUSE-DOUBLECLICK called LOV_ON_CLICK(:system.cursor_item), a
 * routine from the shared HRM_PL library that isn't part of this .fmb. That
 * generic LOV pop-up has no equivalent here since the library source wasn't
 * supplied — EMPNO/NEW_EMPNO are read-only in this UI instead.
 */
class DailyOfficeFoodController extends Controller
{
    /**
     * Oracle/PDO_OCI can hand back column names in upper, lower, or mixed
     * case depending on the connection's PDO::ATTR_CASE setting — this is
     * almost certainly why ATT_DATE (and friends) sometimes came back empty
     * in the UI: the JS was reading `row.ATT_DATE` but the JSON key was
     * actually `att_date`. This normalizes every row to a fixed key set
     * before it ever reaches the response, and forces ATT_DATE through
     * Carbon so it's always a plain 'Y-m-d' string regardless of whether it
     * arrived as a string, a Carbon instance, or an OCI date object.
     *
     * IMPORTANT: $row can be either an Eloquent model (the "saved" path,
     * from DailyOfficeFood::get()) or a plain stdClass (the "fresh" path,
     * from DB::table()->get()). A raw `(array) $row` cast only works for
     * stdClass — casting an Eloquent model that way doesn't return its
     * columns, it returns PHP's mangled internal property names (things
     * like "\0*\0attributes") because the model's real properties are
     * protected. That mismatch was silently breaking every "saved" row
     * (ATT_DATE and the rest) while the "fresh" query path looked fine.
     * toArray() is required for models; stdClass still needs (array).
     */
    private function normalizeRow($row): array
    {
        if ($row instanceof \Illuminate\Database\Eloquent\Model) {
            $arr = $row->toArray();
        } else {
            $arr = (array) $row;
        }

        $lookup = [];
        foreach ($arr as $key => $value) {
            $lookup[strtoupper($key)] = $value;
        }

        $rawDate = $lookup['ATT_DATE'] ?? null;
        $attDate = null;
        if ($rawDate) {
            try {
                $attDate = Carbon::parse($rawDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $attDate = $rawDate;
            }
        }

        return [
            'EMPNO'     => $lookup['EMPNO'] ?? null,
            'NEW_EMPNO' => $lookup['NEW_EMPNO'] ?? null,
            'EMP_NAME'  => $lookup['EMP_NAME'] ?? null,
            'ATT_DATE'  => $attDate,
            'STATUS'    => $lookup['STATUS'] ?? null,
            'IS_FOOD'   => $lookup['IS_FOOD'] ?? null,
        ];
    }

    /**
     * GET /hrm/daily-office-food
     * Renders the page shell. The grid itself is populated via load().
     */
    public function index()
    {
        return view('hrm.daily-office-food.index');
    }

    /**
     * POST /hrm/daily-office-food/load
     * Mirrors the PB_LOAD trigger:
     *  - if DAILLY_OOFICE_FOOD already has rows for the chosen date, return those (saved state).
     *  - otherwise, build a fresh candidate list from EMP_PERSONAL + ATTENDANCE_DETAILS
     *    for employees flagged OFFICE_FOOD = 'Y' and STATUS = 'Active', defaulting IS_FOOD
     *    to 'Y' when the attendance status was 'P' (present), else 'N'.
     */
    public function load(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $foodDate = $request->input('food_date');

        $existingCount = DailyOfficeFood::where('ATT_DATE', $foodDate)->count();

        if ($existingCount > 0) {
            $rows = DailyOfficeFood::where('ATT_DATE', $foodDate)
                ->orderBy('NEW_EMPNO')
                ->get([
                    'EMPNO', 'NEW_EMPNO', 'EMP_NAME', 'ATT_DATE', 'STATUS', 'IS_FOOD',
                ])
                ->map(fn ($row) => $this->normalizeRow($row));

            return response()->json([
                'source' => 'saved',
                'food_date' => $foodDate,
                'rows' => $rows,
            ]);
        }

        // Equivalent to cursor c1 in the original PL/SQL block.
        $rows = DB::connection('oracle')
            ->table('EMP_PERSONAL as A')
            ->join('ATTENDANCE_DETAILS as B', 'A.EMPNO', '=', 'B.EMPNO')
            ->where('B.ATT_DATE', $foodDate)
            ->where('A.OFFICE_FOOD', 'Y')
            ->where('A.STATUS', 'Active')
            ->orderBy('A.NEW_EMPNO')
            ->selectRaw(
                "A.EMPNO as EMPNO,
                 A.NEW_EMPNO as NEW_EMPNO,
                 A.FIRST_NAME || ' ' || A.MIDDLE_NAME || ' ' || A.LAST_NAME as EMP_NAME,
                 B.STATUS2 as STATUS,
                 CASE WHEN B.STATUS2 = 'P' THEN 'Y' ELSE 'N' END as IS_FOOD"
            )
            ->get()
            ->map(function ($row) use ($foodDate) {
                $row->ATT_DATE = $foodDate;
                return $this->normalizeRow($row);
            });

        return response()->json([
            'source' => 'fresh',
            'food_date' => $foodDate,
            'rows' => $rows,
        ]);
    }

    /**
     * POST /hrm/daily-office-food/save
     * Mirrors PB_SAVE -> commit_form, followed by the POST-FORMS-COMMIT alert.
     *
     * The original block had no surrogate key, so Forms tracked insert/update
     * per row internally. Here we replace the full day's rows in one
     * transaction: delete whatever exists for the date, then re-insert the
     * grid exactly as submitted. This produces the same end state as the
     * form's commit for a given date.
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_date' => 'required|date_format:Y-m-d',
            'rows' => 'required|array|min:1',
            'rows.*.EMPNO' => 'required',
            'rows.*.NEW_EMPNO' => 'nullable',
            'rows.*.EMP_NAME' => 'nullable|string',
            'rows.*.STATUS' => 'nullable|string',
            'rows.*.IS_FOOD' => 'required|in:Y,N',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $foodDate = $request->input('food_date');
        $rows = $request->input('rows');

        DB::connection('oracle')->transaction(function () use ($foodDate, $rows) {
            DailyOfficeFood::where('ATT_DATE', $foodDate)->delete();

            $now = Carbon::parse($foodDate)->format('Y-m-d');

            foreach ($rows as $row) {
                DailyOfficeFood::create([
                    'EMPNO' => $row['EMPNO'],
                    'NEW_EMPNO' => $row['NEW_EMPNO'] ?? null,
                    'EMP_NAME' => $row['EMP_NAME'] ?? null,
                    'ATT_DATE' => $now,
                    'STATUS' => $row['STATUS'] ?? null,
                    'IS_FOOD' => $row['IS_FOOD'],
                ]);
            }
        });

        // POST-FORMS-COMMIT alert text, reused verbatim.
        return response()->json([
            'message' => 'Record(s) successfully Saved.',
        ]);
    }

    /**
     * POST /hrm/daily-office-food/delete-preview
     * Returns how many DAILLY_OOFICE_FOOD rows fall inside [date_from, date_to]
     * so the confirm modal can show a count before anything is removed.
     * Not part of the original form — added for the bulk delete-by-range action.
     */
    public function deletePreview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date_format:Y-m-d',
            'date_to'   => 'required|date_format:Y-m-d|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $count = DailyOfficeFood::whereBetween('ATT_DATE', [
            $request->input('date_from'),
            $request->input('date_to'),
        ])->count();

        return response()->json([
            'count' => $count,
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ]);
    }

    /**
     * DELETE /hrm/daily-office-food
     * Deletes all DAILLY_OOFICE_FOOD rows with ATT_DATE between date_from and
     * date_to (inclusive). Used by the Delete modal after the user confirms
     * the count returned by deletePreview().
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date_format:Y-m-d',
            'date_to'   => 'required|date_format:Y-m-d|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $deleted = DB::connection('oracle')->transaction(function () use ($dateFrom, $dateTo) {
            return DailyOfficeFood::whereBetween('ATT_DATE', [$dateFrom, $dateTo])->delete();
        });

        return response()->json([
            'message' => $deleted > 0
                ? "{$deleted} record(s) successfully deleted."
                : 'No matching records were found to delete.',
            'deleted' => $deleted,
        ]);
    }
}