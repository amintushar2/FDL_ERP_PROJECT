@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
    <div class="wrapperbg-light">
        <div class="container-fluid py-3 px-4">

            {{-- Flash --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert"
                    style="font-size:13px;border-left:4px solid #198754;">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert"
                    style="font-size:13px;border-left:4px solid #dc3545;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('fail') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page Header --}}
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2"
                style="border-bottom:2px solid #f59e0b;">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded"
                        style="width:36px;height:36px;background:#0b1828;">
                        <i class="fas fa-users" style="color:#f59e0b;"></i>
                    </span>
                    <div>
                        <h5 class="mb-0 fw-bold text-uppercase lh-sm"
                            style="font-family:'Rajdhani',sans-serif;letter-spacing:1.5px;color:#0b1828;">
                            Employee List
                        </h5>
                        <small class="text-muted text-uppercase" style="font-size:10px;letter-spacing:.5px;">
                            Human Resource Management
                        </small>
                    </div>
                </div>
                <div>
                    <a href="{{ route('empnewentry') }}"
                        class="btn btn-sm fw-bold text-uppercase text-white d-inline-flex align-items-center gap-2"
                        style="background:#1a3a5c;border-color:#1a3a5c;border-radius:4px;font-family:'Rajdhani',sans-serif;letter-spacing:1px;">
                        <i class="fas fa-plus" style="color:#f59e0b;"></i> Add New Employee
                    </a>
                    <a href="{{ route('temp-emp.index') }}"
                        class="btn btn-sm fw-bold text-uppercase text-white d-inline-flex align-items-center gap-2"
                        style="background:#1a3a5c;border-color:#1a3a5c;border-radius:4px;font-family:'Rajdhani',sans-serif;letter-spacing:1px;">
                        <i class="fas fa-plus" style="color:#f59e0b;"></i> Add Temp. Employee
                    </a>
                </div>
            </div>

            {{-- ── FILTER BAR ── --}}
            <div class="card border-0 shadow-sm mb-3" style="border-radius:6px;">
                <div class="card-body py-2 px-3">
                    <div class="row g-2 align-items-end">

                        {{-- Search --}}
                        <div class="col-md-3">
                            <label class="filter-label">Search</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text" style="background:#f0f4f8;border-color:#bfcfdf;">
                                    <i class="fas fa-search" style="color:#7a9abc;font-size:11px;"></i>
                                </span>
                                <input type="text" id="f_search" class="form-control filter-input"
                                    placeholder="Name / Father / Mother…">
                            </div>
                        </div>

                        {{-- Emp No --}}
                        <div class="col-md-2">
                            <label class="filter-label">Emp No</label>
                            <input type="text" id="f_empno" class="form-control form-control-sm filter-input"
                                placeholder="e.g. EMP001">
                        </div>

                        {{-- Mobile --}}
                        <div class="col-md-2">
                            <label class="filter-label">Mobile No</label>
                            <input type="text" id="f_mobile" class="form-control form-control-sm filter-input"
                                placeholder="01XXXXXXXXX">
                        </div>

                        {{-- Company --}}
                        <div class="col-md-2">
                            <label class="filter-label">Company</label>
                            <select id="f_company" class="form-select form-select-sm filter-input">
                                <option value="">All</option>
                                @foreach ($companyList as $c)
                                    <option value="{{ $c->company_id }}">{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-2">
                            <label class="filter-label">Status</label>
                            <select name="status" id="f_status" class="form-select form-select-sm filter-input">
                                <option value="Active">Active</option>
                                <option value="">All</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- Search Button --}}
                        <div class="col-md-1">
                            <button id="btnSearch" class="btn btn-sm w-100"
                                style="background:#1a3a5c;color:#f59e0b;border:none;border-radius:4px;font-weight:800;font-size:12px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                    </div>
                    {{-- Result summary --}}
                    <div class="mt-1" style="font-size:11px;color:#7a9abc;">
                        Showing <span id="resultCount" class="fw-bold" style="color:#1a3a5c;">—</span> employee(s)
                        <span id="filterNote" class="ms-2" style="color:#f59e0b;display:none;">
                            <i class="fas fa-filter me-1"></i>Filtered
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── TABLE CARD ── --}}
            <div class="card border-0 shadow-sm rounded-1 overflow-hidden">

                <div class="card-header d-flex align-items-center gap-2 py-2 px-3 border-0"
                    style="background:#1a3a5c;border-bottom:2px solid #f59e0b;">
                    <i class="fas fa-table" style="color:#f59e0b;font-size:13px;"></i>
                    <span class="fw-bold text-white text-uppercase"
                        style="font-family:'Rajdhani',sans-serif;font-size:11px;letter-spacing:1.5px;">
                        Employee Records
                    </span>
                    <span id="badgeCount" class="ms-auto badge" style="background:#f59e0b;color:#0b1828;font-size:11px;">—
                        Total</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" id="empTable"
                            style="width:100%;font-size:13px;">
                            <thead>
                                <tr>
                                    <th class="th-cell">#</th>
                                    <th class="th-cell" style="white-space:nowrap;">Emp No</th>
                                    <th class="th-cell" style="white-space:nowrap;">New Emp No</th>
                                    <th class="th-cell">Name</th>
                                    <th class="th-cell" style="white-space:nowrap;">Father Name</th>
                                    <th class="th-cell" style="white-space:nowrap;">Mother Name</th>
                                    <th class="th-cell" style="white-space:nowrap;">Mobile</th>
                                    <th class="th-cell" style="text-align:center;">Sex</th>
                                    <th class="th-cell" style="text-align:center;">Status</th>
                                    <th class="th-cell" style="text-align:center;">Insert By</th>
                                    <th class="th-cell" style="text-align:center;white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="empTableBody">
                                {{-- Filled by AJAX --}}
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted" style="font-size:13px;">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Loading…
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination bar --}}
                <div class="card-footer d-flex align-items-center justify-content-between py-2 px-3"
                    style="background:#f8fafc;border-top:1px solid #dde8f2;">
                    <div style="font-size:12px;color:#6b7c93;">
                        Page <span id="pgCurrent">1</span> of <span id="pgTotal">1</span>
                        &nbsp;·&nbsp; <span id="pgInfo">0 records</span>
                    </div>
                    <div id="pgButtons" class="d-flex gap-1">
                        {{-- Built by JS --}}
                    </div>
                </div>

            </div>{{-- /card --}}

        </div>



    </div>



    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&display=swap');

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            color: #374a5a;
            display: block;
            margin-bottom: 3px;
        }

        .filter-input {
            font-size: 12.5px !important;
            border-color: #bfcfdf !important;
            border-radius: 4px !important;
            height: 30px !important;
            padding: 3px 8px !important;
        }

        .filter-input:focus {
            border-color: #1a3a5c !important;
            box-shadow: 0 0 0 2px rgba(26, 58, 92, .1) !important;
            outline: none;
        }

        .th-cell {
            background: #1a3a5c !important;
            color: #fff !important;
            font-family: 'Rajdhani', sans-serif;
            font-size: 11px;
            letter-spacing: 1.2px;
            font-weight: 700;
            padding: 9px 10px !important;
            border: none !important;
            text-transform: uppercase;
        }

        #empTable tbody tr:nth-child(even) {
            background: #f5f8fc;
        }

        #empTable tbody tr:hover {
            background: #e4f0fb !important;
            cursor: pointer;
        }

        #empTable td {
            border-color: #dde8f2 !important;
            padding: 7px 10px !important;
            vertical-align: middle;
        }

        /* Pagination */
        .pg-btn {
            min-width: 30px;
            height: 28px;
            padding: 0 8px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #cdd8e8;
            background: #fff;
            color: #1a3a5c;
            border-radius: 3px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .15s;
        }

        .pg-btn:hover {
            background: #1a3a5c;
            color: #fff;
            border-color: #1a3a5c;
        }

        .pg-btn.active {
            background: #1a3a5c !important;
            color: #f59e0b !important;
            border-color: #1a3a5c !important;
            font-weight: 700;
        }

        .pg-btn:disabled {
            color: #bfcfdf;
            background: #f6f9fc;
            border-color: #e0eaf4;
            cursor: default;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f0f4f8;
        }

        ::-webkit-scrollbar-thumb {
            background: #9ab4cc;
            border-radius: 3px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        // ════════════════════════════════════════════════
        //  EMPLIST — filter + AJAX pagination
        // ════════════════════════════════════════════════
        const PER_PAGE = 25;
        let currentPage = 1;
        let lastResults = [];

        // ── Badge helpers ──────────────────────────────
        function sexBadge(sex) {
            const s = (sex || '').toLowerCase();
            if (s === 'male' || s === 'm')
                return '<span class="badge" style="background:#dbeafe;color:#1e40af;font-size:10px;"><i class="fas fa-mars me-1"></i>Male</span>';
            if (s === 'female' || s === 'f')
                return '<span class="badge" style="background:#fce7f3;color:#9d174d;font-size:10px;"><i class="fas fa-venus me-1"></i>Female</span>';
            return `<span class="badge bg-secondary" style="font-size:10px;">${sex||''}</span>`;
        }

        function statusBadge(st) {
            const s = (st || '').toLowerCase();
            if (s === 'active')
                return '<span class="badge" style="background:#dcfce7;color:#166534;font-size:10px;"><i class="fas fa-circle me-1" style="font-size:6px;"></i>Active</span>';
            return `<span class="badge" style="background:#fee2e2;color:#991b1b;font-size:10px;"><i class="fas fa-circle me-1" style="font-size:6px;"></i>${st||'N/A'}</span>`;
        }

        // ── Render one page of rows ────────────────────
        function renderPage(data, page) {
            const start = (page - 1) * PER_PAGE;
            const slice = data.slice(start, start + PER_PAGE);
            const total = data.length;
            const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));

            if (slice.length === 0) {
                $('#empTableBody').html(
                    '<tr><td colspan="10" class="text-center py-5 text-muted fst-italic">' +
                    '<i class="fas fa-inbox d-block mb-2" style="font-size:24px;"></i>No employees found</td></tr>'
                );
            } else {
                let html = '';
                slice.forEach(function(emp, idx) {
                    const rowNum = start + idx + 1;
                    html += `<tr>
                <td class="text-muted" style="font-size:11px;">${rowNum}</td>
                <td class="fw-semibold">${emp.empno||''}</td>
                <td>${emp.new_empno||''}</td>
                <td class="fw-medium">${emp.empname||''}</td>
                <td>${emp.father_name||''}</td>
                <td>${emp.mother_name||''}</td>
                <td>${emp.emp_mobile_no||'—'}</td>
                <td class="text-center">${sexBadge(emp.sex)}</td>
                <td class="text-center">${statusBadge(emp.status)}</td>
                <td class="text-center">${emp.insert_by||'—'}</td>
                <td class="text-center" style="white-space:nowrap;">
                    <a href="/hrm/empedit/${emp.empno}" class="btn btn-sm me-1"
                        style="background:#1a3a5c;color:#f59e0b;border:none;border-radius:4px;padding:3px 10px;font-size:11px;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="/hrm/empedit/${emp.empno}" class="btn btn-sm"
                        style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:4px;padding:3px 10px;font-size:11px;">
                        <i class="fas fa-eye me-1"></i>View
                    </a>
                </td>
            </tr>`;
                });
                $('#empTableBody').html(html);
            }

            // Update counters
            $('#resultCount').text(total);
            $('#badgeCount').text(total + ' Total');
            $('#pgCurrent').text(page);
            $('#pgTotal').text(totalPages);
            $('#pgInfo').text(`${Math.min(start+1, total)}–${Math.min(start+PER_PAGE, total)} of ${total}`);

            // Build pagination buttons
            buildPagination(page, totalPages);
        }

        function buildPagination(current, total) {
            let html = '';
            const prev = current > 1;
            const next = current < total;

            html += `<button class="pg-btn" onclick="goPage(1)" ${!prev?'disabled':''} title="First">«</button>`;
            html += `<button class="pg-btn" onclick="goPage(${current-1})" ${!prev?'disabled':''} title="Prev">‹</button>`;

            // Show window of pages
            const start = Math.max(1, current - 2);
            const end = Math.min(total, current + 2);
            for (let p = start; p <= end; p++) {
                html += `<button class="pg-btn ${p===current?'active':''}" onclick="goPage(${p})">${p}</button>`;
            }

            html += `<button class="pg-btn" onclick="goPage(${current+1})" ${!next?'disabled':''} title="Next">›</button>`;
            html += `<button class="pg-btn" onclick="goPage(${total})" ${!next?'disabled':''} title="Last">»</button>`;

            $('#pgButtons').html(html);
        }

        function goPage(p) {
            const totalPages = Math.max(1, Math.ceil(lastResults.length / PER_PAGE));
            p = Math.max(1, Math.min(p, totalPages));
            if (p === currentPage) return;
            currentPage = p;
            renderPage(lastResults, currentPage);
            // Scroll table into view
            document.getElementById('empTable').scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        // ── Search / Filter ────────────────────────────
        function doSearch() {
            const params = {
                search: $('#f_search').val().trim(),
                empno: $('#f_empno').val().trim(),
                mobile: $('#f_mobile').val().trim(),
                company_id: $('#f_company').val(),
                status: $('#f_status').val(),
            };

            // Show filter indicator
            const isFiltered = params.search || params.empno || params.mobile ||
                params.company_id || params.status !== 'Active';
            $('#filterNote').toggle(!!isFiltered);

            $('#empTableBody').html(
                '<tr><td colspan="10" class="text-center py-4 text-muted">' +
                '<i class="fas fa-spinner fa-spin me-2"></i>Searching…</td></tr>'
            );

            $.get('{{ route('emplist.search') }}', params)
                .done(function(res) {
                    lastResults = res.data || [];
                    currentPage = 1;
                    renderPage(lastResults, 1);
                })
                .fail(function() {
                    $('#empTableBody').html(
                        '<tr><td colspan="10" class="text-center py-4 text-danger">' +
                        '<i class="fas fa-exclamation-triangle me-2"></i>Search failed. Please try again.</td></tr>'
                    );
                });
        }

        // ── Wire events ────────────────────────────────
        $(function() {
            // Search button
            $('#btnSearch').on('click', doSearch);

            // Enter key on any filter input
            $('.filter-input').on('keydown', function(e) {
                if (e.keyCode === 13) doSearch();
            });

            // Status change triggers immediate search
            $('#f_status').on('change', doSearch);

            // Initial load: Active employees only
            doSearch();
        });
    </script>
@endpush
