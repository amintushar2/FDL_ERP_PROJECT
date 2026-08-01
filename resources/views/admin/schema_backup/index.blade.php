@extends('layouts.app')

@section('title', 'Database Backup')

@push('styles')
    <style>
        .table-wrap {
            position: relative;
            min-height: 100px;
        }

        .table-loading {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .75);
            z-index: 10;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
        }

        .table-loading.show {
            display: flex;
        }

        /* FTP status dot */
        .ftp-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .ftp-dot.ok {
            background: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .2);
        }

        .ftp-dot.fail {
            background: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, .2);
        }

        .ftp-dot.pending {
            background: #ffc107;
            animation: ftppulse 1s infinite;
        }

        @keyframes ftppulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .35
            }
        }

        /* Schema card hover */
        .schema-card {
            transition: box-shadow .2s, transform .15s;
        }

        .schema-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .13) !important;
            transform: translateY(-2px);
        }

        /* Size bar */
        .size-bar-bg {
            background: #e9ecef;
            border-radius: 4px;
            height: 5px;
            margin-top: 3px;
        }

        .size-bar {
            background: #0d6efd;
            height: 5px;
            border-radius: 4px;
            transition: width .4s;
        }

        /* Output log */
        .output-log {
            background: #1e1e2e;
            color: #cdd6f4;
            font-family: 'Courier New', monospace;
            font-size: .78rem;
            border-radius: .375rem;
            padding: 1rem;
            max-height: 360px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .log-ok {
            color: #a6e3a1;
        }

        .log-err {
            color: #f38ba8;
        }

        .log-warn {
            color: #f9e2af;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3 px-3">

        {{-- ── Page Header ──────────────────────────────────────── --}}
        <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom flex-wrap">
            <div class="bg-dark rounded p-2 fs-4 lh-1 text-white">
                <i class="bi bi-database-lock"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">Database Backup</h5>
                <small class="text-muted">FDL ERP &mdash; Server-side expdp &bull; Files listed from FTP</small>
            </div>
            {{-- FTP status --}}
            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
                <span class="ftp-dot pending" id="ftp-dot"></span>
                <small class="text-muted" id="ftp-status-txt">Checking FTP…</small>
                <button class="btn btn-outline-secondary btn-sm" onclick="testFtp()">
                    <i class="bi bi-arrow-repeat me-1"></i>Test FTP
                </button>
            </div>
        </div>

        {{-- ── Alert ────────────────────────────────────────────── --}}
        <div id="fb-alert" class="alert d-none" role="alert"></div>

        {{-- ── Info banner ──────────────────────────────────────── --}}
        <div class="alert alert-info d-flex gap-3 align-items-start mb-4 py-2">
            <i class="bi bi-info-circle-fill fs-5 mt-1 flex-shrink-0"></i>
            <div class="small mb-0">
                <strong>Server-side backup:</strong>
                Click <strong>Run Backup</strong> — the Laravel server runs <code>expdp</code> directly
                (Oracle client is installed on the server). No Oracle client needed on your PC.
                The exported <code>.dmp</code> file is saved to the Oracle <code>BACK_UP</code> directory
                and appears in the FTP file list below once complete.
            </div>
        </div>

        {{-- ── Schema Cards ─────────────────────────────────────── --}}
        <div class="row g-3 mb-4">

            @foreach ($schemas as $key => $cfg)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card shadow-sm schema-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div
                                    class="bg-{{ $cfg['badge'] }} bg-opacity-10
                                    border border-{{ $cfg['badge'] }} border-opacity-50
                                    rounded p-2 fs-5 text-{{ $cfg['badge'] }}">
                                    <i class="bi {{ $cfg['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $cfg['schema'] }}</div>
                                    <small class="text-muted">{{ $cfg['label'] }}</small>
                                </div>
                            </div>

                            <div class="mb-3 small text-muted">
                                <i class="bi bi-file-earmark-zip me-1"></i>
                                <code>{{ $cfg['prefix'] }}_DDMMYYYYHHMM.dmp</code>
                            </div>

                            {{-- Progress / status area --}}
                            <div class="mb-2 d-none small" id="status-{{ $key }}">
                                <div class="progress mb-1" style="height:6px">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated
                                        bg-{{ $cfg['badge'] }}"
                                        style="width:100%" id="prog-{{ $key }}"></div>
                                </div>
                                <span class="text-muted" id="status-txt-{{ $key }}">Running…</span>
                            </div>

                            <button class="btn btn-dark btn-sm w-100 fw-semibold" id="btn-run-{{ $key }}"
                                onclick="runBackup('{{ $key }}')">
                                <span class="spinner-border spinner-border-sm d-none me-1" id="sp-{{ $key }}"
                                    role="status"></span>
                                <i class="bi bi-play-fill me-1" id="icon-{{ $key }}"></i>
                                Run Backup
                            </button>
                        </div>
                        <div class="card-footer text-muted small bg-light d-flex justify-content-between">
                            <span><i class="bi bi-hdd-network me-1"></i>Server-side expdp</span>
                            <span>TNS: {{ env('DB_TNS', 'orcl') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Backup All card --}}
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm schema-card border-dark h-100">
                    <div
                        class="card-body d-flex flex-column justify-content-center
                            align-items-center text-center gap-2">
                        <div class="text-dark fs-2">
                            <i class="bi bi-database-fill-up"></i>
                        </div>
                        <div class="fw-bold">Backup All Schemas</div>
                        <small class="text-muted">Runs F_STORE then HRM sequentially on server</small>

                        <div class="mb-1 d-none w-100" id="status-ALL">
                            <div class="progress mb-1" style="height:6px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"
                                    id="prog-ALL"></div>
                            </div>
                            <small class="text-muted" id="status-txt-ALL">Running…</small>
                        </div>

                        <button class="btn btn-dark btn-sm w-100 fw-semibold mt-1" id="btn-run-ALL" onclick="runAll()">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="sp-ALL" role="status"></span>
                            <i class="bi bi-play-fill me-1" id="icon-ALL"></i>
                            Run All Backups
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── FTP Backup File List ─────────────────────────────── --}}
        <div class="card shadow-sm">

            <div
                class="card-header bg-light d-flex align-items-center
                    justify-content-between flex-wrap gap-2">
                <span class="fw-semibold">
                    <i class="bi bi-hdd-network me-1"></i>Backup Files on FTP Server
                </span>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="summary-txt">—</span>
                    <button class="btn btn-outline-secondary btn-sm" onclick="loadFiles(1)">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </button>
                </div>
            </div>

            {{-- Filter bar --}}
            <div class="card-body border-bottom bg-light py-2">
                <div class="row g-2 align-items-center">

                    <div class="col-sm-4 col-md-3">
                        <select id="f-schema" class="form-select form-select-sm" onchange="loadFiles(1)">
                            <option value="">— All Schemas —</option>
                            @foreach ($schemas as $key => $cfg)
                                <option value="{{ $key }}">{{ $cfg['schema'] }} — {{ $cfg['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4 col-md-3">
                        <select id="f-sort" class="form-select form-select-sm" onchange="loadFiles(1)">
                            <option value="date_desc">Newest First</option>
                            <option value="date_asc">Oldest First</option>
                            <option value="size_desc">Largest First</option>
                            <option value="size_asc">Smallest First</option>
                            <option value="name_asc">Name A→Z</option>
                        </select>
                    </div>

                    <div class="col-auto ms-auto d-flex align-items-center gap-2">
                        <span class="text-muted small">Rows:</span>
                        <select id="per-page" class="form-select form-select-sm" style="width:75px"
                            onchange="loadFiles(1)">
                            <option value="10">10</option>
                            <option value="15" selected>15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <small class="text-muted" id="hist-meta">—</small>
                    </div>

                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrap">
                <div class="table-loading" id="table-loading">
                    <div class="spinner-border text-secondary" role="status"></div>
                    <small class="text-muted">Reading FTP directory…</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Dump File</th>
                                <th>Schema</th>
                                <th>Backup Date</th>
                                <th class="text-end">File Size</th>
                                <th style="width:130px">Size (relative)</th>
                            </tr>
                        </thead>
                        <tbody id="files-body">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Loading from FTP…
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-light d-flex align-items-center
                    justify-content-between flex-wrap gap-2 d-none"
                id="pagination-bar">
                <small class="text-muted" id="page-info">—</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="page-btns"></ul>
                </nav>
            </div>

        </div>

    </div>

    {{-- ── Output Modal ─────────────────────────────────────────── --}}
    <div class="modal fade" id="modal-output" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        <i class="bi bi-terminal me-1"></i>
                        Backup Output — <span id="out-dumpfile" class="font-monospace small"></span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pb-2">
                    <div class="d-flex gap-2 align-items-center mb-2 flex-wrap">
                        <span class="badge bg-secondary" id="out-schema">—</span>
                        <span id="out-status-badge">—</span>
                        <span class="text-muted small ms-auto" id="out-time">—</span>
                    </div>
                    <div class="output-log" id="out-log">—</div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Confirm Delete FTP File Modal ──────────────────────── --}}
    <div class="modal fade" id="modal-ftp-delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger bg-opacity-10 border-danger">
                    <h6 class="modal-title fw-bold text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>Delete Backup File
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-2">
                        This will <strong>permanently delete</strong> the file from the FTP server.
                        This action cannot be undone.
                    </p>
                    <div class="alert alert-warning py-2 mb-0">
                        <i class="bi bi-file-earmark-zip me-1"></i>
                        <strong id="del-filename-display">—</strong>
                        <span class="text-muted small ms-2" id="del-size-display"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger fw-bold" id="btn-confirm-ftp-del"
                        onclick="doFtpDelete()">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="sp-ftp-del" role="status"></span>
                        <i class="bi bi-trash me-1"></i>Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const bsOutput = new bootstrap.Modal(document.getElementById('modal-output'));
        const schemaKeys = @json(array_keys($schemas));

        // ─────────────────────────────────────────────────────────────
        //  Alert
        // ─────────────────────────────────────────────────────────────
        function showAlert(msg, type = 'success') {
            const el = document.getElementById('fb-alert');
            el.className = `alert alert-${type} alert-dismissible fade show`;
            el.innerHTML = msg + `<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ─────────────────────────────────────────────────────────────
        //  FTP Test
        // ─────────────────────────────────────────────────────────────
        function testFtp() {
            const dot = document.getElementById('ftp-dot');
            const txt = document.getElementById('ftp-status-txt');
            dot.className = 'ftp-dot pending';
            txt.textContent = 'Testing FTP…';

            fetch('{{ route('admin.schema-backup.ftp-test') }}')
                .then(r => r.json())
                .then(d => {
                    dot.className = d.success ? 'ftp-dot ok' : 'ftp-dot fail';
                    txt.textContent = d.success ?
                        `FTP OK — ${d.host}${d.path} (${d.message})` :
                        d.message;
                })
                .catch(() => {
                    dot.className = 'ftp-dot fail';
                    txt.textContent = 'FTP check failed.';
                });
        }

        // ─────────────────────────────────────────────────────────────
        //  Run backup for one schema  (server-side exec)
        // ─────────────────────────────────────────────────────────────
        function setRunning(key, running) {
            const btn = document.getElementById(`btn-run-${key}`);
            const sp = document.getElementById(`sp-${key}`);
            const icon = document.getElementById(`icon-${key}`);
            const status = document.getElementById(`status-${key}`);

            btn.disabled = running;
            sp.classList.toggle('d-none', !running);
            icon.classList.toggle('d-none', running);
            if (status) status.classList.toggle('d-none', !running);
        }

        function runBackup(key) {
            setRunning(key, true);

            const txt = document.getElementById(`status-txt-${key}`);
            if (txt) txt.textContent = `Running ${key} backup… (this may take several minutes)`;

            fetch('{{ route('admin.schema-backup.run') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        schema_key: key
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    setRunning(key, false);

                    const type = data.success ? 'success' : 'danger';
                    const viewBtn = data.output ?
                        ` <button class="btn btn-sm btn-outline-${type} ms-2"
                  onclick='showOutput("${key}", "${data.dumpfile}", ${JSON.stringify(data.success)}, ${JSON.stringify(data.output)})'>
                  <i class="bi bi-terminal me-1"></i>View Output</button>` :
                        '';

                    showAlert(data.message + viewBtn, type);
                    if (data.success) loadFiles(1);
                })
                .catch(() => {
                    setRunning(key, false);
                    showAlert(`Network error running backup for <strong>${key}</strong>.`, 'danger');
                });
        }

        // ─────────────────────────────────────────────────────────────
        //  Run All — sequential
        // ─────────────────────────────────────────────────────────────
        async function runAll() {
            setRunning('ALL', true);
            let allOk = true;

            for (const key of schemaKeys) {
                const txt = document.getElementById('status-txt-ALL');
                if (txt) txt.textContent = `Backing up ${key}… (may take several minutes)`;

                try {
                    const res = await fetch('{{ route('admin.schema-backup.run') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            schema_key: key
                        }),
                    });
                    const data = await res.json();
                    if (!data.success) allOk = false;
                    showAlert(`<strong>${key}:</strong> ${data.message}`, data.success ? 'success' : 'danger');
                } catch (e) {
                    allOk = false;
                    showAlert(`Network error on <strong>${key}</strong>.`, 'danger');
                }
            }

            setRunning('ALL', false);
            if (allOk) showAlert('All schema backups completed successfully.', 'success');
            loadFiles(1);
        }

        // ─────────────────────────────────────────────────────────────
        //  Show output modal
        // ─────────────────────────────────────────────────────────────
        function showOutput(schemaKey, dumpfile, success, output) {
            document.getElementById('out-dumpfile').textContent = dumpfile;
            document.getElementById('out-schema').textContent = schemaKey;
            document.getElementById('out-time').textContent = new Date().toLocaleString();
            document.getElementById('out-status-badge').innerHTML = success ?
                `<span class="badge bg-success">Success</span>` :
                `<span class="badge bg-danger">Failed</span>`;

            const lines = (output || '(no output captured)').split('\n');
            document.getElementById('out-log').innerHTML = lines.map(line => {
                const l = line.toLowerCase();
                if (l.includes('successfully') || l.includes('completed'))
                    return `<span class="log-ok">${esc(line)}</span>`;
                if (l.includes('error') || l.includes('ora-') || l.includes('failed'))
                    return `<span class="log-err">${esc(line)}</span>`;
                if (l.includes('warning'))
                    return `<span class="log-warn">${esc(line)}</span>`;
                return esc(line);
            }).join('\n');

            bsOutput.show();
        }

        // ─────────────────────────────────────────────────────────────
        //  Load FTP file list
        // ─────────────────────────────────────────────────────────────
        function loadFiles(page) {
            page = page || 1;
            const schemaKey = document.getElementById('f-schema').value;
            const sort = document.getElementById('f-sort').value;
            const perPage = document.getElementById('per-page').value;

            const params = new URLSearchParams({
                page,
                per_page: perPage,
                sort
            });
            if (schemaKey) params.set('schema_key', schemaKey);

            document.getElementById('table-loading').classList.add('show');

            fetch(`{{ route('admin.schema-backup.ftp-list') }}?${params}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('table-loading').classList.remove('show');

                    if (!data.success) {
                        document.getElementById('files-body').innerHTML =
                            `<tr><td colspan="6" class="text-center text-danger py-4" colspan="7">
                        <i class="bi bi-exclamation-triangle me-2"></i>${esc(data.message)}</td></tr>`;
                        document.getElementById('pagination-bar').classList.add('d-none');
                        // Update FTP dot to fail
                        document.getElementById('ftp-dot').className = 'ftp-dot fail';
                        document.getElementById('ftp-status-txt').textContent = data.message;
                        return;
                    }

                    const s = data.summary;
                    document.getElementById('summary-txt').textContent =
                        `${s.total_files} file(s) · ${s.total_size}`;
                    document.getElementById('hist-meta').innerHTML =
                        `<strong>${data.meta.total}</strong> file(s)`;

                    renderRows(data.data, data.meta.from);
                    renderPagination(data.meta);
                })
                .catch(err => {
                    document.getElementById('table-loading').classList.remove('show');
                    showAlert('Network error loading FTP file list.', 'danger');
                });
        }

        // ─────────────────────────────────────────────────────────────
        //  Render table rows
        // ─────────────────────────────────────────────────────────────
        let maxSize = 1;

        function renderRows(rows, fromIdx) {
            const tbody = document.getElementById('files-body');

            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">
            No .dmp files found in the FTP directory.</td></tr>`;
                return;
            }

            maxSize = Math.max(...rows.map(r => r.size_bytes || 0), 1);

            const badgeMap = {
                'F_STORE': 'warning',
                'HRM': 'info'
            };

            tbody.innerHTML = rows.map((r, i) => {
                const badge = badgeMap[r.schema_key] ?? 'secondary';
                const barPct = Math.round((r.size_bytes / maxSize) * 100);
                const fn = esc(r.filename);

                return `<tr>
            <td class="text-muted small">${fromIdx + i}</td>
            <td class="font-monospace small fw-semibold">${fn}</td>
            <td>
                ${r.schema_key
                    ? `<span class="badge bg-${badge}">${r.schema_key}</span>
                               <small class="text-muted d-block">${r.label}</small>`
                    : `<span class="badge bg-secondary">Unknown</span>`}
            </td>
            <td class="small">${r.backup_date ?? '<span class="text-muted">—</span>'}</td>
            <td class="text-end fw-semibold">${r.size_human}</td>
            <td class="pe-3">
                <div class="size-bar-bg">
                    <div class="size-bar" style="width:${barPct}%"></div>
                </div>
                <small class="text-muted" style="font-size:.68rem">${barPct}%</small>
            </td>
            <td class="text-center">
                <a href="{{ route('admin.schema-backup.ftp-download') }}?filename=${r.filename}"
                   class="btn btn-outline-primary btn-sm me-1"
                   title="Download .dmp" download>
                    <i class="bi bi-download"></i>
                </a>
                <button class="btn btn-outline-danger btn-sm"
                        title="Delete from FTP"
                        onclick="confirmDelete('${r.filename}', '${r.size_human}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
            }).join('');
        }

        // ─────────────────────────────────────────────────────────────
        //  Pagination
        // ─────────────────────────────────────────────────────────────
        function renderPagination(m) {
            const bar = document.getElementById('pagination-bar');
            const info = document.getElementById('page-info');
            const ul = document.getElementById('page-btns');

            if (m.total === 0) {
                bar.classList.add('d-none');
                return;
            }
            bar.classList.remove('d-none');

            info.innerHTML = `Showing <strong>${m.from}</strong>–<strong>${m.to}</strong>
        of <strong>${m.total}</strong> files`;

            const pages = buildPageList(m.page, m.last_page);
            ul.innerHTML =
                `<li class="page-item ${m.page <= 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="go(event,${m.page-1})">‹</a>
        </li>` +
                pages.map(p =>
                    p === '…' ?
                    `<li class="page-item disabled"><span class="page-link">…</span></li>` :
                    `<li class="page-item ${p === m.page ? 'active' : ''}">
                       <a class="page-link" href="#" onclick="go(event,${p})">${p}</a>
                   </li>`
                ).join('') +
                `<li class="page-item ${m.page >= m.last_page ? 'disabled' : ''}">
               <a class="page-link" href="#" onclick="go(event,${m.page+1})">›</a>
           </li>`;
        }

        function go(e, page) {
            e.preventDefault();
            loadFiles(page);
        }

        function buildPageList(current, last) {
            if (last <= 7) return Array.from({
                length: last
            }, (_, i) => i + 1);
            const pages = [1];
            if (current > 3) pages.push('…');
            for (let p = Math.max(2, current - 1); p <= Math.min(last - 1, current + 1); p++) pages.push(p);
            if (current < last - 2) pages.push('…');
            pages.push(last);
            return pages;
        }

        // ─────────────────────────────────────────────────────────────
        //  Helpers
        // ─────────────────────────────────────────────────────────────
        function esc(s) {
            return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }


        // ─────────────────────────────────────────────────────────────
        //  FTP Delete
        // ─────────────────────────────────────────────────────────────
        let pendingDeleteFile = null;
        const bsFtpDelete = new bootstrap.Modal(document.getElementById('modal-ftp-delete'));

        function confirmDelete(filename, sizeHuman) {
            pendingDeleteFile = filename;
            document.getElementById('del-filename-display').textContent = filename;
            document.getElementById('del-size-display').textContent = sizeHuman;
            bsFtpDelete.show();
        }

        function doFtpDelete() {
            if (!pendingDeleteFile) return;

            const sp = document.getElementById('sp-ftp-del');
            const btn = document.getElementById('btn-confirm-ftp-del');
            sp.classList.remove('d-none');
            btn.disabled = true;

            fetch('{{ route('admin.schema-backup.ftp-delete') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        filename: pendingDeleteFile
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    bsFtpDelete.hide();
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    if (data.success) loadFiles(1);
                })
                .catch(() => {
                    bsFtpDelete.hide();
                    showAlert('Network error during delete.', 'danger');
                })
                .finally(() => {
                    sp.classList.add('d-none');
                    btn.disabled = false;
                    pendingDeleteFile = null;
                });
        }

        // ─────────────────────────────────────────────────────────────
        //  Init
        // ─────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            testFtp();
            loadFiles(1);
        });
    </script>
@endpush
