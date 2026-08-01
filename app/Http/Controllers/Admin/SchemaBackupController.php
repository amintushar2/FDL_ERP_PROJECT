<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SchemaBackupController extends Controller
{
    // ─────────────────────────────────────────────
    //  Schema definitions
    // ─────────────────────────────────────────────
    private array $schemas = [
        'F_STORE' => [
            'label'    => 'Inventory (F_STORE)',
            'user'     => 'f_store',
            'password' => 'fstore',
            'schema'   => 'F_STORE',
            'prefix'   => 'inv',
            'icon'     => 'bi-box-seam',
            'badge'    => 'warning',
        ],
        'HRM' => [
            'label'    => 'Human Resources (HRM)',
            'user'     => 'hrm',
            'password' => 'hrm',
            'schema'   => 'HRM',
            'prefix'   => 'hrm',
            'icon'     => 'bi-people',
            'badge'    => 'info',
        ],
    ];

    // expdp path ON THE SERVER where Laravel runs (Oracle client installed there)
    private string $expdpBin  = 'D:\db\ORACLE_HOME\bin\expdp.exe';
    private string $tns       = '192.168.210.205:1521/orcl';
    private string $directory = 'BACK_UP';

    // FTP config — reads from .env
    private function ftpConfig(): array
    {
        return [
            'host'    => env('BACKUP_FTP_HOST',    '192.168.210.205'),
            'port'    => (int) env('BACKUP_FTP_PORT', 21),
            'user'    => env('BACKUP_FTP_USER',    'Administrator'),
            'pass'    => env('BACKUP_FTP_PASS',    'FDL@2828'),
            'path'    => env('BACKUP_FTP_PATH',    '/back_up'),
            'passive' => (bool) env('BACKUP_FTP_PASSIVE', true),
            'timeout' => (int) env('BACKUP_FTP_TIMEOUT',  60),
        ];
    }

    // ─────────────────────────────────────────────
    //  Page entry-point
    // ─────────────────────────────────────────────
    public function index()
    {
        $schemas = $this->schemas;
        return view('admin.schema_backup.index', compact('schemas'));
    }

    // ─────────────────────────────────────────────
    //  Run backup ON THE SERVER via exec()
    //  POST /admin/schema-backup/run
    //  body: { schema_key: "HRM" }
    // ─────────────────────────────────────────────
    public function run(Request $request)
    {
        $request->validate([
            'schema_key' => 'required|string|in:' . implode(',', array_keys($this->schemas)),
        ]);

        $key    = $request->schema_key;
        $cfg    = $this->schemas[$key];
        $mydate = Carbon::now()->format('dmYHi');       // DDMMYYYYHHmm
        $dump   = "{$cfg['prefix']}_{$mydate}.dmp";

        // Build expdp command — runs on the server
        $cmd = sprintf(
            '"%s" %s/%s@%s schemas=%s compress=Y directory=%s dumpfile=%s 2>&1',
            $this->expdpBin,
            $cfg['user'],
            $cfg['password'],
            $this->tns,
            $cfg['schema'],
            $this->directory,
            $dump
        );

        Log::info('Oracle Backup Started (server-side)', [
            'schema'   => $key,
            'dumpfile' => $dump,
        ]);

        $output     = [];
        $returnCode = 0;

        // exec() blocks until expdp finishes
        exec($cmd, $output, $returnCode);

        $outputStr = implode("\n", $output);
        $success   = ($returnCode === 0)
                  && stripos($outputStr, 'successfully completed') !== false;

        Log::info('Oracle Backup Finished', [
            'schema'      => $key,
            'dumpfile'    => $dump,
            'return_code' => $returnCode,
            'success'     => $success,
        ]);

        if (!$success) {
            Log::error('Oracle Backup Failed Output', ['output' => $outputStr]);
        }

        return response()->json([
            'success'  => $success,
            'message'  => $success
                ? "Backup completed: <strong>{$dump}</strong>"
                : "Backup failed (exit {$returnCode}). Check output for details.",
            'dumpfile' => $dump,
            'output'   => $outputStr,
        ], $success ? 200 : 500);
    }

    // ─────────────────────────────────────────────
    //  List .dmp files from FTP server
    //  GET /admin/schema-backup/ftp-list
    // ─────────────────────────────────────────────
    public function ftpList(Request $request)
    {
        $schemaKey = strtoupper($request->input('schema_key', ''));
        $perPage   = max(1, (int) $request->input('per_page', 15));
        $page      = max(1, (int) $request->input('page', 1));
        $sort      = $request->input('sort', 'date_desc');

        $ftp  = $this->ftpConfig();
        $conn = @ftp_connect($ftp['host'], $ftp['port'], $ftp['timeout']);

        if (!$conn) {
            return response()->json([
                'success' => false,
                'message' => "Cannot connect to FTP server ({$ftp['host']}:{$ftp['port']}). Check BACKUP_FTP_* in .env",
            ], 503);
        }

        if (!@ftp_login($conn, $ftp['user'], $ftp['pass'])) {
            ftp_close($conn);
            return response()->json([
                'success' => false,
                'message' => 'FTP login failed. Check BACKUP_FTP_USER / BACKUP_FTP_PASS in .env',
            ], 503);
        }

        if ($ftp['passive']) ftp_pasv($conn, true);

        $rawList = @ftp_rawlist($conn, $ftp['path']);
        ftp_close($conn);

        if ($rawList === false) {
            return response()->json([
                'success' => false,
                'message' => "Cannot list FTP directory: {$ftp['path']}",
            ], 503);
        }

        // Parse raw FTP listing
        $files = [];
        foreach ($rawList as $line) {
            $line = trim($line);
            if (!$line) continue;

            // Skip directories
            if (str_starts_with($line, 'd') || stripos($line, '<dir>') !== false) continue;

            // Extract filename (last token)
            if (!preg_match('/(\S+\.dmp)$/i', $line, $m)) continue;
            $filename = $m[1];

            // Detect schema from filename prefix
            $detectedSchema = null;
            foreach ($this->schemas as $k => $cfg) {
                if (str_starts_with(strtolower($filename), strtolower($cfg['prefix']) . '_')) {
                    $detectedSchema = $k;
                    break;
                }
            }

            if ($schemaKey && $detectedSchema !== $schemaKey) continue;

            // Extract size
            $sizeBytes = 0;
            if (preg_match('/\s(\d+)\s+\w+\s+\d+\s+[\d:]+\s+\S+$/', $line, $sm)) {
                $sizeBytes = (int) $sm[1]; // Unix ls -l style
            } elseif (preg_match('/^\d{2}-\d{2}-\d{2}\s+\d{2}:\d{2}[AP]M\s+(\d+)\s+/i', $line, $sm2)) {
                $sizeBytes = (int) $sm2[1]; // Windows IIS FTP
            }

            // Parse date from filename: prefix_DDMMYYYYHHmm.dmp
            $dateStr = null;
            if (preg_match('/_(\d{2})(\d{2})(\d{4})(\d{2})(\d{2})\.dmp$/i', $filename, $dm)) {
                try {
                    $dateStr = Carbon::createFromFormat(
                        'd/m/Y H:i',
                        "{$dm[1]}/{$dm[2]}/{$dm[3]} {$dm[4]}:{$dm[5]}"
                    )->format('d-M-Y H:i');
                } catch (\Exception $e) {}
            }

            $files[] = [
                'filename'    => $filename,
                'schema_key'  => $detectedSchema,
                'label'       => $detectedSchema ? $this->schemas[$detectedSchema]['label'] : 'Unknown',
                'size_bytes'  => $sizeBytes,
                'size_human'  => $this->humanSize($sizeBytes),
                'backup_date' => $dateStr,
            ];
        }

        // Sort
        usort($files, fn($a, $b) => match ($sort) {
            'date_asc'  =>  strtotime($b['backup_date'] ?? '') <=> strtotime($a['backup_date'] ?? ''),
            'size_desc' =>  $b['size_bytes'] <=> $a['size_bytes'],
            'size_asc'  =>  $a['size_bytes'] <=> $b['size_bytes'],
            'name_asc'  =>  strcmp($a['filename'], $b['filename']),
            default     => strtotime($b['backup_date'] ?? '') <=> strtotime($a['backup_date'] ?? ''),
        });

        // Paginate
        $total    = count($files);
        $lastPage = max(1, (int) ceil($total / $perPage));
        $offset   = ($page - 1) * $perPage;
        $rows     = array_slice($files, $offset, $perPage);

        $totalSize = array_sum(array_column($files, 'size_bytes'));

        return response()->json([
            'success' => true,
            'data'    => $rows,
            'summary' => [
                'total_files' => $total,
                'total_size'  => $this->humanSize($totalSize),
            ],
            'meta' => [
                'total'     => $total,
                'page'      => $page,
                'per_page'  => $perPage,
                'last_page' => $lastPage,
                'from'      => $total > 0 ? $offset + 1 : 0,
                'to'        => min($offset + $perPage, $total),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  Test FTP connection
    //  GET /admin/schema-backup/ftp-test
    // ─────────────────────────────────────────────
    public function ftpTest()
    {
        $ftp  = $this->ftpConfig();
        $conn = @ftp_connect($ftp['host'], $ftp['port'], $ftp['timeout']);

        if (!$conn) {
            return response()->json([
                'success' => false,
                'message' => "Cannot connect to {$ftp['host']}:{$ftp['port']}",
            ]);
        }

        if (!@ftp_login($conn, $ftp['user'], $ftp['pass'])) {
            ftp_close($conn);
            return response()->json(['success' => false, 'message' => 'FTP login failed.']);
        }

        $sys = ftp_systype($conn);
        ftp_close($conn);

        return response()->json([
            'success' => true,
            'message' => "Connected. Server: {$sys}",
            'host'    => $ftp['host'],
            'path'    => $ftp['path'],
        ]);
    }

    // ─────────────────────────────────────────────
    //  Download a .dmp file from FTP to the browser
    //  GET /admin/schema-backup/ftp-download?filename=inv_*.dmp
    // ─────────────────────────────────────────────
    public function ftpDownload(Request $request)
    {
        $request->validate(['filename' => 'required|string|regex:/^[\w\-\.]+\.dmp$/i']);

        $filename = $request->filename;
        $ftp      = $this->ftpConfig();

        $conn = @ftp_connect($ftp['host'], $ftp['port'], $ftp['timeout']);
        if (!$conn) abort(503, 'Cannot connect to FTP server.');

        if (!@ftp_login($conn, $ftp['user'], $ftp['pass'])) {
            ftp_close($conn);
            abort(503, 'FTP login failed.');
        }

        if ($ftp['passive']) ftp_pasv($conn, true);

        $tmpPath = tempnam(sys_get_temp_dir(), 'fdl_dmp_');
        $remote  = rtrim($ftp['path'], '/') . '/' . $filename;
        $ok      = ftp_get($conn, $tmpPath, $remote, FTP_BINARY);
        ftp_close($conn);

        if (!$ok) {
            @unlink($tmpPath);
            abort(404, "File not found on FTP: {$filename}");
        }

        Log::info('FTP Download', ['filename' => $filename, 'user' => auth()->id()]);

        return response()->download($tmpPath, $filename, [
            'Content-Type' => 'application/octet-stream',
        ])->deleteFileAfterSend(true);
    }

    // ─────────────────────────────────────────────
    //  Delete a .dmp file from FTP
    //  DELETE /admin/schema-backup/ftp-delete
    //  body: { filename: "inv_*.dmp" }
    // ─────────────────────────────────────────────
    public function ftpDelete(Request $request)
    {
        $request->validate(['filename' => 'required|string|regex:/^[\w\-\.]+\.dmp$/i']);

        $filename = $request->filename;
        $ftp      = $this->ftpConfig();

        $conn = @ftp_connect($ftp['host'], $ftp['port'], $ftp['timeout']);
        if (!$conn) {
            return response()->json(['success' => false, 'message' => 'Cannot connect to FTP server.'], 503);
        }

        if (!@ftp_login($conn, $ftp['user'], $ftp['pass'])) {
            ftp_close($conn);
            return response()->json(['success' => false, 'message' => 'FTP login failed.'], 503);
        }

        if ($ftp['passive']) ftp_pasv($conn, true);

        $remote = rtrim($ftp['path'], '/') . '/' . $filename;
        $ok     = @ftp_delete($conn, $remote);
        ftp_close($conn);

        if (!$ok) {
            return response()->json([
                'success' => false,
                'message' => "Could not delete: {$filename} — check FTP write permissions.",
            ], 500);
        }

        Log::info('FTP Delete', ['filename' => $filename, 'user' => auth()->id()]);

        return response()->json(['success' => true, 'message' => "Deleted: {$filename}"]);
    }

    // ─────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────
    private function humanSize(int $bytes): string
    {
        if ($bytes <= 0)         return '0 B';
        if ($bytes < 1024)       return "{$bytes} B";
        if ($bytes < 1048576)    return round($bytes / 1024, 1)    . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
        return round($bytes / 1073741824, 2) . ' GB';
    }
}