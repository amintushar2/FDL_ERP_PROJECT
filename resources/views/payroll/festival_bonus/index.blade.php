@extends('layouts.app')

@section('title', 'Festival Bonus Generator')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* ══════════════════════════════════════════
               VARIABLES — same as empForm
            ══════════════════════════════════════════ */
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

        /* ── Mode Banner ── */
        .mode-banner {
            background: linear-gradient(90deg, var(--pri), var(--pri-lt));
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid var(--amber);
        }

        .badge-pill {
            background: var(--amber);
            color: #0b1828;
            font-weight: 700;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 12px;
        }

        .btn-back {
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 4px;
            padding: 4px 14px;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, .25);
            color: #fff;
        }

        /* ── Section Cards ── */
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
            gap: 6px;
        }

        .sec-card-body {
            padding: 12px 14px 10px;
        }

        /* ── Page Heading ── */
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
        label.col-form-label {
            font-size: 11px !important;
            font-weight: 600 !important;
            color: var(--lbl) !important;
            padding-top: 6px !important;
        }

        .form-label-sm {
            font-size: 11px;
            font-weight: 600;
            color: var(--lbl);
            margin-bottom: 3px;
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

        /* ── Input group ── */
        .input-group .form-control {
            border-radius: 4px 0 0 4px !important;
        }

        .input-group-text {
            background: #dde8f5;
            border: 1px solid #bfcfdf;
            color: var(--pri);
            font-size: 12px;
            cursor: pointer;
            padding: 0 10px;
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
            padding: 5px 16px !important;
            border-radius: 4px !important;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-save {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }

        .btn-amber {
            background: var(--amber) !important;
            border-color: var(--amber) !important;
            color: #0b1828 !important;
            font-weight: 700 !important;
        }

        .btn-del {
            background: var(--danger) !important;
            border-color: var(--danger) !important;
            color: #fff !important;
        }

        .btn-sec {
            background: #546e7a !important;
            border-color: #546e7a !important;
            color: #fff !important;
        }

        .btn-outline-pri {
            background: transparent !important;
            border: 1.5px solid var(--pri) !important;
            color: var(--pri) !important;
        }

        .btn-outline-pri:hover {
            background: #e6eff8 !important;
        }

        .action-bar {
            padding: 10px 0 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ── KPI cards ── */
        .kpi-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .kpi-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-top: 3px solid var(--amber);
            border-radius: var(--r);
            padding: 10px 18px;
            min-width: 150px;
            flex: 1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            text-align: center;
        }

        .kpi-val {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--pri);
            line-height: 1.1;
        }

        .kpi-val.green {
            color: var(--accent);
        }

        .kpi-val.amber {
            color: #b45309;
        }

        .kpi-lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6a8aaa;
            margin-top: 3px;
        }

        /* ── Filter bar ── */
        .filter-bar {
            background: #e6eff8;
            border-bottom: 1px solid var(--border);
            padding: 8px 14px;
        }

        /* ── Table (same as empForm sub-tables) ── */
        .sub-table-wrap {
            overflow-x: auto;
            position: relative;
        }

        .emp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .emp-table thead th {
            background: var(--pri);
            color: #fff;
            padding: 8px 10px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            border: none;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 3;
        }

        .emp-table tbody tr:nth-child(even) {
            background: #f2f7fc;
        }

        .emp-table tbody td {
            padding: 6px 10px;
            border-bottom: 1px solid #dde8f2;
            color: #2a3a4a;
            vertical-align: middle;
        }

        .emp-table tbody tr:hover {
            background: #e4f0fb;
        }

        .emp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .emp-table tfoot td {
            background: #dde8f5;
            font-weight: 700;
            font-size: 11px;
            border-top: 2px solid var(--pri);
            padding: 6px 10px;
            color: var(--pri);
        }

        /* Emp badge */
        .emp-badge {
            background: #dde8f5;
            border: 1px solid #b0c8e8;
            color: var(--pri);
            padding: 1px 7px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 700;
            font-family: monospace;
        }

        /* Type badge */
        .type-pill {
            display: inline-block;
            padding: 1px 9px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .type-pill.bonus {
            background: #fff3cd;
            color: #7a5800;
            border: 1px solid #ffe082;
        }

        /* Table wrap with max-height */
        .tbl-max {
            max-height: 360px;
            overflow: auto;
        }

        /* Table footer bar */
        .tbl-footer {
            background: #e6eff8;
            border-top: 1px solid var(--border);
            padding: 5px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tbl-footer small {
            font-size: 11px;
            color: #5a7a94;
        }

        .tbl-footer small strong {
            color: var(--pri);
        }

        /* Pagination */
        .pagination .page-link {
            font-size: 11.5px;
            color: var(--pri);
            border-color: var(--border);
            padding: 3px 9px;
        }

        .pagination .page-link:hover {
            background: #dde8f5;
        }

        .pagination .page-item.active .page-link {
            background: var(--pri);
            border-color: var(--pri);
            color: #fff;
        }

        .page-ellipsis {
            display: inline-flex;
            align-items: center;
            padding: 0 6px;
            color: #6a8aaa;
        }

        /* Table loading overlay */
        .table-wrap {
            position: relative;
            min-height: 100px;
        }

        .table-loading {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .72);
            z-index: 10;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
        }

        .table-loading.show {
            display: flex;
        }

        /* Highlight match */
        mark.hl {
            background: rgba(245, 158, 11, .25);
            padding: 0 2px;
            border-radius: 2px;
            color: var(--pri);
        }

        /* Filter dot */
        #filter-dot {
            display: none;
        }

        #filter-dot.on {
            display: inline;
        }

        /* Batch info box */
        .batch-info-box {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-left: 3px solid var(--amber);
            border-radius: var(--r);
            padding: .75rem 1rem;
            margin: .75rem 0;
        }

        .batch-info-box .bi-date {
            font-size: 13px;
            font-weight: 700;
            color: var(--pri);
        }

        .batch-info-box .bi-count {
            font-size: 11px;
            color: #6a8aaa;
            margin-top: 3px;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-top: 3px solid var(--amber);
            border-radius: var(--r);
        }

        .modal-hdr-pri {
            background: linear-gradient(90deg, var(--pri), var(--pri-lt));
            border-bottom: none;
            padding: 10px 16px;
            border-radius: 3px 3px 0 0;
        }

        .modal-hdr-del {
            background: linear-gradient(90deg, #7f0000, var(--danger));
            border-bottom: none;
            padding: 10px 16px;
            border-radius: 3px 3px 0 0;
        }

        .modal-title {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .modal-body {
            padding: 16px;
            font-size: 12.5px;
        }

        .modal-footer {
            background: #f4f7fb;
            border-top: 1px solid var(--border);
            padding: 10px 16px;
        }

        /* per-page select */
        .per-page-select {
            width: 72px !important;
            height: 26px !important;
            font-size: .72rem !important;
            padding: 2px 4px !important;
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
            <span class="badge-pill">🎉 FESTIVAL BONUS</span>
            <span style="font-size:14px;font-weight:600;">Festival Bonus Generator</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:11px;opacity:.75;">FOUR DESIGN (PVT.) LTD. — Payroll Module</span>
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="container-fluid px-4 py-3">

        {{-- ALERT --}}
        <div id="fb-alert" class="mb-2"></div>

        {{-- PAGE HEADING --}}
        <div class="page-heading">
            <i class="bi bi-gift-fill"></i> Festival Bonus Generator
        </div>

        {{-- PARAMETERS CARD --}}
        <div class="sec-card">
            <div class="sec-card-head">
                <i class="bi bi-gear-fill"></i> Bonus Parameters
            </div>
            <div class="sec-card-body">
                <div class="row g-2 align-items-end">

                    <div class="col-sm-6 col-md-3">
                        <label class="form-label-sm">Payment Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="txt_date" class="form-control" placeholder="dd-Mon-yyyy"
                                autocomplete="off">
                            <span class="input-group-text" onclick="document.getElementById('txt_date')._flatpickr?.open()">
                                <i class="bi bi-calendar3"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label class="form-label-sm">Religion</label>
                        <select id="religion_id" class="form-select">
                            <option value="">— All Religions —</option>
                            @foreach ($religions as $r)
                                <option value="{{ $r->religion_id }}">{{ $r->religion_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label class="form-label-sm">Bonus Type <span class="text-danger">*</span></label>
                        <select id="bonus_type_id" class="form-select">
                            <option value="">— Select Type —</option>
                            @foreach ($bonusTypes as $b)
                                <option value="{{ $b->bonus_id }}">{{ $b->bonus_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label class="form-label-sm d-block">&nbsp;</label>
                        <div class="action-bar" style="padding:0;">
                            <button class="btn btn-amber" id="btn-calculate" onclick="confirmCalculate()">
                                <span class="spinner-border spinner-border-sm d-none me-1" id="sp-calc"
                                    role="status"></span>
                                <i class="bi bi-calculator"></i> Calculate
                            </button>
                            <button class="btn btn-outline-pri" onclick="loadPreview(1)">
                                <i class="bi bi-eye"></i> Preview
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- DELETE LAST BATCH --}}
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-del" onclick="confirmDelete()"
                style="font-size:11.5px !important;padding:4px 14px !important;">
                <i class="bi bi-trash3"></i> Delete Last Batch
            </button>
        </div>

        {{-- KPI ROW --}}
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-val amber" id="kpi-total-rows">—</div>
                <div class="kpi-lbl"><i class="bi bi-people-fill me-1"></i>Total Employees</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val green" id="kpi-grand-total">—</div>
                <div class="kpi-lbl"><i class="bi bi-cash-coin me-1"></i>Grand Total (BDT)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val" id="kpi-avg">—</div>
                <div class="kpi-lbl"><i class="bi bi-graph-up me-1"></i>Avg. Bonus</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val" id="kpi-pages">—</div>
                <div class="kpi-lbl"><i class="bi bi-file-earmark-text me-1"></i>Pages</div>
            </div>
        </div>

        {{-- PREVIEW TABLE CARD --}}
        <div class="sec-card">
            {{-- Card head with per-page --}}
            <div class="sec-card-head" style="justify-content:space-between;">
                <span>
                    <i class="bi bi-table"></i> Bonus Preview
                    <span id="filter-dot" class="on ms-1"
                        style="background:var(--amber);color:#0b1828;padding:1px 8px;
                                 border-radius:10px;font-size:10px;font-weight:700;">
                        Filtered
                    </span>
                </span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:10px;color:#a8c8e8;">Rows per page:</span>
                    <select id="per-page" class="form-select per-page-select" onchange="changePerPage()">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            {{-- Filter bar --}}
            <div class="filter-bar">
                <div class="row g-2 align-items-center">
                    <div class="col-sm-4 col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="filter-empno" class="form-control" placeholder="Emp No…"
                                onkeydown="filterKeydown(event)" autocomplete="off">
                            <span class="input-group-text" onclick="clearInput('filter-empno')" style="cursor:pointer;">
                                <i class="bi bi-x"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" id="filter-name" class="form-control" placeholder="Emp Name…"
                                onkeydown="filterKeydown(event)" autocomplete="off">
                            <span class="input-group-text" onclick="clearInput('filter-name')" style="cursor:pointer;">
                                <i class="bi bi-x"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-amber" onclick="applyFilters()"
                            style="font-size:11.5px !important;padding:4px 12px !important;">
                            <i class="bi bi-funnel"></i> Search
                        </button>
                        <button class="btn btn-outline-pri ms-1" onclick="clearFilters()"
                            style="font-size:11.5px !important;padding:4px 12px !important;">
                            Clear
                        </button>
                    </div>
                    <div class="col-auto ms-auto">
                        <span style="font-size:11px;color:#5a7a94;" id="filter-meta">—</span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrap">
                <div class="table-loading" id="table-loading">
                    <div class="spinner-border" style="color:var(--amber);width:1.4rem;height:1.4rem;" role="status">
                    </div>
                    <small style="color:#5a7a94;">Loading records…</small>
                </div>
                <div class="sub-table-wrap tbl-max">
                    <table class="emp-table" id="previewTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Emp No</th>
                                <th>Name</th>
                                <th>Bonus Type</th>
                                <th>Religion</th>
                                <th>Joining Date</th>
                                <th class="text-end">Service (Mo)</th>
                                <th class="text-end">Basic</th>
                                <th class="text-end">Gross</th>
                                <th class="text-end">%</th>
                                <th class="text-end">Bonus Amount</th>
                            </tr>
                        </thead>
                        <tbody id="preview-body">
                            <tr>
                                <td colspan="11" class="text-center text-muted py-5" style="font-size:.80rem;">
                                    <i class="bi bi-gift"
                                        style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>
                                    Select a date and click <strong>Preview</strong>.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination footer --}}
            <div class="tbl-footer d-none" id="pagination-bar">
                <small id="page-info">—</small>
                <nav aria-label="Preview pagination">
                    <ul class="pagination pagination-sm mb-0" id="page-btns"></ul>
                </nav>
            </div>

        </div>{{-- /.sec-card --}}

    </div>{{-- /.container-fluid --}}

    {{-- CONFIRM CALCULATE MODAL --}}
    <div class="modal fade" id="modal-calc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-hdr-pri">
                    <h6 class="modal-title">
                        <i class="bi bi-calculator me-2" style="color:var(--amber);"></i>
                        Confirm Bonus Calculation
                    </h6>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="modal-calc-text" style="color:#374a5a;">
                        Are you sure you want to generate festival bonus for all eligible employees?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sec btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-save btn-sm" id="btn-confirm-calc" onclick="doCalculate()">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="sp-modal" role="status"></span>
                        <i class="bi bi-check-circle"></i> Yes, Calculate
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CONFIRM DELETE MODAL --}}
    <div class="modal fade" id="modal-del" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-top-color:var(--danger);">
                <div class="modal-header modal-hdr-del">
                    <h6 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill me-2" style="color:#ffcdd2;"></i>
                        Delete Last Bonus Batch
                    </h6>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div
                        style="background:#ffeaea;border:1px solid #f5c6c6;
                                border-left:3px solid var(--danger);border-radius:var(--r);
                                padding:8px 12px;font-size:12px;color:var(--danger);margin-bottom:10px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        This will <strong>permanently remove</strong> the most recently
                        calculated festival bonus batch.
                    </div>
                    <div class="batch-info-box">
                        <div class="text-muted fst-italic" id="del-loading" style="font-size:11.5px;">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Loading last batch info…
                        </div>
                        <div id="del-batch-info" class="d-none">
                            <div class="bi-date" id="del-date">—</div>
                            <div class="bi-count" id="del-count">—</div>
                        </div>
                    </div>
                    <p class="mb-0" style="font-size:12px;color:#374a5a;">
                        Are you sure you want to continue?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sec btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-del btn-sm" id="btn-confirm-del" onclick="doDelete()"
                        disabled>
                        <span class="spinner-border spinner-border-sm d-none me-1" id="sp-del" role="status"></span>
                        <i class="bi bi-trash3"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        const bsCalcModal = new bootstrap.Modal(document.getElementById('modal-calc'));
        const bsDelModal = new bootstrap.Modal(document.getElementById('modal-del'));

        const state = {
            page: 1,
            perPage: 25,
            total: 0,
            lastPage: 1,
            from: 0,
            to: 0
        };

        /* ── Flatpickr ── */
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr('#txt_date', {
                dateFormat: 'd-M-Y',
                defaultDate: new Date(),
                allowInput: true,
                onChange: () => loadPreview(1),
            });
            loadPreview(1);
        });

        /* ── Alert ── */
        function showAlert(msg, type = 'success') {
            const el = document.getElementById('fb-alert');
            const color = type === 'success' ? '#1e7e34' : '#c0392b';
            el.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show py-2 mb-2" role="alert"
            style="font-size:12.5px;border-left:4px solid ${color};">
            ${msg}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>`;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        /* ── Params ── */
        function getCalcParams() {
            return {
                payment_date: document.getElementById('txt_date').value,
                religion_id: document.getElementById('religion_id').value || null,
                bonus_type_id: document.getElementById('bonus_type_id').value,
            };
        }

        /* ── Confirm Calculate ── */
        function confirmCalculate() {
            const p = getCalcParams();
            if (!p.payment_date) {
                showAlert('Please enter a Payment Date.', 'danger');
                return;
            }
            if (!p.bonus_type_id) {
                showAlert('Please select a Bonus Type.', 'danger');
                return;
            }
            const dateStr = p.payment_date;
            const rel = document.getElementById('religion_id');
            const relTxt = rel.value ? rel.options[rel.selectedIndex].text : 'All Religions';
            document.getElementById('modal-calc-text').textContent =
                `Generate bonus for ${relTxt} employees with payment date ${dateStr}?`;
            bsCalcModal.show();
        }

        /* ── Confirm Delete ── */
        function confirmDelete() {
            document.getElementById('del-loading').classList.remove('d-none');
            document.getElementById('del-batch-info').classList.add('d-none');
            document.getElementById('btn-confirm-del').disabled = true;
            bsDelModal.show();
            fetch('{{ route('payroll.festival-bonus.last-batch') }}')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('del-loading').classList.add('d-none');
                    if (data.success) {
                        document.getElementById('del-date').textContent = '📅 ' + data.last_date_fmt;
                        document.getElementById('del-count').textContent = data.total_records +
                            ' records will be deleted';
                        document.getElementById('del-batch-info').classList.remove('d-none');
                        document.getElementById('btn-confirm-del').disabled = false;
                    } else {
                        document.getElementById('del-loading').textContent = data.message || 'No records found.';
                        document.getElementById('del-loading').classList.remove('d-none');
                    }
                })
                .catch(() => {
                    document.getElementById('del-loading').textContent = 'Failed to load batch info.';
                });
        }

        /* ── Do Calculate ── */
        function doCalculate() {
            const sp = document.getElementById('sp-modal');
            const btn = document.getElementById('btn-confirm-calc');
            sp.classList.remove('d-none');
            btn.disabled = true;
            fetch('{{ route('payroll.festival-bonus.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(getCalcParams()),
                })
                .then(r => r.json())
                .then(data => {
                    bsCalcModal.hide();
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    if (data.success) loadPreview(1);
                })
                .catch(() => showAlert('Network error. Please try again.', 'danger'))
                .finally(() => {
                    sp.classList.add('d-none');
                    btn.disabled = false;
                });
        }

        /* ── Do Delete ── */
        function doDelete() {
            const sp = document.getElementById('sp-del');
            const btn = document.getElementById('btn-confirm-del');
            sp.classList.remove('d-none');
            btn.disabled = true;
            fetch('{{ route('payroll.festival-bonus.delete-last') }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                })
                .then(r => r.json())
                .then(data => {
                    bsDelModal.hide();
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    if (data.success) {
                        document.getElementById('preview-body').innerHTML =
                            `<tr><td colspan="11" class="text-center text-muted py-5" style="font-size:.80rem;">
                        <i class="bi bi-trash3" style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>
                        Batch deleted. Select a date and click Preview.</td></tr>`;
                        document.getElementById('pagination-bar').classList.add('d-none');
                        ['kpi-total-rows', 'kpi-grand-total', 'kpi-avg', 'kpi-pages']
                        .forEach(id => document.getElementById(id).textContent = '—');
                        document.getElementById('filter-meta').textContent = '—';
                    }
                })
                .catch(() => {
                    bsDelModal.hide();
                    showAlert('Network error.', 'danger');
                })
                .finally(() => {
                    sp.classList.add('d-none');
                    btn.disabled = false;
                });
        }

        /* ── Load Preview ── */
        function loadPreview(page) {
            const date = document.getElementById('txt_date').value;
            if (!date) {
                showAlert('Select a payment date first.', 'danger');
                return;
            }
            page = page || state.page;
            const empno = document.getElementById('filter-empno').value.trim();
            const empName = document.getElementById('filter-name').value.trim();
            const perPage = parseInt(document.getElementById('per-page').value);
            document.getElementById('filter-dot').classList.toggle('on', !!(empno || empName));
            const params = new URLSearchParams({
                payment_date: date,
                page,
                per_page: perPage
            });
            if (empno) params.set('empno', empno);
            if (empName) params.set('emp_name', empName);
            document.getElementById('table-loading').classList.add('show');
            fetch(`{{ route('payroll.festival-bonus.preview') }}?${params}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('table-loading').classList.remove('show');
                    if (!data.success) {
                        showAlert(data.message || 'Error loading preview.', 'danger');
                        return;
                    }
                    const m = data.meta;
                    Object.assign(state, {
                        page: m.page,
                        perPage: m.per_page,
                        total: m.total,
                        lastPage: m.last_page,
                        from: m.from,
                        to: m.to
                    });
                    document.getElementById('kpi-total-rows').textContent = fmt0(m.total);
                    document.getElementById('kpi-grand-total').textContent = fmt(m.grand_total);
                    document.getElementById('kpi-avg').textContent = fmt(m.grand_avg);
                    document.getElementById('kpi-pages').textContent = m.last_page;
                    document.getElementById('filter-meta').innerHTML =
                        (empno || empName) ?
                        `<strong>${m.total}</strong> matching record(s)` :
                        `<strong>${m.total}</strong> total record(s)`;
                    renderRows(data.data, m.from, empno, empName);
                    renderPagination(m);
                })
                .catch(err => {
                    document.getElementById('table-loading').classList.remove('show');
                    showAlert('Network error loading preview.', 'danger');
                });
        }

        /* ── Render Rows ── */
        function renderRows(rows, fromIdx, empnoQ, nameQ) {
            const tbody = document.getElementById('preview-body');
            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted py-5" style="font-size:.80rem;">
                <i class="bi bi-inbox" style="font-size:1.8rem;opacity:.2;display:block;margin-bottom:8px;"></i>
                No records found${empnoQ||nameQ?' matching your filter.':' for this date.'}</td></tr>`;
                return;
            }
            tbody.innerHTML = rows.map((r, i) => {
                const empno = String(r.empno ?? '');
                const empName = String(r.emp_name ?? '');
                return `<tr>
                <td style="color:#6a8aaa;font-size:11px;">${fromIdx + i}</td>
                <td><span class="emp-badge">${hl(empno, empnoQ)}</span></td>
                <td><strong>${hl(empName, nameQ)}</strong></td>
                <td><span class="type-pill bonus">${r.bonus_type ?? '—'}</span></td>
                <td style="font-size:11.5px;">${r.religion_name ?? '—'}</td>
                <td style="font-size:11.5px;">${r.joining_date  ?? '—'}</td>
                <td class="text-end">${r.job_months ?? '—'}</td>
                <td class="text-end" style="font-family:monospace;">${fmt(r.basic)}</td>
                <td class="text-end" style="font-family:monospace;">${fmt(r.gross)}</td>
                <td class="text-end">${r.PERCENT ?? r.percent ?? 0}%</td>
                <td class="text-end" style="font-family:monospace;font-weight:700;color:var(--accent);">${fmt(r.bonus_amount)}</td>
            </tr>`;
            }).join('');
        }

        /* ── Pagination ── */
        function renderPagination(m) {
            const bar = document.getElementById('pagination-bar');
            const info = document.getElementById('page-info');
            const ul = document.getElementById('page-btns');
            if (m.total === 0) {
                bar.classList.add('d-none');
                return;
            }
            bar.classList.remove('d-none');
            info.innerHTML =
                `Showing <strong>${m.from}</strong>–<strong>${m.to}</strong> of <strong>${m.total}</strong> records`;
            const pages = buildPageList(m.page, m.last_page);
            ul.innerHTML =
                `<li class="page-item ${m.page<=1?'disabled':''}">
                <a class="page-link" href="#" onclick="go(event,${m.page-1})">‹</a></li>` +
                pages.map(p =>
                    p === '…' ?
                    `<li class="page-item disabled"><span class="page-link page-ellipsis">…</span></li>` :
                    `<li class="page-item ${p===m.page?'active':''}">
                       <a class="page-link" href="#" onclick="go(event,${p})">${p}</a></li>`
                ).join('') +
                `<li class="page-item ${m.page>=m.last_page?'disabled':''}">
                <a class="page-link" href="#" onclick="go(event,${m.page+1})">›</a></li>`;
        }

        function go(e, page) {
            e.preventDefault();
            loadPreview(page);
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

        /* ── Filters ── */
        function applyFilters() {
            loadPreview(1);
        }

        function changePerPage() {
            loadPreview(1);
        }

        function clearFilters() {
            document.getElementById('filter-empno').value = '';
            document.getElementById('filter-name').value = '';
            loadPreview(1);
        }

        function clearInput(id) {
            document.getElementById(id).value = '';
        }

        function filterKeydown(e) {
            if (e.key === 'Enter') applyFilters();
        }

        /* ── Helpers ── */
        function hl(text, query) {
            if (!query || !text) return text || '—';
            const idx = text.toLowerCase().indexOf(query.toLowerCase());
            if (idx === -1) return text;
            return text.slice(0, idx) + `<mark class="hl">${text.slice(idx,idx+query.length)}</mark>` + text.slice(idx +
                query.length);
        }

        function fmt(n) {
            return parseFloat(n || 0).toLocaleString('en-BD', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function fmt0(n) {
            return parseInt(n || 0).toLocaleString('en-BD');
        }
    </script>
@endpush
