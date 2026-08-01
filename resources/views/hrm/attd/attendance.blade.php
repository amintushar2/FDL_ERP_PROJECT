{{-- resources/views/hrm/attd/attendance.blade.php --}}
@extends('layouts.app')

@push('styles')
    <style>
        /* ── HRM module color scheme (navy/teal) ─────────────────── */
        :root {
            --brand: #1a3a5c;
            --brand2: #1e6b8a;
            --accent: #2e9ab7;
            --accent-lt: #e8f4f8;
            --border: #c0d8e8;
            --text-dim: #5a7a94;
            --row-odd: #f2f8fb;
            --row-even: #ffffff;
        }

        .atnd-wrap {
            background: #f4f7fb;
            min-height: 100vh;
            padding: 0;
        }

        /* ── TOOLBAR ─────────────────────────────────────────────── */
        .atnd-toolbar {
            background: linear-gradient(135deg, #1a3a5c 0%, #1e6b8a 100%);
            border-bottom: 2px solid #2e9ab7;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 3px 12px rgba(26, 58, 92, .30);
        }

        .toolbar-title {
            font-size: .88rem;
            font-weight: 700;
            color: #7ecfe0;
            letter-spacing: .4px;
            margin-right: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tdiv {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, .2);
            margin: 0 3px;
        }

        .btn-t {
            font-size: .75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 3px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
            color: #fff;
        }

        .btn-t:hover {
            filter: brightness(1.15);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, .3);
        }

        .btn-t:active {
            transform: translateY(0);
        }

        .btn-t .bi {
            font-size: .82rem;
        }

        /* Individual button styles */
        .b-fetch {
            background: #1976d2;
            border-color: #82b1ff;
        }

        .b-fetch .bi {
            color: #bbdefb;
        }

        .b-del {
            background: #c62828;
            border-color: #ef9a9a;
        }

        .b-del .bi {
            color: #ffcdd2;
        }

        .b-old {
            background: #880e4f;
            border-color: #f48fb1;
        }

        .b-old .bi {
            color: #fce4ec;
        }

        .b-proc {
            background: #2e7d32;
            border-color: #69f0ae;
        }

        .b-proc .bi {
            color: #c8e6c9;
        }

        .b-dev {
            background: #1565c0;
            border-color: #82b1ff;
        }

        .b-dev .bi {
            color: #bbdefb;
        }

        .b-ref {
            background: #37474f;
            border-color: #90a4ae;
        }

        .b-ref .bi {
            color: #cfd8dc;
        }

        .b-down {
            background: #00695c;
            border-color: #64ffda;
        }

        .b-down .bi {
            color: #b2dfdb;
        }

        .mode-badge {
            margin-left: auto;
            padding: 3px 14px;
            border-radius: 14px;
            font-size: .70rem;
            font-weight: 700;
            letter-spacing: .6px;
            border: 1.5px solid rgba(255, 255, 255, .5);
            color: #fff;
            background: rgba(255, 255, 255, .12);
        }

        /* ── MACHINE BAR ─────────────────────────────────────────── */
        .mbar {
            background: #e0eef6;
            border-bottom: 1px solid var(--border);
            padding: 5px 14px;
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .mbar-lbl {
            font-size: .70rem;
            color: var(--text-dim);
            font-weight: 700;
        }

        .mchip {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 2px 10px;
            font-size: .70rem;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: border-color .15s;
        }

        .mchip:hover {
            border-color: var(--accent);
        }

        .mchip.sel {
            border-color: var(--accent);
            background: var(--accent-lt);
        }

        .mchip .ip {
            color: var(--text-dim);
            font-size: .64rem;
        }

        .led {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .l-on {
            background: #43a047;
            box-shadow: 0 0 4px #43a047;
            animation: pulse-led 1.5s infinite;
        }

        .l-off {
            background: #bbb;
        }

        @keyframes pulse-led {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .35
            }
        }

        /* ── FILTER BAR ──────────────────────────────────────────── */
        .fbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 7px 14px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .fbar label {
            font-size: .72rem;
            color: var(--text-dim);
            font-weight: 600;
            white-space: nowrap;
        }

        .fbar .form-control,
        .fbar .form-select {
            font-size: .76rem;
            padding: 3px 8px;
            height: 28px;
            border: 1px solid var(--border);
            border-radius: 3px;
            color: #1a2f45;
        }

        .fbar .form-control:focus,
        .fbar .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(46, 154, 183, .15);
            outline: none;
        }

        .rc {
            margin-left: auto;
            font-size: .70rem;
            color: var(--text-dim);
        }

        .rc span {
            color: var(--brand);
            font-weight: 700;
        }

        /* ── PROCESS PANEL ───────────────────────────────────────── */
        .ppanel {
            background: linear-gradient(135deg, rgba(46, 125, 50, .08), rgba(46, 154, 183, .06));
            border: 1px solid rgba(67, 160, 71, .35);
            border-radius: 5px;
            margin: 8px 14px;
            padding: 10px 14px;
            display: none;
        }

        .ppanel.show {
            display: block;
        }

        .pp-title {
            font-size: .72rem;
            font-weight: 700;
            color: #2e7d32;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── DATA TABLE ──────────────────────────────────────────── */
        .tbl-wrap {
            overflow: auto;
        }

        .at-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
        }

        .at-tbl thead th {
            background: var(--brand);
            color: #fff;
            padding: 7px 10px;
            font-weight: 600;
            font-size: .70rem;
            text-transform: uppercase;
            letter-spacing: .6px;
            border: none;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .at-tbl thead th:first-child {
            width: 34px;
            text-align: center;
        }

        .at-tbl tbody tr:nth-child(odd) {
            background: var(--row-odd);
        }

        .at-tbl tbody tr:nth-child(even) {
            background: var(--row-even);
        }

        .at-tbl tbody tr:hover {
            background: #dff0f7;
        }

        .at-tbl tbody td {
            padding: 5px 10px;
            border-bottom: 1px solid #dce8ef;
            vertical-align: middle;
            color: #1a2f45;
        }

        .at-tbl tbody td:first-child {
            text-align: center;
        }

        .mb {
            background: rgba(46, 154, 183, .15);
            border: 1px solid var(--accent);
            color: var(--brand);
            padding: 1px 6px;
            border-radius: 9px;
            font-size: .66rem;
            font-weight: 700;
        }

        .tc {
            font-family: 'Courier New', monospace;
            color: #1b5e20;
            font-weight: 600;
        }

        .cc {
            color: #1565c0;
        }

        .nc {
            color: #6a1b9a;
        }

        .sc {
            color: #e65100;
            font-weight: 700;
        }

        .at-tbl tfoot td {
            font-weight: 700;
            font-size: .74rem;
            background: #e0eef6;
            border-top: 2px solid var(--brand2);
            padding: 5px 10px;
        }

        /* ── TABLE FOOTER ────────────────────────────────────────── */
        .tfoot {
            background: #e8f0f7;
            border-top: 1px solid var(--border);
            padding: 5px 14px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .si {
            font-size: .68rem;
            color: var(--text-dim);
        }

        .si span {
            color: var(--brand);
            font-weight: 700;
        }

        /* ── MODALS ──────────────────────────────────────────────── */
        .modal-header {
            background: var(--brand);
            color: #fff;
            padding: 9px 14px;
        }

        .modal-title {
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
        }

        .modal-content {
            border: none;
            border-radius: 5px;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 9px 14px;
        }

        .modal-body {
            padding: 14px;
        }

        .modal-body .form-label {
            font-size: .74rem;
            font-weight: 600;
            color: #344a60;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            font-size: .78rem;
            border: 1px solid var(--border);
            border-radius: 3px;
            color: #1a2f45;
            padding: 4px 8px;
            height: 30px;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(46, 154, 183, .15);
            outline: none;
        }

        .modal-body table thead th {
            background: var(--brand);
            color: #fff;
            font-size: .70rem;
            padding: 6px 8px;
            letter-spacing: .5px;
        }

        .modal-body table tbody td {
            font-size: .75rem;
            padding: 5px 8px;
        }

        .modal-body table tbody tr:hover {
            background: var(--row-odd);
        }

        .warn-box {
            background: #fff3e0;
            border: 1px solid #ffcc80;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: .76rem;
            color: #e65100;
            margin-bottom: 10px;
        }

        /* Fetch log */
        .flog {
            background: #f8f9fa;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 8px;
            font-family: 'Courier New', monospace;
            font-size: .70rem;
            color: #1a2f45;
            max-height: 160px;
            overflow-y: auto;
            line-height: 1.7;
            margin-top: 8px;
        }

        .li {
            color: #1565c0;
        }

        .lok {
            color: #2e7d32;
            font-weight: 600;
        }

        .lwarn {
            color: #e65100;
        }

        .lerr {
            color: #c62828;
        }

        .spin {
            animation: spin .8s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Device chip in delete modal */
        .dev-chk-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .78rem;
            padding: 6px 10px;
            background: #f8fbfd;
            border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 5px;
        }

        input[type=checkbox] {
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* Download format toggle */
        .fmt-group {
            display: flex;
            gap: 8px;
        }

        .fmt-opt {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .76rem;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="atnd-wrap">

        {{-- ══════════ TOOLBAR ═════════════════════════════════════════════ --}}
        <div class="atnd-toolbar">
            <div class="toolbar-title">
                <i class="bi bi-fingerprint"></i> ZKTeco Attendance
            </div>
            <div class="tdiv"></div>
            <button class="btn-t b-fetch" id="btnFetchData">
                <i class="bi bi-cloud-download"></i> Fetch from Device
            </button>
            <button class="btn-t b-del" id="btnDeleteDevice">
                <i class="bi bi-trash3"></i> Delete from Device
            </button>
            <div class="tdiv"></div>
            <button class="btn-t b-old" id="btnDeleteOld">
                <i class="bi bi-calendar-x"></i> Delete Old Data
            </button>
            <button class="btn-t b-ref" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
            <div class="tdiv"></div>
            <button class="btn-t b-proc" id="btnProcess">
                <i class="bi bi-gear-fill"></i> Process Attendance
            </button>
            <div class="tdiv"></div>
            <button class="btn-t b-down" id="btnDownload">
                <i class="bi bi-download"></i> Download
            </button>
            <div class="tdiv"></div>
            <button class="btn-t b-dev" id="btnManageDevices">
                <i class="bi bi-hdd-network"></i> Manage Devices
            </button>
            <span class="mode-badge">● LIVE</span>
        </div>

        {{-- ══════════ MACHINE STATUS BAR ══════════════════════════════════ --}}
        <div class="mbar">
            <span class="mbar-lbl"><i class="bi bi-hdd-network me-1"></i>Devices:</span>
            <span id="machineChips" style="display:flex;gap:6px;flex-wrap:wrap;">
                <span style="font-size:.70rem;color:var(--text-dim);">Loading…</span>
            </span>
        </div>

        {{-- ══════════ FILTER BAR ══════════════════════════════════════════ --}}
        <div class="fbar">
            <label>Machine:</label>
            <select id="filterMachine" class="form-select" style="width:140px;" onchange="loadData()">
                <option value="">All</option>
            </select>
            <label>Date From:</label>
            <input type="text" id="filterFrom" class="form-control flatpickr-date" style="width:120px;"
                placeholder="dd-Mon-yyyy">
            <label>Date To:</label>
            <input type="text" id="filterTo" class="form-control flatpickr-date" style="width:120px;"
                placeholder="dd-Mon-yyyy">
            <label>Card No:</label>
            <input type="text" id="filterCard" class="form-control" style="width:100px;" placeholder="Card No">
            <button class="btn-t b-fetch" style="padding:3px 10px;" onclick="loadData()">
                <i class="bi bi-search"></i> Search
            </button>
            <button class="btn-t b-ref" style="padding:3px 10px;" onclick="clearFilters()">
                <i class="bi bi-x-circle"></i> Clear
            </button>
            <div class="rc">Showing: <span id="recCount">0</span> records</div>
        </div>

        {{-- ══════════ PROCESS PANEL ═══════════════════════════════════════ --}}
        <div class="ppanel" id="processPanel">
            <div class="pp-title">
                <i class="bi bi-gear-fill"></i> Attendance Process – TAT_ATTENDANCE_PROCESS(:company_id)
            </div>
            <div class="d-flex gap-2 align-items-end flex-wrap">
                <div>
                    <label class="form-label"
                        style="font-size:.72rem;font-weight:600;color:#344a60;display:block;margin-bottom:3px;">
                        Company <span class="text-danger">*</span>
                    </label>
                    <select id="processCompany" class="form-select"
                        style="font-size:.78rem;height:30px;padding:3px 8px;min-width:220px;">
                        <option value="">— Select Company —</option>
                        @foreach ($companies ?? [] as $c)
                            <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-t b-proc" id="btnRunProcess" style="height:30px;">
                    <i class="bi bi-play-circle-fill"></i> Run TAT Process
                </button>
                <button class="btn-t b-ref" style="height:30px;padding:4px 10px;"
                    onclick="document.getElementById('processPanel').classList.remove('show');
                         document.getElementById('processLog').style.display='none';">
                    <i class="bi bi-x"></i> Close
                </button>
            </div>
            <div class="flog" id="processLog" style="display:none;margin-top:8px;"></div>
        </div>

        {{-- ══════════ DATA TABLE ═══════════════════════════════════════════ --}}
        <div class="tbl-wrap" style="max-height:calc(100vh - 230px);overflow:auto;">
            <table class="at-tbl">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="chkAll" onchange="toggleAll(this)"></th>
                        <th>Mach No</th>
                        <th>Card No</th>
                        <th>Attendance Date</th>
                        <th>Attendance Time</th>
                        <th>Name</th>
                        <th>Shift</th>
                    </tr>
                </thead>
                <tbody id="atndBody">
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4" style="font-size:.80rem;">
                            <i class="bi bi-fingerprint me-2" style="font-size:1.5rem;opacity:.3;"></i><br>
                            Click <strong>Fetch from Device</strong> or use filters to load attendance data.
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Total Records:</td>
                        <td id="ftTotal" colspan="4">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="tfoot">
            <div class="si">Total: <span id="stTotal">0</span></div>
            <div class="si">Selected: <span id="stSel">0</span></div>
            <div class="si">Machines: <span id="stMach">0</span></div>
            <div class="si" style="margin-left:auto;font-size:.65rem;color:var(--text-dim);" id="lastLoad"></div>
        </div>

        {{-- ══════════ FETCH MODAL ══════════════════════════════════════════ --}}
        <div class="modal fade" id="fetchModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="bi bi-cloud-download me-2"></i>Fetch Attendance from Devices
                        </h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Date From</label>
                                <input type="text" id="fetchFrom" class="form-control flatpickr-date"
                                    placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date To</label>
                                <input type="text" id="fetchTo" class="form-control flatpickr-date"
                                    placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Device</label>
                                <select id="fetchDevice" class="form-select">
                                    <option value="all">All Devices</option>
                                </select>
                            </div>
                        </div>

                        <p
                            style="font-size:.72rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.7px;margin-bottom:5px;">
                            <i class="bi bi-hdd-network me-1"></i>Device Status
                        </p>
                        <table class="table table-sm table-bordered" id="fetchDeviceTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="chkAllDevices"></th>
                                    <th>Mach</th>
                                    <th>Device Name</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>New Records</th>
                                </tr>
                            </thead>
                            <tbody id="fetchDeviceBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-2">Loading…</td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="fetchLogWrap" style="display:none;">
                            <p
                                style="font-size:.70rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.7px;margin-bottom:3px;">
                                <i class="bi bi-terminal me-1"></i>Progress
                            </p>
                            <div class="flog" id="fetchLog"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-t b-ref btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>
                            Cancel</button>
                        <button class="btn-t b-fetch" id="btnStartFetch">
                            <i class="bi bi-play-fill"></i> Start Fetch
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ DELETE OLD DATA MODAL ════════════════════════════════ --}}
        <div class="modal fade" id="deleteOldModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="bi bi-calendar-x me-2"></i>Delete Old Data from ATND_RAW</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Permanently deletes records from <strong>ATND_RAW</strong> table before the selected date.
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Delete records before: <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="deleteBeforeDate" class="form-control flatpickr-date"
                                    placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Machine (optional)</label>
                                <select id="deleteMachine" class="form-select">
                                    <option value="">All Machines</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2" id="deleteCountInfo" style="display:none;">
                            <span style="font-size:.78rem;color:var(--brand);font-weight:600;">
                                <i class="bi bi-info-circle me-1"></i>
                                <span id="deleteCountText"></span>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-t b-ref btn-sm" onclick="checkDeleteCount()">
                            <i class="bi bi-search"></i> Count Records
                        </button>
                        <button class="btn-t b-ref btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>
                            Cancel</button>
                        <button class="btn-t b-old" id="btnConfirmDelete">
                            <i class="bi bi-trash3"></i> Delete Old Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ DELETE FROM DEVICE MODAL ════════════════════════════ --}}
        <div class="modal fade" id="deleteDeviceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="bi bi-trash3 me-2"></i>Delete Data from Device</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Permanently deletes ALL attendance logs from the selected ZKTeco device(s). Cannot be undone!
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                            <label class="form-label" style="margin:0;">Select Device(s)</label>
                            <label
                                style="margin-left:auto;display:flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;cursor:pointer;">
                                <input type="checkbox" id="chkDelAllDevices" onchange="toggleDelAllDevices()">
                                <span>Select All</span>
                            </label>
                        </div>
                        <div id="deleteDeviceList">
                            <p class="text-muted" style="font-size:.76rem;">Loading devices…</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-t b-ref btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>
                            Cancel</button>
                        <button class="btn-t b-del" id="btnConfirmDeviceDelete">
                            <i class="bi bi-trash3"></i> Delete from Device
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ DOWNLOAD MODAL ══════════════════════════════════════ --}}
        <div class="modal fade" id="downloadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="bi bi-download me-2"></i>Download Attendance Data</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:.76rem;color:var(--text-dim);margin-bottom:10px;">
                            Downloads <strong>ATND_RAW</strong> data matching current filters as comma-separated file.
                            Columns: <code style="font-size:.72rem;">MACH_NO, CARD_NO, ATND_DATE, ATND_TIME, NAME,
                                ATND_SHIFT</code>
                        </p>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Date From</label>
                                <input type="text" id="dlFrom" class="form-control flatpickr-date"
                                    placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date To</label>
                                <input type="text" id="dlTo" class="form-control flatpickr-date"
                                    placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Machine (optional)</label>
                                <select id="dlMachine" class="form-select">
                                    <option value="">All Machines</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Card No (optional)</label>
                                <input type="text" id="dlCard" class="form-control" placeholder="All">
                            </div>
                        </div>
                        <label class="form-label">File Format</label>
                        <div class="fmt-group">
                            <label class="fmt-opt">
                                <input type="radio" name="dlFormat" value="csv" checked> .CSV (Excel compatible)
                            </label>
                            <label class="fmt-opt">
                                <input type="radio" name="dlFormat" value="txt"> .TXT (plain text)
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-t b-ref btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>
                            Cancel</button>
                        <button class="btn-t b-down" onclick="downloadNow()">
                            <i class="bi bi-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ MANAGE DEVICES MODAL ════════════════════════════════ --}}
        <div class="modal fade" id="devicesModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="bi bi-hdd-network me-2"></i>Manage Attendance Devices</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Add/Edit Form --}}
                        <div class="p-3 mb-3"
                            style="background:var(--accent-lt);border:1px solid var(--accent);border-radius:5px;">
                            <p
                                style="font-size:.70rem;font-weight:700;color:var(--brand2);text-transform:uppercase;
                              letter-spacing:.7px;margin-bottom:8px;">
                                <i class="bi bi-plus-circle me-1"></i> Add / Edit Device
                            </p>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label">Machine No <span class="text-danger">*</span></label>
                                    <input type="number" id="devMachineNo" class="form-control" placeholder="1">
                                    <input type="hidden" id="devEditId">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">IP Address <span class="text-danger">*</span></label>
                                    <input type="text" id="devIp" class="form-control"
                                        placeholder="192.168.1.201">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" id="devName" class="form-control" placeholder="Main Gate">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn-t b-dev btn-sm" id="btnSaveDevice">
                                    <i class="bi bi-save2"></i> Save
                                </button>
                                <button class="btn-t b-ref btn-sm" id="btnClearDevForm">
                                    <i class="bi bi-x-circle"></i> Clear
                                </button>
                                <button class="btn-t b-fetch btn-sm ms-auto" id="btnTestConn">
                                    <i class="bi bi-wifi"></i> Test Connection
                                </button>
                            </div>
                        </div>

                        {{-- Device List --}}
                        <p
                            style="font-size:.70rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;
                          letter-spacing:.7px;margin-bottom:5px;">
                            <i class="bi bi-list-ul me-1"></i> Registered Devices
                        </p>
                        <table class="table table-sm table-bordered" id="deviceListTable">
                            <thead>
                                <tr>
                                    <th>Mach</th>
                                    <th>Device Name</th>
                                    <th>IP Address</th>
                                    <th>Ping</th>
                                    <th style="width:80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="deviceListBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Loading…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-t b-ref btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /atnd-wrap --}}
@endsection

@push('scripts')
    <script>
        /* ═══════════════════════════════════════════════════════════════════
                               ZKTeco Attendance JS Controller
                            ═══════════════════════════════════════════════════════════════════ */

        const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const API = {
            devices: '/api/attendance/devices',
            fetch: '/api/attendance/fetch',
            data: '/api/attendance/data',
            download: '/api/attendance/download',
            deleteOld: '/api/attendance/delete-old',
            deleteDevice: '/api/attendance/delete-device',
            process: '/api/attendance/process',
            ping: '/api/attendance/ping',
        };

        /* ── Flatpickr ──────────────────────────────────────────────── */
        flatpickr('.flatpickr-date', {
            dateFormat: 'd-M-Y',
            allowInput: true
        });

        /* ── State ──────────────────────────────────────────────────── */
        let allDevices = [];
        let selIds = new Set();

        /* ══════════════ INIT ══════════════════════════════════════════ */
        window.addEventListener('DOMContentLoaded', () => {
            loadDevices();
            loadData();
        });

        /* ══════════════ LOAD DEVICES ═══════════════════════════════════ */
        async function loadDevices() {
            try {
                const r = await fetch(API.devices);
                const d = await r.json();
                allDevices = d.data ?? [];
                renderMachineBar(allDevices);
                populateSelects(allDevices);
                document.getElementById('stMach').textContent = allDevices.length;
            } catch (e) {
                document.getElementById('machineChips').innerHTML =
                    '<span style="font-size:.70rem;color:#c62828;">Failed to load devices</span>';
            }
        }

        function renderMachineBar(devs) {
            const w = document.getElementById('machineChips');
            if (!devs.length) {
                w.innerHTML =
                    '<span style="font-size:.70rem;color:var(--text-dim);">No devices. Click Manage Devices.</span>';
                return;
            }
            w.innerHTML = devs.map(d => `
        <div class="mchip" data-m="${d.machine_no}" onclick="filterByMachine('${d.machine_no}')">
            <span class="led l-off" id="led_${d.machine_no}"></span>
            <strong>${String(d.machine_no).padStart(3,'0')}</strong>
            ${d.device_name}
            <span class="ip">${d.m_ip}</span>
        </div>`).join('');
        }

        function populateSelects(devs) {
            const targets = ['filterMachine', 'fetchDevice', 'deleteMachine', 'dlMachine'];
            targets.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const first = el.options[0].cloneNode(true);
                el.innerHTML = '';
                el.appendChild(first);
                devs.forEach(d => {
                    const o = document.createElement('option');
                    o.value = d.machine_no;
                    o.textContent = `${String(d.machine_no).padStart(3,'0')} – ${d.device_name}`;
                    el.appendChild(o);
                });
            });

            // Fetch modal device table
            const fdb = document.getElementById('fetchDeviceBody');
            if (fdb) {
                fdb.innerHTML = devs.length ? devs.map(d => `
            <tr>
                <td><input type="checkbox" class="chk-fd" value="${d.machine_no}" checked></td>
                <td><span class="mb">${String(d.machine_no).padStart(3,'0')}</span></td>
                <td>${d.device_name}</td>
                <td class="tc">${d.m_ip}</td>
                <td><span class="led l-off" id="fled_${d.machine_no}"></span>
                    <span id="fst_${d.machine_no}" style="font-size:.66rem;color:#777;">—</span></td>
                <td><span id="frec_${d.machine_no}" style="font-size:.66rem;color:var(--brand);">—</span></td>
            </tr>`).join('') :
                    '<tr><td colspan="6" class="text-center text-muted">No devices</td></tr>';
            }

            // Delete device checkboxes
            const ddl = document.getElementById('deleteDeviceList');
            if (ddl) {
                ddl.innerHTML = devs.length ? devs.map(d => `
            <label class="dev-chk-label">
                <input type="checkbox" class="chk-del-dev" value="${d.machine_no}" onchange="updateDelAllCheckbox()">
                <strong>${String(d.machine_no).padStart(3,'0')}</strong> – ${d.device_name}
                <span style="color:var(--text-dim);font-size:.66rem;margin-left:auto;">${d.m_ip}</span>
            </label>`).join('') :
                    '<p class="text-muted" style="font-size:.76rem;">No devices registered.</p>';
                // Reset Select All checkbox when list is repopulated
                document.getElementById('chkDelAllDevices').checked = false;
            }
        }

        /* ══════════════ LOAD DATA ══════════════════════════════════════ */
        async function loadData() {
            const params = buildFilterParams();
            const tbody = document.getElementById('atndBody');

            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">
        <i class="bi bi-hourglass-split spin me-2"></i>Loading…</td></tr>`;

            try {
                const r = await fetch(`${API.data}?${params}`);
                const d = await r.json();
                const rows = d.data ?? [];
                renderTable(rows);
                document.getElementById('recCount').textContent = rows.length;
                document.getElementById('stTotal').textContent = rows.length;
                document.getElementById('ftTotal').textContent = rows.length;
                document.getElementById('lastLoad').textContent = 'Loaded: ' + new Date().toLocaleTimeString();
            } catch (e) {
                tbody.innerHTML =
                    `<tr><td colspan="7" class="text-center text-danger py-3">Error: ${e.message}</td></tr>`;
            }
        }

        function buildFilterParams() {
            const p = new URLSearchParams();
            const m = document.getElementById('filterMachine').value;
            const f = document.getElementById('filterFrom').value;
            const t = document.getElementById('filterTo').value;
            const c = document.getElementById('filterCard').value.trim();
            if (m) p.set('machine_no', m);
            if (f) p.set('date_from', f);
            if (t) p.set('date_to', t);
            if (c) p.set('card_no', c);
            return p;
        }

        function renderTable(rows) {
            const tbody = document.getElementById('atndBody');
            selIds.clear();
            updateSel();

            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4" style="font-size:.80rem;">
            <i class="bi bi-inbox" style="font-size:1.4rem;opacity:.3;"></i><br>
            No attendance records found.</td></tr>`;
                return;
            }

            tbody.innerHTML = rows.map((r, i) => `
        <tr>
            <td><input type="checkbox" class="chk-r" value="${i}" onchange="onChk(this)"></td>
            <td><span class="mb">${r.mach_no ?? ''}</span></td>
            <td class="cc">${r.card_no ?? ''}</td>
            <td>${r.atnd_date ?? ''}</td>
            <td class="tc">${fmtTime(r.atnd_time)}</td>
            <td class="nc">${r.name ?? '—'}</td>
            <td class="sc">${r.atnd_shift ?? '—'}</td>
        </tr>`).join('');
        }

        function fmtTime(t) {
            if (!t) return '—';
            const s = String(t).padStart(6, '0');
            return `${s.slice(0,2)}:${s.slice(2,4)}:${s.slice(4,6)}`;
        }

        /* ══════════════ CHECKBOXES ══════════════════════════════════════ */
        document.getElementById('chkAll').addEventListener('change', function() {
            document.querySelectorAll('.chk-r').forEach(c => {
                c.checked = this.checked;
                this.checked ? selIds.add(c.value) : selIds.delete(c.value);
            });
            updateSel();
        });

        function onChk(el) {
            el.checked ? selIds.add(el.value) : selIds.delete(el.value);
            updateSel();
        }

        function updateSel() {
            document.getElementById('stSel').textContent = selIds.size;
        }

        function filterByMachine(m) {
            document.getElementById('filterMachine').value = m;
            document.querySelectorAll('.mchip').forEach(c =>
                c.classList.toggle('sel', c.dataset.m == m));
            loadData();
        }

        function clearFilters() {
            document.getElementById('filterMachine').value = '';
            document.getElementById('filterCard').value = '';
            document.querySelectorAll('#filterFrom,#filterTo').forEach(el => {
                const fp = el._flatpickr;
                fp ? fp.clear() : (el.value = '');
            });
            document.querySelectorAll('.mchip').forEach(c => c.classList.remove('sel'));
            loadData();
        }

        /* ══════════════ TOOLBAR BUTTONS ═══════════════════════════════ */

        document.getElementById('btnFetchData').onclick = () => {
            populateSelects(allDevices);
            new bootstrap.Modal(document.getElementById('fetchModal')).show();
        };

        document.getElementById('btnDeleteDevice').onclick = () => {
            populateSelects(allDevices);
            new bootstrap.Modal(document.getElementById('deleteDeviceModal')).show();
        };

        document.getElementById('btnDeleteOld').onclick = () => {
            document.getElementById('deleteCountInfo').style.display = 'none';
            new bootstrap.Modal(document.getElementById('deleteOldModal')).show();
        };

        document.getElementById('btnRefresh').onclick = () => loadData();

        document.getElementById('btnProcess').onclick = () => {
            const p = document.getElementById('processPanel');
            p.classList.toggle('show');
            if (p.classList.contains('show')) document.getElementById('processCompany').focus();
        };

        document.getElementById('btnDownload').onclick = () => {
            // Pre-fill download modal with current filters
            const fm = document.getElementById('filterMachine').value;
            const ff = document.getElementById('filterFrom').value;
            const ft = document.getElementById('filterTo').value;
            const fc = document.getElementById('filterCard').value;
            if (fm) document.getElementById('dlMachine').value = fm;
            if (ff) document.getElementById('dlFrom')._flatpickr?.setDate(ff, false, 'd-M-Y');
            if (ft) document.getElementById('dlTo')._flatpickr?.setDate(ft, false, 'd-M-Y');
            if (fc) document.getElementById('dlCard').value = fc;
            populateSelects(allDevices);
            new bootstrap.Modal(document.getElementById('downloadModal')).show();
        };

        document.getElementById('btnManageDevices').onclick = () => {
            loadDeviceList();
            new bootstrap.Modal(document.getElementById('devicesModal')).show();
        };

        /* ══════════════ FETCH FROM DEVICE ══════════════════════════════ */
        document.getElementById('btnStartFetch').onclick = async () => {
            const btn = document.getElementById('btnStartFetch');
            const log = document.getElementById('fetchLog');
            const logWrap = document.getElementById('fetchLogWrap');
            const selDevs = [...document.querySelectorAll('.chk-fd:checked')].map(c => c.value);

            if (!selDevs.length)
                return toast('warning', 'Select at least one device');

            logWrap.style.display = 'block';
            log.innerHTML = '';
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split spin"></i> Fetching…';

            const addLog = (msg, cls = 'li') => {
                const ts = new Date().toLocaleTimeString();
                log.innerHTML += `<div class="${cls}">[${ts}] ${msg}</div>`;
                log.scrollTop = log.scrollHeight;
            };

            addLog('Starting fetch operation…');
            let total = 0;

            for (const machNo of selDevs) {
                const dev = allDevices.find(d => d.machine_no == machNo);
                if (!dev) continue;
                addLog(`Connecting to ${dev.device_name} (${dev.m_ip})…`);

                const stEl = document.getElementById(`fst_${machNo}`);
                if (stEl) stEl.textContent = 'Connecting…';

                try {
                    const r = await fetch(API.fetch, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            machine_no: machNo,
                            ip: dev.m_ip,
                            date_from: document.getElementById('fetchFrom').value,
                            date_to: document.getElementById('fetchTo').value,
                        }),
                    });
                    const d = await r.json();
                    if (!r.ok) throw new Error(d.message ?? 'Fetch failed');

                    const ins = d.inserted ?? 0;
                    total += ins;
                    addLog(`✔ ${dev.device_name}: ${d.fetched} fetched, ${ins} new inserted.`, 'lok');
                    const led = document.getElementById(`led_${machNo}`);
                    if (led) {
                        led.classList.remove('l-off');
                        led.classList.add('l-on');
                    }
                    if (stEl) {
                        stEl.textContent = 'Done';
                        stEl.style.color = '#2e7d32';
                    }
                    const rEl = document.getElementById(`frec_${machNo}`);
                    if (rEl) rEl.textContent = `${ins} new`;

                } catch (e) {
                    addLog(`✘ ${dev.device_name}: ${e.message}`, 'lerr');
                    if (stEl) {
                        stEl.textContent = 'Error';
                        stEl.style.color = '#c62828';
                    }
                }
            }

            addLog(`──────────────────────────────`, 'li');
            addLog(`Complete. Total inserted: ${total}`, 'lok');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Done';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-play-fill"></i> Start Fetch';
            }, 3000);
            setTimeout(() => loadData(), 1200);
        };

        document.getElementById('chkAllDevices').addEventListener('change', function() {
            document.querySelectorAll('.chk-fd').forEach(c => c.checked = this.checked);
        });

        /* ══════════════ DELETE OLD DATA ════════════════════════════════ */
        async function checkDeleteCount() {
            const dt = document.getElementById('deleteBeforeDate').value;
            const mch = document.getElementById('deleteMachine').value;
            if (!dt) return toast('warning', 'Select a date first');

            try {
                const p = new URLSearchParams({
                    before_date: dt
                });
                if (mch) p.set('machine_no', mch);
                const r = await fetch(`${API.deleteOld}/count?${p}`);
                const d = await r.json();
                document.getElementById('deleteCountInfo').style.display = 'block';
                document.getElementById('deleteCountText').textContent =
                    `${d.count ?? 0} record(s) found before ${dt} will be deleted.`;
            } catch (e) {
                toast('error', e.message);
            }
        }

        document.getElementById('btnConfirmDelete').onclick = async () => {
            const dt = document.getElementById('deleteBeforeDate').value;
            const mch = document.getElementById('deleteMachine').value;
            if (!dt) return toast('warning', 'Select a date first');

            const res = await Swal.fire({
                title: 'Delete Old ATND_RAW Data?',
                html: `Records before <b>${dt}</b>${mch ? ` — Machine <b>${mch}</b>` : ''} will be permanently deleted.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#880e4f',
                confirmButtonText: 'Yes, Delete',
            });
            if (!res.isConfirmed) return;

            try {
                const r = await fetch(API.deleteOld, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        before_date: dt,
                        machine_no: mch || null
                    }),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteOldModal')).hide();
                loadData();
                toast('success', d.message);
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        };

        /* ══════════════ DELETE FROM DEVICE ════════════════════════════ */
        document.getElementById('btnConfirmDeviceDelete').onclick = async () => {
            const sel = [...document.querySelectorAll('.chk-del-dev:checked')].map(c => c.value);
            if (!sel.length) return toast('warning', 'Select at least one device');

            const names = sel.map(m => {
                const dv = allDevices.find(d => d.machine_no == m);
                return dv ? `${dv.device_name} (${dv.m_ip})` : m;
            }).join('<br>');

            const res = await Swal.fire({
                title: 'Delete from Device?',
                html: `All attendance logs will be deleted from:<br><strong>${names}</strong>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#c62828',
                confirmButtonText: 'Yes, Delete',
            });
            if (!res.isConfirmed) return;

            try {
                const r = await fetch(API.deleteDevice, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        machines: sel
                    }),
                });
                const d = await r.json();
                if (!r.ok && r.status !== 207) throw new Error(d.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteDeviceModal')).hide();
                Swal.fire('Done', d.message, d.errors?.length ? 'warning' : 'success');
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        };

        /* ══════════════ DOWNLOAD CSV / TXT ════════════════════════════
           GET /api/attendance/download
           Same columns as ATND_RAW, comma-separated
        ═══════════════════════════════════════════════════════════════ */
        function downloadNow() {
            const params = new URLSearchParams();
            const m = document.getElementById('dlMachine').value;
            const f = document.getElementById('dlFrom').value;
            const t = document.getElementById('dlTo').value;
            const c = document.getElementById('dlCard').value.trim();
            const fmt = document.querySelector('input[name=dlFormat]:checked')?.value ?? 'csv';

            if (m) params.set('machine_no', m);
            if (f) params.set('date_from', f);
            if (t) params.set('date_to', t);
            if (c) params.set('card_no', c);
            params.set('format', fmt);

            // Use anchor download trick (GET request with auth cookie)
            const link = document.createElement('a');
            link.href = `${API.download}?${params}`;
            link.click();
            bootstrap.Modal.getInstance(document.getElementById('downloadModal')).hide();
            toast('success', 'Download started…');
        }

        /* ══════════════ PROCESS ATTENDANCE ════════════════════════════ */
        document.getElementById('btnRunProcess').onclick = async () => {
            const cid = document.getElementById('processCompany').value;
            const log = document.getElementById('processLog');
            const btn = document.getElementById('btnRunProcess');

            if (!cid) return toast('warning', 'Select a company first');

            const res = await Swal.fire({
                title: 'Run Attendance Process?',
                html: `Execute <b>TAT_ATTENDANCE_PROCESS</b><br>Company ID: <b>${cid}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2e7d32',
                confirmButtonText: 'Yes, Process',
            });
            if (!res.isConfirmed) return;

            log.style.display = 'block';
            log.innerHTML = '';
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split spin"></i> Processing…';

            const addLog = (msg, cls = 'li') => {
                const ts = new Date().toLocaleTimeString();
                log.innerHTML += `<div class="${cls}">[${ts}] ${msg}</div>`;
                log.scrollTop = log.scrollHeight;
            };

            addLog(`Calling TAT_ATTENDANCE_PROCESS(${cid})…`);

            try {
                const r = await fetch(API.process, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        company_id: cid
                    }),
                });

                // Guard: if response is not JSON (e.g. HTML 404 page) handle gracefully
                const contentType = r.headers.get('content-type') ?? '';
                if (!contentType.includes('application/json')) {
                    throw new Error(
                        `Server returned ${r.status} – route not registered. Run: php artisan route:clear`);
                }

                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Process failed');

                addLog(`✔ ${d.message}`, 'lok');
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Completed';
                toast('success', 'Attendance process completed successfully');

            } catch (e) {
                addLog(`✘ ${e.message}`, 'lerr');
                Swal.fire('Error', e.message, 'error');
                btn.innerHTML = '<i class="bi bi-play-circle-fill"></i> Run TAT Process';
            }

            btn.disabled = false;
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-play-circle-fill"></i> Run TAT Process';
            }, 4000);
        };

        /* ══════════════ MANAGE DEVICES ════════════════════════════════ */
        async function loadDeviceList() {
            const tbody = document.getElementById('deviceListBody');
            try {
                const r = await fetch(API.devices);
                const d = await r.json();
                const devs = d.data ?? [];
                tbody.innerHTML = devs.length ? devs.map(d => `
            <tr>
                <td><span class="mb">${String(d.machine_no).padStart(3,'0')}</span></td>
                <td>${d.device_name}</td>
                <td class="tc">${d.m_ip}</td>
                <td><span class="led l-off" id="dled_${d.machine_no}"></span>
                    <span id="dping_${d.machine_no}" style="font-size:.66rem;color:#777;"> —</span></td>
                <td>
                    <button class="btn-t b-ref" style="font-size:.66rem;padding:2px 7px;margin-right:3px;"
                        onclick='editDev(${JSON.stringify(d).replace(/"/g,"&quot;")})'>
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn-t b-del" style="font-size:.66rem;padding:2px 7px;"
                        onclick="delDev(${d.machine_no})">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
            </tr>`).join('') :
                    '<tr><td colspan="5" class="text-center text-muted py-2">No devices registered.</td></tr>';
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${e.message}</td></tr>`;
            }
        }

        document.getElementById('btnSaveDevice').onclick = async () => {
            const mn = document.getElementById('devMachineNo').value.trim();
            const ip = document.getElementById('devIp').value.trim();
            const name = document.getElementById('devName').value.trim();
            const eid = document.getElementById('devEditId').value;

            if (!mn || !ip || !name) return toast('warning', 'All fields required');
            if (!/^(\d{1,3}\.){3}\d{1,3}$/.test(ip)) return toast('warning', 'Invalid IP address');

            try {
                const r = await fetch(eid ? `${API.devices}/${eid}` : API.devices, {
                    method: eid ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        machine_no: mn,
                        m_ip: ip,
                        device_name: name
                    }),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message);
                clearDevForm();
                await loadDeviceList();
                await loadDevices();
                toast('success', eid ? 'Device updated' : 'Device saved');
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        };

        function editDev(d) {
            document.getElementById('devMachineNo').value = d.machine_no;
            document.getElementById('devIp').value = d.m_ip;
            document.getElementById('devName').value = d.device_name;
            document.getElementById('devEditId').value = d.machine_no;
            document.getElementById('devMachineNo').focus();
        }

        async function delDev(mn) {
            const res = await Swal.fire({
                title: 'Delete Device?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c62828',
                confirmButtonText: 'Delete'
            });
            if (!res.isConfirmed) return;
            try {
                const r = await fetch(`${API.devices}/${mn}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message);
                await loadDeviceList();
                await loadDevices();
                toast('success', 'Device deleted');
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        }

        document.getElementById('btnClearDevForm').onclick = clearDevForm;

        function clearDevForm() {
            ['devMachineNo', 'devIp', 'devName', 'devEditId'].forEach(id =>
                document.getElementById(id).value = '');
        }

        document.getElementById('btnTestConn').onclick = async () => {
            const ip = document.getElementById('devIp').value.trim();
            const btn = document.getElementById('btnTestConn');
            if (!ip) return toast('warning', 'Enter IP address first');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split spin"></i> Testing…';
            try {
                const r = await fetch(API.ping, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        ip
                    }),
                });
                const d = await r.json();
                if (d.online) toast('success', `✔ Device online at ${ip}`);
                else toast('error', `✘ Device unreachable at ${ip}`);
            } catch (e) {
                toast('error', 'Connection test failed');
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-wifi"></i> Test Connection';
        };

        /* ══════════════ DELETE DEVICE - SELECT ALL ════════════════════ */
        function toggleDelAllDevices() {
            const chkAll = document.getElementById('chkDelAllDevices');
            const allDeviceChks = document.querySelectorAll('.chk-del-dev');
            allDeviceChks.forEach(chk => {
                chk.checked = chkAll.checked;
            });
        }

        function updateDelAllCheckbox() {
            const chkAll = document.getElementById('chkDelAllDevices');
            const allDeviceChks = document.querySelectorAll('.chk-del-dev');
            const allChecked = allDeviceChks.length > 0 && [...allDeviceChks].every(chk => chk.checked);
            chkAll.checked = allChecked;
        }

        /* ══════════════ HELPER ════════════════════════════════════════ */
        function toast(icon, title) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon,
                title,
                showConfirmButton: false,
                timer: 2500
            });
        }
    </script>
@endpush
