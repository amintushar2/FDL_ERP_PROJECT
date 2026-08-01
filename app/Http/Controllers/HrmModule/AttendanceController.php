<?php

namespace App\Http\Controllers\HrmModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDO;
use Rats\Zkteco\Lib\ZKTeco;

class AttendanceController extends Controller
{
    /* ══════════════════════════════════════════════════════════════════
       INDEX – Blade View
    ══════════════════════════════════════════════════════════════════ */
    public function index()
    {
        $companies = DB::table('company_profile as cp')
            ->join('company_permission as cpm', 'cp.company_id', '=', 'cpm.company_id')
            ->select('cp.company_id', 'cp.company_name')
            ->orderBy('cp.company_name')
            ->distinct()
            ->get();

        return view('hrm.attd.attendance', compact('companies'));
    }

    /* ══════════════════════════════════════════════════════════════════
       DEVICES – CRUD
       Table: attd_machine_list (machine_no, m_ip, device_name)
    ══════════════════════════════════════════════════════════════════ */
    public function getDevices(): JsonResponse
    {
        $devices = DB::table('attd_machine_list')
            ->orderBy('machine_no')
            ->get();

        return response()->json(['data' => $devices]);
    }

    public function storeDevice(Request $request): JsonResponse
    {
        $request->validate([
            'machine_no'  => 'required|integer|unique:attd_machine_list,machine_no',
            'm_ip'        => 'required|string|max:20',
            'device_name' => 'required|string|max:20',
        ]);

        DB::table('attd_machine_list')->insert([
            'machine_no'  => $request->machine_no,
            'm_ip'        => $request->m_ip,
            'device_name' => $request->device_name,
        ]);

        return response()->json(['message' => 'Device saved successfully.'], 201);
    }

    public function updateDevice(Request $request, int $machineNo): JsonResponse
    {
        $request->validate([
            'm_ip'        => 'required|string|max:20',
            'device_name' => 'required|string|max:20',
        ]);

        DB::table('attd_machine_list')
            ->where('machine_no', $machineNo)
            ->update([
                'm_ip'        => $request->m_ip,
                'device_name' => $request->device_name,
            ]);

        return response()->json(['message' => 'Device updated successfully.']);
    }

    public function destroyDevice(int $machineNo): JsonResponse
    {
        DB::table('attd_machine_list')->where('machine_no', $machineNo)->delete();
        return response()->json(['message' => 'Device deleted.']);
    }

    /* ══════════════════════════════════════════════════════════════════
       PING DEVICE – Test Connection (socket on port 4370)
    ══════════════════════════════════════════════════════════════════ */
    public function pingDevice(Request $request): JsonResponse
    {
        $ip     = $request->ip_address ?? $request->ip;
        $online = false;

        try {
            $socket = @fsockopen($ip, 4370, $errno, $errstr, 3);
            if ($socket) {
                fclose($socket);
                $online = true;
            }
        } catch (\Exception $e) {
            $online = false;
        }

        return response()->json(['online' => $online, 'ip' => $ip]);
    }

    /* ══════════════════════════════════════════════════════════════════
       FETCH FROM DEVICE
       Library: composer require rats/zkteco
       Reads logs → inserts into ATND_RAW (skips duplicates)
       ATND_RAW: MACH_NO, CARD_NO, ATND_DATE, ATND_TIME, NAME, ATND_SHIFT
    ══════════════════════════════════════════════════════════════════ */
    public function fetchFromDevice(Request $request): JsonResponse
    {
        $request->validate([
            'machine_no' => 'required',
            'ip'         => 'required|string',
        ]);

        $ip        = $request->ip;
        $machineNo = str_pad($request->machine_no, 3, '0', STR_PAD_LEFT);
        $dateFrom  = $request->date_from ? \Carbon\Carbon::parse($request->date_from) : null;
        $dateTo    = $request->date_to   ? \Carbon\Carbon::parse($request->date_to)   : null;

        try {
            $zk = new ZKTeco($ip, 4370);

            if (!$zk->connect()) {
                return response()->json([
                    'message' => "Cannot connect to device at {$ip}",
                ], 422);
            }

            $zk->disableDevice();

            // Get attendance logs from device
            $logs = $zk->getAttendance();
            // Get users for name mapping (rats/zkteco uses getUser())
            $users = collect($zk->getUser())->keyBy('userid');

            $zk->enableDevice();
            $zk->disconnect();

            // Filter by date range if provided
            if ($dateFrom || $dateTo) {
                $logs = array_filter($logs, function ($log) use ($dateFrom, $dateTo) {
                    try {
                        $logDate = \Carbon\Carbon::parse($log['timestamp']);
                        if ($dateFrom && $logDate->lt($dateFrom))            return false;
                        if ($dateTo   && $logDate->gt($dateTo->endOfDay())) return false;
                        return true;
                    } catch (\Exception $e) {
                        \Log::error('ZKTeco date filter error: ' . $e->getMessage());
                        return true;
                    }
                });
            }

            $fetched  = count($logs);
            $inserted = 0;

            foreach ($logs as $log) {
                try {
                    $ts       = \Carbon\Carbon::parse($log['timestamp']);
                    $atndDate = $ts->format('Y-m-d');
                    $atndTime = $ts->format('His');  // HHMMSS e.g. 164131

                    // CARD_NO = fingerprint/face UID
                    $cardNo = $log['id'] ?? '';

                    // Name from user list (keyed by userid)
                    $name = $users[$log['id'] ?? '']['name'] ?? null;
                    if ($name) $name = substr(trim($name), 0, 20);

                    // Skip duplicates: same machine + card + date + time
                    $exists = DB::table('atnd_raw')
                        ->where('mach_no',  $machineNo)
                        ->where('card_no',  $cardNo)
                        ->whereDate('atnd_date', $atndDate)
                        ->where('atnd_time', $atndTime)
                        ->exists();

                    if (!$exists) {
                        DB::table('atnd_raw')->insert([
                            'mach_no'    => $machineNo,
                            'card_no'    => $cardNo,
                            'atnd_date'  => $atndDate,
                            'atnd_time'  => $atndTime,
                            'name'       => $name,
                            'atnd_shift' => null,
                        ]);
                        $inserted++;
                    }
                } catch (\Exception $e) {
                    \Log::error('ZKTeco insert error: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'message'  => "Fetched {$fetched} records, inserted {$inserted} new.",
                'fetched'  => $fetched,
                'inserted' => $inserted,
            ]);

        } catch (\Exception $e) {
            \Log::error('ZKTeco fetch error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       GET ATTENDANCE DATA  (for table display)
       Table: ATND_RAW
    ══════════════════════════════════════════════════════════════════ */
    public function getData(Request $request): JsonResponse
    {
        $query = DB::table('atnd_raw')
            ->select(
                'mach_no',
                'card_no',
                DB::raw("TO_CHAR(atnd_date, 'DD-Mon-YYYY') as atnd_date"),
                'atnd_time',
                'name',
                'atnd_shift'
            )
            ->orderBy('atnd_date', 'desc')
            ->orderBy('atnd_time', 'desc');

        if ($request->filled('machine_no')) {
            $query->where('mach_no', str_pad($request->machine_no, 3, '0', STR_PAD_LEFT));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('atnd_date', '>=', \Carbon\Carbon::parse($request->date_from)->format('Y-m-d'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('atnd_date', '<=', \Carbon\Carbon::parse($request->date_to)->format('Y-m-d'));
        }
        if ($request->filled('card_no')) {
            $query->where('card_no', 'like', '%' . $request->card_no . '%');
        }

        $data = $query->limit(5000)->get();

        return response()->json(['data' => $data]);
    }

    /* ══════════════════════════════════════════════════════════════════
       DOWNLOAD CSV / TXT
       Same columns as ATND_RAW, comma-separated
       MACH_NO,CARD_NO,ATND_DATE,ATND_TIME,NAME,ATND_SHIFT
    ══════════════════════════════════════════════════════════════════ */
    public function downloadData(Request $request)
    {
        $format = $request->format ?? 'csv'; // csv or txt

        $query = DB::table('atnd_raw')
            ->select(
                'mach_no',
                'card_no',
                DB::raw("TO_CHAR(atnd_date, 'YYYY-MM-DD') as atnd_date"),
                'atnd_time',
                'name',
                'atnd_shift'
            )
            ->orderBy('atnd_date', 'desc')
            ->orderBy('atnd_time', 'desc');

        if ($request->filled('machine_no')) {
            $query->where('mach_no', str_pad($request->machine_no, 3, '0', STR_PAD_LEFT));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('atnd_date', '>=', \Carbon\Carbon::parse($request->date_from)->format('Y-m-d'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('atnd_date', '<=', \Carbon\Carbon::parse($request->date_to)->format('Y-m-d'));
        }
        if ($request->filled('card_no')) {
            $query->where('card_no', 'like', '%' . $request->card_no . '%');
        }

        $rows = $query->get();

        // Build CSV content
        $lines   = [];
        // Header row – exact DB column names
        // $lines[] = 'MACH_NO,CARD_NO,ATND_DATE,ATND_TIME,NAME,ATND_SHIFT';

        foreach ($rows as $row) {
            $lines[] = implode(':', [
                $row->mach_no    ?? '',
                $row->card_no    ?? '',
                Carbon::parse($row->atnd_date)->format('ymd') ?? '',
                // $row->atnd_date  ?? '',
                $row->atnd_time  ?? '',
           
            ]);
        }

        $content   = implode("\n", $lines);
        $ext       = $format === 'txt' ? 'txt' : 'csv';
        $filename  = 'RAWDATA_' . date('Ymd_His') . '.' . $ext;
        $mime      = $format === 'txt' ? 'text/plain' : 'text/csv';

        return response($content, 200, [
            'Content-Type'        => $mime . '; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'no-cache',
        ]);
    }
        
    /* ══════════════════════════════════════════════════════════════════
       COUNT OLD DATA  (preview before delete)
    ══════════════════════════════════════════════════════════════════ */
    public function countOldData(Request $request): JsonResponse
    {
        if (!$request->filled('before_date')) {
            return response()->json(['count' => 0]);
        }

        $before = \Carbon\Carbon::parse($request->before_date)->format('Y-m-d');
        $query  = DB::table('atnd_raw')->whereDate('atnd_date', '<', $before);

        if ($request->filled('machine_no')) {
            $query->where('mach_no', str_pad($request->machine_no, 3, '0', STR_PAD_LEFT));
        }

        return response()->json(['count' => $query->count()]);
    }

    /* ══════════════════════════════════════════════════════════════════
       DELETE OLD DATA  FROM ATND_RAW
       DELETE FROM ATND_RAW WHERE ATND_DATE < :before_date (optional)
    ══════════════════════════════════════════════════════════════════ */
    public function deleteOldData(Request $request): JsonResponse
    {
        $request->validate(['before_date' => 'nullable|date']);

        $query = DB::table('atnd_raw');

        if ($request->filled('before_date')) {
            $before = \Carbon\Carbon::parse($request->before_date)->format('Y-m-d');
            $query->whereDate('atnd_date', '<', $before);
        }

        if ($request->filled('machine_no') && $request->machine_no) {
            $query->where('mach_no', str_pad($request->machine_no, 3, '0', STR_PAD_LEFT));
        }

        $deleted = $query->delete();

        return response()->json([
            'message' => "{$deleted} record(s) deleted from ATND_RAW.",
            'deleted' => $deleted,
        ]);
    }

    /* ══════════════════════════════════════════════════════════════════
       DELETE DATA FROM PHYSICAL DEVICE  (clears device logs)
    ══════════════════════════════════════════════════════════════════ */
    public function deleteFromDevice(Request $request): JsonResponse
    {
        $request->validate(['machines' => 'required|array']);

        $results = [];
        $errors  = [];

        foreach ($request->machines as $machNo) {
            $device = DB::table('attd_machine_list')
                ->where('machine_no', $machNo)
                ->first();

            if (!$device) {
                $errors[] = "Machine {$machNo} not found.";
                continue;
            }

            try {
                $zk = new ZKTeco($device->m_ip, 4370);

                if (!$zk->connect()) {
                    $errors[] = "Cannot connect to {$device->device_name} ({$device->m_ip}).";
                    continue;
                }

                $zk->disableDevice();
                $zk->clearAttendance();
                $zk->enableDevice();
                $zk->disconnect();

                $results[] = "✔ {$device->device_name}: Logs cleared.";

            } catch (\Exception $e) {
                $errors[] = "✘ {$device->device_name}: " . $e->getMessage();
            }
        }

        return response()->json([
            'message' => implode("\n", array_merge($results, $errors)) ?: 'Done.',
            'results' => $results,
            'errors'  => $errors,
        ], empty($errors) ? 200 : 207);
    }

    /* ══════════════════════════════════════════════════════════════════
       PROCESS ATTENDANCE
       Calls Oracle stored procedure: TAT_ATTENDANCE_PROCESS(:company_id)
       FIX: Use DB::statement with positional binding for Oracle
    ══════════════════════════════════════════════════════════════════ */
    public function processAttendance(Request $request): JsonResponse
    {
        $request->validate(['company_id' => 'required']);

        $companyId = (string) $request->company_id;

        try {
            // Set Oracle session date format to match procedure expectations
            DB::statement("
                ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MON-YYYY'
            ");

            // Call the stored procedure with proper parameter binding
            DB::statement(
                'BEGIN TAT_ATTENDANCE_PROCESS(:p1); END;',
                ['p1' => $companyId]
            );

            return response()->json([
                'message' => "TAT_ATTENDANCE_PROCESS completed successfully for company: {$companyId}.",
            ]);

        } catch (\Exception $e) {
            \Log::error('TAT_ATTENDANCE_PROCESS error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
