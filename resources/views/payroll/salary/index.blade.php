@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        /* Banner */
        .mode-banner {
            background: linear-gradient(90deg, var(--pri), var(--pri-lt));
            color: #fff;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid var(--amber);
            position: sticky;
            top: 0;
            z-index: 300;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }

        .badge-pill {
            background: var(--amber);
            color: #0b1828;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11.5px;
        }

        .t-div {
            width: 1px;
            height: 20px;
            background: rgba(255, 255, 255, .2);
            margin: 0 2px;
        }

        .btn-tb {
            background: rgba(255, 255, 255, .13);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 4px;
            padding: 4px 11px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-tb:hover {
            background: rgba(255, 255, 255, .25);
        }

        .btn-tb.green {
            background: #1e7e34;
            border-color: #27ae60;
        }

        .btn-tb.green:hover {
            background: #155d27;
        }

        .btn-tb.red {
            background: #c0392b;
            border-color: #e74c3c;
        }

        .btn-tb.red:hover {
            background: #922b21;
        }

        .btn-tb.amber {
            background: var(--amber);
            color: #0b1828;
            border-color: var(--amber);
            font-weight: 700;
        }

        .btn-tb.amber:hover {
            background: #d97706;
        }

        .btn-tb.blue {
            background: #1565c0;
            border-color: #1976d2;
        }

        .btn-tb.blue:hover {
            background: #0d47a1;
        }

        .mode-badge {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            color: #fff;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        /* Control bar */
        .ctrl-bar {
            background: #e6eff8;
            border-bottom: 1px solid var(--border);
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ctrl-bar label {
            font-size: 11px;
            font-weight: 700;
            color: var(--pri);
            white-space: nowrap;
        }

        .ctrl-bar .fc {
            height: 28px !important;
            font-size: 12px !important;
            border: 1px solid #bfcfdf !important;
            border-radius: 4px !important;
            background: var(--inp) !important;
            color: #1a2a3a !important;
            padding: 2px 7px !important;
        }

        .ctrl-bar .fc:focus {
            border-color: var(--pri-lt) !important;
            box-shadow: 0 0 0 2px rgba(34, 87, 160, .12) !important;
            outline: none;
        }

        .btn-ctrl {
            background: var(--pri);
            border: 1px solid var(--pri-lt);
            color: #fff;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }

        .btn-ctrl:hover {
            background: var(--pri-lt);
        }

        .btn-ctrl.grey {
            background: #546e7a;
            border-color: #546e7a;
        }

        .btn-ctrl.grey:hover {
            background: #37474f;
        }

        /* Stat row */
        .stat-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 8px 16px;
            background: #dde8f5;
            border-bottom: 1px solid var(--border);
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-top: 3px solid var(--amber);
            border-radius: var(--r);
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 9px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        .stat-val {
            font-size: 1rem;
            font-weight: 700;
            color: var(--pri);
            line-height: 1;
        }

        .stat-lbl {
            font-size: 10px;
            color: #6a8aaa;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* Section card */
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

        /* Section label */
        .sec-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--pri);
            background: #edf2f8;
            border-left: 3px solid var(--amber);
            padding: 3px 10px;
            margin: 12px 0 8px;
            border-radius: 0 3px 3px 0;
        }

        .sec-label:first-child {
            margin-top: 0;
        }

        /* Form */
        .form-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--lbl);
            margin-bottom: 2px;
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

        .form-control[readonly] {
            background: #dde8f5 !important;
            color: var(--pri) !important;
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

        .amt-earn {
            color: var(--accent) !important;
            font-weight: 700 !important;
        }

        .amt-deduct {
            color: var(--danger) !important;
            font-weight: 700 !important;
        }

        .amt-net {
            color: var(--pri-lt) !important;
            font-weight: 700 !important;
        }

        /* Tabbar */
        .emp-tabbar {
            background: var(--pri);
            display: flex;
            flex-wrap: wrap;
            padding: 0 6px;
            border-bottom: 3px solid #0c1f35;
            margin-top: 12px;
        }

        .emp-tabbar .nav-link {
            color: #a8c8e8 !important;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 8px 12px;
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            margin-bottom: -3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .emp-tabbar .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, .08);
        }

        .emp-tabbar .nav-link.active {
            color: #fff !important;
            border-bottom-color: var(--amber);
            background: rgba(255, 255, 255, .1);
        }

        .tab-content {
            border: 1px solid var(--border);
            border-top: none;
            background: var(--card);
        }

        .tab-content-body {
            padding: 12px 14px;
        }

        /* Table */
        .sal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .sal-table thead th {
            background: var(--pri);
            color: #fff;
            padding: 8px 9px;
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

        .sal-table tbody tr:nth-child(even) {
            background: #f2f7fc;
        }

        .sal-table tbody td {
            padding: 5px 9px;
            border-bottom: 1px solid #dde8f2;
            color: #2a3a4a;
            vertical-align: middle;
            white-space: nowrap;
        }

        .sal-table tbody tr:hover {
            background: #e4f0fb;
            cursor: pointer;
        }

        .sal-table tfoot td {
            background: #dde8f5;
            font-weight: 700;
            font-size: 11px;
            border-top: 2px solid var(--pri);
            padding: 6px 9px;
            color: var(--pri);
        }

        .num {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

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

        .ot-yes {
            background: #d4edda;
            color: #1e7e34;
            border: 1px solid #b2dfdb;
            padding: 1px 7px;
            border-radius: 9px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .tbl-footer {
            background: #e6eff8;
            border-top: 1px solid var(--border);
            padding: 5px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .tbl-footer small {
            font-size: 11px;
            color: #5a7a94;
        }

        .tbl-footer small strong {
            color: var(--pri);
        }

        .tbl-max {
            max-height: calc(100vh - 320px);
            overflow: auto;
        }

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

        /* Date toggle */
        .date-toggle {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .dt-opt {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 11px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            background: #fff;
            color: #5a7a94;
        }

        .dt-opt:hover {
            border-color: var(--pri);
            color: var(--pri);
            background: #edf2f8;
        }

        .dt-opt.active {
            background: var(--pri);
            color: #fff;
            border-color: var(--pri);
        }

        /* Proc log */
        .proc-log {
            background: #f4f7fb;
            border: 1px solid var(--border);
            border-left: 3px solid var(--amber);
            border-radius: var(--r);
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            max-height: 130px;
            overflow-y: auto;
            margin-top: 10px;
            line-height: 1.8;
        }

        .li {
            color: var(--pri-lt);
        }

        .lok {
            color: var(--accent);
            font-weight: 600;
        }

        .lerr {
            color: var(--danger);
        }

        /* Warn box */
        .warn-box {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-left: 3px solid var(--amber);
            border-radius: var(--r);
            padding: 8px 12px;
            font-size: 12px;
            color: #7a5800;
            margin-bottom: 10px;
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
            padding: 14px;
            font-size: 12.5px;
        }

        .modal-body .form-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--lbl);
        }

        .modal-footer {
            background: #f4f7fb;
            border-top: 1px solid var(--border);
            padding: 10px 14px;
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

        hr.sec-hr {
            border: none;
            border-top: 1.5px dashed #d0dceb;
            margin: 10px 0;
        }

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
    {{-- TOOLBAR --}}
    <div class="mode-banner">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <span class="badge-pill"><i class="bi bi-cash-stack"></i> SALARY ENTRY</span>
            <div class="t-div" hidden></div>
            <button class="btn-tb" id="btnNew" hidden><i class="bi bi-file-earmark-plus"></i> New</button>
            <button class="btn-tb green" id="btnSave" style="display:none;"><i class="bi bi-save2"></i> Save</button>
            <button class="btn-tb amber" id="btnEdit" style="display:none;"><i class="bi bi-pencil-square"></i>
                Edit</button>
            <button class="btn-tb red" id="btnDeleteRow" style="display:none;"><i class="bi bi-trash3"></i> Delete
                Entry</button>
            <button class="btn-tb" id="btnCancel" style="display:none;"><i class="bi bi-x-circle"></i> Cancel</button>
            <div class="t-div"></div>
            <button class="btn-tb amber" id="btnGrossEntry"><i class="bi bi-people-fill"></i> Gross Entry</button>
            <div class="t-div"></div>
            <button class="btn-tb" id="btnQuery"><i class="bi bi-search"></i> Query</button>
            <button class="btn-tb" id="btnRefresh"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            <div class="t-div"></div>
            <button class="btn-tb green" id="btnOpenProcessor"><i class="bi bi-play-circle-fill"></i> Salary
                Processor</button>
            <button class="btn-tb red" id="btnOpenDelete"><i class="bi bi-trash3"></i> Delete Salary</button>
        </div>
        <span class="mode-badge" id="modeBadge">👁 VIEW</span>
    </div>

    {{-- CONTROL BAR --}}
    <div class="ctrl-bar">
        <label>Company:</label>
        <select id="ctlCompany" class="fc" style="width:190px;" onchange="onCompanyChange()">
            <option value="">— Select —</option>
            @foreach ($companies ?? [] as $c)
                <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
            @endforeach
        </select>
        <label>Salary Month:</label>
        <div style="display:flex;">
            <input type="text" id="ctlSalaryDate" class="fc fp-date"
                style="width:130px;border-radius:4px 0 0 4px!important;" placeholder="dd-Mon-yyyy" onchange="loadEntries()">
            <span class="input-group-text" style="height:28px;"
                onclick="document.getElementById('ctlSalaryDate')._flatpickr?.open()"><i class="bi bi-calendar3"></i></span>
        </div>
        <label>Emp No:</label>
        <input type="text" id="ctlEmpNo" class="fc" style="width:100px;" placeholder="All"
            onkeydown="if(event.key==='Enter')loadEntries()">
        <button class="btn-ctrl" onclick="loadEntries()"><i class="bi bi-search"></i> Load</button>
        <button class="btn-ctrl grey" onclick="clearCtl()"><i class="bi bi-x-circle"></i> Clear</button>
        <div style="margin-left:auto;font-size:11px;color:#5a7a94;">Records: <strong id="recCount"
                style="color:var(--pri);">0</strong></div>
    </div>

    {{-- STAT ROW --}}
    <div class="stat-row">
        <div class="stat-card"><i class="bi bi-people-fill" style="font-size:1.2rem;color:var(--amber);"></i>
            <div>
                <div class="stat-val" id="stEmp">0</div>
                <div class="stat-lbl">Employees</div>
            </div>
        </div>
        <div class="stat-card"><i class="bi bi-cash-coin" style="font-size:1.2rem;color:var(--amber);"></i>
            <div>
                <div class="stat-val" id="stGross">0.00</div>
                <div class="stat-lbl">Total Gross</div>
            </div>
        </div>
        <div class="stat-card"><i class="bi bi-arrow-down-circle" style="font-size:1.2rem;color:var(--danger);"></i>
            <div>
                <div class="stat-val" id="stDeduct">0.00</div>
                <div class="stat-lbl">Total Deduct</div>
            </div>
        </div>
        <div class="stat-card"><i class="bi bi-bank" style="font-size:1.2rem;color:var(--pri-lt);"></i>
            <div>
                <div class="stat-val" id="stNet">0.00</div>
                <div class="stat-lbl">Net Payable</div>
            </div>
        </div>
        <div class="stat-card" id="formStatCard" style="display:none;border-top-color:var(--pri-lt);">
            <i class="bi bi-calculator" style="font-size:1rem;color:var(--pri-lt);"></i>
            <span style="font-size:11.5px;color:#5a7a94;">
                Earn: <strong class="amt-earn" id="liveEarn">0.00</strong> &nbsp;|&nbsp;
                Deduct: <strong class="amt-deduct" id="liveDeduct">0.00</strong> &nbsp;|&nbsp;
                Net: <strong class="amt-net" id="liveNet">0.00</strong>
            </span>
        </div>
    </div>

    <div style="padding:14px 16px;">

        {{-- ENTRY FORM --}}
        <div id="entryFormWrap" style="display:none;">
            <div class="sec-card">
                <div class="sec-card-head"><i class="bi bi-person-vcard"></i><span id="formTitle">New Salary Entry</span>
                </div>
                <div class="sec-card-body">
                    <span class="sec-label">Employee Information</span>
                    <div class="row g-2">
                        <div class="col-md-2"><label class="form-label">Emp No <span class="text-danger">*</span></label>
                            <div class="input-group"><input type="text" id="entEmpNo" class="form-control"
                                    onkeydown="if(event.key==='Enter'){event.preventDefault();loadEmpInfo();}">
                                <span class="input-group-text" onclick="loadEmpInfo()"><i
                                        class="bi bi-search"></i></span>
                            </div>
                        </div>
                        <div class="col-md-2"><label class="form-label">New Emp No</label><input type="text"
                                id="entNewEmpNo" class="form-control" readonly></div>
                        <div class="col-md-3"><label class="form-label">Employee Name</label><input type="text"
                                id="entEmpName" class="form-control" readonly></div>
                        <div class="col-md-3"><label class="form-label">Designation</label><input type="text"
                                id="entDesName" class="form-control" readonly></div>
                        <div class="col-md-2"><label class="form-label">Grade</label><input type="text"
                                id="entGrade" class="form-control" readonly></div>
                        <div class="col-md-3"><label class="form-label">Department</label><input type="text"
                                id="entDeptName" class="form-control" readonly></div>
                        <div class="col-md-2"><label class="form-label">Section</label><input type="text"
                                id="entSection" class="form-control" readonly></div>
                        <div class="col-md-2"><label class="form-label">Emp Type</label><input type="text"
                                id="entEmpType" class="form-control" readonly></div>
                        <div class="col-md-2"><label class="form-label">Joining Date</label><input type="text"
                                id="entJoiningDate" class="form-control" readonly></div>
                        <div class="col-md-3"><label class="form-label">Salary Date <span
                                    class="text-danger">*</span></label>
                            <div class="input-group"><input type="text" id="entSalaryDate"
                                    class="form-control fp-date" placeholder="dd-Mon-yyyy">
                                <span class="input-group-text"
                                    onclick="document.getElementById('entSalaryDate')._flatpickr?.open()"><i
                                        class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="emp-tabbar" id="entryTabs">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabEarn"><i
                                class="bi bi-plus-circle"></i> Earnings</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabDeduct"><i
                                class="bi bi-dash-circle"></i> Deductions</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabOT"><i
                                class="bi bi-clock-history"></i> OT / Piece Rate</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAttend"><i
                                class="bi bi-calendar-check"></i> Attendance</button>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabEarn">
                            <div class="tab-content-body">
                                <div class="row g-2">
                                    <div class="col-md-3"><label class="form-label">Gross Salary</label>
                                        <div class="input-group"><input type="number" id="entGross"
                                                class="form-control" step="0.01"><span class="input-group-text"
                                                onclick="calcFromGross()"><i class="bi bi-calculator"></i></span></div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label">Basic Pay</label><input
                                            type="number" id="entBasic" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">House Rent</label><input
                                            type="number" id="entHouseRent" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Medical</label><input type="number"
                                            id="entMedical" class="form-control" step="0.01" oninput="calcTotals()">
                                    </div>
                                    <div class="col-md-3"><label class="form-label">Conveyance</label><input
                                            type="number" id="entConvance" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Food Allowance</label><input
                                            type="number" id="entFood" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Entertainment</label><input
                                            type="number" id="entEntertain" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Extra Allowance</label><input
                                            type="number" id="entExtraAllow" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Extra Pay</label><input
                                            type="number" id="entExtraPay" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Stamp</label><input type="number"
                                            id="entStamp" class="form-control" step="0.01" oninput="calcTotals()">
                                    </div>
                                    <div class="col-md-3"><label class="form-label">Attendance Bonus</label><input
                                            type="number" id="entAtndBonus" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Festival Bonus</label><input
                                            type="number" id="entFestBonus" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Arrear Amount</label><input
                                            type="number" id="entArrear" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Production Bonus</label><input
                                            type="number" id="entProdBonus" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label amt-earn">Total Earnings</label><input
                                            type="number" id="entTotalEarn" class="form-control amt-earn" readonly
                                            step="0.01"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabDeduct">
                            <div class="tab-content-body">
                                <div class="row g-2">
                                    <div class="col-md-3"><label class="form-label">PF Deduction</label><input
                                            type="number" id="entPF" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Advance/Loan A</label><input
                                            type="number" id="entLoanA" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Advance/Loan B</label><input
                                            type="number" id="entLoanB" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Advance/Loan C</label><input
                                            type="number" id="entLoanC" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Advance/Loan D</label><input
                                            type="number" id="entLoanD" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Other Deduction</label><input
                                            type="number" id="entOtherDeduct" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Lunch Deduction</label><input
                                            type="number" id="entLunch" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Late Deduction</label><input
                                            type="number" id="entLate" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Absent Deduction</label><input
                                            type="number" id="entAbsent" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Tax Deduction</label><input
                                            type="number" id="entTax" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label amt-deduct">Total
                                            Deductions</label><input type="number" id="entTotalDeduct"
                                            class="form-control amt-deduct" readonly step="0.01"></div>
                                    <div class="col-md-3"><label class="form-label amt-net">Net Payable</label><input
                                            type="number" id="entNet" class="form-control amt-net" readonly
                                            step="0.01"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabOT">
                            <div class="tab-content-body">
                                <div class="row g-2">
                                    <div class="col-md-3"><label class="form-label">OT Entry</label><select
                                            id="entOtEnt" class="form-select">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select></div>
                                    <div class="col-md-3"><label class="form-label">OT Hours</label><input type="number"
                                            id="entOtHour" class="form-control" step="0.01" oninput="calcOT()"></div>
                                    <div class="col-md-3"><label class="form-label">OT Rate</label><input type="number"
                                            id="entOtRate" class="form-control" step="0.01" oninput="calcOT()"></div>
                                    <div class="col-md-3"><label class="form-label">OT Amount</label><input
                                            type="number" id="entOtAmount" class="form-control" readonly
                                            step="0.01"></div>
                                    <div class="col-md-3"><label class="form-label">OT Rate 2</label><input
                                            type="number" id="entOtRate1" class="form-control" step="0.01"></div>
                                    <div class="col-md-3"><label class="form-label">Piece Rate Entry</label><select
                                            id="entPieceRateEnt" class="form-select">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select></div>
                                    <div class="col-md-3"><label class="form-label">Piece Rate Total</label><input
                                            type="number" id="entPieceTotal" class="form-control" step="0.01"
                                            oninput="calcTotals()"></div>
                                    <div class="col-md-3"><label class="form-label">Piece Rate Advance</label><input
                                            type="number" id="entPieceAdv" class="form-control" step="0.01"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabAttend">
                            <div class="tab-content-body">
                                <div class="row g-2">
                                    <div class="col-md-2"><label class="form-label">Present Days</label><input
                                            type="number" id="entPresent" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Absent Days</label><input
                                            type="number" id="entAbsentCnt" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Leave Days</label><input
                                            type="number" id="entLeave" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">SL Days</label><input type="number"
                                            id="entSL" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Weekday Count</label><input
                                            type="number" id="entWeekday" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Holiday Count</label><input
                                            type="number" id="entHoliday" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Total Days</label><input
                                            type="number" id="entTotalDays" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Work Days</label><input
                                            type="number" id="entWorkDay" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Late Count</label><input
                                            type="number" id="entLateCount" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Leave Amount</label><input
                                            type="number" id="entLeaveAmt" class="form-control" step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Advance Balance</label><input
                                            type="number" id="entAdvBalance" class="form-control" readonly
                                            step="0.01"></div>
                                    <div class="col-md-2"><label class="form-label">Bank A/C No</label><input
                                            type="text" id="entBankAcc" class="form-control"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LIST --}}
        <div id="listWrap">
            <div class="sec-card" style="margin-bottom:0;">
                <div class="sec-card-head"><i class="bi bi-table"></i> Salary Records</div>
                <div class="table-wrap">
                    <div class="table-loading" id="tableLoading">
                        <div class="spinner-border" style="color:var(--amber);width:1.4rem;height:1.4rem;"
                            role="status"></div>
                        <small style="color:#5a7a94;">Loading…</small>
                    </div>
                    <div class="tbl-max">
                        <table class="sal-table" id="salTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp No</th>
                                    <th>New No</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Grade</th>
                                    <th class="num">Basic</th>
                                    <th class="num">Gross</th>
                                    <th class="num">HR</th>
                                    <th class="num">Medical</th>
                                    <th class="num">Conv.</th>
                                    <th class="num">Food</th>
                                    <th class="num">OT Amt</th>
                                    <th class="num">Prod.Bonus</th>
                                    <th class="num">Total Earn</th>
                                    <th class="num">PF</th>
                                    <th class="num">Adv/Loan</th>
                                    <th class="num">Other Ded.</th>
                                    <th class="num">Total Ded.</th>
                                    <th class="num">Net Pay</th>
                                    <th class="text-center">OT</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="salBody">
                                <tr>
                                    <td colspan="23" class="text-center text-muted py-5" style="font-size:.80rem;">
                                        <i class="bi bi-cash-stack"
                                            style="font-size:2rem;opacity:.15;display:block;margin-bottom:8px;"></i>
                                        Select company and salary date, then click <strong>Load</strong>.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-end">Totals:</td>
                                    <td class="num" id="ftBasic">0.00</td>
                                    <td class="num" id="ftGross">0.00</td>
                                    <td class="num" id="ftHR">0.00</td>
                                    <td class="num" id="ftMed">0.00</td>
                                    <td class="num" id="ftConv">0.00</td>
                                    <td class="num" id="ftFood">0.00</td>
                                    <td class="num" id="ftOT">0.00</td>
                                    <td class="num" id="ftProd">0.00</td>
                                    <td class="num" id="ftEarn">0.00</td>
                                    <td class="num" id="ftPF">0.00</td>
                                    <td class="num" id="ftAdv">0.00</td>
                                    <td class="num" id="ftOther">0.00</td>
                                    <td class="num" id="ftDeduct">0.00</td>
                                    <td class="num" id="ftNet">0.00</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="tbl-footer">
                    <small>Total: <strong id="stTotal">0</strong> records</small>
                    <small id="lastLoad"></small>
                </div>
            </div>
        </div>

    </div>{{-- /padding --}}

    {{-- PROCESSOR MODAL --}}
    <div class="modal fade" id="processorModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-hdr-pri">
                    <h6 class="modal-title"><i class="bi bi-play-circle-fill me-2" style="color:var(--amber);"></i>Salary
                        Processor <small class="ms-2" style="font-size:10px;font-weight:400;opacity:.75;"></small>
                    </h6>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <span class="sec-label" style="margin-top:0;">Company &amp; Employee Filter</span>
                    <div class="row g-2 mb-3">
                        <div class="col-md-5"><label class="form-label">Company <span
                                    class="text-danger">*</span></label>
                            <select id="procCompany" class="form-select">
                                <option value="">— Select —</option>
                                @foreach ($companies ?? [] as $c)
                                    <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Employee Type</label>
                            <select id="procEmpType" class="form-select">
                                <option value="">All Types</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Worker">Worker</option>
                            </select>
                        </div>
                    </div>
                    <span class="sec-label">Date Range</span>
                    <div class="date-toggle">
                        <span class="dt-opt active" data-mode="full" onclick="setDateMode('full')"><i
                                class="bi bi-calendar-month"></i> Full Month</span>
                        <span class="dt-opt" data-mode="custom" onclick="setDateMode('custom')"><i
                                class="bi bi-calendar-range"></i> Custom Range</span>
                        <span class="dt-opt" data-mode="first10" onclick="setDateMode('first10')"><i
                                class="bi bi-1-circle"></i> First 10 Days</span>
                        <span class="dt-opt" data-mode="last10" onclick="setDateMode('last10')"><i
                                class="bi bi-calendar-minus"></i> Last 10 Days</span>
                    </div>
                    <div id="fullMonthRow">
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label">Month <span
                                        class="text-danger">*</span></label>
                                <select id="procMonth" class="form-select">
                                    <option value="">— Month —</option>
                                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $i => $m)
                                        <option value="{{ $i + 1 }}" {{ now()->month == $i + 1 ? 'selected' : '' }}>
                                            {{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label">Year <span
                                        class="text-danger">*</span></label>
                                <select id="procYear" class="form-select">
                                    @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label">Resolved Range</label>
                                <input type="text" id="fullMonthResolved" class="form-control" readonly
                                    style="background:#dde8f5!important;color:var(--pri)!important;font-weight:600;">
                            </div>
                        </div>
                    </div>
                    <div id="customRow" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-3"><label class="form-label">From Date</label>
                                <div class="input-group"><input type="text" id="procDateFrom"
                                        class="form-control fp-date" placeholder="dd-Mon-yyyy">
                                    <span class="input-group-text"
                                        onclick="document.getElementById('procDateFrom')._flatpickr?.open()"><i
                                            class="bi bi-calendar3"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3"><label class="form-label">To Date</label>
                                <div class="input-group"><input type="text" id="procDateTo"
                                        class="form-control fp-date" placeholder="dd-Mon-yyyy">
                                    <span class="input-group-text"
                                        onclick="document.getElementById('procDateTo')._flatpickr?.open()"><i
                                            class="bi bi-calendar3"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end"><button class="btn-tb blue w-100"
                                    style="justify-content:center;" onclick="resolveCustomDates()"><i
                                        class="bi bi-check-circle"></i> Apply</button></div>
                            <div class="col-md-3"><label class="form-label">Resolved Range</label>
                                <input type="text" id="customResolved" class="form-control" readonly
                                    style="background:#dde8f5!important;color:var(--pri)!important;font-weight:600;">
                            </div>
                        </div>
                    </div>
                    <div id="fixed10Row" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label">Month</label>
                                <select id="proc10Month" class="form-select" onchange="resolve10Days()">
                                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $i => $m)
                                        <option value="{{ $i + 1 }}"
                                            {{ now()->month == $i + 1 ? 'selected' : '' }}>
                                            {{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><label class="form-label">Year</label>
                                <select id="proc10Year" class="form-select" onchange="resolve10Days()">
                                    @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-5"><label class="form-label">Resolved Range</label>
                                <input type="text" id="fixed10Resolved" class="form-control" readonly
                                    style="background:#dde8f5!important;color:var(--pri)!important;font-weight:600;">
                            </div>
                        </div>
                    </div>
                    <div class="proc-log" id="procLog" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-tb" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
                    <button class="btn-tb green" id="btnRunProcess"><i class="bi bi-play-fill"></i> Run Salary
                        Process</button>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-top-color:var(--danger);">
                <div class="modal-header modal-hdr-del">
                    <h6 class="modal-title"><i class="bi bi-trash3 me-2" style="color:#ffcdd2;"></i>Delete Salary Records
                    </h6>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="warn-box"><i class="bi bi-exclamation-triangle-fill me-2"></i>Deletes from
                        <strong>SALARY_PAYMENT_INFO</strong> where <code>PAYMENT_DATE = :date AND COMPANY_ID = :cid</code>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-5"><label class="form-label">Company <span
                                    class="text-danger">*</span></label>
                            <select id="delCompany" class="form-select">
                                <option value="">— Select —</option>
                                @foreach ($companies ?? [] as $c)
                                    <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">Payment Date Period</label>
                            <select id="delDate" class="form-select">
                                <option value="">— Select Period —</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn-tb blue w-100" style="justify-content:center;" onclick="loadDelDates()"><i
                                    class="bi bi-search"></i> Load Periods</button>
                        </div>
                    </div>
                    <div class="mt-2" id="delCountInfo"
                        style="display:none;font-size:12px;color:var(--pri);font-weight:600;">
                        <i class="bi bi-info-circle me-1"></i><span id="delCountTxt"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-tb" onclick="checkDelCount()"><i class="bi bi-search"></i> Count Records</button>
                    <button class="btn-tb" data-bs-dismiss="modal"><i class="bi bi-x"></i> Cancel</button>
                    <button class="btn-tb red" id="btnConfirmDelete"><i class="bi bi-trash3"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const API = {
            entries: '/api/salary/entries',
            entry: '/api/salary/entry',
            process: '/api/salary/process',
            deleteSal: '/api/salary/delete',
            delCount: '/api/salary/delete/count',
            delDates: '/api/salary/delete/dates',
            empInfo: '/api/salary/emp-info',
            params: '/api/salary/params',
            grossEntry: '/api/salary/gross-entry'
        };

        flatpickr('.fp-date', {
            dateFormat: 'd-M-Y',
            allowInput: true
        });

        let mode = 'view',
            currentEmp = null,
            salParams = {},
            dateMode = 'full';

        // TOOLBAR
        document.getElementById('btnNew').onclick = () => {
            const cid = document.getElementById('ctlCompany').value;
            if (!cid) return toast('warning', 'Select a company first');
            setMode('new');
            clearEntryForm();
            const d = document.getElementById('ctlSalaryDate').value;
            if (d) document.getElementById('entSalaryDate').value = d;
            document.getElementById('entEmpNo').focus();
        };
        document.getElementById('btnSave').onclick = () => saveEntry();
        document.getElementById('btnCancel').onclick = () => {
            setMode('view');
            loadEntries();
        };
        document.getElementById('btnDeleteRow').onclick = () => deleteEntry();
        document.getElementById('btnEdit').onclick = () => {
            enableEditing(true);
            setMode('edit');
            toast('info', 'Edit mode — make changes then Save');
        };
        document.getElementById('btnQuery').onclick = () => loadEntries();
        document.getElementById('btnRefresh').onclick = () => loadEntries();
        document.getElementById('btnOpenProcessor').onclick = () => {
            const cid = document.getElementById('ctlCompany').value;
            if (cid) document.getElementById('procCompany').value = cid;
            updateFullMonthResolved();
            document.getElementById('procLog').style.display = 'none';
            document.getElementById('procLog').innerHTML = '';
            new bootstrap.Modal(document.getElementById('processorModal')).show();
        };
        document.getElementById('btnOpenDelete').onclick = () => {
            const cid = document.getElementById('ctlCompany').value;
            if (cid) document.getElementById('delCompany').value = cid;
            document.getElementById('delCountInfo').style.display = 'none';
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        };
        document.getElementById('btnGrossEntry').onclick = () => runGrossEntry();

        // MODE
        function setMode(m) {
            mode = m;
            const badge = document.getElementById('modeBadge');
            badge.textContent = m === 'new' ? ' NEW' : m === 'edit' ? 'EDIT' : 'VIEW';
            const sf = (m === 'new' || m === 'edit');
            document.getElementById('entryFormWrap').style.display = sf ? 'block' : 'none';
            document.getElementById('listWrap').style.display = sf ? 'none' : 'block';
            document.getElementById('formStatCard').style.display = sf ? 'flex' : 'none';
            document.getElementById('btnNew').style.display = sf ? 'none' : 'inline-flex';
            document.getElementById('btnSave').style.display = sf ? 'inline-flex' : 'none';
            document.getElementById('btnEdit').style.display = (m === 'view' && currentEmp) ? 'inline-flex' : 'none';
            document.getElementById('btnCancel').style.display = sf ? 'inline-flex' : 'none';
            document.getElementById('btnDeleteRow').style.display = (m === 'edit') ? 'inline-flex' : 'none';
            document.getElementById('formTitle').textContent = m === 'new' ? 'New Salary Entry' : 'Edit Salary Entry — ' + (
                currentEmp ?? '');
            enableEditing(m !== 'view');
        }

        function enableEditing(on) {
            const ro = ['entNewEmpNo', 'entEmpName', 'entDesName', 'entGrade', 'entDeptName', 'entSection', 'entEmpType',
                'entJoiningDate', 'entAdvBalance', 'entOtAmount', 'entTotalEarn', 'entTotalDeduct', 'entNet'
            ];
            document.querySelectorAll('#entryFormWrap input,#entryFormWrap select').forEach(el => {
                if (ro.includes(el.id)) {
                    el.readOnly = true;
                } else {
                    el.readOnly = !on;
                    if (el.tagName === 'SELECT') el.disabled = !on;
                }
            });
        }

        function onCompanyChange() {
            loadSalParams();
            loadEntries();
        }
        async function loadSalParams() {
            const cid = document.getElementById('ctlCompany').value;
            if (!cid) return;
            try {
                const r = await fetch(`${API.params}?company_id=${cid}`);
                const d = await r.json();
                salParams = d.data ?? {};
            } catch (e) {}
        }

        async function loadEntries() {
            const cid = document.getElementById('ctlCompany').value;
            if (!cid) return;
            const p = new URLSearchParams({
                company_id: cid
            });
            const sd = document.getElementById('ctlSalaryDate').value;
            const en = document.getElementById('ctlEmpNo').value.trim();
            if (sd) p.set('salary_date', sd);
            if (en) p.set('emp_no', en);
            document.getElementById('tableLoading').classList.add('show');
            document.getElementById('salBody').innerHTML = '';
            try {
                const r = await fetch(`${API.entries}?${p}`);
                const d = await r.json();
                renderTable(d.data ?? []);
                document.getElementById('lastLoad').textContent = 'Loaded: ' + new Date().toLocaleTimeString();
            } catch (e) {
                document.getElementById('salBody').innerHTML =
                    `<tr><td colspan="23" class="text-center text-danger py-3">${e.message}</td></tr>`;
            }
            document.getElementById('tableLoading').classList.remove('show');
        }

        function renderTable(rows) {
            const tbody = document.getElementById('salBody');
            const tots = {};
            ['basic', 'gross', 'hr', 'med', 'conv', 'food', 'ot', 'prod', 'earn', 'pf', 'adv', 'other', 'deduct', 'net']
            .forEach(k => tots[k] = 0);
            if (!rows.length) {
                tbody.innerHTML =
                    `<tr><td colspan="23" class="text-center text-muted py-5" style="font-size:.80rem;"><i class="bi bi-inbox" style="font-size:1.8rem;opacity:.15;display:block;margin-bottom:8px;"></i>No salary records found.</td></tr>`;
                updateStats(0, 0, 0, 0);
                return;
            }
            tbody.innerHTML = rows.map((r, i) => {
                const adv = (+r.advance_loan_deduction_a || 0) + (+r.advance_loan_deduction_b || 0) + (+r
                    .advance_loan_deduction_c || 0) + (+r.advance_loan_deduction_d || 0);
                tots.basic += +r.basic_pay || 0;
                tots.gross += +r.gross || 0;
                tots.hr += +r.house_rent || 0;
                tots.med += +r.medical || 0;
                tots.conv += +r.convence || 0;
                tots.food += +r.food_allownce || 0;
                tots.ot += +r.ot_amount || 0;
                tots.prod += +r.production_bonus || 0;
                tots.earn += +r.total_salary || 0;
                tots.pf += +r.pf_deduction || 0;
                tots.adv += adv;
                tots.other += +r.other_deduction || 0;
                tots.deduct += +r.total_deduction || 0;
                tots.net += +r.net_payable || 0;
                return `<tr ondblclick="loadForEdit('${r.empno}')"><td style="color:#6a8aaa;font-size:11px;">${i+1}</td><td><span class="emp-badge">${r.empno??''}</span></td><td style="font-size:11.5px;">${r.new_empno??''}</td><td><strong>${r.emp_name??''}</strong></td><td style="font-size:11px;">${r.des_name??''}</td><td style="font-size:11px;">${r.dept_name??''}</td><td>${r.grade??''}</td><td class="num">${fmt(r.basic_pay)}</td><td class="num">${fmt(r.gross)}</td><td class="num">${fmt(r.house_rent)}</td><td class="num">${fmt(r.medical)}</td><td class="num">${fmt(r.convence)}</td><td class="num">${fmt(r.food_allownce)}</td><td class="num">${fmt(r.ot_amount)}</td><td class="num">${fmt(r.production_bonus)}</td><td class="num amt-earn"><strong>${fmt(r.total_salary)}</strong></td><td class="num">${fmt(r.pf_deduction)}</td><td class="num">${fmt(adv)}</td><td class="num">${fmt(r.other_deduction)}</td><td class="num amt-deduct">${fmt(r.total_deduction)}</td><td class="num amt-net"><strong>${fmt(r.net_payable)}</strong></td><td class="text-center">${r.ot_ent==='Yes'?'<span class="ot-yes">Yes</span>':'<span style="color:#bbb;font-size:11px;">No</span>'}</td><td class="text-center"><button class="btn-tb amber" style="padding:2px 8px;font-size:11px;" onclick="loadForEdit('${r.empno}')"><i class="bi bi-pencil"></i></button></td></tr>`;
            }).join('');
            [
                ['ftBasic', tots.basic],
                ['ftGross', tots.gross],
                ['ftHR', tots.hr],
                ['ftMed', tots.med],
                ['ftConv', tots.conv],
                ['ftFood', tots.food],
                ['ftOT', tots.ot],
                ['ftProd', tots.prod],
                ['ftEarn', tots.earn],
                ['ftPF', tots.pf],
                ['ftAdv', tots.adv],
                ['ftOther', tots.other],
                ['ftDeduct', tots.deduct],
                ['ftNet', tots.net]
            ].forEach(([id, v]) => document.getElementById(id).textContent = fmt(v));
            updateStats(rows.length, tots.gross, tots.deduct, tots.net);
            document.getElementById('recCount').textContent = rows.length;
            document.getElementById('stTotal').textContent = rows.length;
            currentEmp = null;
            document.getElementById('btnEdit').style.display = 'none';
        }

        function updateStats(cnt, gross, deduct, net) {
            document.getElementById('stEmp').textContent = cnt;
            document.getElementById('stGross').textContent = fmt(gross);
            document.getElementById('stDeduct').textContent = fmt(deduct);
            document.getElementById('stNet').textContent = fmt(net);
        }

        async function loadForEdit(empNo) {
            const cid = document.getElementById('ctlCompany').value;
            const sd = document.getElementById('ctlSalaryDate').value;
            currentEmp = empNo;
            setMode('view');
            try {
                const r = await fetch(`${API.entry}?emp_no=${empNo}&company_id=${cid}&salary_date=${sd}`);
                const d = await r.json();
                if (!d.data) throw new Error('Record not found');
                fillEntryForm(d.data);
                setMode('edit');
                enableEditing(false);
            } catch (e) {
                toast('error', e.message);
            }
        }

        async function loadEmpInfo() {
            const empNo = document.getElementById('entEmpNo').value.trim();
            const cid = document.getElementById('ctlCompany').value;
            if (!empNo || !cid) return;
            try {
                const r = await fetch(`${API.empInfo}?emp_no=${empNo}&company_id=${cid}`);
                const d = await r.json();
                if (!d.data) throw new Error('Employee not found');
                const e = d.data;
                sv('entNewEmpNo', e.new_empno);
                sv('entEmpName', e.emp_name);
                sv('entDesName', e.des_name);
                sv('entGrade', e.grade);
                sv('entDeptName', e.dept_name);
                sv('entSection', e.section_name);
                sv('entEmpType', e.emp_type);
                sv('entJoiningDate', e.joining_date);
                sv('entGross', e.gross);
                sv('entAdvBalance', e.advance_balance ?? 0);
                if (e.gross) calcFromGross();
                document.getElementById('entSalaryDate').focus();
            } catch (e) {
                toast('warning', e.message);
            }
        }

        function fillEntryForm(d) {
            const m = {
                entEmpNo: d.empno,
                entNewEmpNo: d.new_empno,
                entEmpName: d.emp_name,
                entDesName: d.des_name,
                entGrade: d.grade,
                entDeptName: d.dept_name,
                entSection: d.section_name,
                entEmpType: d.emp_type,
                entJoiningDate: d.joining_date,
                entSalaryDate: d.salary_date,
                entGross: d.gross,
                entBasic: d.basic_pay,
                entHouseRent: d.house_rent,
                entMedical: d.medical,
                entConvance: d.convence,
                entFood: d.food_allownce,
                entEntertain: d.entertinment,
                entExtraAllow: d.extra_allowance,
                entExtraPay: d.extra_pay,
                entStamp: d.stamp,
                entAtndBonus: d.attendance_bonus_month,
                entFestBonus: d.fest_bonus,
                entArrear: d.arr_amnt,
                entProdBonus: d.production_bonus,
                entTotalEarn: d.total_salary,
                entPF: d.pf_deduction,
                entLoanA: d.advance_loan_deduction_a,
                entLoanB: d.advance_loan_deduction_b,
                entLoanC: d.advance_loan_deduction_c,
                entLoanD: d.advance_loan_deduction_d,
                entOtherDeduct: d.other_deduction,
                entLunch: d.lunch_deduction,
                entLate: d.late_deduct,
                entAbsent: d.absent_amount,
                entTax: d.tax_deduction,
                entTotalDeduct: d.total_deduction,
                entNet: d.net_payable,
                entOtEnt: d.ot_ent,
                entOtHour: d.ot_hour,
                entOtRate: d.ot_rate,
                entOtAmount: d.ot_amount,
                entOtRate1: d.ot_rate1,
                entPieceRateEnt: d.piece_rate_ent,
                entPieceTotal: d.piece_rate_total,
                entPieceAdv: d.piece_rate_advance,
                entPresent: d.present_count,
                entAbsentCnt: d.absent_count,
                entLeave: d.leave_count,
                entSL: d.sl_count,
                entWeekday: d.weekday_count,
                entHoliday: d.holiday_count,
                entTotalDays: d.total_days,
                entWorkDay: d.work_day,
                entLateCount: d.late_count,
                entLeaveAmt: d.leave_amount,
                entAdvBalance: d.advance_balance,
                entBankAcc: d.bank_account_number
            };
            Object.entries(m).forEach(([id, v]) => sv(id, v));
            calcTotals();
        }

        async function calcFromGross() {
            const gross = parseFloat(document.getElementById('entGross').value) || 0;
            const cid = document.getElementById('ctlCompany').value;
            if (!gross) return;
            if (!salParams.hr_per && cid) {
                const r = await fetch(`${API.params}?company_id=${cid}`);
                const d = await r.json();
                salParams = d.data ?? {};
            }
            const mr = parseFloat(salParams.mr_amt) || 0,
                conv = parseFloat(salParams.convance_amt) || 0,
                food = parseFloat(salParams.food_amt) || 0,
                hr = parseFloat(salParams.hr_per) || 0,
                stmp = parseFloat(salParams.stamp_amt) || 0;
            const basic = hr > 0 ? Math.round((gross - mr - conv - food) / hr) : 0;
            const hrAmt = (gross - basic) - (mr + conv + food);
            sv('entBasic', basic.toFixed(2));
            sv('entHouseRent', hrAmt.toFixed(2));
            sv('entMedical', mr.toFixed(2));
            sv('entConvance', conv.toFixed(2));
            sv('entFood', food.toFixed(2));
            sv('entStamp', stmp.toFixed(2));
            calcTotals();
        }

        function calcOT() {
            sv('entOtAmount', (gn('entOtHour') * gn('entOtRate')).toFixed(2));
            calcTotals();
        }

        function calcTotals() {
            const earn = gn('entBasic') + gn('entHouseRent') + gn('entMedical') + gn('entConvance') + gn('entFood') + gn(
                    'entEntertain') + gn('entExtraAllow') + gn('entExtraPay') + gn('entAtndBonus') + gn('entFestBonus') +
                gn('entArrear') + gn('entProdBonus') + gn('entOtAmount') + gn('entPieceTotal');
            const deduct = gn('entPF') + gn('entLoanA') + gn('entLoanB') + gn('entLoanC') + gn('entLoanD') + gn(
                'entOtherDeduct') + gn('entLunch') + gn('entLate') + gn('entAbsent') + gn('entTax') + gn('entStamp');
            const net = earn - deduct;
            sv('entTotalEarn', earn.toFixed(2));
            sv('entTotalDeduct', deduct.toFixed(2));
            sv('entNet', net.toFixed(2));
            document.getElementById('liveEarn').textContent = fmt(earn);
            document.getElementById('liveDeduct').textContent = fmt(deduct);
            document.getElementById('liveNet').textContent = fmt(net);
        }

        async function saveEntry() {
            const empNo = document.getElementById('entEmpNo').value.trim();
            const cid = document.getElementById('ctlCompany').value;
            const sd = document.getElementById('entSalaryDate').value;
            if (!empNo) return toast('warning', 'Enter Employee No');
            if (!cid) return toast('warning', 'Select Company');
            if (!sd) return toast('warning', 'Enter Salary Date');
            const payload = {
                company_id: '' + cid,
                empno: empNo,
                salary_date: sd,
                gross: gn('entGross'),
                basic_pay: gn('entBasic'),
                house_rent: gn('entHouseRent'),
                medical: gn('entMedical'),
                convence: gn('entConvance'),
                food_allownce: gn('entFood'),
                entertinment: gn('entEntertain'),
                extra_allowance: gn('entExtraAllow'),
                extra_pay: gn('entExtraPay'),
                stamp: gn('entStamp'),
                attendance_bonus_month: gn('entAtndBonus'),
                fest_bonus: gn('entFestBonus'),
                arr_amnt: gn('entArrear'),
                production_bonus: gn('entProdBonus'),
                total_salary: gn('entTotalEarn'),
                pf_deduction: gn('entPF'),
                advance_loan_deduction_a: gn('entLoanA'),
                advance_loan_deduction_b: gn('entLoanB'),
                advance_loan_deduction_c: gn('entLoanC'),
                advance_loan_deduction_d: gn('entLoanD'),
                other_deduction: gn('entOtherDeduct'),
                lunch_deduction: gn('entLunch'),
                late_deduct: gn('entLate'),
                absent_amount: gn('entAbsent'),
                tax_deduction: gn('entTax'),
                total_deduction: gn('entTotalDeduct'),
                net_payable: gn('entNet'),
                ot_ent: gs('entOtEnt'),
                ot_hour: gn('entOtHour'),
                ot_rate: gn('entOtRate'),
                ot_amount: gn('entOtAmount'),
                ot_rate1: gn('entOtRate1'),
                piece_rate_ent: gs('entPieceRateEnt'),
                piece_rate_total: gn('entPieceTotal'),
                piece_rate_advance: gn('entPieceAdv'),
                present_count: gn('entPresent'),
                absent_count: gn('entAbsentCnt'),
                leave_count: gn('entLeave'),
                sl_count: gn('entSL'),
                weekday_count: gn('entWeekday'),
                holiday_count: gn('entHoliday'),
                total_days: gn('entTotalDays'),
                work_day: gn('entWorkDay'),
                late_count: gn('entLateCount'),
                leave_amount: gn('entLeaveAmt'),
                bank_account_number: gs('entBankAcc')
            };
            try {
                const isEdit = (mode === 'edit');
                const r = await fetch(isEdit ? `${API.entry}/${empNo}` : API.entry, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload)
                });
                const ct = r.headers.get('content-type') ?? '';
                if (!ct.includes('application/json')) throw new Error(`Server ${r.status}`);
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Save failed');
                setMode('view');
                loadEntries();
                toast('success', isEdit ? 'Entry updated' : 'Entry saved');
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        }

        async function deleteEntry() {
            const empNo = document.getElementById('entEmpNo').value.trim();
            const cid = document.getElementById('ctlCompany').value;
            const sd = document.getElementById('entSalaryDate').value;
            if (!empNo) return;
            const res = await Swal.fire({
                title: 'Delete this salary entry?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                confirmButtonText: 'Delete'
            });
            if (!res.isConfirmed) return;
            try {
                const r = await fetch(`${API.entry}/${empNo}?company_id=${cid}&salary_date=${sd}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message);
                setMode('view');
                loadEntries();
                toast('success', 'Entry deleted');
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        }

        // DATE MODES
        function setDateMode(m) {
            dateMode = m;
            document.querySelectorAll('.dt-opt').forEach(el => el.classList.toggle('active', el.dataset.mode === m));
            document.getElementById('fullMonthRow').style.display = m === 'full' ? 'block' : 'none';
            document.getElementById('customRow').style.display = m === 'custom' ? 'block' : 'none';
            document.getElementById('fixed10Row').style.display = (m === 'first10' || m === 'last10') ? 'block' : 'none';
            if (m === 'full') updateFullMonthResolved();
            if (m === 'first10' || m === 'last10') resolve10Days();
        }

        function updateFullMonthResolved() {
            const m = document.getElementById('procMonth').value;
            const y = document.getElementById('procYear').value;
            if (!m || !y) return;
            document.getElementById('fullMonthResolved').value = `01-${mn(m)}-${y}  →  ${lastDay(m,y)}-${mn(m)}-${y}`;
        }

        function resolveCustomDates() {
            const f = document.getElementById('procDateFrom').value;
            const t = document.getElementById('procDateTo').value;
            document.getElementById('customResolved').value = f && t ? `${f}  →  ${t}` : '—';
        }

        function resolve10Days() {
            const m = document.getElementById('proc10Month').value;
            const y = document.getElementById('proc10Year').value;
            if (!m || !y) return;
            const last = lastDay(m, y);
            const d1 = dateMode === 'first10' ? `01-${mn(m)}-${y}` : `${last-9}-${mn(m)}-${y}`;
            const d2 = dateMode === 'first10' ? `10-${mn(m)}-${y}` : `${last}-${mn(m)}-${y}`;
            document.getElementById('fixed10Resolved').value = `${d1}  →  ${d2}`;
        }
        ['procMonth', 'procYear'].forEach(id => document.getElementById(id)?.addEventListener('change',
            updateFullMonthResolved));

        function mn(m) {
            return ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][+m];
        }

        function lastDay(m, y) {
            return new Date(+y, +m, 0).getDate();
        }

        function getProcessDates() {
            const months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ];
            if (dateMode === 'full') {
                const m = document.getElementById('procMonth').value;
                const y = document.getElementById('procYear').value;
                if (!m || !y) {
                    toast('warning', 'Select month and year');
                    return null;
                }
                return {
                    date_from: `01-${months[+m]}-${y}`,
                    date_to: `${lastDay(m,y)}-${months[+m]}-${y}`,
                    label: `${months[+m]} ${y}`
                };
            } else if (dateMode === 'custom') {
                const f = document.getElementById('procDateFrom').value;
                const t = document.getElementById('procDateTo').value;
                if (!f || !t) {
                    toast('warning', 'Enter From and To dates');
                    return null;
                }
                return {
                    date_from: f,
                    date_to: t,
                    label: `${f} → ${t}`
                };
            } else {
                const m = document.getElementById('proc10Month').value;
                const y = document.getElementById('proc10Year').value;
                if (!m || !y) {
                    toast('warning', 'Select month and year');
                    return null;
                }
                const mn2 = months[+m];
                const last = lastDay(m, y);
                const d1 = dateMode === 'first10' ? `01-${mn2}-${y}` : `${last-9}-${mn2}-${y}`;
                const d2 = dateMode === 'first10' ? `10-${mn2}-${y}` : `${last}-${mn2}-${y}`;
                return {
                    date_from: d1,
                    date_to: d2,
                    label: `${d1} → ${d2}`
                };
            }
        }

        // RUN PROCESS
        document.getElementById('btnRunProcess').onclick = async () => {
            const cid = document.getElementById('procCompany').value;
            const etype = document.getElementById('procEmpType').value;
            const log = document.getElementById('procLog');
            const btn = document.getElementById('btnRunProcess');
            if (!cid) return toast('warning', 'Select a company');
            const dates = getProcessDates();
            if (!dates) return;
            const res = await Swal.fire({
                title: 'Run Salary Process?',
                html: `Company: <b>${cid}</b><br>Period: <b>${dates.label}</b>${etype?`<br>Emp Type: <b>${etype}</b>`:''}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e7e34',
                confirmButtonText: 'Yes, Process'
            });
            if (!res.isConfirmed) return;
            log.style.display = 'block';
            log.innerHTML = '';
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split spin"></i> Processing…';
            const addLog = (msg, cls = 'li') => {
                log.innerHTML += `<div class="${cls}">[${new Date().toLocaleTimeString()}] ${msg}</div>`;
                log.scrollTop = log.scrollHeight;
            };
            addLog('SALARY PROCESS');
            addLog(`Company: ${cid}  |  Period: ${dates.label}`);
            if (etype) addLog(`Emp Type: ${etype}`);
            try {
                const r = await fetch(API.process, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        company_id: cid,
                        date_from: dates.date_from,
                        date_to: dates.date_to,
                        emp_type: etype
                    })
                });
                const ct = r.headers.get('content-type') ?? '';
                if (!ct.includes('application/json')) throw new Error(`Server ${r.status}`);
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Process failed');
                addLog(`✔ ${d.message}`, 'lok');
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Completed';
                toast('success', 'Salary processed successfully');
                setTimeout(() => loadEntries(), 800);
            } catch (e) {
                addLog(`✘ ${e.message}`, 'lerr');
                Swal.fire('Error', e.message, 'error');
                btn.innerHTML = '<i class="bi bi-play-fill"></i> Run Salary Process';
            }
            btn.disabled = false;
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-play-fill"></i> Run Salary Process';
            }, 4000);
        };

        // DELETE SALARY
        async function loadDelDates() {
            const cid = document.getElementById('delCompany').value;
            if (!cid) return toast('warning', 'Select company first');
            try {
                const r = await fetch(`${API.delDates}?company_id=${cid}`);
                const d = await r.json();
                const sel = document.getElementById('delDate');
                sel.innerHTML = '<option value="">— Select Period —</option>';
                (d.data ?? []).forEach(row => {
                    const o = document.createElement('option');
                    o.value = row.payment_date;
                    o.textContent = row.payment_date_display ?? row.payment_date;
                    sel.appendChild(o);
                });
                toast('success', `${d.data?.length??0} period(s) loaded`);
            } catch (e) {
                toast('error', e.message);
            }
        }
        async function checkDelCount() {
            const cid = document.getElementById('delCompany').value;
            const dt = document.getElementById('delDate').value;
            if (!cid || !dt) return toast('warning', 'Select company and period');
            try {
                const r = await fetch(`${API.delCount}?company_id=${cid}&payment_date=${encodeURIComponent(dt)}`);
                const d = await r.json();
                document.getElementById('delCountInfo').style.display = 'block';
                document.getElementById('delCountTxt').textContent =
                    `${d.count??0} salary record(s) found for this period.`;
            } catch (e) {
                toast('error', e.message);
            }
        }
        document.getElementById('btnConfirmDelete').onclick = async () => {
            const cid = document.getElementById('delCompany').value;
            const dt = document.getElementById('delDate').value;
            if (!cid || !dt) return toast('warning', 'Select company and period');
            const res = await Swal.fire({
                title: 'Delete Salary Records?',
                html: `Company: <b>${cid}</b><br>Payment Date: <b>${dt}</b>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                confirmButtonText: 'Yes, Delete'
            });
            if (!res.isConfirmed) return;
            try {
                const r = await fetch(API.deleteSal, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        company_id: cid,
                        payment_date: dt
                    })
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                loadEntries();
                toast('success', d.message);
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
        };

        // GROSS ENTRY
        async function runGrossEntry() {
            const cid = document.getElementById('ctlCompany').value;
            const sd = document.getElementById('ctlSalaryDate').value;
            if (!cid) return toast('warning', 'Select a company first');
            if (!sd) return toast('warning', 'Enter salary month date first');
            const res = await Swal.fire({
                title: 'Gross Entry',
                html: `Load employees with attendance in <b>${sd}</b><br>into <b>EMP_PAYMENT</b>?<br><small class="text-muted">Existing employees will not be duplicated.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Yes, Load'
            });
            if (!res.isConfirmed) return;
            const btn = document.getElementById('btnGrossEntry');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split spin"></i> Loading...';
            try {
                const r = await fetch(API.grossEntry, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        company_id: cid,
                        salary_date: sd
                    })
                });
                const ct = r.headers.get('content-type') ?? '';
                if (!ct.includes('application/json')) throw new Error('Server ' + r.status);
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Gross Entry failed');
                if (d.data) renderTable(d.data);
                toast('success', d.message);
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-people-fill"></i> Gross Entry';
        }

        // HELPERS
        function clearEntryForm() {
            document.querySelectorAll('#entryFormWrap input').forEach(el => el.value = '');
            document.querySelectorAll('#entryFormWrap select').forEach(el => el.selectedIndex = 0);
            ['liveEarn', 'liveDeduct', 'liveNet'].forEach(id => document.getElementById(id).textContent = '0.00');
        }

        function clearCtl() {
            document.getElementById('ctlEmpNo').value = '';
            loadEntries();
        }

        function sv(id, v) {
            const el = document.getElementById(id);
            if (el) el.value = v ?? '';
        }

        function gn(id) {
            return parseFloat(document.getElementById(id)?.value) || 0;
        }

        function gs(id) {
            return document.getElementById(id)?.value || null;
        }

        function fmt(v) {
            return Number(v || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

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
