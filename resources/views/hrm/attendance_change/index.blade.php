@extends('layouts.app')

@section('title', 'Attendance Change on Request')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --pri: #1a3a5c;
            --pri-lt: #2257a0;
            --accent: #1e7e34;
            --danger: #c0392b;
            --amber: #f59e0b;
            --bg: #f0f4f8;
            --card: #fff;
            --border: #cdd8e8;
            --lbl: #374a5a;
            --inp: #fafdff;
            --r: 5px;
            --h: 32px;
        }

        html,
        body {
            background: var(--bg);
            font-family: 'Segoe UI', sans-serif;
            font-size: 13px;
            color: #222;
        }

        /* ── Banner ── */
        .mode-banner {
            background: linear-gradient(90deg, var(--pri), var(--pri-lt));
            color: #fff;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid var(--amber);
        }

        .badge-pill {
            background: var(--amber);
            color: #0b1828;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11.5px;
        }

        /* ── Section card ── */
        .sec-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            margin-bottom: 14px;
        }

        .sec-card-head {
            background: var(--pri);
            color: #fff;
            padding: 7px 14px;
            border-radius: var(--r) var(--r) 0 0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .sec-card-head-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sec-card-body {
            padding: 12px 14px 10px;
        }

        /* ── Page heading ── */
        .page-heading {
            font-size: 15px;
            font-weight: 700;
            color: var(--pri);
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--pri-lt);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        /* ── Form labels & inputs ── */
        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: var(--lbl);
            margin-bottom: 2px;
            display: block;
        }

        .form-control,
        .form-select {
            height: var(--h) !important;
            font-size: 12.5px !important;
            border: 1px solid #bfcfdf !important;
            border-radius: 4px !important;
            background: var(--inp) !important;
            color: #1a2a3a !important;
            padding: 3px 8px !important;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pri-lt) !important;
            box-shadow: 0 0 0 2px rgba(34, 87, 160, .12) !important;
            outline: none;
        }

        .input-group .form-control {
            border-radius: 4px 0 0 4px !important;
        }

        .input-group-text {
            background: #dde8f5;
            border: 1px solid #bfcfdf;
            color: var(--pri);
            font-size: 12px;
            cursor: pointer;
            padding: 0 9px;
            height: var(--h);
            display: flex;
            align-items: center;
            border-radius: 0 4px 4px 0;
        }

        .input-group-text:hover {
            background: var(--pri);
            color: var(--amber);
        }

        /* ── Buttons ── */
        .btn {
            font-size: 12px !important;
            font-weight: 600 !important;
            padding: 5px 14px !important;
            border-radius: 4px !important;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-pri {
            background: var(--pri) !important;
            border-color: var(--pri-lt) !important;
            color: #fff !important;
        }

        .btn-pri:hover {
            background: var(--pri-lt) !important;
        }

        .btn-save {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }

        .btn-save:hover {
            background: #155d27 !important;
        }

        .btn-info2 {
            background: #0277bd !important;
            border-color: #0288d1 !important;
            color: #fff !important;
        }

        .btn-info2:hover {
            background: #01579b !important;
        }

        .btn-del {
            background: var(--danger) !important;
            border-color: var(--danger) !important;
            color: #fff !important;
        }

        .btn-del:hover {
            background: #922b21 !important;
        }

        .btn-amber {
            background: var(--amber) !important;
            border-color: var(--amber) !important;
            color: #0b1828 !important;
            font-weight: 700 !important;
        }

        .btn-amber:hover {
            background: #d97706 !important;
        }

        .btn-sec {
            background: #546e7a !important;
            border-color: #546e7a !important;
            color: #fff !important;
        }

        .btn-sec:hover {
            background: #37474f !important;
        }

        /* ── Select2 ── */
        .select2-container--bootstrap-5 .select2-selection {
            font-size: 12.5px !important;
        }

        /* ── Grid table ── */
        .att-table-wrap {
            overflow-x: auto;
            max-height: 520px;
            overflow-y: auto;
        }

        #att_table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin: 0;
            font-size: 12px;
        }

        #att_table thead th {
            background: var(--pri);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            white-space: nowrap;
            padding: 7px 6px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        #att_table thead th:first-child {
            border-radius: 0;
        }

        #att_table tbody tr:nth-child(even) td {
            background: #f2f7fc;
        }

        #att_table tbody tr:nth-child(odd) td {
            background: #fff;
        }

        #att_table tbody tr:hover td {
            background: #e4f0fb !important;
        }

        #att_table tbody td {
            font-size: 11.5px;
            padding: 2px 4px;
            border-bottom: 1px solid #dde8f2;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* editable cells */
        #att_table td input.ci {
            border: none;
            background: transparent;
            width: 100%;
            font-size: 11.5px;
            padding: 1px 3px;
            color: #1a2a3a;
        }

        #att_table td input.ci:focus {
            outline: 2px solid var(--amber);
            background: #fffde7;
            border-radius: 2px;
        }

        #att_table td select.cs {
            border: none;
            background: transparent;
            width: 100%;
            font-size: 11.5px;
            padding: 1px 2px;
            cursor: pointer;
            color: #1a2a3a;
        }

        #att_table td select.cs:focus {
            outline: 2px solid var(--amber);
            background: #fffde7;
            border-radius: 2px;
        }

        .tc-wrap {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .tc-wrap input.ci {
            flex: 1;
            min-width: 0;
        }

        .tc-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #6a8aaa;
            padding: 0 2px;
            font-size: .68rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .tc-btn:hover {
            color: var(--amber);
        }

        /* row states */
        #att_table tbody tr.row-modified td {
            background: #fff8e1 !important;
        }

        #att_table tbody tr.row-deleted td {
            background: #fde8e8 !important;
            opacity: .65;
            text-decoration: line-through;
        }

        #att_table tbody tr.row-new td {
            background: #e8f5e9 !important;
        }

        #att_table tbody tr.row-new.row-modified td {
            background: #d4edda !important;
        }

        #att_table tbody tr:has(.row-chk:checked) td {
            background: #dde8f5 !important;
        }

        /* count badges */
        .count-badge {
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
        }

        .cb-grey {
            background: rgba(255, 255, 255, .15);
        }

        .cb-amber {
            background: rgba(245, 158, 11, .7);
            color: #0b1828;
        }

        .cb-red {
            background: rgba(192, 57, 43, .7);
        }

        /* time picker popup */
        #time_picker_popup {
            border-radius: var(--r);
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(26, 58, 92, .2);
        }

        .tp-quick {
            min-width: 44px;
            font-size: 10.5px !important;
            padding: 1px 4px !important;
        }

        /* progress */
        .prog-wrap {
            margin-top: 8px;
        }

        .prog-bar {
            height: 4px;
            background: #dde8f5;
            border-radius: 2px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            background: var(--accent);
            border-radius: 2px;
            transition: width .3s;
            width: 0%;
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #eef2f8;
        }

        ::-webkit-scrollbar-thumb {
            background: #9ab4cc;
            border-radius: 3px;
        }
    </style>
@endpush

@section('content')

    {{-- MODE BANNER --}}
    <div class="mode-banner">
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="badge-pill"><i class="bi bi-clock-history"></i> ATTENDANCE</span>
            <span style="font-size:14px;font-weight:600;">Attendance Change on Request</span>
        </div>
        <div>
            <span style="font-size:11px;opacity:.7;">View &amp; update missing attendance records</span>
        </div>
    </div>

    <div class="container-fluid px-4 py-3">

        {{-- PAGE HEADING --}}
        <div class="page-heading">
            <i class="bi bi-clock-history"></i> Attendance Change on Request
        </div>

        {{-- ALERT --}}
        <div id="alert_area" class="mb-2"></div>

        {{-- FILTER PANEL --}}
        <div class="sec-card">
            <div class="sec-card-head">
                <div class="sec-card-head-left"><i class="bi bi-funnel-fill"></i> Search Criteria</div>
            </div>
            <div class="sec-card-body">

                {{-- Row 1 --}}
                <div class="row g-2 align-items-end mb-2">
                    <div class="col-md-3">
                        <label class="form-label-sm">Company <span class="text-danger">*</span></label>
                        <select id="company_id" class="form-select select2" style="width:100%">
                            <option value="">-- Select Company --</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">From Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="from_date" class="form-control flatpickr" placeholder="dd-Mon-yyyy"
                                autocomplete="off">
                            <span class="input-group-text"
                                onclick="document.getElementById('from_date')._flatpickr?.open()"><i
                                    class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">To Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="to_date" class="form-control flatpickr" placeholder="dd-Mon-yyyy"
                                autocomplete="off">
                            <span class="input-group-text"
                                onclick="document.getElementById('to_date')._flatpickr?.open()"><i
                                    class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">Emp No</label>
                        <input type="text" id="f_empno" class="form-control" placeholder="Exact match"
                            autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">New Emp No</label>
                        <input type="text" id="f_empno_new" class="form-control" placeholder="Partial search"
                            autocomplete="off">
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label-sm">Status</label>
                        <select id="f_status" class="form-select">
                            <option value="">-- All --</option>
                            <option value="P">P</option>
                            <option value="A">A</option>
                            <option value="W">W</option>
                            <option value="H">H</option>
                            <option value="L">L</option>
                            <option value="NULL">— NULL —</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">Status 2</label>
                        <select id="f_status2" class="form-select">
                            <option value="">-- All --</option>
                            <option value="P">P</option>
                            <option value="A">A</option>
                            <option value="W">W</option>
                            <option value="H">H</option>
                            <option value="L">L</option>
                            <option value="NULL">— NULL —</option>
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-2 flex-wrap align-items-end">
                        <button id="btn_search" class="btn btn-pri">
                            <i class="bi bi-search"></i> Query
                        </button>
                        <button id="btn_save" class="btn btn-save" disabled>
                            <i class="bi bi-save2"></i> Save All
                        </button>
                        <button id="btn_add_row" class="btn btn-info2">
                            <i class="bi bi-plus-circle"></i> Add Row
                        </button>
                        <button id="btn_delete_selected" class="btn btn-del" disabled>
                            <i class="bi bi-trash3"></i> Delete Selected
                        </button>
                        <button id="btn_set_out3" class="btn btn-amber" disabled>
                            <i class="bi bi-clock"></i> Set OUT3
                        </button>
                        <button id="btn_clear" class="btn btn-sec">
                            <i class="bi bi-eraser"></i> Clear
                        </button>
                    </div>
                    <div class="col-auto ms-auto d-flex align-items-end">
                        <small style="font-size:10.5px;color:#6a8aaa;">
                            <i class="bi bi-keyboard me-1"></i>Arrow keys navigate · Enter=down
                        </small>
                    </div>
                </div>

                {{-- Progress --}}
                <div id="progress_wrap" class="prog-wrap" style="display:none;">
                    <div class="prog-bar">
                        <div class="prog-fill" id="progress_bar"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ATTENDANCE GRID --}}
        <div class="sec-card" style="margin-bottom:0;">
            <div class="sec-card-head">
                <div class="sec-card-head-left">
                    <i class="bi bi-table"></i> Attendance Records
                </div>
                <div style="display:flex;gap:6px;align-items:center;">
                    <span id="rec_count" class="count-badge cb-grey">0 records</span>
                    <span id="mod_count" class="count-badge cb-amber" style="display:none;">0 modified</span>
                    <span id="del_count" class="count-badge cb-red" style="display:none;">0 to delete</span>
                </div>
            </div>
            <div class="sec-card-body p-0">
                <div class="att-table-wrap">
                    <table id="att_table">
                        <thead>
                            <tr>
                                <th style="width:32px;text-align:center;">
                                    <input type="checkbox" id="chk_all" title="Select all"
                                        style="cursor:pointer;width:14px;height:14px;accent-color:var(--amber);">
                                </th>
                                <th style="width:34px;">#</th>
                                <th style="min-width:80px;">Emp No</th>
                                <th style="min-width:80px;">New Emp No</th>
                                <th style="min-width:100px;">Date</th>
                                <th style="min-width:88px;">In Time</th>
                                <th style="min-width:88px;">In Time 2</th>
                                <th style="min-width:88px;">Out Time</th>
                                <th style="min-width:88px;">Out Time 2</th>
                                <th style="min-width:88px;">Out Time 3</th>
                                <th style="min-width:52px;">OT Hr</th>
                                <th style="min-width:52px;">OT Hr 2</th>
                                <th style="min-width:52px;">OT Hr 3</th>
                                <th style="min-width:48px;">Late</th>
                                <th style="min-width:48px;">Late 2</th>
                                <th style="min-width:60px;">Late Xtr</th>
                                <th style="min-width:60px;">Extra OT</th>
                                <th style="min-width:52px;">Status</th>
                                <th style="min-width:58px;">Status 2</th>
                                <th style="min-width:120px;">Remarks</th>
                                <th style="min-width:120px;">Lat Remarks</th>
                                <th style="min-width:68px;">Manual</th>
                            </tr>
                        </thead>
                        <tbody id="att_tbody">
                            <tr>
                                <td colspan="22" style="text-align:center;padding:32px;color:#6a8aaa;font-size:.80rem;">
                                    <i class="bi bi-clock-history"
                                        style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>
                                    No records loaded. Use Search Criteria above.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- TIME PICKER POPUP --}}
    <div id="time_picker_popup" style="display:none;position:fixed;z-index:9999;width:230px;">
        <div
            style="background:linear-gradient(90deg,var(--pri),var(--pri-lt));color:#fff;padding:6px 10px;border-radius:var(--r) var(--r) 0 0;display:flex;align-items:center;justify-content:space-between;">
            <small style="font-weight:700;" id="tp_label">Select Time</small>
            <button type="button" id="tp_close"
                style="background:transparent;border:none;color:#fff;cursor:pointer;font-size:.75rem;padding:0;"><i
                    class="bi bi-x-lg"></i></button>
        </div>
        <div
            style="background:var(--card);border:1px solid var(--border);border-top:none;border-radius:0 0 var(--r) var(--r);padding:10px;">
            <div style="display:flex;gap:6px;margin-bottom:8px;">
                <div style="flex:1;">
                    <label class="form-label-sm">HH</label>
                    <select id="tp_hour" class="form-select"></select>
                </div>
                <div style="flex:1;">
                    <label class="form-label-sm">MM</label>
                    <select id="tp_min" class="form-select"></select>
                </div>
            </div>
            <div style="margin-bottom:8px;">
                <label class="form-label-sm">Type time <small style="color:#6a8aaa;font-weight:400;">(8→08:00 ·
                        18.30→18:30)</small></label>
                <input type="text" id="tp_type" class="form-control" placeholder="e.g. 8  or  18.30"
                    maxlength="5" autocomplete="off">
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">
                @foreach (['06:00', '07:00', '08:00', '09:00', '10:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'] as $qt)
                    <button class="btn btn-sec tp-quick" data-val="{{ $qt }}">{{ $qt }}</button>
                @endforeach
            </div>
            <div style="display:flex;gap:6px;">
                <button id="tp_apply" class="btn btn-pri" style="flex:1;justify-content:center;">
                    <i class="bi bi-check-circle"></i> Apply
                </button>
                <button id="tp_clear" class="btn btn-del" style="padding:5px 10px!important;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(function() {
            const CSRF = '{{ csrf_token() }}';

            const TIME_FIELDS = ['in_time', 'in_time2', 'out_time', 'out_time2', 'out_time3'];
            const NUM_FIELDS = ['othour', 'othour2', 'othour3', 'late', 'late2', 'late_extra', 'extraot'];
            const SELECT_FIELDS = ['status', 'status2'];
            const TAIL_FIELDS = ['remarks', 'lat_remarks', 'manual_att'];
            const EDIT_FIELDS = [...TIME_FIELDS, ...NUM_FIELDS, ...SELECT_FIELDS, ...TAIL_FIELDS];
            const COL_IDX = {};
            EDIT_FIELDS.forEach((f, i) => COL_IDX[f] = i);

            let tableData = [],
                modified = new Set(),
                deleted = new Set();

            /* ── Flatpickr ── */
            flatpickr('#from_date', {
                dateFormat: 'd-M-Y',
                allowInput: true
            });
            flatpickr('#to_date', {
                dateFormat: 'd-M-Y',
                allowInput: true
            });

            /* ── Select2: Company ── */
            $('#company_id').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Select Company --',
                allowClear: true,
                ajax: {
                    url: '{{ route('hrm.att-change.companies') }}',
                    dataType: 'json',
                    delay: 200,
                    processResults: d => ({
                        results: d.map(r => ({
                            id: r.id,
                            text: r.text
                        }))
                    }),
                    cache: true,
                },
            });

            /* ── Time picker: populate HH MM ── */
            (function() {
                const hSel = document.getElementById('tp_hour');
                const mSel = document.getElementById('tp_min');
                for (let h = 0; h < 24; h++) {
                    const o = document.createElement('option');
                    o.value = String(h).padStart(2, '0');
                    o.textContent = String(h).padStart(2, '0');
                    hSel.appendChild(o);
                }
                for (let m = 0; m < 60; m += 5) {
                    const o = document.createElement('option');
                    o.value = String(m).padStart(2, '0');
                    o.textContent = String(m).padStart(2, '0');
                    mSel.appendChild(o);
                }
            })();

            /* ── Normalise time ── */
            function normaliseTime(raw) {
                raw = String(raw).trim();
                if (!raw) return '';
                if (/^\d{2}:\d{2}$/.test(raw)) return raw;
                const sepMatch = raw.match(/^(\d{1,2})[\.:](\d{1,2})$/);
                if (sepMatch) {
                    const h = +sepMatch[1];
                    const minRaw = sepMatch[2];
                    const mn = minRaw.length === 1 ? +minRaw * 10 : +minRaw;
                    if (h >= 0 && h <= 23 && mn >= 0 && mn <= 59) return String(h).padStart(2, '0') + ':' + String(
                        mn).padStart(2, '0');
                }
                const digits = raw.replace(/\D/g, '');
                if (!digits) return '';
                if (digits.length <= 2) {
                    const h = +digits;
                    if (h >= 0 && h <= 23) return String(h).padStart(2, '0') + ':00';
                }
                if (digits.length === 3) {
                    const h = +digits[0];
                    const mn = +digits.slice(1);
                    if (h >= 0 && h <= 23 && mn >= 0 && mn <= 59) return String(h).padStart(2, '0') + ':' + String(
                        mn).padStart(2, '0');
                }
                if (digits.length === 4) {
                    const h = +digits.slice(0, 2);
                    const mn = +digits.slice(2);
                    if (h >= 0 && h <= 23 && mn >= 0 && mn <= 59) return String(h).padStart(2, '0') + ':' + String(
                        mn).padStart(2, '0');
                }
                return raw;
            }

            /* ── Normalise date ── */
            const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function normaliseDateInput(raw) {
                raw = String(raw).trim();
                if (!raw) return '';
                if (/^\d{2}-[A-Za-z]{3}-\d{4}$/i.test(raw)) {
                    const p = raw.split('-');
                    return p[0].padStart(2, '0') + '-' + p[1].charAt(0).toUpperCase() + p[1].slice(1)
                    .toLowerCase() + '-' + p[2];
                }

                function out(d, m, y) {
                    if (d < 1 || d > 31 || m < 1 || m > 12 || y < 1900 || y > 2099) return null;
                    return String(d).padStart(2, '0') + '-' + MONTHS[m - 1] + '-' + y;
                }

                function ey(yy) {
                    yy = +yy;
                    return yy <= 50 ? 2000 + yy : 1900 + yy;
                }
                const sm = raw.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{2,4})$/);
                if (sm) {
                    const d = +sm[1],
                        m = +sm[2],
                        y = sm[3].length === 2 ? ey(sm[3]) : +sm[3];
                    return out(d, m, y);
                }
                const digs = raw.replace(/\D/g, '');
                if (!digs) return null;
                if (digs.length === 6) {
                    return out(+digs.slice(0, 2), +digs.slice(2, 4), ey(digs.slice(4, 6)));
                }
                if (digs.length === 7) {
                    let r = out(+digs[0], +digs.slice(1, 3), +digs.slice(3, 7));
                    if (r) return r;
                    return out(+digs.slice(0, 2), +digs[2], +digs.slice(3, 7));
                }
                if (digs.length === 8) {
                    return out(+digs.slice(0, 2), +digs.slice(2, 4), +digs.slice(4, 8));
                }
                return null;
            }

            /* ── Time picker state ── */
            let $activeTimeInput = null;

            function openTimePicker($inp, label) {
                $activeTimeInput = $inp;
                const val = $inp.val() || '';
                const parts = val.split(':');
                $('#tp_hour').val(parts[0] || '08');
                $('#tp_min').val(parts[1] || '00');
                $('#tp_type').val(val);
                $('#tp_label').text(label);
                const pos = $inp[0].getBoundingClientRect();
                const popup = document.getElementById('time_picker_popup');
                popup.style.display = 'block';
                let top = pos.bottom + window.scrollY + 2;
                let left = pos.left + window.scrollX;
                if (left + 230 > window.innerWidth) left = window.innerWidth - 235;
                popup.style.top = top + 'px';
                popup.style.left = left + 'px';
                $('#tp_type').focus().select();
            }

            function applyTimePicker(val) {
                if (!$activeTimeInput) return;
                const norm = normaliseTime(val);
                $activeTimeInput.val(norm).trigger('input');
                $activeTimeInput.focus();
                closeTimePicker();
            }

            function closeTimePicker() {
                $('#time_picker_popup').hide();
                $activeTimeInput = null;
            }

            $('#tp_close').on('click', closeTimePicker);
            $(document).on('mousedown', function(e) {
                if (!$(e.target).closest('#time_picker_popup,.tc-btn').length) closeTimePicker();
            });
            $('#tp_type').on('input', function() {
                const norm = normaliseTime($(this).val());
                if (/^\d{2}:\d{2}$/.test(norm)) {
                    const p = norm.split(':');
                    $('#tp_hour').val(p[0]);
                    $('#tp_min').val(p[1]);
                }
            });
            $('#tp_apply').on('click', function() {
                applyTimePicker($('#tp_type').val() || ($('#tp_hour').val() + ':' + $('#tp_min').val()));
            });
            $('#tp_type').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#tp_apply').trigger('click');
                }
            });
            $('#tp_clear').on('click', () => applyTimePicker(''));
            $(document).on('click', '.tp-quick', function() {
                applyTimePicker($(this).data('val'));
            });
            $('#tp_hour,#tp_min').on('change', function() {
                $('#tp_type').val($('#tp_hour').val() + ':' + $('#tp_min').val());
            });

            /* ── Normalise on blur ── */
            $(document).on('blur',
                '#att_tbody input.ci[data-field^="in_time"],#att_tbody input.ci[data-field^="out_time"]',
                function() {
                    const norm = normaliseTime($(this).val());
                    if (norm !== $(this).val()) $(this).val(norm).trigger('input');
                });
            $(document).on('blur', '#att_tbody input.ci[data-field="att_date"]', function() {
                const raw = $(this).val().trim();
                if (!raw || /^\d{2}-[A-Za-z]{3}-\d{4}$/i.test(raw)) return;
                const norm = normaliseDateInput(raw);
                if (norm) {
                    $(this).val(norm).trigger('input');
                    const fp = this._flatpickr;
                    if (fp) fp.setDate(norm, false);
                }
            });
            $(document).on('blur', '#from_date,#to_date', function() {
                const raw = $(this).val().trim();
                if (!raw || /^\d{2}-[A-Za-z]{3}-\d{4}$/i.test(raw)) return;
                const norm = normaliseDateInput(raw);
                if (norm) {
                    const fp = this._flatpickr;
                    if (fp) fp.setDate(norm, true);
                    else $(this).val(norm);
                }
            });

            /* ── Alert ── */
            function showAlert(msg, type = 'success') {
                const color = type === 'success' ? '#1e7e34' : type === 'warning' ? '#7a5800' : '#c0392b';
                $('#alert_area').html(
                    `<div class="alert alert-${type} alert-dismissible fade show py-2 small mb-2" role="alert" style="font-size:12.5px;border-left:4px solid ${color};">${msg}<button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button></div>`
                    );
            }

            /* ── Badge counts ── */
            function updateCounts() {
                $('#rec_count').text(tableData.length + ' records');
                modified.size ? $('#mod_count').show().text(modified.size + ' modified') : $('#mod_count').hide();
                deleted.size ? $('#del_count').show().text(deleted.size + ' to delete') : $('#del_count').hide();
            }

            const STATUS_OPTS = ['P', 'A', 'W', 'H', 'L'];

            /* ── Make select cell ── */
            function makeSelectCell(rowIdx, field, val) {
                const td = document.createElement('td');
                const sel = document.createElement('select');
                sel.className = 'cs';
                sel.dataset.row = rowIdx;
                sel.dataset.field = field;
                sel.dataset.col = COL_IDX[field];
                sel.innerHTML = '<option value=""></option>';
                STATUS_OPTS.forEach(v => {
                    const o = document.createElement('option');
                    o.value = v;
                    o.textContent = v;
                    if (v === (val || '').trim().toUpperCase()) o.selected = true;
                    sel.appendChild(o);
                });
                const normVal = (val || '').trim().toUpperCase();
                if (normVal && !STATUS_OPTS.includes(normVal)) {
                    const o = document.createElement('option');
                    o.value = normVal;
                    o.textContent = normVal;
                    o.selected = true;
                    sel.appendChild(o);
                }
                sel.addEventListener('change', function() {
                    tableData[rowIdx][field] = this.value;
                    modified.add(rowIdx);
                    this.closest('tr').classList.add('row-modified');
                    updateCounts();
                });
                sel.addEventListener('keydown', onKeyDown);
                td.appendChild(sel);
                return td;
            }

            /* ── Make time cell ── */
            function makeTimeCell(rowIdx, field, val, colLabel) {
                const td = document.createElement('td');
                const div = document.createElement('div');
                div.className = 'tc-wrap';
                const inp = document.createElement('input');
                inp.type = 'text';
                inp.className = 'ci';
                inp.value = val || '';
                inp.placeholder = 'HH:MM';
                inp.dataset.row = rowIdx;
                inp.dataset.field = field;
                inp.dataset.col = COL_IDX[field];
                inp.addEventListener('input', onCellInput);
                inp.addEventListener('keydown', onKeyDown);
                const btn = document.createElement('button');
                btn.className = 'tc-btn';
                btn.title = 'Pick time';
                btn.innerHTML = '<i class="bi bi-clock"></i>';
                btn.type = 'button';
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openTimePicker($(inp), colLabel);
                });
                div.appendChild(inp);
                div.appendChild(btn);
                td.appendChild(div);
                return td;
            }

            /* ── Render table ── */
            function renderTable(rows) {
                tableData = rows;
                modified.clear();
                deleted.clear();
                const tbody = document.getElementById('att_tbody');
                tbody.innerHTML = '';
                if (!rows.length) {
                    tbody.innerHTML =
                        `<tr><td colspan="22" style="text-align:center;padding:32px;color:#6a8aaa;font-size:.80rem;"><i class="bi bi-inbox" style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>No records found.</td></tr>`;
                    updateCounts();
                    return;
                }
                const TL = ['In Time', 'In Time 2', 'Out Time', 'Out Time 2', 'Out Time 3'];
                rows.forEach((r, i) => {
                    const tr = document.createElement('tr');
                    tr.dataset.row = i;
                    const tdChk = document.createElement('td');
                    tdChk.style.textAlign = 'center';
                    const chk = document.createElement('input');
                    chk.type = 'checkbox';
                    chk.className = 'row-chk';
                    chk.dataset.row = i;
                    chk.style.cssText = 'cursor:pointer;width:14px;height:14px;accent-color:var(--amber);';
                    chk.addEventListener('change', onCheckChange);
                    tdChk.appendChild(chk);
                    tr.appendChild(tdChk);
                    [i + 1, r.empno || '', r.empno_new || '', r.att_date || ''].forEach(v => {
                        const td = document.createElement('td');
                        td.style.whiteSpace = 'nowrap';
                        td.style.color = '#5a7a94';
                        td.textContent = v;
                        tr.appendChild(td);
                    });
                    TIME_FIELDS.forEach((f, fi) => tr.appendChild(makeTimeCell(i, f, r[f], TL[fi])));
                    NUM_FIELDS.forEach(f => {
                        const td = document.createElement('td');
                        const inp = document.createElement('input');
                        inp.type = 'text';
                        inp.className = 'ci';
                        inp.value = r[f] || '';
                        inp.dataset.row = i;
                        inp.dataset.field = f;
                        inp.dataset.col = COL_IDX[f];
                        inp.addEventListener('input', onCellInput);
                        inp.addEventListener('keydown', onKeyDown);
                        td.appendChild(inp);
                        tr.appendChild(td);
                    });
                    tr.appendChild(makeSelectCell(i, 'status', r.status || ''));
                    tr.appendChild(makeSelectCell(i, 'status2', r.status2 || ''));
                    TAIL_FIELDS.forEach(f => {
                        const td = document.createElement('td');
                        const inp = document.createElement('input');
                        inp.type = 'text';
                        inp.className = 'ci';
                        inp.value = r[f] || '';
                        inp.dataset.row = i;
                        inp.dataset.field = f;
                        inp.dataset.col = COL_IDX[f];
                        inp.addEventListener('input', onCellInput);
                        inp.addEventListener('keydown', onKeyDown);
                        td.appendChild(inp);
                        tr.appendChild(td);
                    });
                    tbody.appendChild(tr);
                });
                updateCounts();
                updateSelectionState();
                $('#btn_save,#btn_set_out3').prop('disabled', false);
                $('#btn_delete_selected').prop('disabled', true);
            }

            function onCheckChange() {
                updateSelectionState();
            }

            function updateSelectionState() {
                const total = document.querySelectorAll('#att_tbody .row-chk').length;
                const checked = document.querySelectorAll('#att_tbody .row-chk:checked').length;
                document.getElementById('chk_all').checked = total > 0 && checked === total;
                document.getElementById('chk_all').indeterminate = checked > 0 && checked < total;
                $('#btn_delete_selected').prop('disabled', checked === 0);
            }
            $('#chk_all').on('change', function() {
                const state = this.checked;
                document.querySelectorAll('#att_tbody .row-chk').forEach(c => {
                    c.checked = state;
                });
                updateSelectionState();
            });

            /* ── Add Row ── */
            $('#btn_add_row').on('click', function() {
                const companyId = $('#company_id').val() || '';
                if (!companyId) {
                    showAlert('Please select a Company before adding a row.', 'warning');
                    return;
                }
                const newRow = {
                    empno: '',
                    empno_new: '',
                    att_date: '',
                    in_time: '',
                    in_time2: '',
                    out_time: '',
                    out_time2: '',
                    out_time3: '',
                    othour: '',
                    othour2: '',
                    othour3: '',
                    late: '',
                    late2: '',
                    late_extra: '',
                    extraot: '',
                    status: 'P',
                    status2: 'P',
                    remarks: '',
                    lat_remarks: '',
                    manual_att: '',
                    company_id: companyId,
                    is_new: true
                };
                tableData.push(newRow);
                const newIdx = tableData.length - 1;
                const emptyRow = document.querySelector('#att_tbody td[colspan]');
                if (emptyRow) emptyRow.closest('tr').remove();
                appendRow(newRow, newIdx);
                modified.add(newIdx);
                $('#btn_save').prop('disabled', false);
                updateCounts();
                const newTr = document.querySelector(`#att_tbody tr[data-row="${newIdx}"]`);
                if (newTr) {
                    newTr.scrollIntoView({
                        block: 'nearest'
                    });
                    newTr.classList.add('row-new');
                    const fi = newTr.querySelector('input.ci[data-field="empno_new"],input.ci,select.cs');
                    if (fi) setTimeout(() => {
                        fi.focus();
                        fi.select && fi.select();
                    }, 50);
                }
            });

            /* ── Delete Selected ── */
            $('#btn_delete_selected').on('click', function() {
                const checked = [...document.querySelectorAll('#att_tbody .row-chk:checked')];
                if (!checked.length) return;
                if (!confirm(checked.length + ' row(s) selected. Delete from database?')) return;
                const rowsToDelete = checked.map(chk => tableData[+chk.dataset.row]);
                const newRows = rowsToDelete.filter(r => r.is_new);
                const dbRows = rowsToDelete.filter(r => !r.is_new);
                const delIndices = new Set(checked.map(chk => +chk.dataset.row));
                tableData = tableData.filter((_, i) => !delIndices.has(i));
                modified = new Set([...modified].filter(i => !delIndices.has(i)));
                deleted.clear();
                if (dbRows.length === 0) {
                    renderTable(tableData);
                    showAlert(newRows.length + ' new row(s) removed.', 'info');
                    return;
                }
                const btn = $(this).prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split spin"></i> Deleting…');
                $.ajax({
                    url: '{{ route('hrm.att-change.delete') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: JSON.stringify({
                        rows: dbRows
                    }),
                    success: res => {
                        showAlert(res.message + (newRows.length ? ' · ' + newRows.length +
                            ' new row(s) discarded.' : ''), 'success');
                        renderTable(tableData);
                    },
                    error: xhr => showAlert('Delete error: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                    complete: () => btn.prop('disabled', false).html(
                        '<i class="bi bi-trash3"></i> Delete Selected'),
                });
            });

            /* ── Append row ── */
            function appendRow(r, i) {
                const tbody = document.getElementById('att_tbody');
                const TL = ['In Time', 'In Time 2', 'Out Time', 'Out Time 2', 'Out Time 3'];
                const tr = document.createElement('tr');
                tr.dataset.row = i;
                const tdChk = document.createElement('td');
                tdChk.style.textAlign = 'center';
                const chk = document.createElement('input');
                chk.type = 'checkbox';
                chk.className = 'row-chk';
                chk.dataset.row = i;
                chk.style.cssText = 'cursor:pointer;width:14px;height:14px;accent-color:var(--amber);';
                chk.addEventListener('change', onCheckChange);
                tdChk.appendChild(chk);
                tr.appendChild(tdChk);
                [i + 1, r.empno || '', r.empno_new || '', r.att_date || ''].forEach((v, fi) => {
                    const td = document.createElement('td');
                    td.style.whiteSpace = 'nowrap';
                    if (r.is_new && fi > 0) {
                        const inp = document.createElement('input');
                        inp.type = 'text';
                        inp.className = 'ci';
                        inp.value = v;
                        inp.dataset.row = i;
                        inp.dataset.field = fi === 1 ? 'empno' : fi === 2 ? 'empno_new' : 'att_date';
                        inp.dataset.col = fi === 1 ? -3 : fi === 2 ? -2 : -1;
                        inp.placeholder = fi === 1 ? 'Emp No' : fi === 2 ? 'New Emp No' : 'dd-Mon-yyyy';
                        inp.addEventListener('input', function() {
                            tableData[i][this.dataset.field] = this.value;
                            modified.add(i);
                            tr.classList.add('row-modified');
                            updateCounts();
                        });
                        td.appendChild(inp);
                        if (fi === 3) {
                            flatpickr(inp, {
                                dateFormat: 'd-M-Y',
                                allowInput: true,
                                onChange: function(_, dateStr) {
                                    tableData[i]['att_date'] = dateStr;
                                    modified.add(i);
                                    tr.classList.add('row-modified');
                                    updateCounts();
                                }
                            });
                        }
                    } else {
                        td.style.color = '#5a7a94';
                        td.textContent = v;
                    }
                    tr.appendChild(td);
                });
                TIME_FIELDS.forEach((f, fi) => tr.appendChild(makeTimeCell(i, f, r[f], TL[fi])));
                NUM_FIELDS.forEach(f => {
                    const td = document.createElement('td');
                    const inp = document.createElement('input');
                    inp.type = 'text';
                    inp.className = 'ci';
                    inp.value = r[f] || '';
                    inp.dataset.row = i;
                    inp.dataset.field = f;
                    inp.dataset.col = COL_IDX[f];
                    inp.addEventListener('input', onCellInput);
                    inp.addEventListener('keydown', onKeyDown);
                    td.appendChild(inp);
                    tr.appendChild(td);
                });
                tr.appendChild(makeSelectCell(i, 'status', r.status || ''));
                tr.appendChild(makeSelectCell(i, 'status2', r.status2 || ''));
                TAIL_FIELDS.forEach(f => {
                    const td = document.createElement('td');
                    const inp = document.createElement('input');
                    inp.type = 'text';
                    inp.className = 'ci';
                    inp.value = r[f] || '';
                    inp.dataset.row = i;
                    inp.dataset.field = f;
                    inp.dataset.col = COL_IDX[f];
                    inp.addEventListener('input', onCellInput);
                    inp.addEventListener('keydown', onKeyDown);
                    td.appendChild(inp);
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
                updateSelectionState();
            }

            function onCellInput(e) {
                const ri = +e.target.dataset.row;
                const field = e.target.dataset.field;
                tableData[ri][field] = e.target.value;
                modified.add(ri);
                e.target.closest('tr').classList.add('row-modified');
                updateCounts();
            }

            function onKeyDown(e) {
                const ri = +e.target.dataset.row;
                const col = +e.target.dataset.col;
                const maxC = EDIT_FIELDS.length - 1;
                const maxR = tableData.length - 1;
                const map = {
                    ArrowRight: [ri, col + 1],
                    ArrowLeft: [ri, col - 1],
                    ArrowDown: [ri + 1, col],
                    ArrowUp: [ri - 1, col],
                    Enter: [ri + 1, col]
                };
                if (map[e.key]) {
                    e.preventDefault();
                    const [nr, nc] = map[e.key];
                    if (nr >= 0 && nr <= maxR && nc >= 0 && nc <= maxC) focusCell(nr, nc);
                }
            }

            function focusCell(row, col) {
                const el = document.querySelector(`#att_tbody [data-row="${row}"][data-col="${col}"]`);
                if (!el) return;
                el.focus();
                if (el.select) el.select();
                el.closest('tr').scrollIntoView({
                    block: 'nearest'
                });
            }

            /* ── Search ── */
            $('#btn_search').on('click', function() {
                const company_id = $('#company_id').val();
                const from_date = $('#from_date').val().trim();
                const to_date = $('#to_date').val().trim();
                if (!company_id || !from_date || !to_date) {
                    showAlert('Company, From Date and To Date are required.', 'warning');
                    return;
                }
                $(this).prop('disabled', true).html('<i class="bi bi-hourglass-split spin"></i> Loading…');
                $.ajax({
                    url: '{{ route('hrm.att-change.search') }}',
                    method: 'GET',
                    data: {
                        company_id,
                        from_date,
                        to_date,
                        empno: $('#f_empno').val().trim(),
                        empno_new: $('#f_empno_new').val().trim(),
                        status: $('#f_status').val(),
                        status2: $('#f_status2').val()
                    },
                    success: rows => {
                        if (rows.error) {
                            showAlert('DB Error: ' + rows.message, 'danger');
                            return;
                        }
                        renderTable(rows);
                        if (!rows.length) showAlert('No records found.', 'info');
                        else showAlert(rows.length + ' record(s) loaded.', 'success');
                    },
                    error: xhr => showAlert('Error: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                    complete: () => $('#btn_search').prop('disabled', false).html(
                        '<i class="bi bi-search"></i> Query'),
                });
            });

            /* ── Save All ── */
            $('#btn_save').on('click', function() {
                let saveRows = tableData.filter((_, i) => !deleted.has(i));
                let deleteRows = tableData.filter((_, i) => deleted.has(i));
                if (!saveRows.length && !deleteRows.length) {
                    showAlert('Nothing to save.', 'info');
                    return;
                }
                const btn = $(this).prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split spin"></i> Saving…');

                function prepRow(r) {
                    ['in_time', 'in_time2', 'out_time', 'out_time2', 'out_time3'].forEach(f => {
                        if (r[f]) r[f] = normaliseTime(r[f]);
                    });
                    if (r.att_date) r.att_date = normaliseDateInput(r.att_date) || r.att_date;
                    if (!r.company_id) r.company_id = $('#company_id').val() || '';
                    return r;
                }
                saveRows = saveRows.map(prepRow);
                deleteRows = deleteRows.map(prepRow);
                const newRows = saveRows.filter(r => r.is_new);
                const existingRows = saveRows.filter(r => !r.is_new);
                const promises = [];
                if (existingRows.length) promises.push($.ajax({
                    url: '{{ route('hrm.att-change.save') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: JSON.stringify({
                        rows: existingRows
                    })
                }));
                if (newRows.length) promises.push($.ajax({
                    url: '{{ route('hrm.att-change.insert') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: JSON.stringify({
                        rows: newRows
                    })
                }));
                if (deleteRows.length) promises.push($.ajax({
                    url: '{{ route('hrm.att-change.delete') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: JSON.stringify({
                        rows: deleteRows
                    })
                }));
                Promise.all(promises.map(p => p.catch(e => e))).then(results => {
                    const errs = results.filter(r => r && ((typeof r.status === 'number' && r
                        .status >= 400) || (r.success === false)));
                    if (errs.length) {
                        const msg = errs[0].responseJSON?.message || errs[0].responseJSON?.error ||
                            (errs[0].status ? 'HTTP ' + errs[0].status + ': ' + (errs[0]
                                .statusText || 'Error') : 'Unknown error');
                        showAlert('<strong>Save failed:</strong> ' + msg, 'danger');
                        return;
                    }
                    let msg = '';
                    if (existingRows.length) msg += existingRows.length + ' updated. ';
                    if (newRows.length) msg += newRows.length + ' inserted. ';
                    if (deleteRows.length) msg += deleteRows.length + ' deleted.';
                    showAlert(msg.trim() || 'Done.', 'success');
                    $('#btn_search').trigger('click');
                }).finally(() => btn.prop('disabled', false).html(
                    '<i class="bi bi-save2"></i> Save All'));
            });

            /* ── Set OUT3 ── */
            $('#btn_set_out3').on('click', function() {
                if (!confirm('Set OUT_TIME3 for all eligible records in this date range?')) return;
                $(this).prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split spin"></i> Processing…');
                $.ajax({
                    url: '{{ route('hrm.att-change.set-out3') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: {
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        company_id: $('#company_id').val()
                    },
                    success: res => {
                        showAlert(res.message, 'success');
                        $('#btn_search').trigger('click');
                    },
                    error: xhr => showAlert('Error: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                    complete: () => $('#btn_set_out3').prop('disabled', false).html(
                        '<i class="bi bi-clock"></i> Set OUT3'),
                });
            });

            /* ── Clear ── */
            $('#btn_clear').on('click', function() {
                $('#company_id').val(null).trigger('change');
                $('#from_date,#to_date,#f_empno,#f_empno_new').val('');
                $('#f_status,#f_status2').val('');
                tableData = [];
                modified.clear();
                deleted.clear();
                document.getElementById('att_tbody').innerHTML =
                    `<tr><td colspan="22" style="text-align:center;padding:32px;color:#6a8aaa;font-size:.80rem;"><i class="bi bi-clock-history" style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>No records loaded.</td></tr>`;
                document.getElementById('chk_all').checked = false;
                document.getElementById('chk_all').indeterminate = false;
                $('#btn_save,#btn_set_out3,#btn_delete_selected').prop('disabled', true);
                $('#alert_area').html('');
                updateCounts();
            });

        });
    </script>
@endpush
