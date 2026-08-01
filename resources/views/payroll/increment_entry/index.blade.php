@extends('layouts.app')

@section('title', 'Increment Entry')

@push('styles')
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

        /* ── Mode Banner (same as empForm) ── */
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

        /* ── Section Cards (same as empForm) ── */
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
            padding: 12px 14px 8px;
        }

        /* ── Page heading (same as empForm) ── */
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

        /* ── Form labels & inputs (same as empForm) ── */
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

        textarea.form-control {
            height: auto !important;
            min-height: 52px !important;
            resize: vertical;
        }

        .ro {
            background: #dde8f5 !important;
            font-weight: 700;
            color: var(--pri) !important;
            cursor: not-allowed;
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
            color: #f59e0b;
        }

        /* ── Buttons (same as empForm) ── */
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

        .btn-upd {
            background: #1565c0 !important;
            border-color: #1565c0 !important;
            color: #fff !important;
        }

        .btn-del {
            background: var(--danger) !important;
            border-color: var(--danger) !important;
            color: #fff !important;
        }

        .btn-clr {
            background: transparent !important;
            color: var(--danger) !important;
            border: 1.5px solid var(--danger) !important;
        }

        .btn-clr:hover {
            background: #ffeaea !important;
        }

        .btn-sec {
            background: #546e7a !important;
            border-color: #546e7a !important;
            color: #fff !important;
        }

        .btn-amber {
            background: var(--amber) !important;
            border-color: var(--amber) !important;
            color: #0b1828 !important;
            font-weight: 700 !important;
        }

        .action-bar {
            padding: 10px 0 14px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ── Employee ID bar (same as empForm) ── */
        .empid-bar {
            background: #e6eff8;
            border: 1px solid #b8d0ea;
            border-radius: var(--r);
            padding: 10px 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
        }

        .empid-bar>div {
            display: flex;
            flex-direction: column;
        }

        .empid-bar label {
            font-size: 11px;
            font-weight: 700;
            color: var(--pri);
            margin-bottom: 3px;
        }

        /* ── Gross value highlight ── */
        .gross-val {
            color: #1e7e34 !important;
            font-weight: 700 !important;
        }

        /* ── History table (same style as emp sub-tables) ── */
        .sub-table-wrap {
            overflow-x: auto;
            border-radius: var(--r);
            border: 1px solid var(--border);
            margin-top: 4px;
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
            white-space: nowrap;
        }

        .emp-table tbody tr:hover {
            background: #e4f0fb;
            cursor: pointer;
        }

        .emp-table tbody tr.selected-row {
            background: #c8e0f4 !important;
        }

        .emp-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Radio cell */
        .radio-cell {
            width: 34px;
            min-width: 34px;
            max-width: 34px;
            text-align: center;
            padding: 2px 4px !important;
        }

        .radio-cell input[type=radio] {
            width: 15px;
            height: 15px;
            margin: 0 auto;
            cursor: pointer;
            accent-color: var(--amber);
            display: block;
        }

        /* History wrap with max-height */
        .hist-table-wrap {
            max-height: 300px;
            overflow: auto;
        }

        /* Type badge */
        .type-pill {
            display: inline-block;
            padding: 1px 9px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .type-pill.fixed {
            background: #dce8f8;
            color: #1a3a5c;
            border: 1px solid #b0c8e8;
        }

        .type-pill.percent {
            background: #fff3cd;
            color: #7a5800;
            border: 1px solid #ffe082;
        }

        /* Select2 height fix */
        .select2-container--default .select2-selection--single {
            height: var(--h) !important;
            border: 1px solid #bfcfdf !important;
            border-radius: 4px !important;
            background: var(--inp) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(var(--h) - 2px) !important;
            font-size: 12.5px !important;
            color: #1a2a3a !important;
            padding-left: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: var(--h) !important;
        }

        /* HR divider */
        hr.sec-hr {
            border: none;
            border-top: 1.5px dashed #d0dceb;
            margin: 10px 0;
        }

        /* row padding fix */
        .row.p-1 {
            padding: 4px 0 !important;
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

    <div class="mode-banner">
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="badge-pill"><i class="bi bi-arrow-up-circle-fill"></i> INCREMENT</span>
            <span style="font-size:14px;font-weight:600;">Employee Salary Increment Entry</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="container-fluid px-4 py-3">

        {{-- ALERT --}}
        <div id="alert_area" class="mb-2"></div>

        {{-- ── PAGE HEADING ── --}}
        <div class="page-heading">
            <i class="bi bi-arrow-up-circle-fill"></i>
            Increment Entry
        </div>

        {{-- ── EMPLOYEE ID BAR ── --}}
        <div class="empid-bar">
            <div style="min-width:280px;">
                <label>Employee <span class="text-danger">*</span></label>
                <select id="emp_select" class="form-select" style="width:100%">
                    <option value="">-- Search by Emp No / Name --</option>
                </select>
            </div>
            <div>
                <label>Emp No</label>
                <input type="text" id="disp_empno" class="form-control ro" readonly style="width:110px;">
            </div>
            <div>
                <label>New Emp No</label>
                <input type="text" id="disp_new_empno" class="form-control ro" readonly style="width:110px;">
            </div>
            <div>
                <label>Section</label>
                <input type="text" id="disp_section" class="form-control ro" readonly style="width:140px;">
            </div>
            <div>
                <label>Name</label>
                <input type="text" id="disp_emp_name" class="form-control ro" readonly style="width:200px;">
            </div>
        </div>

        {{-- ── THREE COLUMN CARDS ── --}}
        <div class="row g-3 mb-2">

            {{-- Previous Salary --}}
            <div class="col-md-4">
                <div class="sec-card h-100">
                    <div class="sec-card-head">
                        <i class="bi bi-clock-history"></i> Previous Salary
                    </div>
                    <div class="sec-card-body">
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Designation</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_designation" class="form-control" readonly>
                                <input type="hidden" id="prev_des_id">
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Grade</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_grade_name" class="form-control" readonly>
                                <input type="hidden" id="prev_grade">
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">OT Entitlement</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_ot_ent" class="form-control" readonly>
                            </div>
                        </div>
                        <hr class="sec-hr">
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Gross</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_gross" class="form-control gross-val" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Basic</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_basic" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">House Rent</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_house_rent" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Medical</label>
                            <div class="col-sm-8">
                                <input type="text" id="prev_medical" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Increment Details --}}
            <div class="col-md-4">
                <div class="sec-card h-100">
                    <div class="sec-card-head" style="background:linear-gradient(90deg,#7a4500,#c47a00);">
                        <i class="bi bi-sliders"></i> Increment Details
                    </div>
                    <div class="sec-card-body">
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Type <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select id="incr_type" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="Fixed">Fixed</option>
                                    <option value="Percent">Percent</option>
                                </select>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" id="increment_amt" class="form-control" step="0.01"
                                    min="0" placeholder="Amount or %">
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Incr. Date <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="incr_date" class="form-control" placeholder="dd-Mon-yyyy"
                                        autocomplete="off">
                                    <span class="input-group-text"
                                        onclick="document.getElementById('incr_date')._flatpickr?.open()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Effective Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="effective_date" class="form-control"
                                        placeholder="dd-Mon-yyyy" autocomplete="off">
                                    <span class="input-group-text"
                                        onclick="document.getElementById('effective_date')._flatpickr?.open()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">Remarks</label>
                            <div class="col-sm-8">
                                <textarea id="remark_text" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Salary --}}
            <div class="col-md-4">
                <div class="sec-card h-100">
                    <div class="sec-card-head" style="background:linear-gradient(90deg,#14531e,#1e7e34);">
                        <i class="bi bi-check-circle-fill"></i> New Salary
                    </div>
                    <div class="sec-card-body">
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New Designation</label>
                            <div class="col-sm-8">
                                <select id="cur_designation_sel" class="form-select" style="width:100%">
                                    <option value="">-- Same / Select --</option>
                                </select>
                                <input type="hidden" id="cur_des_id">
                                <input type="hidden" id="cur_designation">
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New Grade</label>
                            <div class="col-sm-8">
                                <select id="cur_grade_sel" class="form-select" style="width:100%">
                                    <option value="">-- Same --</option>
                                </select>
                                <input type="hidden" id="cur_grade">
                                <input type="hidden" id="cur_grade_name">
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">OT Entitlement</label>
                            <div class="col-sm-8">
                                <input type="text" id="cur_ot_ent" class="form-control">
                            </div>
                        </div>
                        <hr class="sec-hr">
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New Gross</label>
                            <div class="col-sm-8">
                                <input type="text" id="cur_gross" class="form-control gross-val" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New Basic</label>
                            <div class="col-sm-8">
                                <input type="text" id="cur_basic" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New House Rent</label>
                            <div class="col-sm-8">
                                <input type="text" id="cur_house_rent" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row p-1">
                            <label class="col-sm-4 col-form-label">New Medical</label>
                            <div class="col-sm-8">
                                <input type="text" id="cur_medical" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /row --}}

        {{-- ── ACTION BUTTONS ── --}}
        <div class="action-bar">
            <button id="btn_save" class="btn btn-save" disabled>
                <i class="bi bi-save2"></i>
                <span id="btn_save_label">Save Increment</span>
            </button>
            <button id="btn_new" class="btn btn-sec">
                <i class="bi bi-plus-circle"></i> New
            </button>
            <button id="btn_delete" class="btn btn-del" disabled>
                <i class="bi bi-trash3"></i> Delete Selected
            </button>
        </div>

        {{-- ── INCREMENT HISTORY ── --}}
        <div class="sec-card">
            <div class="sec-card-head" style="justify-content:space-between;">
                <span><i class="bi bi-table"></i> Increment History</span>
                <span id="hist_count"
                    style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);
                             padding:1px 10px;border-radius:10px;font-size:10px;font-weight:600;">
                    0 records
                </span>
            </div>
            <div class="sec-card-body p-0">
                <div class="hist-table-wrap">
                    <table class="emp-table" id="hist_table">
                        <thead>
                            <tr>
                                <th class="radio-cell">&nbsp;</th>
                                <th>Incr Date</th>
                                <th>Effective Date</th>
                                <th>Type</th>
                                <th>Amt</th>
                                <th>Prev Gross</th>
                                <th>New Gross</th>
                                <th>Prev Des</th>
                                <th>New Des</th>
                                <th>Prev Grade</th>
                                <th>New Grade</th>
                                <th>Prev OT</th>
                                <th>New OT</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="hist_tbody">
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4" style="font-size:.80rem;">
                                    <i class="bi bi-people"
                                        style="font-size:2rem;opacity:.15;display:block;margin-bottom:6px;"></i>
                                    Select an employee to view history
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- /.container-fluid --}}

@endsection

@push('scripts')
    <script>
        $(function() {

            const CSRF = '{{ csrf_token() }}';
            let selectedHistRow = null;
            let isEditMode = false;

            /* ── Flatpickr ── */
            const fpOpts = {
                dateFormat: 'd-M-Y',
                allowInput: true
            };
            flatpickr('#incr_date', fpOpts);
            flatpickr('#effective_date', fpOpts);

            /* ── Select2: Employee ── */
            $('#emp_select').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Search by Emp No / Name --',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('payroll.increment.employees') }}',
                    dataType: 'json',
                    delay: 250,
                    data: d => ({
                        q: d.term
                    }),
                    processResults: data => ({
                        results: data.map(r => ({
                            id: r.empno,
                            text: `${r.empno} — ${r.emp_name} (${r.section ?? ''})`
                        }))
                    }),
                    cache: true,
                },
            });
            $('#emp_select').on('select2:select', e => loadEmployeeInfo(e.params.data.id));
            $('#emp_select').on('select2:clear', () => clearForm());

            /* ── Select2: Designation ── */
            $('#cur_designation_sel').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Same / Select --',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('payroll.increment.designations') }}',
                    dataType: 'json',
                    delay: 200,
                    data: d => ({
                        q: d.term
                    }),
                    processResults: data => ({
                        results: data.map(r => ({
                            id: r.des_id,
                            text: r.designation_name
                        }))
                    }),
                    cache: true,
                },
            });
            $('#cur_designation_sel').on('select2:select', function(e) {
                $('#cur_des_id').val(e.params.data.id);
                $('#cur_designation').val(e.params.data.text);
            });
            $('#cur_designation_sel').on('select2:clear', function() {
                $('#cur_des_id,#cur_designation').val('');
            });

            /* ── Select2: Grade ── */
            $('#cur_grade_sel').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Same --',
                allowClear: true,
                ajax: {
                    url: '{{ route('payroll.increment.grades') }}',
                    dataType: 'json',
                    delay: 200,
                    processResults: data => ({
                        results: data.filter(r => r.grade_id).map(r => ({
                            id: r.grade_id,
                            text: r.grade_name
                        }))
                    }),
                    cache: true,
                },
            });
            $('#cur_grade_sel').on('select2:select', function(e) {
                $('#cur_grade').val(e.params.data.id);
                $('#cur_grade_name').val(e.params.data.text);
            });
            $('#cur_grade_sel').on('select2:clear', function() {
                $('#cur_grade,#cur_grade_name').val('');
            });

            /* ── Load employee info ── */
            function loadEmployeeInfo(empno) {
                $.ajax({
                    url: '{{ route('payroll.increment.emp-info') }}',
                    data: {
                        empno
                    },
                    success: function(data) {
                        if (!data || data.error) {
                            showAlert(data?.error ?? 'Employee not found.', 'warning');
                            return;
                        }
                        $('#disp_empno').val(empno);
                        $('#disp_new_empno').val(data.new_empno ?? '');
                        $('#disp_emp_name').val(data.emp_name ?? '');
                        $('#disp_section').val(data.section ?? '');
                        $('#prev_designation').val(data.prev_designation ?? '');
                        $('#prev_des_id').val(data.prev_des_id ?? '');
                        $('#prev_grade_name').val(data.prev_grade ?? '');
                        $('#prev_grade').val(data.pre_grade_id ?? '');
                        $('#prev_ot_ent').val(data.prev_ot_ent ?? '');
                        $('#prev_gross').val(data.prev_gross ?? '');
                        $('#prev_basic').val(data.prev_basic ?? '');
                        $('#prev_house_rent').val(data.prev_house_rent ?? '');
                        $('#prev_medical').val(data.prev_medical ?? '');
                        $('#cur_ot_ent').val(data.prev_ot_ent ?? '');
                        $('#btn_save').prop('disabled', false);
                        loadHistory(empno);
                    },
                    error: xhr => showAlert('Error: ' + (xhr.responseJSON?.message ?? xhr.statusText),
                        'danger'),
                });
            }

            /* ── Load increment history ── */
            function loadHistory(empno) {
                $.ajax({
                    url: '{{ route('payroll.increment.history') }}',
                    data: {
                        empno
                    },
                    success: function(rows) {
                        const tbody = document.getElementById('hist_tbody');
                        tbody.innerHTML = '';
                        $('#hist_count').text(rows.length + ' records');

                        if (!rows.length) {
                            tbody.innerHTML = `<tr><td colspan="14" class="text-center text-muted py-4"
                                style="font-size:.80rem;">
                                <i class="bi bi-inbox" style="font-size:1.8rem;opacity:.2;display:block;margin-bottom:6px;"></i>
                                No increment history found.</td></tr>`;
                            return;
                        }

                        rows.forEach(r => {
                            const tr = document.createElement('tr');
                            const typePill = r.incr_type === 'Fixed' ?
                                `<span class="type-pill fixed">Fixed</span>` :
                                `<span class="type-pill percent">Percent</span>`;
                            tr.innerHTML = `
                                <td class="radio-cell">
                                    <input type="radio" name="hist_sel" class="hist-radio">
                                </td>
                                <td>${r.incr_date      ?? ''}</td>
                                <td>${r.effective_date ?? ''}</td>
                                <td>${typePill}</td>
                                <td class="text-end">${r.increment_amt ?? ''}</td>
                                <td class="text-end">${r.prev_gross    ?? ''}</td>
                                <td class="text-end gross-val">${r.cur_gross ?? ''}</td>
                                <td>${r.prev_designation ?? ''}</td>
                                <td>${r.cur_designation  ?? ''}</td>
                                <td>${r.prev_grade_name  ?? ''}</td>
                                <td>${r.cur_grade_name   ?? ''}</td>
                                <td>${r.prev_ot_ent ?? ''}</td>
                                <td>${r.cur_ot_ent  ?? ''}</td>
                                <td>${r.remark_text ?? ''}</td>
                            `;
                            tr.addEventListener('click', function() {
                                document.querySelectorAll('#hist_tbody tr').forEach(
                                    t => {
                                        t.classList.remove('selected-row');
                                        const rb = t.querySelector('.hist-radio');
                                        if (rb) rb.checked = false;
                                    });
                                tr.classList.add('selected-row');
                                tr.querySelector('.hist-radio').checked = true;
                                selectedHistRow = r;
                                isEditMode = true;
                                $('#btn_save_label').text('Update Increment');
                                $('#btn_save').prop('disabled', false);
                                $('#btn_delete').prop('disabled', false);
                                populateFormFromHistory(r);
                            });
                            tbody.appendChild(tr);
                        });
                    },
                    error: xhr => showAlert('Error loading history: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                });
            }

            /* ── Set flatpickr date ── */
            function setFpDate(fieldId, dateStr) {
                if (!dateStr) return;
                const fp = document.getElementById(fieldId)?._flatpickr;
                if (!fp) {
                    document.getElementById(fieldId).value = dateStr;
                    return;
                }
                const norm = dateStr.replace(/^(\d{2})-([A-Z]{3})-(\d{4})$/,
                    (_, d, m, y) => d + '-' + m[0] + m.slice(1).toLowerCase() + '-' + y);
                fp.setDate(norm, false, 'd-M-Y');
            }

            /* ── Populate form from history row ── */
            function populateFormFromHistory(r) {
                $('#incr_type').val(r.incr_type ?? '').trigger('change');
                $('#increment_amt').val(r.increment_amt ?? '');
                setFpDate('incr_date', r.incr_date);
                setFpDate('effective_date', r.effective_date);
                $('#remark_text').val(r.remark_text ?? '');
                $('#cur_gross').val(r.cur_gross ?? '');
                $('#cur_basic').val(r.cur_basic ?? '');
                $('#cur_house_rent').val(r.cur_house_rent ?? '');
                $('#cur_medical').val(r.cur_medical ?? '');
                $('#cur_ot_ent').val(r.cur_ot_ent ?? '');
                $('#cur_des_id').val(r.cur_des_id ?? '');
                $('#cur_designation').val(r.cur_designation ?? '');
                $('#cur_grade').val(r.cur_grade ?? '');
                $('#cur_grade_name').val(r.cur_grade_name ?? '');
                if (r.cur_des_id && r.cur_designation) {
                    const o = new Option(r.cur_designation, r.cur_des_id, true, true);
                    $('#cur_designation_sel').empty().append(o).trigger('change');
                }
                if (r.cur_grade && r.cur_grade_name) {
                    const o = new Option(r.cur_grade_name, r.cur_grade, true, true);
                    $('#cur_grade_sel').empty().append(o).trigger('change');
                }
            }

            /* ── Auto-calculate new salary ── */
            function triggerCalculate() {
                const baseGross = isEditMode && selectedHistRow ?
                    parseFloat(selectedHistRow.prev_gross) || 0 :
                    parseFloat($('#prev_gross').val()) || 0;
                const incrType = $('#incr_type').val();
                const incrementAmt = parseFloat($('#increment_amt').val()) || 0;
                if (!baseGross || !incrType || !incrementAmt) {
                    $('#cur_gross,#cur_basic,#cur_house_rent,#cur_medical').val('');
                    return;
                }
                $.ajax({
                    url: '{{ route('payroll.increment.calculate') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: {
                        prev_gross: baseGross,
                        incr_type: incrType,
                        increment_amt: incrementAmt
                    },
                    success: d => {
                        $('#cur_gross').val(d.cur_gross ?? '');
                        $('#cur_basic').val(d.cur_basic ?? '');
                        $('#cur_house_rent').val(d.cur_house_rent ?? '');
                        $('#cur_medical').val(d.cur_medical ?? '');
                    },
                });
            }
            $('#incr_type, #increment_amt').on('change input', triggerCalculate);

            /* ── SAVE / UPDATE ── */
            $('#btn_save').on('click', function() {
                const empno = $('#disp_empno').val();
                if (!empno) {
                    showAlert('Please select an employee.', 'warning');
                    return;
                }
                const payload = {
                    empno,
                    new_empno: $('#disp_new_empno').val(),
                    emp_name: $('#disp_emp_name').val(),
                    section: $('#disp_section').val(),
                    prev_designation: $('#prev_designation').val(),
                    prev_des_id: $('#prev_des_id').val(),
                    prev_ot_ent: $('#prev_ot_ent').val(),
                    prev_gross: $('#prev_gross').val(),
                    prev_basic: $('#prev_basic').val(),
                    prev_house_rent: $('#prev_house_rent').val(),
                    prev_medical: $('#prev_medical').val(),
                    prev_grade: $('#prev_grade').val(),
                    prev_grade_name: $('#prev_grade_name').val(),
                    incr_type: $('#incr_type').val(),
                    increment_amt: $('#increment_amt').val(),
                    cur_gross: $('#cur_gross').val(),
                    cur_basic: $('#cur_basic').val(),
                    cur_house_rent: $('#cur_house_rent').val(),
                    cur_medical: $('#cur_medical').val(),
                    cur_ot_ent: $('#cur_ot_ent').val(),
                    cur_designation: $('#cur_designation').val() || $('#prev_designation').val(),
                    cur_des_id: $('#cur_des_id').val() || $('#prev_des_id').val(),
                    cur_grade: $('#cur_grade').val() || $('#prev_grade').val(),
                    cur_grade_name: $('#cur_grade_name').val() || $('#prev_grade_name').val(),
                    incr_date: $('#incr_date').val(),
                    effective_date: $('#effective_date').val(),
                    remark_text: $('#remark_text').val(),
                    is_edit: isEditMode ? 1 : 0,
                    orig_incr_date: selectedHistRow ? selectedHistRow.incr_date : '',
                };
                if (!payload.incr_type || !payload.increment_amt || !payload.incr_date) {
                    showAlert('Type, Amount and Date are required.', 'warning');
                    return;
                }
                const btn = $(this).prop('disabled', true)
                    .html('<i class="bi bi-hourglass-split"></i> Saving…');
                $.ajax({
                    url: '{{ route('payroll.increment.save') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: payload,
                    success: function(res) {
                        showAlert(res.message, 'success');
                        isEditMode = false;
                        selectedHistRow = null;
                        $('#btn_save_label').text('Save Increment');
                        $('#btn_delete').prop('disabled', true);
                        loadHistory(empno);
                    },
                    error: xhr => showAlert('Error: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                    complete: () => btn.prop('disabled', false)
                        .html(
                            '<i class="bi bi-save2"></i><span id="btn_save_label">Save Increment</span>'
                        ),
                });
            });

            /* ── DELETE ── */
            $('#btn_delete').on('click', function() {
                if (!selectedHistRow) return;
                if (!confirm('Delete increment record dated ' + selectedHistRow.incr_date + '?')) return;
                const btn = $(this).prop('disabled', true)
                    .html('<i class="bi bi-hourglass-split"></i> Deleting…');
                $.ajax({
                    url: '{{ route('payroll.increment.delete') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    data: {
                        empno: $('#disp_empno').val(),
                        incr_date: selectedHistRow.incr_date,
                        prev_gross: selectedHistRow.prev_gross,
                        prev_designation: selectedHistRow.prev_designation,
                        prev_des_id: selectedHistRow.prev_des_id,
                        prev_ot_ent: selectedHistRow.prev_ot_ent,
                        prev_grade_name: selectedHistRow.prev_grade_name,
                    },
                    success: function(res) {
                        showAlert(res.message, 'success');
                        selectedHistRow = null;
                        isEditMode = false;
                        $('#btn_delete').prop('disabled', true);
                        $('#btn_save_label').text('Save Increment');
                        loadHistory($('#disp_empno').val());
                    },
                    error: xhr => showAlert('Error: ' + (xhr.responseJSON?.message ?? xhr
                        .statusText), 'danger'),
                    complete: () => btn.html('<i class="bi bi-trash3"></i> Delete Selected'),
                });
            });

            /* ── NEW ── */
            $('#btn_new').on('click', function() {
                $('#incr_type').val('');
                $('#increment_amt').val('');
                document.getElementById('incr_date')._flatpickr?.clear();
                document.getElementById('effective_date')._flatpickr?.clear();
                $('#remark_text').val('');
                $('#cur_gross,#cur_basic,#cur_house_rent,#cur_medical').val('');
                $('#cur_ot_ent').val($('#prev_ot_ent').val());
                $('#cur_des_id,#cur_designation,#cur_grade,#cur_grade_name').val('');
                $('#cur_designation_sel').empty().append('<option value="">-- Same / Select --</option>')
                    .trigger('change');
                $('#cur_grade_sel').empty().append('<option value="">-- Same --</option>').trigger(
                    'change');
                selectedHistRow = null;
                isEditMode = false;
                $('#btn_save_label').text('Save Increment');
                $('#btn_delete').prop('disabled', true);
                document.querySelectorAll('#hist_tbody tr').forEach(t => {
                    t.classList.remove('selected-row');
                    const rb = t.querySelector('.hist-radio');
                    if (rb) rb.checked = false;
                });
            });

            /* ── Alert ── */
            function showAlert(msg, type = 'success') {
                $('#alert_area').html(
                    `<div class="alert alert-${type} alert-dismissible fade show py-2 small mb-2" role="alert"
                          style="font-size:12.5px;border-left:4px solid ${type==='success'?'#1e7e34':'#c0392b'};">
                        ${msg}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>`
                );
            }

            /* ── Clear form ── */
            function clearForm() {
                $('#disp_empno,#disp_new_empno,#disp_emp_name,#disp_section').val('');
                $('#prev_designation,#prev_des_id,#prev_grade,#prev_grade_name').val('');
                $('#prev_ot_ent,#prev_gross,#prev_basic,#prev_house_rent,#prev_medical').val('');
                $('#btn_save').prop('disabled', true);
                $('#btn_new').trigger('click');
                document.getElementById('hist_tbody').innerHTML =
                    `<tr><td colspan="14" class="text-center text-muted py-4" style="font-size:.80rem;">
                        <i class="bi bi-people" style="font-size:2rem;opacity:.15;display:block;margin-bottom:6px;"></i>
                        Select an employee to view history</td></tr>`;
                $('#hist_count').text('0 records');
            }

        });
    </script>
@endpush
