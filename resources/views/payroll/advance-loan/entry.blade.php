{{-- resources/views/payroll/advance-loan/entry.blade.php --}}
@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --brand-primary: #1a3a5c;
            --brand-secondary: #1e6b8a;
            --brand-accent: #2e9ab7;
            --brand-light: #e8f4f8;
            --header-bg: #1a3a5c;
            --border-color: #c0d8e8;
            --row-odd: #f2f8fb;
            --row-even: #ffffff;
        }

        .loan-wrapper {
            background: #f4f7fb;
            min-height: 100vh;
            padding: 0;
        }

        /* ── TOOLBAR ── */
        .toolbar {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            padding: 8px 14px;
            background: linear-gradient(135deg, #1a3a5c 0%, #1e6b8a 100%);
            border-bottom: 2px solid #2e9ab7;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 3px 10px rgba(26, 58, 92, .35);
            align-items: center;
        }

        .toolbar-divider {
            width: 1px;
            height: 26px;
            background: rgba(255, 255, 255, .25);
            margin: 0 3px;
        }

        .btn-tool {
            font-size: .76rem;
            padding: 5px 12px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: .3px;
            border: 1px solid transparent;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-tool:hover {
            filter: brightness(1.15);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .4);
            color: #fff;
        }

        .btn-tool:active {
            transform: translateY(0);
            filter: brightness(.9);
        }

        /* ── NEW – solid fill + thick contrasting border + tinted icon ── */
        .btn-new {
            background: #1976d2 !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-new .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-save {
            background: #2e7d32 !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-save .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-edit {
            background: #e65100 !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-edit .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-delete {
            background: #c62828 !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-delete .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-schedule {
            background: #6a1b9a !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-schedule .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-out {
            background: #bf360c !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-out .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-print {
            background: #00695c !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-print .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        .btn-exit {
            background: #37474f !important;
            color: #fff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-exit .bi {
            color: #ffffff;
            font-size: .9rem;
        }

        /* Delete Due button */
        .btn-delete-due {
            background: #880e4f;
            border: 2px solid #f48fb1;
            color: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15);
        }

        .btn-delete-due .bi {
            color: #fce4ec;
            font-size: .9rem;
        }

        /* Search bar inline buttons */
        .search-bar .btn-save {
            background: #2e7d32;
            border: 2px solid #69f0ae;
            color: #fff !important;
        }

        .search-bar .btn-save .bi {
            color: #c8e6c9;
        }

        .search-bar .btn-schedule {
            background: #6a1b9a;
            border: 2px solid #ea80fc;
            color: #fff !important;
        }

        .search-bar .btn-schedule .bi {
            color: #e1bee7;
        }

        /* Mode indicator badge – prominent pill (matches screenshot) */
        .mode-badge {
            margin-left: auto;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: .74rem;
            font-weight: 800;
            letter-spacing: 1px;
            border: 2px solid rgba(255, 255, 255, .6);
            white-space: nowrap;
        }

        .mode-new {
            background: rgba(33, 150, 243, .35);
            color: #e3f2fd;
            border-color: rgba(100, 181, 246, .7);
        }

        .mode-view {
            background: rgba(255, 255, 255, .15);
            color: #ffffff;
            border-color: rgba(255, 255, 255, .7);
        }

        .mode-edit {
            background: rgba(255, 193, 7, .35);
            color: #fff8e1;
            border-color: rgba(255, 193, 7, .8);
        }

        /* ── SEARCH BAR ── */
        .search-bar {
            background: #e8f0f7;
            padding: 7px 14px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .search-bar .form-label {
            font-size: .75rem;
            font-weight: 700;
            color: #1a3a5c;
            margin: 0;
            white-space: nowrap;
        }

        .search-bar .form-control {
            font-size: .78rem;
            height: 30px;
            padding: 3px 8px;
            max-width: 180px;
        }

        .search-bar .btn {
            font-size: .76rem;
            padding: 4px 12px;
        }

        /* ── FORM BODY ── */
        .form-body {
            padding: 14px 16px;
        }

        /* ── CARD ── */
        .form-card {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(26, 58, 92, .10);
            background: #fff;
            margin-bottom: 14px;
        }

        .form-card-header {
            background: var(--header-bg);
            color: #fff;
            padding: 8px 14px;
            border-radius: 4px 4px 0 0;
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 600;
            font-size: .85rem;
            letter-spacing: .3px;
        }

        .form-card-body {
            padding: 14px 16px 10px;
        }

        /* ── SECTION DIVIDER ── */
        .section-divider {
            font-size: .70rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--brand-secondary);
            border-bottom: 2px solid var(--brand-light);
            padding-bottom: 3px;
            margin: 12px 0 8px;
        }

        .section-divider:first-child {
            margin-top: 0;
        }

        /* ── FORM CONTROLS ── */
        .form-label {
            font-size: .75rem;
            font-weight: 600;
            color: #344a60;
            margin-bottom: 2px;
        }

        .form-control,
        .form-select {
            font-size: .80rem;
            border: 1px solid #b8cfe0;
            border-radius: 3px;
            padding: 4px 8px;
            height: calc(1.6rem + 10px);
            color: #1a2f45;
            background-color: #fdfdfe;
            transition: border-color .15s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 3px rgba(46, 154, 183, .15);
        }

        .form-control[readonly] {
            background: #eef4f8;
            color: #445566;
        }

        .form-control.edit-active {
            background: #fffde7 !important;
            border-color: #ffc107 !important;
        }

        .input-group-text {
            background: var(--brand-light);
            border-color: #b8cfe0;
            color: var(--brand-secondary);
            font-size: .75rem;
            padding: 0 8px;
            cursor: pointer;
        }

        .input-group-text:hover {
            background: var(--brand-secondary);
            color: #fff;
        }

        /* ── PANELS (Out Payment / Reschedule) ── */
        .action-panel {
            border-radius: 6px;
            padding: 14px 16px 10px;
            margin: 10px 0;
            border: 1px solid;
            display: none;
        }

        #outPaymentPanel {
            background: #fff8f4;
            border-color: #ff5722;
        }

        #reschedulePanel {
            background: #f3e5f5;
            border-color: #9c27b0;
        }

        .panel-title {
            font-size: .80rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border-bottom: 2px solid;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #outPaymentPanel .panel-title {
            color: #bf360c;
            border-color: #ff5722;
        }

        #reschedulePanel .panel-title {
            color: #6a1b9a;
            border-color: #9c27b0;
        }

        /* Reschedule summary box */
        .reschedule-summary {
            background: #ede7f6;
            border: 1px solid #9c27b0;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: .78rem;
        }

        .reschedule-summary span {
            font-weight: 700;
            color: #4a148c;
            font-size: .85rem;
        }

        .btn-reschedule-go {
            background: linear-gradient(135deg, #9c27b0, #6a1b9a);
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: .82rem;
            letter-spacing: .5px;
            width: 100%;
            box-shadow: 0 3px 8px rgba(106, 27, 154, .4);
            transition: all .2s;
        }

        .btn-reschedule-go:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        /* ── INSTALL TABLE ── */
        .install-table thead th {
            background: var(--brand-primary);
            color: #fff;
            font-size: .72rem;
            font-weight: 600;
            padding: 6px 8px;
            border: none;
            white-space: nowrap;
        }

        .install-table tbody tr:nth-child(odd) {
            background: var(--row-odd);
        }

        .install-table tbody tr:nth-child(even) {
            background: var(--row-even);
        }

        .install-table tbody tr.row-paid {
            background: #e8f5e9 !important;
        }

        .install-table tbody td {
            font-size: .76rem;
            padding: 4px 8px;
            vertical-align: middle;
            border-color: #d8eaf3;
        }

        .install-table tfoot td {
            font-weight: 700;
            font-size: .78rem;
            background: #e0eef6;
            border-top: 2px solid var(--brand-secondary);
            padding: 5px 8px;
        }

        .badge-due {
            background: #f44336;
            color: #fff;
            font-size: .68rem;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .badge-paid {
            background: #4caf50;
            color: #fff;
            font-size: .68rem;
            padding: 2px 7px;
            border-radius: 10px;
        }

        /* ── EMPLOYEE PICKER MODAL ── */
        #empPickerModal .modal-header {
            background: var(--brand-primary);
            color: #fff;
            padding: 8px 14px;
        }

        #empPickerModal .modal-body {
            padding: 10px;
        }

        .emp-search-row {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        .emp-search-row .form-control {
            flex: 1;
        }

        .emp-row:hover {
            background: #e3f2fd !important;
            cursor: pointer;
        }

        /* ── LOAN SEARCH LOV MODAL ── */
        #loanSearchModal .modal-header {
            background: #37474f;
            color: #fff;
            padding: 8px 14px;
        }

        @media(max-width:768px) {
            .toolbar {
                gap: 3px;
            }

            .btn-tool {
                font-size: .68rem;
                padding: 4px 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="loan-wrapper">

        {{-- ═══════════ TOOLBAR ═loan-wrapper══════════════════════════════════════════ --}}
        <div class="toolbar">
            <button type="button" id="btnNew" class="btn btn-tool btn-new">
                <i class="bi bi-file-earmark-plus"></i> New
            </button>
            <button type="button" id="btnSave" class="btn btn-tool btn-save">
                <i class="bi bi-save2"></i> Save
            </button>
            <button type="button" id="btnEdit" class="btn btn-tool btn-edit">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
            <div class="toolbar-divider"></div>
            <button type="button" id="btnDelete" class="btn btn-tool btn-delete">
                <i class="bi bi-trash3"></i> Delete
            </button>
            <button type="button" id="btnDeleteDue" class="btn btn-tool btn-delete-due"
                title="Delete only Due installments">
                <i class="bi bi-calendar-x"></i> Delete Due
            </button>
            <div class="toolbar-divider"></div>
            <button type="button" id="btnSchedule" class="btn btn-tool btn-schedule">
                <i class="bi bi-calendar-week" id="scheduleIcon"></i>
                <span id="scheduleLabel">Generate Schedule</span>
            </button>
            <button type="button" id="btnOutPayment" class="btn btn-tool btn-out">
                <i class="bi bi-cash-coin"></i> Out Payment
            </button>
            <div class="toolbar-divider"></div>
            <button type="button" id="btnPrint" class="btn btn-tool btn-print">
                <i class="bi bi-printer"></i> Print
            </button>
            <button type="button" id="btnExit" class="btn btn-tool btn-exit">
                <i class="bi bi-box-arrow-left"></i> Exit
            </button>
            <span id="modeBadge" class="mode-badge mode-new">● NEW</span>
        </div>

        {{-- ═══════════ SEARCH BAR (LOV style) ════════════════════════════ --}}
        <div class="search-bar">
            <label class="form-label">Company:</label>
            <select id="searchCompany" class="form-select"
                style="width:160px;height:30px;font-size:.78rem;padding:2px 6px;">
                <option value="">— All —</option>
                @foreach ($companies ?? [] as $c)
                    <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                @endforeach
            </select>
            <label class="form-label">Loan No:</label>
            <input type="text" id="searchLoanNo" class="form-control" placeholder="F-100/0001" style="max-width:140px"
                onkeydown="if(event.key==='Enter') doSearch()">
            <label class="form-label">Emp No:</label>
            <input type="text" id="searchEmpNo" class="form-control" placeholder="Emp No" style="max-width:110px"
                onkeydown="if(event.key==='Enter') doSearch()">
            <button type="button" class="btn btn-tool btn-save" onclick="doSearch()" style="padding:4px 12px;">
                <i class="bi bi-search"></i> Search
            </button>
            <button type="button" class="btn btn-tool btn-schedule" onclick="openLoanLov()" style="padding:4px 12px;">
                <i class="bi bi-list-ul"></i> Browse
            </button>
        </div>

        <div class="form-body">
            <form id="loanForm" autocomplete="off">
                @csrf

                {{-- ═══════════ MASTER CARD ════════════════════════════════════════ --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-person-vcard"></i> Advance / Loan Entry
                        <span id="loanStatusBadge" class="ms-auto" style="font-size:.72rem;opacity:.85;"></span>
                    </div>
                    <div class="form-card-body">

                        {{-- Application Info --}}
                        <div class="section-divider">Application Information</div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select name="company_id" id="companyId" class="form-select" required>
                                    <option value="">— Select —</option>
                                    @foreach ($companies ?? [] as $c)
                                        <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Loan App No</label>
                                <input type="text" name="loan_app_no" id="loanAppNo" class="form-control" readonly
                                    placeholder="Auto">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Application Date <span class="text-danger">*</span></label>
                                <input type="text" name="application_date" id="applicationDate"
                                    class="form-control flatpickr-date" placeholder="dd-Mon-yyyy" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Approved Date</label>
                                <input type="text" name="loan_approved_date" id="loanApprovedDate"
                                    class="form-control flatpickr-date" placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loan Type</label>
                                <input type="text" name="loan_type" id="loanType" class="form-control"
                                    placeholder="Advance / Loan / Festival">
                            </div>
                        </div>

                        {{-- Employee Info --}}
                        <div class="section-divider mt-2">Employee Information</div>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label">Emp No <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="emp_no" id="empNo" class="form-control" required
                                        onkeydown="if(event.key==='Enter'){event.preventDefault();openEmpPickerByKey();}">
                                    <span class="input-group-text" id="btnPickEmp" title="Select Employee">
                                        <i class="bi bi-person-lines-fill" style="font-size:.8rem"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">New Emp No</label>
                                <input type="text" name="new_empno" id="newEmpno" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Employee Name</label>
                                <input type="text" name="employe_name" id="employeName" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Designation</label>
                                <input type="text" name="des_name" id="desName" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-3">
                                <label class="form-label">Department</label>
                                <input type="text" id="deptName" class="form-control" readonly>
                                <input type="hidden" name="dept_no" id="deptNo">
                                <input type="hidden" name="dept_name" id="deptNameHidden">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Section</label>
                                <input type="text" id="secNameDisplay" class="form-control" readonly>
                                <input type="hidden" name="section_no" id="sectionNo">
                                <input type="hidden" name="sec_name" id="secName">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Joining Date</label>
                                <input type="text" name="joining_date" id="joiningDate" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Gross Salary</label>
                                <input type="number" name="gross_amount" id="grossAmount" class="form-control" readonly
                                    step="0.01">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Pre Balance</label>
                                <input type="number" name="pre_balance_amount" id="preBalanceAmount"
                                    class="form-control" readonly step="0.01">
                            </div>
                        </div>

                        {{-- Previous Sanction --}}
                        <div class="row g-2 mt-1">
                            <div class="col-md-3">
                                <label class="form-label">Previous Sanction Amt</label>
                                <input type="number" name="previous_sanction_amount" id="previousSanctionAmount"
                                    class="form-control" readonly step="0.01">
                            </div>
                        </div>

                        {{-- Financials --}}
                        <div class="section-divider mt-2">Loan Financials</div>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label">Sanction Amount <span class="text-danger">*</span></label>
                                <input type="number" name="sanction_amount" id="sanctionAmount" class="form-control"
                                    step="0.01" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Period (months) <span class="text-danger">*</span></label>
                                <input type="number" name="period" id="period" class="form-control" min="1"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Monthly Installment</label>
                                <input type="number" name="monthly_installment" id="monthlyInstallment"
                                    class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">First Install Date</label>
                                <input type="text" name="first_install_date" id="firstInstallDate"
                                    class="form-control flatpickr-date" placeholder="dd-Mon-yyyy">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Is Close</label>
                                <select name="is_close" id="isClose" class="form-select">
                                    <option value="N">Active</option>
                                    <option value="Y">Closed</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ═══════════ OUT PAYMENT PANEL ════════════════════════════════ --}}
                <div id="outPaymentPanel" class="action-panel">
                    <div class="panel-title">
                        <i class="bi bi-cash-stack"></i> Out Payment
                        <button type="button" class="btn-close btn-sm ms-auto" onclick="togglePanels(false,false)"
                            style="filter:invert(.5)"></button>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">From Install No <span class="text-danger">*</span></label>
                            <input type="number" id="outFrom" class="form-control" min="1"
                                placeholder="From">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Install No <span class="text-danger">*</span></label>
                            <input type="number" id="outTo" class="form-control" min="1" placeholder="To">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Total Amount</label>
                            <input type="number" id="outAmt" class="form-control" readonly step="0.01">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pay Date <span class="text-danger">*</span></label>
                            <input type="text" id="outPayDate" class="form-control flatpickr-date"
                                placeholder="dd-Mon-yyyy">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Voucher No</label>
                            <input type="text" id="outPayVoucher" class="form-control" placeholder="Optional">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="btnProcessOut" class="btn btn-tool btn-out w-100"
                                style="height:calc(1.6rem + 10px);">
                                <i class="bi bi-check2-all"></i> Process Payment
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══════════ RESCHEDULE PANEL ════════════════════════════════= --}}
                <div id="reschedulePanel" class="action-panel">
                    <div class="panel-title">
                        <i class="bi bi-arrow-repeat"></i> Reschedule Loan
                        <button type="button" class="btn-close btn-sm ms-auto" onclick="togglePanels(false,false)"
                            style="filter:invert(.5)"></button>
                    </div>
                    {{-- Summary box --}}
                    <div class="reschedule-summary" id="rescheduleSummary">
                        Remaining Balance: <span id="rescheduleBalance">—</span> &nbsp;|&nbsp;
                        Paid Installments: <span id="reschedulePaidCount">—</span> &nbsp;|&nbsp;
                        New Monthly Install: <span id="newMonthlyInstall">—</span>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">New Period (months) <span class="text-danger">*</span></label>
                            <input type="number" id="newPeriod" class="form-control" min="1"
                                placeholder="e.g. 12">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">New First Install Date <span class="text-danger">*</span></label>
                            <input type="text" id="newInsttDate" class="form-control flatpickr-date"
                                placeholder="dd-Mon-yyyy">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">New Monthly Install (preview)</label>
                            <input type="number" id="newMonthlyInstallInput" class="form-control" readonly
                                step="0.01">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btnProcessReschedule" class="btn-reschedule-go">
                                <i class="bi bi-arrow-repeat me-1"></i> Re-Generate Schedule
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══════════ INSTALLMENT TABLE ═════════════════════════════════ --}}
                <div class="form-card mt-2">
                    <div class="form-card-header">
                        <i class="bi bi-table"></i> Installment Schedule
                        <span id="installSummary" class="ms-auto" style="font-size:.72rem;opacity:.85;"></span>
                    </div>
                    <div class="form-card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 install-table" id="installTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Install No</th>
                                        <th>Install Date</th>
                                        <th class="text-end">Install Amount</th>
                                        <th class="text-end">Bal. BOM</th>
                                        <th class="text-end">Bal. EOM</th>
                                        <th class="text-center">Status</th>
                                        <th>Pay Date</th>
                                        <th>Voucher</th>
                                    </tr>
                                </thead>
                                <tbody id="installBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3" style="font-size:.80rem">
                                            <i class="bi bi-info-circle me-1"></i> Save loan then Generate Schedule.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end" id="ftTotal">0.00</td>
                                        <td colspan="5"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
        </div>{{-- /form-body --}}

        {{-- ═══════════ EMPLOYEE PICKER MODAL ════════════════════════════ --}}
        <div class="modal fade" id="empPickerModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--brand-primary);color:#fff;padding:8px 14px;">
                        <h6 class="modal-title mb-0"><i class="bi bi-people-fill me-2"></i><span
                                id="empModalTitle">Select Employee</span></h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-2">
                        <div class="emp-search-row">
                            <input type="text" id="empSearchInput" class="form-control"
                                placeholder="Search by name, emp no…" style="max-width:340px">
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="loadEmpList(document.getElementById('empSearchInput').value)">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <span id="empResultCount" class="ms-2 text-muted"
                                style="font-size:.75rem;align-self:center;"></span>
                        </div>
                        <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                            <table class="table table-sm table-hover install-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Emp No</th>
                                        <th>New No</th>
                                        <th>Employee Name</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Section</th>
                                        <th>Joining Date</th>
                                        <th class="text-end">Gross</th>
                                    </tr>
                                </thead>
                                <tbody id="empPickerBody">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">Select company first…
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ LOAN SEARCH LOV MODAL ═════════════════════════════ --}}
        <div class="modal fade" id="loanSearchModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header" style="background:#37474f;color:#fff;padding:8px 14px;">
                        <h6 class="modal-title mb-0"><i class="bi bi-list-ul me-2"></i>Loan Records</h6>
                        <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-2">
                        <div class="emp-search-row mb-2">
                            <input type="text" id="lovLoanSearch" class="form-control"
                                placeholder="Search loan no, emp no, name…" style="max-width:300px"
                                oninput="filterLovTable(this.value)">
                            <span id="lovCount" class="ms-2 text-muted"
                                style="font-size:.75rem;align-self:center;"></span>
                        </div>
                        <div class="table-responsive" style="max-height:420px;overflow-y:auto;">
                            <table class="table table-sm table-hover install-table mb-0" id="lovTable">
                                <thead>
                                    <tr>
                                        <th>Loan App No</th>
                                        <th>Emp No</th>
                                        <th>Employee Name</th>
                                        <th>Dept</th>
                                        <th>Sanction Amt</th>
                                        <th>Period</th>
                                        <th>App Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="lovBody">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Loading…</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /loan-wrapper --}}
@endsection

@push('scripts')
    <script>
        /* ═══════════════════════════════════════════════════════════════════
                                                                                                                                                                                                                                                                                                                   ADVANCE LOAN JS  –  Full Controller
                                                                                                                                                                                                                                                                                                                ═══════════════════════════════════════════════════════════════════ */

        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const API = {
            employees: '/api/payroll/employees',
            loanSave: '/api/payroll/advance-loan',
            loanGet: '/api/payroll/advance-loan',
            loanDelete: '/api/payroll/advance-loan',
            schedule: '/api/payroll/advance-loan/schedule',
            outPayment: '/api/payroll/advance-loan/out-payment',
            reschedule: '/api/payroll/advance-loan/reschedule',
            outAmount: '/api/payroll/advance-loan/out-amount',
            prevBalance: '/api/payroll/advance-loan/previous-balance',
            print: '/payroll/advance-loan/print/',
        };

        /* ── Flatpickr ─────────────────────────────────────────────────── */
        /* Flatpickr: auto-format typed numeric dates (110996, 11092026 etc.) */
        function makeFpOpts(el) {
            return {
                dateFormat: 'd-M-Y',
                allowInput: true,
                // When user leaves the field, parse and reformat automatically
                onClose(selectedDates, dateStr, instance) {
                    const raw = el.value.trim();
                    if (!raw) return;
                    // If user typed a pure number → convert then set
                    if (/^\d{5,8}$/.test(raw)) {
                        const converted = parseOracleDate(raw);
                        if (converted && converted !== raw) {
                            instance.setDate(converted, true, 'd-M-Y');
                        }
                    }
                },
                // Also handle while typing: when length hits 6 or 8 digits auto-convert
                onReady(selectedDates, dateStr, instance) {
                    el.addEventListener('keyup', function() {
                        const v = this.value.trim().replace(/\D/g, '');
                        if (v.length === 6 || v.length === 8) {
                            const converted = parseOracleDate(v);
                            if (converted && converted !== v) {
                                // Small delay so user sees what they typed first
                                setTimeout(() => {
                                    instance.setDate(converted, true, 'd-M-Y');
                                    // Move cursor to end
                                    const inp = instance.altInput || instance.input;
                                    inp.setSelectionRange(inp.value.length, inp.value.length);
                                }, 120);
                            }
                        }
                    });
                }
            };
        }
        // Initialize Flatpickr
        document.querySelectorAll('.flatpickr-date').forEach(el => {
            flatpickr(el, makeFpOpts(el));
        });

        /**
         * Convert Oracle numeric date:
         * 110196   -> 11-Jan-1996
         * 110126   -> 11-Jan-2026
         * 11011996 -> 11-Jan-1996
         * 11012026 -> 11-Jan-2026
         */
        function parseOracleDate(v) {

            if (!v) return '';

            const s = String(v).replace(/\D/g, '');

            let day, month, year;

            if (s.length === 6) {

                day = s.substring(0, 2);
                month = s.substring(2, 4);

                let yy = parseInt(s.substring(4, 6), 10);

                // Adjust cutoff year as needed
                year = yy <= 30 ? 2000 + yy : 1900 + yy;

            } else if (s.length === 8) {

                day = s.substring(0, 2);
                month = s.substring(2, 4);
                year = parseInt(s.substring(4, 8), 10);

            } else {
                return v;
            }

            const d = parseInt(day, 10);
            const m = parseInt(month, 10);

            if (
                isNaN(d) || isNaN(m) || isNaN(year) ||
                d < 1 || d > 31 ||
                m < 1 || m > 12 ||
                year < 1900 || year > 2099
            ) {
                return v;
            }

            const months = [
                '',
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];

            return `${String(d).padStart(2, '0')}-${months[m]}-${year}`;
        }

        /**
         * Smart date formatter
         * Handles:
         * - Oracle numeric dates
         * - ISO dates
         * - JS Date strings
         */
        function smartDate(v) {

            if (!v) return '';

            const value = String(v).trim();

            // Oracle numeric date
            if (/^\d{6}$/.test(value) || /^\d{8}$/.test(value)) {
                return parseOracleDate(value);
            }

            // ISO / normal date
            const dt = new Date(value);

            if (!isNaN(dt.getTime())) {
                const day = String(dt.getDate()).padStart(2, '0');

                const month = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ][dt.getMonth()];

                const year = dt.getFullYear();

                return `${day}-${month}-${year}`;
            }

            return value;
        }
        /* Smart date formatter: handles Oracle numeric, ISO, and display strings */
        function smartDate(v) {
            if (!v) return '';
            if (/^\d{5,8}$/.test(String(v))) return parseOracleDate(v);
            // Already formatted (d-M-Y) or ISO – pass through flatpickr
            try {
                const dt = new Date(v);
                if (!isNaN(dt)) {
                    return dt.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }).replace(/ /g, '-');
                }
            } catch {}
            return v;
        }

        /* ── State ─────────────────────────────────────────────────────── */
        let mode = 'new'; // 'new' | 'view' | 'edit'
        let currentId = null;
        let pickerTarget = 'main';
        let lovAllRows = [];

        /* ═══════════════ TOOLBAR ═══════════════════════════════════════ */

        document.getElementById('btnNew').onclick = () => {
            mode = 'new';
            currentId = null;
            clearForm();
            togglePanels(false, false);
            setMode('new');
            document.getElementById('companyId').focus();
        };

        document.getElementById('btnSave').onclick = () => saveLoan();

        document.getElementById('btnEdit').onclick = () => {
            if (!currentId) return Swal.fire('Info', 'Search and load a record first.', 'info');
            mode = 'edit';
            setMode('edit');
            enableEditing(true);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Edit mode – make changes then click Save',
                showConfirmButton: false,
                timer: 2500
            });
        };

        document.getElementById('btnDelete').onclick = () => deleteLoan();
        document.getElementById('btnDeleteDue').onclick = () => deleteDueDetails();

        document.getElementById('btnSchedule').onclick = () => {
            if (!currentId) return Swal.fire('Info', 'Save loan first.', 'info');

            // Check if details (installments) already exist
            const rows = document.querySelectorAll('#installBody tr[data-install]');
            const hasDetails = rows.length > 0;

            if (!hasDetails) {
                // NO details → Generate Schedule directly
                generateSchedule();
            } else {
                // HAS details → Open Reschedule panel
                const rp = document.getElementById('reschedulePanel');
                const showing = rp.style.display === 'block';
                togglePanels(false, !showing);
                if (!showing) {
                    fillRescheduleInfo();
                    document.getElementById('newPeriod').focus();
                }
            }
        };

        document.getElementById('btnOutPayment').onclick = () => {
            if (!currentId) return Swal.fire('Info', 'Load a loan record first.', 'info');
            const op = document.getElementById('outPaymentPanel');
            const showing = op.style.display === 'block';
            togglePanels(!showing, false);
            if (!showing) {
                // Auto-fill From = first Due install_no
                const firstDue = [...document.querySelectorAll('#installBody tr')]
                    .find(tr => tr.cells[6]?.textContent.trim() === 'Due');
                if (firstDue) {
                    document.getElementById('outFrom').value = firstDue.cells[1].textContent.trim();
                    document.getElementById('outTo').value = firstDue.cells[1].textContent.trim();
                    fetchOutAmount();
                }
                document.getElementById('outFrom').focus();
            }
        };

        document.getElementById('btnPrint').onclick = () => {
            if (!currentId) return Swal.fire('Info', 'Load a loan record first.', 'info');
            window.open(API.print + currentId, '_blank');
        };

        document.getElementById('btnExit').onclick = () => {
            window.location.href = '/payroll/advance-loan';
        };
        document.getElementById('btnProcessOut').onclick = () => processOutPayment();
        document.getElementById('btnProcessReschedule').onclick = () => processReschedule();

        /* ═══════════════ MODE MANAGEMENT ══════════════════════════════ */

        function setMode(m) {
            mode = m;
            const badge = document.getElementById('modeBadge');
            badge.className = 'mode-badge mode-' + m;
            badge.textContent = m === 'new' ? '● NEW' : m === 'edit' ? '✎ EDIT' : '👁 VIEW';
            // Save / Edit button state
            document.getElementById('btnSave').style.opacity = (m === 'view') ? '.5' : '1';
            document.getElementById('btnEdit').style.opacity = (m === 'edit') ? '.5' : '1';
        }

        function enableEditing(on) {
            // Highlight editable fields when in edit mode
            const editables = ['applicationDate', 'loanApprovedDate', 'loanType',
                'sanctionAmount', 'period', 'firstInstallDate'
            ];
            editables.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.readOnly = !on;
                    on ? el.classList.add('edit-active') : el.classList.remove('edit-active');
                }
            });
        }

        /* ═══════════════ AUTO-CALC ═════════════════════════════════════ */

        // Calculation 1: Calculate Monthly Installment (Sanction Amount ÷ Period)
        ['sanctionAmount', 'period'].forEach(id => {
            document.getElementById(id).addEventListener('input', calcMonthlyInstallment);
        });

        function calcMonthlyInstallment() {
            const sa = parseFloat(document.getElementById('sanctionAmount').value) || 0;
            const p = parseFloat(document.getElementById('period').value) || 0;
            document.getElementById('monthlyInstallment').value = p > 0 ? (sa / p).toFixed(2) : '';
        }

        // Calculation 2: Calculate Period (Sanction Amount ÷ Monthly Installment)
        ['sanctionAmount', 'monthlyInstallment'].forEach(id => {
            document.getElementById(id).addEventListener('input', calcPeriod);
        });

        function calcPeriod() {
            const sa = parseFloat(document.getElementById('sanctionAmount').value) || 0;
            const mi = parseFloat(document.getElementById('monthlyInstallment').value) || 0;
            document.getElementById('period').value = mi > 0 ? (sa / mi).toFixed(0) : '';
        }

        /* ═══════════════ RESCHEDULE INFO ═══════════════════════════════ */

        function fillRescheduleInfo() {
            let lastPbeom = 0,
                paidCount = 0;
            document.querySelectorAll('#installBody tr').forEach(tr => {
                const status = tr.cells[6]?.textContent.trim();
                if (status === 'Paid') {
                    paidCount++;
                    const v = parseFloat(tr.cells[5]?.textContent.replace(/,/g, '')) || 0;
                    if (v !== 0) lastPbeom = v;
                }
            });
            if (lastPbeom === 0)
                lastPbeom = parseFloat(document.getElementById('sanctionAmount').value) || 0;

            document.getElementById('rescheduleBalance').textContent = fmtNum(lastPbeom);
            document.getElementById('reschedulePaidCount').textContent = paidCount;
            document.getElementById('newMonthlyInstall').textContent = '—';
            document.getElementById('newMonthlyInstallInput').value = '';
            document.getElementById('newPeriod').dataset.balance = lastPbeom;
        }

        document.getElementById('newPeriod').addEventListener('input', function() {
            const bal = parseFloat(this.dataset.balance) || 0;
            const p = parseFloat(this.value) || 0;
            const mi = p > 0 ? (bal / p).toFixed(2) : '—';
            document.getElementById('newMonthlyInstall').textContent = mi;
            document.getElementById('newMonthlyInstallInput').value = p > 0 ? mi : '';
        });

        /* ═══════════════ OUT AMOUNT FETCH ══════════════════════════════ */

        ['outFrom', 'outTo'].forEach(id =>
            document.getElementById(id).addEventListener('change', fetchOutAmount));

        async function fetchOutAmount() {
            const loanAppNo = document.getElementById('loanAppNo').value;
            const from = document.getElementById('outFrom').value;
            const to = document.getElementById('outTo').value;
            const cid = document.getElementById('companyId').value;
            if (!loanAppNo || !from || !to) return;
            try {
                const r = await fetch(
                    `${API.outAmount}?loan_app_no=${encodeURIComponent(loanAppNo)}&from=${from}&to=${to}&company_id=${cid}`
                );
                const d = await r.json();
                document.getElementById('outAmt').value = d.amount ?? '';
            } catch {}
        }

        /* ═══════════════ EMPLOYEE PICKER ════════════════════════════════ */

        document.getElementById('btnPickEmp').onclick = () => {
            pickerTarget = 'main';
            openEmpPicker();
        };

        function openEmpPickerByKey() {
            const v = document.getElementById('empNo').value.trim();
            pickerTarget = 'main';
            openEmpPicker(v);
        }

        function openEmpPicker(preSearch = '') {
            document.getElementById('empModalTitle').textContent =
                'Select Employee';
            const modal = new bootstrap.Modal(document.getElementById('empPickerModal'));
            modal.show();
            document.getElementById('empSearchInput').value = preSearch;
            loadEmpList(preSearch);
            document.getElementById('empSearchInput').oninput = (e) => loadEmpList(e.target.value);
            document.getElementById('empSearchInput').onkeydown = (e) => {
                if (e.key === 'Enter') loadEmpList(e.target.value);
            };
        }

        async function loadEmpList(q = '') {
            const tbody = document.getElementById('empPickerBody');
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-2">Loading…</td></tr>';
            const cid = document.getElementById('companyId').value;
            if (!cid) {
                tbody.innerHTML =
                    '<tr><td colspan="8" class="text-center text-warning py-2">Please select a Company first.</td></tr>';
                return;
            }
            try {
                const r = await fetch(`${API.employees}?company_id=${cid}&q=${encodeURIComponent(q)}`);
                const d = await r.json();
                const emps = d.data ?? d;
                document.getElementById('empResultCount').textContent = emps.length + ' record(s)';
                if (!emps.length) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="text-center text-muted py-2">No employees found.</td></tr>';
                    return;
                }
                tbody.innerHTML = emps.map(e => `
            <tr class="emp-row" onclick='selectEmp(${JSON.stringify(e)})'>
                <td>${e.emp_no ?? ''}</td>
                <td>${e.new_empno ?? ''}</td>
                <td><strong>${e.emp_name ?? ''}</strong></td>
                <td>${e.des_name ?? ''}</td>
                <td>${e.dept_name ?? ''}</td>
                <td>${e.section_name ?? ''}</td>
                <td>${e.joining_date ?? ''}</td>
                <td class="text-end">${fmtNum(e.gross ?? 0)}</td>
            </tr>`).join('');
            } catch (err) {
                tbody.innerHTML =
                    `<tr><td colspan="8" class="text-center text-danger py-2">Error: ${err.message}</td></tr>`;
            }
        }

        function selectEmp(e) {
            bootstrap.Modal.getInstance(document.getElementById('empPickerModal'))?.hide();
            if (pickerTarget === 'main') {
                setVal('empNo', e.emp_no);
                setVal('newEmpno', e.new_empno);
                setVal('employeName', e.emp_name);
                setVal('desName', e.des_name);
                // Dept
                setVal('deptNo', e.dept_no);
                setVal('deptNameHidden', e.dept_name ?? '');
                document.getElementById('deptName').value = e.dept_name ?? '';
                // Section – API returns 'section_name' key
                setVal('sectionNo', e.section_no);
                setVal('secName', e.section_name ?? e.sec_name ?? '');
                document.getElementById('secNameDisplay').value = e.section_name ?? e.sec_name ?? '';
                // Other
                setVal('joiningDate', smartDate(e.joining_date));
                setVal('grossAmount', e.gross ?? '');
                fetchPreviousLoan(e.emp_no);
                document.getElementById('applicationDate').focus();
            }
        }

        function formatDateDisplay(d) {
            if (!d) return '';
            try {
                return new Date(d).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }).replace(/ /g, '-');
            } catch {
                return d;
            }
        }

        async function fetchPreviousLoan(empNo) {
            const cid = document.getElementById('companyId').value;
            if (!empNo || !cid) return;
            try {
                const r = await fetch(`${API.prevBalance}?emp_no=${empNo}&company_id=${cid}`);
                const d = await r.json();
                setVal('previousSanctionAmount', d.previous_sanction_amount ?? '0.00');
                setVal('preBalanceAmount', d.pre_balance_amount ?? '0.00');
            } catch {}
        }

        /* ═══════════════ SEARCH ════════════════════════════════════════ */

        async function doSearch() {
            const cid = document.getElementById('searchCompany').value ||
                document.getElementById('companyId').value;
            const loanNo = document.getElementById('searchLoanNo').value.trim();
            const empNo = document.getElementById('searchEmpNo').value.trim();

            if (!cid) return Swal.fire('Warning', 'Select a company first.', 'warning');
            if (!loanNo && !empNo) return Swal.fire('Warning', 'Enter Loan No or Emp No.', 'warning');

            try {
                const params = new URLSearchParams({
                    company_id: cid
                });
                if (loanNo) params.set('loan_app_no', loanNo);
                if (empNo) params.set('emp_no', empNo);
                const r = await fetch(`${API.loanGet}?${params}`);
                const d = await r.json();
                if (!r.ok || d.error) return Swal.fire('Not Found', 'No matching loan record found.', 'info');
                fillForm(d);
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ LOAN LOV (Browse) ════════════════════════════ */

        async function openLoanLov() {
            const cid = document.getElementById('searchCompany').value ||
                document.getElementById('companyId').value;
            if (!cid) return Swal.fire('Warning', 'Select a company first.', 'warning');

            const modal = new bootstrap.Modal(document.getElementById('loanSearchModal'));
            modal.show();

            document.getElementById('lovBody').innerHTML =
                '<tr><td colspan="8" class="text-center text-muted py-2">Loading…</td></tr>';

            try {
                const r = await fetch(`${API.loanGet}?company_id=${cid}&list=1`);
                const d = await r.json();
                const loans = d.data ?? (Array.isArray(d) ? d : []);
                lovAllRows = loans;
                renderLovTable(loans);
                document.getElementById('lovCount').textContent = loans.length + ' record(s)';
            } catch (err) {
                document.getElementById('lovBody').innerHTML =
                    `<tr><td colspan="8" class="text-center text-danger">${err.message}</td></tr>`;
            }
        }

        function renderLovTable(rows) {
            const tbody = document.getElementById('lovBody');
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-2">No records found.</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `
        <tr class="emp-row" onclick='selectLovLoan("${r.loan_app_no}")'>
            <td><strong>${r.loan_app_no}</strong></td>
            <td>${r.emp_no ?? ''}</td>
            <td>${r.employe_name ?? ''}</td>
            <td>${r.dept_name ?? ''}</td>
            <td class="text-end">${fmtNum(r.sanction_amount ?? 0)}</td>
            <td class="text-center">${r.period ?? ''}</td>
            <td>${r.application_date ?? ''}</td>
            <td class="text-center">
                ${r.is_close === 'Y'
                    ? '<span class="badge-paid">Closed</span>'
                    : '<span class="badge-due">Active</span>'}
            </td>
        </tr>`).join('');
        }

        function filterLovTable(q) {
            const filtered = lovAllRows.filter(r =>
                (r.loan_app_no ?? '').toLowerCase().includes(q.toLowerCase()) ||
                (r.emp_no ?? '').toLowerCase().includes(q.toLowerCase()) ||
                (r.employe_name ?? '').toLowerCase().includes(q.toLowerCase())
            );
            renderLovTable(filtered);
            document.getElementById('lovCount').textContent = filtered.length + ' record(s)';
        }

        async function selectLovLoan(loanAppNo) {
            bootstrap.Modal.getInstance(document.getElementById('loanSearchModal'))?.hide();
            document.getElementById('searchLoanNo').value = loanAppNo;
            // Load the record
            const cid = document.getElementById('searchCompany').value ||
                document.getElementById('companyId').value;
            try {
                const r = await fetch(`${API.loanGet}?loan_app_no=${encodeURIComponent(loanAppNo)}&company_id=${cid}`);
                const d = await r.json();
                if (!r.ok || d.error) return Swal.fire('Error', 'Could not load record.', 'error');
                fillForm(d);
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ FILL FORM ════════════════════════════════════ */

        function fillForm(d) {
            currentId = d.loan_app_no;
            setMode('view');
            enableEditing(false);

            setVal('loanAppNo', d.loan_app_no);
            setVal('companyId', d.company_id);
            // Sync search company
            document.getElementById('searchCompany').value = d.company_id ?? '';

            setVal('applicationDate', smartDate(d.application_date));
            setVal('loanApprovedDate', smartDate(d.loan_approved_date));
            setVal('loanType', d.loan_type);
            setVal('empNo', d.emp_no);
            setVal('newEmpno', d.new_empno);
            setVal('employeName', d.employe_name);
            setVal('desName', d.des_name);
            setVal('deptNo', d.dept_no);
            setVal('deptNameHidden', d.dept_name);
            document.getElementById('deptName').value = d.dept_name ?? '';
            setVal('sectionNo', d.section_no);
            setVal('secName', d.sec_name ?? '');
            document.getElementById('secNameDisplay').value = d.sec_name ?? d.section_name ?? '';
            setVal('grossAmount', d.gross_amount);
            setVal('joiningDate', smartDate(d.joining_date));
            setVal('sanctionAmount', d.sanction_amount);
            setVal('period', d.period);
            setVal('monthlyInstallment', d.monthly_installment);
            setVal('firstInstallDate', smartDate(d.first_install_date));
            setVal('previousSanctionAmount', d.previous_sanction_amount);
            setVal('preBalanceAmount', d.pre_balance_amount);
            document.getElementById('isClose').value = d.is_close ?? 'N';
            // Style the select based on value
            const isCl = document.getElementById('isClose');
            isCl.style.color = (d.is_close === 'Y') ? '#c62828' : '#2e7d32';
            isCl.style.fontWeight = '600';

            // Loan status badge in header
            const sb = document.getElementById('loanStatusBadge');
            sb.textContent = d.is_close === 'Y' ? '🔒 CLOSED' : '✅ ACTIVE';
            sb.style.color = d.is_close === 'Y' ? '#f48fb1' : '#a5d6a7';

            renderInstallments(d.details ?? []);
            togglePanels(false, false);
            updateScheduleButton((d.details ?? []).length > 0);
            document.getElementById('loanAppNo').scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        /* ═══════════════ RENDER INSTALLMENTS ══════════════════════════ */

        function renderInstallments(rows) {
            const tbody = document.getElementById('installBody');
            let total = 0,
                paid = 0,
                due = 0;

            if (!rows.length) {
                tbody.innerHTML =
                    '<tr><td colspan="9" class="text-center text-muted py-3">No installments generated.</td></tr>';
                document.getElementById('ftTotal').textContent = '0.00';
                document.getElementById('installSummary').textContent = '';
                return;
            }

            tbody.innerHTML = rows.map((r, i) => {
                const amt = parseFloat(r.install_amount ?? 0);
                total += amt;
                const isPaid = r.status === 'Paid';
                if (isPaid) paid++;
                else due++;
                const badge = isPaid ?
                    `<span class="badge-paid">Paid</span>` :
                    `<span class="badge-due">Due</span>`;
                return `<tr class="${isPaid ? 'row-paid' : ''}" data-install="${r.install_no}">
            <td>${i+1}</td>
            <td>${r.install_no}</td>
            <td>${r.install_date ?? ''}</td>
            <td class="text-end">${fmtNum(amt)}</td>
            <td class="text-end">${fmtNum(r.pbbom ?? 0)}</td>
            <td class="text-end">${fmtNum(r.pbeom ?? 0)}</td>
            <td class="text-center">${badge}</td>
            <td>${r.paydate ?? ''}</td>
            <td>${r.is_voucher === 'Y' ? '<i class="bi bi-check-circle-fill text-success"></i>' : ''}</td>
        </tr>`;
            }).join('');

            document.getElementById('ftTotal').textContent = fmtNum(total);
            document.getElementById('installSummary').textContent =
                `Total: ${rows.length} | Paid: ${paid} | Due: ${due}`;

            // Smart button: if rows exist show Reschedule, else Generate Schedule
            updateScheduleButton(rows.length > 0);
        }

        /* ── Smart Schedule/Reschedule button updater ─────────────── */
        function updateScheduleButton(hasDetails) {
            const icon = document.getElementById('scheduleIcon');
            const label = document.getElementById('scheduleLabel');
            if (!icon || !label) return;
            if (hasDetails) {
                icon.className = 'bi bi-arrow-repeat';
                label.textContent = 'Reschedule';
            } else {
                icon.className = 'bi bi-calendar-week';
                label.textContent = 'Generate Schedule';
            }
        }

        /* ═══════════════ SAVE ══════════════════════════════════════════ */

        async function saveLoan() {
            const form = document.getElementById('loanForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const payload = collectPayload();

            const res = await Swal.fire({
                title: mode === 'new' ? '💾 Save New Loan?' : '✎ Update Loan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2e7d32',
                confirmButtonText: mode === 'new' ? 'Yes, Save' : 'Yes, Update',
            });
            if (!res.isConfirmed) return;

            try {
                const url = mode === 'new' ? API.loanSave : `${API.loanSave}/${currentId}`;
                const method = mode === 'new' ? 'POST' : 'PUT';
                const r = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Save failed');

                currentId = d.loan_app_no ?? currentId;
                setVal('loanAppNo', currentId);
                if (d.details) renderInstallments(d.details);
                setMode('view');
                enableEditing(false);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: mode === 'new' ? 'Loan saved!' : 'Loan updated!',
                    showConfirmButton: false,
                    timer: 2000
                });

            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        function collectPayload() {
            const g = id => document.getElementById(id)?.value ?? null;
            return {
                company_id: g('companyId'),
                emp_no: g('empNo'),
                new_empno: g('newEmpno'),
                des_name: g('desName'),
                dept_no: g('deptNo'),
                section_no: g('sectionNo'),
                gross_amount: g('grossAmount'),
                joining_date: g('joiningDate'),
                application_date: g('applicationDate'),
                loan_approved_date: g('loanApprovedDate'),
                loan_type: g('loanType'),
                sanction_amount: g('sanctionAmount'),
                period: g('period'),
                monthly_installment: g('monthlyInstallment'),
                first_install_date: g('firstInstallDate'),
                previous_sanction_amount: g('previousSanctionAmount'),
                pre_balance_amount: g('preBalanceAmount'),
                is_close: g('isClose'),
            };
        }

        /* ═══════════════ DELETE ════════════════════════════════════════ */

        async function deleteLoan() {
            if (!currentId) return Swal.fire('Info', 'No record loaded.', 'info');
            const res = await Swal.fire({
                title: 'Delete this loan?',
                text: 'All installments will also be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c62828',
                confirmButtonText: 'Yes, Delete',
            });
            if (!res.isConfirmed) return;
            try {
                const r = await fetch(`${API.loanDelete}/${currentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Delete failed');
                clearForm();
                currentId = null;
                setMode('new');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Deleted!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ GENERATE SCHEDULE ════════════════════════════ */

        async function generateSchedule() {
            const loanAppNo = document.getElementById('loanAppNo').value;
            if (!loanAppNo) return Swal.fire('Info', 'Save loan first.', 'info');
            const res = await Swal.fire({
                title: 'Generate Schedule?',
                text: 'Existing Due installments will be replaced.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6a1b9a',
                confirmButtonText: 'Yes, Generate',
            });
            if (!res.isConfirmed) return;
            try {
                const r = await fetch(API.schedule, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        loan_app_no: loanAppNo,
                        sanction_amount: document.getElementById('sanctionAmount').value,
                        period: document.getElementById('period').value,
                        monthly_installment: document.getElementById('monthlyInstallment').value,
                        first_install_date: document.getElementById('firstInstallDate').value,
                        company_id: document.getElementById('companyId').value,
                    }),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Schedule failed');
                renderInstallments(d.details ?? []);
                togglePanels(false, false);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Schedule generated!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ OUT PAYMENT ═══════════════════════════════════
           Oracle query:
             UPDATE emp_loan_detail
             SET COMPANY_ID=:company_id, STATUS='Paid',
                 PAYDATE=:out_pay_date, IS_VOUCHER='Y'
             WHERE loan_app_no=:loan_app_no
             AND install_no BETWEEN :out_from AND :out_to
        ═══════════════════════════════════════════════════════════════ */

        async function processOutPayment() {
            const loanAppNo = document.getElementById('loanAppNo').value;
            if (!loanAppNo) return Swal.fire('Info', 'Load a loan record first.', 'info');

            const outFrom = document.getElementById('outFrom').value;
            const outTo = document.getElementById('outTo').value;
            const outDate = document.getElementById('outPayDate').value;
            const voucher = document.getElementById('outPayVoucher').value ?? '';

            if (!outFrom || !outTo) return Swal.fire('Warning', 'Enter From and To Install No.', 'warning');
            if (!outDate) return Swal.fire('Warning', 'Enter Pay Date.', 'warning');
            if (parseInt(outTo) < parseInt(outFrom))
                return Swal.fire('Warning', 'To Install No must be ≥ From.', 'warning');

            const res = await Swal.fire({
                title: '💰 Process Out Payment?',
                html: `Install <b>${outFrom}</b> to <b>${outTo}</b> → <b>Paid</b><br>
                 Amount: <b>${document.getElementById('outAmt').value || '—'}</b><br>
                 Date: <b>${outDate}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e65100',
                confirmButtonText: 'Yes, Process',
            });
            if (!res.isConfirmed) return;

            try {
                const r = await fetch(API.outPayment, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        loan_app_no: loanAppNo,
                        out_from: outFrom,
                        out_to: outTo,
                        out_pay_date: outDate,
                        out_pay_voucher: voucher,
                        company_id: document.getElementById('companyId').value,
                    }),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Payment failed');
                renderInstallments(d.details ?? []);
                togglePanels(false, false);

                if (d.is_closed) {
                    Swal.fire('🎉 Loan Closed!', 'All installments paid. Loan is now closed.', 'success');
                    document.getElementById('isClose').value = 'Closed';
                    document.getElementById('isClose').style.color = '#c62828';
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: d.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
                // Reset fields
                ['outFrom', 'outTo', 'outAmt', 'outPayVoucher'].forEach(id => setVal(id, ''));
                document.getElementById('outPayDate').value = '';

            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ RESCHEDULE ════════════════════════════════════ */

        async function processReschedule() {
            const loanAppNo = document.getElementById('loanAppNo').value;
            if (!loanAppNo) return Swal.fire('Info', 'Load a loan record first.', 'info');

            const newPeriod = document.getElementById('newPeriod').value;
            const newDate = document.getElementById('newInsttDate').value;
            const newMI = document.getElementById('newMonthlyInstallInput').value;
            const balance = document.getElementById('rescheduleBalance').textContent;

            if (!newPeriod) return Swal.fire('Warning', 'Enter New Period.', 'warning');
            if (!newDate) return Swal.fire('Warning', 'Enter New First Install Date.', 'warning');

            const res = await Swal.fire({
                title: '🔄 Reschedule Loan?',
                html: `Balance: <b>${balance}</b><br>
                 New Period: <b>${newPeriod} months</b><br>
                 New Monthly Install: <b>${newMI || '—'}</b><br>
                 Starting: <b>${newDate}</b><br><br>
                 <small class="text-muted">Due installments will be replaced. Paid rows kept.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6a1b9a',
                confirmButtonText: 'Yes, Reschedule',
            });
            if (!res.isConfirmed) return;

            try {
                const r = await fetch(API.reschedule, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        loan_app_no: loanAppNo,
                        new_period: newPeriod,
                        new_instt_date: newDate,
                        company_id: document.getElementById('companyId').value,
                    }),
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Reschedule failed');
                renderInstallments(d.details ?? []);
                togglePanels(false, false);
                setVal('newPeriod', '');
                setVal('newInsttDate', '');
                setVal('newMonthlyInstallInput', '');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Loan rescheduled!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        /* ═══════════════ HELPERS ═══════════════════════════════════════ */

        /* ═══════════════ DELETE DUE DETAILS ONLY ═══════════════════════
           Deletes ONLY Due installments, keeps Paid rows intact
           Mirrors Oracle: DELETE FROM EMP_LOAN_DETAIL
                           WHERE LOAN_APP_NO=:b1 AND STATUS='Due'
        ═══════════════════════════════════════════════════════════ */
        async function deleteDueDetails() {
            if (!currentId) return Swal.fire('Info', 'Load a loan record first.', 'info');

            // Count Due rows in current table
            const dueRows = [...document.querySelectorAll('#installBody tr[data-install]')]
                .filter(tr => tr.querySelector('.badge-due'));
            if (dueRows.length === 0)
                return Swal.fire('Info', 'No Due installments found to delete.', 'info');

            const res = await Swal.fire({
                title: '🗑 Delete Due Installments?',
                html: `<b>${dueRows.length}</b> Due installment(s) will be deleted.<br>
                       <small class="text-muted">Paid rows will not be affected.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#880e4f',
                confirmButtonText: 'Yes, Delete Due',
            });
            if (!res.isConfirmed) return;

            try {
                const r = await fetch(`${API.loanDelete}/${currentId}/due`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const d = await r.json();
                if (!r.ok) throw new Error(d.message ?? 'Delete failed');

                renderInstallments(d.details ?? []);
                updateScheduleButton((d.details ?? []).length > 0);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: d.message ?? 'Due installments deleted.',
                    showConfirmButton: false,
                    timer: 2500
                });
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        function clearForm() {
            document.getElementById('loanForm').reset();
            document.getElementById('installBody').innerHTML =
                '<tr><td colspan="9" class="text-center text-muted py-3"><i class="bi bi-info-circle me-1"></i>Save loan then Generate Schedule.</td></tr>';
            document.getElementById('ftTotal').textContent = '0.00';
            document.getElementById('installSummary').textContent = '';
            document.getElementById('loanStatusBadge').textContent = '';
            document.getElementById('isClose').value = '';
            document.getElementById('deptName').value = '';
            document.getElementById('secNameDisplay').value = '';
            enableEditing(false);
            updateScheduleButton(false);
        }

        function togglePanels(op, rp) {
            document.getElementById('outPaymentPanel').style.display = op ? 'block' : 'none';
            document.getElementById('reschedulePanel').style.display = rp ? 'block' : 'none';
        }

        function setVal(id, val) {
            const el = document.getElementById(id);
            if (el) el.value = val ?? '';
        }

        function fmtNum(n) {
            return Number(n).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        /* ── Enter → next field ─────────────────────────────────────── */
        document.getElementById('loanForm').addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const all = Array.from(document.querySelectorAll(
                    '#loanForm input:not([readonly]):not([type=hidden]), #loanForm select'
                )).filter(f => f.offsetParent !== null);
                const i = all.indexOf(e.target);
                if (i >= 0 && i < all.length - 1) all[i + 1].focus();
            }
        });
    </script>
@endpush
