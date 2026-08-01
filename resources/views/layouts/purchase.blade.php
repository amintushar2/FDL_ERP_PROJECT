<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Purchase Order Entry')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f8f9fa;
            font-size: 13px;
        }

        /* Navbar */
        .navbar {
            background: #fff !important;
            border-bottom: 1px solid #dee2e6;
        }

        .navbar-brand {
            font-size: 13px;
            font-weight: 600;
        }

        /* Cards */
        .card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .card-header {
            background: #f1f3f5;
            border-bottom: 1px solid #dee2e6;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #343a40;
        }

        .card-footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
        }

        .card-header {
            width: 100%;
        }

        /* Block badge */
        .block-badge {
            font-size: 10px;
            font-weight: 500;
            background: #e9ecef;
            color: #6c757d;
            border: 1px solid #ced4da;
            border-radius: 3px;
            padding: 1px 6px;
            font-family: monospace;
        }

        /* Form labels */
        .form-label {
            font-size: 11px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        /* Inputs */
        .form-control,
        .form-select {
            font-size: 12px;
            padding: 5px 9px;
        }

        .form-control[readonly] {
            background: #f8f9fa;
            color: #6c757d;
        }

        /* Tables */
        .table {
            font-size: 12px;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f1f3f5;
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 7px 8px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 4px 6px;
            vertical-align: middle;
        }

        .table tbody tr.dup-row {
            background: #fff3cd;
        }

        .table tfoot td {
            background: #f1f3f5;
            font-weight: 700;
            font-size: 12px;
            padding: 7px 8px;
        }

        .table .form-control,
        .table .form-select {
            font-size: 11px;
            padding: 3px 6px;
        }

        .table tbody tr.selected-style {
            background: #dbeafe;
        }

        /* LOV list rows */
        .lov-row {
            display: grid;
            grid-template-columns: 140px 1fr 100px;
            gap: 10px;
            padding: 9px 16px;
            border-bottom: 1px solid #f1f3f5;
            cursor: pointer;
            font-size: 12px;
            transition: background .1s;
        }

        .lov-row:hover {
            background: #e7f1ff;
        }

        .lov-id {
            font-family: monospace;
            color: #0d6efd;
            font-size: 11px;
        }

        .lov-qty {
            color: #6c757d;
            text-align: right;
            font-family: monospace;
            font-size: 11px;
        }

        /* Value cells */
        .val-cell {
            color: #0d6efd !important;
            font-family: monospace;
            font-weight: 600;
        }

        .bdt-cell {
            color: #198754 !important;
            font-family: monospace;
            font-weight: 600;
        }

        /* Search bar */
        #searchBar {
            display: none;
        }

        #searchBar.visible {
            display: block;
        }

        /* Offcanvas */
        .offcanvas-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>

<body>

    @include('topbar.sidebar')


    {{-- ── Page content ── --}}
    <div class="content-wrapper" style="margin-left: 2vw; padding: 2vw;">
        {{-- ── Navbar ── --}}
        <nav class="navbar navbar-expand-lg sticky-top shadow-sm px-3 py-2">
            <div class="d-flex align-items-center gap-3 me-auto">
                <span class="navbar-brand mb-0 text-primary">
                    <i class="bi bi-file-earmark-text me-1"></i>Purchase Order Entry
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                    style="font-size:10px">CANVAS_MAIN</span>
            </div>
            <div class="d-flex gap-2">
                <button onclick="deletePO()" class="btn btn-danger btn-sm"> <i class="bi bi-trash"></i> Delete Full Po
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleSearch()">
                    <i class="bi bi-search me-1"></i>Search
                </button>
                <button class="btn btn-warning btn-sm" onclick="bsPoCheck.show()">
                    <i class="bi bi-clipboard-check me-1"></i>PO Check
                </button>
                <button class="btn btn-outline-info btn-sm" onclick="showSummary()">
                    <i class="bi bi-file-text me-1"></i>Summary
                </button>
                <button class="btn btn-primary btn-sm" id="saveBtn" onclick="saveOrder()">
                    <i class="bi bi-check-lg me-1"></i>Save
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise"></i></i>Exit
                </button>
            </div>
        </nav>
        <div>
            @yield('content')
        </div>
    </div>

    {{-- ── PO Check Offcanvas ── --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="poCheckOffcanvas" style="width:580px">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title">
                <i class="bi bi-clipboard-check text-warning me-2"></i>PO Check — XL_DATA_UPLOAD
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p class="text-muted small mb-3">
                Replaces: <code>show_view('PO_CHECK')</code> trigger.<br>
                Validates uploaded PO data from XL_DATA_UPLOAD before import.
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>PO Number</th>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Curr.</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-primary fw-bold font-monospace" style="font-size:11px">PO-2026-001</td>
                            <td>ACC-0041</td>
                            <td>Zipper 5cm Metal</td>
                            <td>5,000</td>
                            <td>USD</td>
                            <td>Standard</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-bold font-monospace" style="font-size:11px">PO-2026-001</td>
                            <td>ACC-0042</td>
                            <td>Button 15mm Pearl</td>
                            <td>12,000</td>
                            <td>USD</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-bold font-monospace" style="font-size:11px">PO-2026-002</td>
                            <td>ACC-0055</td>
                            <td>Elastic Band 2cm</td>
                            <td>800 m</td>
                            <td>USD</td>
                            <td>Roll</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-bold font-monospace" style="font-size:11px">PO-2026-002</td>
                            <td>ACC-0088</td>
                            <td>Sewing Thread</td>
                            <td>300</td>
                            <td>USD</td>
                            <td>Cone</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-primary btn-sm" data-bs-dismiss="offcanvas"
                    onclick="showToast('PO data accepted — navigating to PUR_ORDER_MASTER', 'success')">
                    <i class="bi bi-check-lg me-1"></i>Accept &amp; Import
                </button>
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </div>
    </div>

    {{-- ── LOV Modal ── --}}
    <div class="modal fade" id="lovModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="lovTitle">List of Values</h6>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="p-3 border-bottom">
                    <input type="text" class="form-control form-control-sm" id="lovSearch" placeholder="Filter..."
                        oninput="filterLov(this.value)">
                </div>
                <div class="modal-body p-0" style="max-height:400px;overflow-y:auto">
                    <div id="lovList"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Toast ── --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
        <div id="liveToast" class="toast align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">Message</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    {{-- ── Scripts ── --}}

    <script src="{{ URL::asset('mainjs/jquery.min.js') }}"></script>

    <script src="{{ URL::asset('mainjs/select2.min.js') }}"></script>

    <!-- 5. Other scripts -->
    <script src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/sweetalert2.all.min.js') }}"></script>

    <script>
        // Bootstrap instances
        let bsPoCheck, bsLovModal, bsToast;

        document.addEventListener('DOMContentLoaded', () => {
            bsPoCheck = new bootstrap.Offcanvas(document.getElementById('poCheckOffcanvas'));
            bsLovModal = new bootstrap.Modal(document.getElementById('lovModal'));
            bsToast = new bootstrap.Toast(document.getElementById('liveToast'), {
                delay: 3500
            });
        });

        function showToast(message, type = 'success') {
            const el = document.getElementById('liveToast');
            const body = document.getElementById('toastMessage');
            el.className = `toast align-items-center border-0 text-bg-${type}`;
            body.textContent = message;
            bsToast.show();
        }

        function filterLov(q) {
            document.querySelectorAll('#lovList .lov-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
            });
        }

        function showSummary() {
            const pk = document.getElementById('PUR_ORDER_PK')?.value || '—';
            showToast(`Summary for ${pk} — ADD_PARAMETER + RUN_PRODUCT`, 'info');
        }
    </script>

    @stack('scripts')
</body>

</html>
