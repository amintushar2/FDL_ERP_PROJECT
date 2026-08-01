<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\BaseController;
use App\Models\HrmReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * ReportCenterController
 * Laravel equivalent of Oracle Forms: hrm_report_center.fmb
 *
 * ═══════════════════════════════════════════════════════════════
 * DUAL ENGINE — both go through the same POST /reports/run route:
 *
 *   js_report SET  → proxyJasperReport()
 *                    http://192.168.210.205:8080/jri/report
 *                    ?_repName=bank_advise_ad
 *                    &_repFormat=xls
 *                    &_dataSource=default
 *                    &_repLocale=en_US
 *                    &_repEncoding=UTF-8
 *                    &P_COMPANY=100&...
 *
 *   js_report NULL → proxyOracleReport()
 *                    http://192.168.210.205:9002/reports/rwservlet
 *                    ?report=D:/four_design/reports/myreport.rdf
 *                    &server=...&desformat=pdf&P_COMPANY=100&...
 *
 * PARAMETER FLOW:
 *   getParameters() returns block_item / block_value_item per param.
 *   JS builds: parameters: { block_item: value, ... }
 *   runReport() maps block_item → parameter_name for Oracle URL.
 *   For Jasper, parameter_name is used directly in the query string.
 * ═══════════════════════════════════════════════════════════════
 */
class ReportCenterController extends BaseController
{
    // ── Jasper base URL ───────────────────────────────────────────────
    private const JASPER_BASE = 'http://192.168.210.205:8080/jri/report';

    // ── Oracle desformat / MIME / extension maps ──────────────────────
    private const ORACLE_FORMAT_MAP = [
        'pdf' => 'pdf',
        'xls' => 'spreadsheet',
        'xml' => 'xml',
    ];

    private const MIME_MAP = [
        'pdf'         => 'application/pdf',
        'spreadsheet' => 'application/vnd.ms-excel',
        'xml'         => 'application/xml',
        'xls'         => 'application/vnd.ms-excel', // Jasper returns xls key
    ];

    private const EXT_MAP = [
        'pdf'         => 'pdf',
        'spreadsheet' => 'xls',
        'xml'         => 'xml',
        'xls'         => 'xls',
    ];

    // ── LOV SOURCE MAP ────────────────────────────────────────────────
    private const LOV_SOURCES = [
        'COMPANY_NAME'   => ['table' => 'COMPANY_PROFILE',  'value' => 'COMPANY_ID',   'label' => 'COMPANY_NAME',  'order' => 'COMPANY_NAME'],
        'SETION_TXT'     => ['table' => 'HRM.SECTION',      'value' => 'SECTION_NO',   'label' => 'SECTION_NAME',  'order' => 'SECTION_NAME'],
        'DEPT_NAME'      => ['table' => 'HRM.dept',         'value' => 'DEPT_NO',      'label' => 'DEPT_NAME',     'order' => 'DEPT_NAME'],
        'EMP_TYPE'       => ['table' => 'HRM.EMP_TYPE',     'value' => 'EMP_TYPE',     'label' => 'EMP_TYPE',      'order' => 'PRIORITY'],
        'EMP_GRADE'      => ['table' => 'HRM.GRADE',        'value' => 'GRADE_ID',     'label' => 'GRADE_NAME',    'order' => 'GRADE_NAME'],
        'RELEGION'       => ['table' => 'RELIGION',         'value' => 'RELIGION_ID',  'label' => 'RELIGION_NAME', 'order' => 'RELIGION_NAME'],
        'BLOOD_GROUP'    => ['raw' => [
            ['v'=>'A+','l'=>'A+'],  ['v'=>'A-','l'=>'A-'],
            ['v'=>'B+','l'=>'B+'],  ['v'=>'B-','l'=>'B-'],
            ['v'=>'AB+','l'=>'AB+'],['v'=>'AB-','l'=>'AB-'],
            ['v'=>'O+','l'=>'O+'],  ['v'=>'O-','l'=>'O-'],
        ]],
        'SEX'            => ['raw' => [['v'=>'Male','l'=>'Male'],['v'=>'Female','l'=>'Female']]],
        'DES_NAME'       => ['table' => 'HRM.DESIGNATION',  'value' => 'DES_NO',       'label' => 'DES_NAME',      'order' => 'DES_NAME'],
        'WORK_ENT'       => ['raw' => [['v'=>'Officer','l'=>'Officer'],['v'=>'Staff','l'=>'Staff'],['v'=>'Worker','l'=>'Worker']]],
        'STATUS'         => ['raw' => [['v'=>'Active','l'=>'Active'],['v'=>'Inactive','l'=>'Inactive']]],
        'P_MONTH'        => ['raw' => [
            ['v'=>'01','l'=>'January'], ['v'=>'02','l'=>'February'], ['v'=>'03','l'=>'March'],
            ['v'=>'04','l'=>'April'],   ['v'=>'05','l'=>'May'],      ['v'=>'06','l'=>'June'],
            ['v'=>'07','l'=>'July'],    ['v'=>'08','l'=>'August'],   ['v'=>'09','l'=>'September'],
            ['v'=>'10','l'=>'October'], ['v'=>'11','l'=>'November'], ['v'=>'12','l'=>'December'],
        ]],
        'FLOOR_NAME'     => ['table' => 'HRM.FLOOR',        'value' => 'FLOOR_ID',     'label' => 'FLOOR_DESC',    'order' => 'FLOOR_DESC'],
        'INCREMENT_TYPE' => ['raw' => [['v'=>'Increment','l'=>'Increment'],['v'=>'Promotion','l'=>'Promotion'],['v'=>'Demotion','l'=>'Demotion']]],
        'LINE_NAME'      => ['table' => 'HRM.LINE_INFO',    'value' => 'LINE_NO',      'label' => 'LINE',          'order' => 'LINE'],
        'P_BILL'         => ['raw' => [['v'=>'0','l'=>'ALL EMP'],['v'=>'1','l'=>'Only Bill']]],
    ];

    // ─────────────────────────────────────────────────────────────────
    // 1. INDEX
    // ─────────────────────────────────────────────────────────────────
    public function index(): \Illuminate\View\View
    {
        $reports = HrmReport::forHrmModule()->get(['report_id', 'report_title']);
        return view('reports.center', compact('reports'));
    }

    // ─────────────────────────────────────────────────────────────────
    // 2. GET PARAMETERS  GET /hrm/reports/{reportId}/parameters
    // ─────────────────────────────────────────────────────────────────
    public function getParameters(int $reportId): JsonResponse
    {
        $report = HrmReport::where('report_id', $reportId)->firstOrFail();

        $parameters = DB::table('hrm_report_parameter as r')
            ->join('hrm_parameter_master as p', 'r.parameter_no', '=', 'p.parameter_no')
            ->where('r.report_id', $reportId)
            ->orderBy('r.serial_no')
            ->select([
                'r.parameter_name',
                'p.block_item',
                'p.block_value_item',
                'p.parameter_type',
                'r.p_block_name',
                'r.serial_no',
            ])
            ->get()
            ->map(function ($row) {
                $blockItem = $row->block_item;
                $isLov     = $this->isLovField($blockItem, $row->parameter_type);
                $inputType = $this->resolveInputType($row->parameter_type, $blockItem);

                return [
                    'parameter_name'   => $row->parameter_name,
                    'block_item'       => $blockItem,
                    'block_value_item' => $row->block_value_item,
                    'parameter_type'   => $row->parameter_type,
                    'p_block_name'     => $row->p_block_name,
                    'serial_no'        => $row->serial_no,
                    'label'            => $this->labelFromBlockItem($blockItem),
                    'input_type'       => $inputType,
                    'is_lov'           => $isLov,
                    'lov_options'      => $isLov ? $this->fetchLovOptions($blockItem) : [],
                ];
            });

        return response()->json([
            'report_id'        => $report->report_id,
            'report_file_name' => $report->report_file_name,
            'js_report'        => $report->js_report ?? null,
            'parameters'       => $parameters,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // 3. LOV OPTIONS  GET /reports/lov/{blockItem}
    // ─────────────────────────────────────────────────────────────────
    public function getLovOptions(string $blockItem): JsonResponse
    {
        return response()->json([
            'options' => $this->fetchLovOptions(strtoupper($blockItem)),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // 4. RUN REPORT  POST /reports/run
    //
    //    Single entry point for BOTH engines.
    //    JS sends JSON:
    //    {
    //      "report_id":  5,
    //      "_repFormat": "pdf",
    //      "parameters": {
    //        "COMPANY_NAME": "100",
    //        "P_FROM_DATE":  "31-05-2026"
    //      }
    //    }
    //
    //    Engine decision:
    //      js_report SET  → proxyJasperReport()
    //      js_report NULL → proxyOracleReport()
    // ─────────────────────────────────────────────────────────────────
    public function runReport(Request $request): \Illuminate\Http\Response|JsonResponse
    {
        $request->validate([
            'report_id'  => 'required|integer',
            '_repFormat' => 'nullable|in:pdf,xls,xml',
            'parameters' => 'nullable|array',
        ]);

        $reportId  = (int) $request->input('report_id');
        $repFormat = $request->input('_repFormat', 'pdf');

        $report = HrmReport::where('report_id', $reportId)->firstOrFail();

        // ── Safely extract parameters array from JSON body ────────────
        $userParams = $request->input('parameters');
        if (is_string($userParams)) {
            $userParams = json_decode($userParams, true) ?? [];
        }
        if (!is_array($userParams)) {
            $userParams = [];
        }

        // ── Load parameter definitions from DB ────────────────────────
        $reportParams = DB::table('hrm_report_parameter as r')
            ->join('hrm_parameter_master as p', 'r.parameter_no', '=', 'p.parameter_no')
            ->where('r.report_id', $reportId)
            ->orderBy('r.serial_no')
            ->select(
                'r.parameter_name',
                'p.block_item',
                'p.block_value_item',
                'p.parameter_type'
            )
            ->get();

        // ── Map user values → Oracle parameter_name keys ──────────────
        // JS sends: { block_item: value }  (key = block_value_item ?? block_item)
        // We output: { parameter_name: value } for the report server URL
        $reportServerParams = [];

        foreach ($reportParams as $rp) {
            // Key that JS used: block_value_item if set, else block_item
            $blockKey = !empty($rp->block_value_item)
                ? $rp->block_value_item
                : $rp->block_item;

            // Find user value — try blockKey first, then parameter_name as fallback
            $value = $userParams[$blockKey]
                  ?? $userParams[$rp->parameter_name]
                  ?? null;

            // Skip truly empty values
            if (is_null($value) || $value === '') {
                continue;
            }

            // Date conversion for Oracle (Jasper receives DD-MM-YYYY as-is)
            // We store the original value for Jasper and the converted for Oracle
            $reportServerParams[$rp->parameter_name] = [
                'raw'   => $value,                          // DD-MM-YYYY → Jasper
                'oracle'=> $this->isDateType($rp->parameter_type)
                              ? $this->toOracleDate($value) // DD-MON-YYYY → Oracle
                              : $value,
            ];
        }

        Log::debug('HRM Report run', [
            'report_id'   => $reportId,
            'report_file' => $report->report_file_name,
            'js_report'   => $report->js_report,
            'format'      => $repFormat,
            'user_params' => $userParams,
            'mapped'      => $reportServerParams,
        ]);

        try {
            // ── ENGINE DECISION ────────────────────────────────────────
            if (!empty($report->js_report)) {
                // Jasper: send raw (DD-MM-YYYY) dates, parameter_name as key
                $jasperParams = array_map(fn($p) => $p['raw'], $reportServerParams);
                return $this->proxyJasperReport($report->js_report, $repFormat, $jasperParams);
            } else {
                // Oracle: send Oracle-converted dates, parameter_name as key
                $oracleParams = array_map(fn($p) => $p['oracle'], $reportServerParams);
                return $this->proxyOracleReport($report->report_file_name, $oracleParams, $repFormat);
            }

        } catch (\Exception $e) {
            Log::error('HRM Report proxy failed: ' . $e->getMessage(), [
                'report_id'   => $reportId,
                'js_report'   => $report->js_report,
                'report_file' => $report->report_file_name,
                'format'      => $repFormat,
            ]);
            return response()->json([
                'error' => 'HRM-9999: ERROR !!! ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // 5A. PROXY TO JASPERREPORTS SERVER
    //     http://192.168.210.205:8080/jri/report
    //
    //     URL format (same as demo link):
    //     ?_repName=bank_advise_ad
    //     &_repFormat=xls
    //     &_dataSource=default
    //     &_repLocale=en_US
    //     &_repEncoding=UTF-8
    //     &P_COMPANY=100
    //     &P_PAYMENT_DATE=31-MAY-26
    //     ...
    //
    //     Dates: Jasper receives DD-MM-YYYY (no Oracle conversion needed).
    //     Response: streamed back to browser same as Oracle proxy.
    // ─────────────────────────────────────────────────────────────────
    protected function proxyJasperReport(
        string $jsReportName,
        string $repFormat,
        array  $params
    ): \Illuminate\Http\Response {

        // Build Jasper fixed params first
        $jasperFixed = [
            '_repName'     => $jsReportName,
            '_repFormat'   => $repFormat,        // pdf | xls | xml
            '_dataSource'  => 'default',
            '_repLocale'   => 'en_US',
            '_repEncoding' => 'UTF-8',
        ];

        // Merge: fixed Jasper params + report parameters (P_COMPANY, P_PAYMENT_DATE, ...)
        $allParams = array_merge($jasperFixed, $params);

        $url = self::JASPER_BASE . '?' . http_build_query($allParams);

        Log::debug('Jasper URL: ' . $url);

        $response = Http::timeout(config('hrm.timeout', 120))
                        ->withOptions(['verify' => false])
                        ->get($url);

        if (!$response->successful()) {
            Log::error('Jasper Reports HTTP error', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
                'url'    => $url,
            ]);
            throw new \RuntimeException(
                "JasperReports Server returned HTTP {$response->status()} for: {$jsReportName}. "
                . "Body: " . substr($response->body(), 0, 200)
            );
        }

        // Determine MIME / disposition from format
        $mime     = self::MIME_MAP[$repFormat]    ?? 'application/pdf';
        $ext      = self::EXT_MAP[$repFormat]     ?? 'pdf';
        $inline   = $repFormat === 'pdf' ? 'inline' : 'attachment';

        return response($response->body(), 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => $inline . '; filename="' . $jsReportName . '.' . $ext . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // 5B. PROXY TO ORACLE REPORTS SERVER
    //     http://192.168.210.205:9002/reports/rwservlet
    // ─────────────────────────────────────────────────────────────────
    protected function proxyOracleReport(
        string $reportFileName,
        array  $oracleParams,
        string $repFormat = 'pdf'
    ): \Illuminate\Http\Response {

        $serverUrl    = config('hrm.report_server_url');
        $serverName   = config('hrm.report_server_name');
        $filePath     = 'D:/four_design/reports/';
        $oracleFormat = self::ORACLE_FORMAT_MAP[$repFormat] ?? 'pdf';

        $fixedParams = [
            'server'    => $serverName,
            'userid'    => 'HRM/hrm@192.168.210.205:1521/orcl',
            'paramform' => 'no',
            'destype'   => 'cache',
            'desformat' => $oracleFormat,
        ];

        $allParams   = array_merge($fixedParams, $oracleParams);
        $reportPath  = $filePath . strtolower($reportFileName);

        $url = rtrim($serverUrl, '/')
             . '/reports/rwservlet'
             . '?report=' . rawurlencode($reportPath)
             . '&' . http_build_query($allParams);

        Log::debug('Oracle URL: ' . $url);

        $response = Http::timeout(config('hrm.timeout', 120))
                        ->withOptions(['verify' => false])
                        ->get($url);

        if (!$response->successful()) {
            Log::error('Oracle Reports HTTP error', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
                'url'    => $url,
            ]);
            throw new \RuntimeException(
                "Oracle Reports Server returned HTTP {$response->status()} for: {$reportFileName}. "
                . "Body: " . substr($response->body(), 0, 200)
            );
        }

        $mime     = self::MIME_MAP[$oracleFormat] ?? 'application/pdf';
        $ext      = self::EXT_MAP[$oracleFormat]  ?? 'pdf';
        $baseName = pathinfo($reportFileName, PATHINFO_FILENAME);
        $inline   = $repFormat === 'pdf' ? 'inline' : 'attachment';

        return response($response->body(), 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => $inline . '; filename="' . $baseName . '.' . $ext . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────

    private function fetchLovOptions(string $blockItem): array
    {
        $source = self::LOV_SOURCES[$blockItem] ?? null;
        if (!$source) return [];

        if (isset($source['raw'])) {
            return array_map(fn($r) => ['value' => $r['v'], 'label' => $r['l']], $source['raw']);
        }

        try {
            $rows = DB::table($source['table'])
                ->select($source['value'] . ' as value', $source['label'] . ' as label')
                ->orderBy($source['order'])
                ->get();
            return $rows->map(fn($r) => ['value' => $r->value, 'label' => $r->label])->toArray();
        } catch (\Exception $e) {
            Log::warning("LOV fetch failed for {$blockItem}: " . $e->getMessage());
            return [];
        }
    }

    private function isLovField(string $blockItem, ?string $paramType): bool
    {
        return isset(self::LOV_SOURCES[$blockItem])
            || strtolower($paramType ?? '') === 'lov';
    }

    private function resolveInputType(?string $paramType, string $blockItem): string
    {
        if ($this->isLovField($blockItem, $paramType)) return 'lov';
        return $this->isDateType($paramType)
            ? 'date'
            : (strtolower($paramType ?? '') === 'number' ? 'number' : 'text');
    }

    private function isDateType(?string $paramType): bool
    {
        return in_array(strtolower($paramType ?? ''), ['date', 'datetime']);
    }

    /**
     * Convert DD-MM-YYYY or YYYY-MM-DD → DD-MON-YYYY (Oracle rwservlet format).
     * Jasper does NOT need this — it receives DD-MM-YYYY directly.
     */
    private function toOracleDate(string $date): string
    {
        $months = [
            '01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR',
            '05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG',
            '09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC',
        ];

        // DD-MM-YYYY (flatpickr output)
        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $m)) {
            $mon = $months[$m[2]] ?? null;
            if ($mon) return $m[1] . '-' . $mon . '-' . $m[3];
        }

        // YYYY-MM-DD (HTML date input fallback)
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $m)) {
            $mon = $months[$m[2]] ?? null;
            if ($mon) return $m[3] . '-' . $mon . '-' . $m[1];
        }

        return $date; // already DD-MON-YYYY or unrecognised — pass through
    }

    private function labelFromBlockItem(?string $blockItem): string
    {
        $map = [
            'COMPANY_NAME'   => 'Company',
            'SETION_TXT'     => 'Section',
            'P_YEAR'         => 'Year',
            'DEPT_NAME'      => 'Department',
            'AS_DATE'        => 'As of Date',
            'EMP_TYPE'       => 'Employee Type',
            'EMP_GRADE'      => 'Employee Grade',
            'RELEGION'       => 'Religion',
            'BLOOD_GROUP'    => 'Blood Group',
            'SEX'            => 'Gender',
            'DES_NAME'       => 'Designation',
            'EMP_NO'         => 'Employee No',
            'WORK_ENT'       => 'Work Entity',
            'P_FROM_DATE'    => 'From Date',
            'P_TO_DATE'      => 'To Date',
            'ATT_DATE'       => 'Attendance Date',
            'STATUS'         => 'Status',
            'P_MONTH'        => 'Month',
            'P_DAYES'        => 'Days',
            'FLOOR_NAME'     => 'Floor',
            'INCREMENT_TYPE' => 'Increment Type',
            'P_BILL'         => 'Bill Type',
            'LINE_NAME'      => 'Line',
            'P_LETTER_NO'    => 'Letter No',
        ];
        return $map[$blockItem] ?? ucwords(strtolower(str_replace('_', ' ', $blockItem ?? '')));
    }
}
