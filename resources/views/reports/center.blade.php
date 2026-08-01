@extends('layouts.app')

@section('title', 'HRM Report Center')

@push('styles')
    <style>
        :root {
            --hrm-primary: #003366;
            --hrm-secondary: #0055a5;
            --hrm-accent: #e8a020;
            --hrm-light: #f4f7fb;
            --hrm-border: #d0dce8;
        }

        body {
            background-color: var(--hrm-light);
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.875rem;
        }

        /* ── Navbar ── */
        .navbar-hrm {
            background: linear-gradient(135deg, var(--hrm-primary) 0%, var(--hrm-secondary) 100%);
            border-bottom: 3px solid var(--hrm-accent);
            padding: 0.5rem 1.5rem;
        }

        .navbar-hrm .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .3px;
        }

        .navbar-hrm .nav-badge {
            background: var(--hrm-accent);
            color: #fff;
            font-size: .7rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* ── Page Header ── */
        .page-header {
            background: #fff;
            border-bottom: 1px solid var(--hrm-border);
            padding: .75rem 1.5rem;
        }

        .page-header h5 {
            color: var(--hrm-primary);
            font-weight: 700;
            margin: 0;
        }

        .breadcrumb {
            margin: 0;
            font-size: .78rem;
        }

        .breadcrumb-item.active {
            color: var(--hrm-secondary);
        }

        /* ── Card ── */
        .card {
            border: 1px solid var(--hrm-border);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 51, 102, .07);
        }

        .card-header-hrm {
            background: linear-gradient(135deg, var(--hrm-primary) 0%, var(--hrm-secondary) 100%);
            color: #fff;
            border-radius: 7px 7px 0 0 !important;
            padding: .65rem 1rem;
            font-weight: 600;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ── Form Controls ── */
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: .25rem;
            font-size: .8rem;
        }

        .form-control,
        .form-select {
            border-color: var(--hrm-border);
            font-size: .85rem;
            border-radius: 5px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--hrm-secondary);
            box-shadow: 0 0 0 .18rem rgba(0, 85, 165, .18);
        }

        .input-group-text {
            background: var(--hrm-light);
            border-color: var(--hrm-border);
            color: var(--hrm-secondary);
            font-size: .85rem;
        }

        /* ── LOV Select2 fix ── */
        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .select2-container .select2-selection--single {
            height: 36px;
            border-radius: 0 5px 5px 0 !important;
            border-left: 0 !important;
            border-color: var(--hrm-border) !important;
            display: flex;
            align-items: center;
        }

        .input-group .select2-container .select2-selection__rendered {
            line-height: normal;
            font-size: .85rem;
        }

        .input-group .select2-container .select2-selection__arrow {
            height: 34px;
        }

        /* ── Flatpickr ── */
        .flatpickr-input {
            background: #fff !important;
        }

        .flatpickr-calendar {
            font-size: .82rem;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: var(--hrm-secondary);
            border-color: var(--hrm-secondary);
        }

        /* ── Format selector pills ── */
        .format-pill-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .format-pill-group input[type="radio"] {
            display: none;
        }

        .format-pill-group label {
            cursor: pointer;
            padding: 4px 14px;
            border: 2px solid var(--hrm-border);
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            color: #6c757d;
            transition: all .18s;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
            user-select: none;
        }

        .format-pill-group input[type="radio"]:checked+label {
            border-color: var(--hrm-primary);
            background: var(--hrm-primary);
            color: var(--hrm-accent);
        }

        .format-pill-group label:hover {
            border-color: var(--hrm-primary);
            color: var(--hrm-primary);
        }

        /* ── Engine badge ── */
        .engine-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .engine-badge.jasper {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .engine-badge.oracle {
            background: #fff3e0;
            color: #c75000;
            border: 1px solid #ffcc80;
        }

        /* ── Parameter section ── */
        #param-section {
            border-top: 2px dashed var(--hrm-border);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .param-section-title {
            font-size: .78rem;
            font-weight: 700;
            color: var(--hrm-secondary);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: .75rem;
        }

        .param-row {
            animation: fadeSlide .25s ease forwards;
            opacity: 0;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Buttons ── */
        .btn-run {
            background: linear-gradient(135deg, var(--hrm-primary), var(--hrm-secondary));
            color: #fff;
            border: none;
            font-weight: 600;
            padding: .45rem 1.5rem;
            font-size: .875rem;
            border-radius: 5px;
            transition: opacity .2s;
        }

        .btn-run:hover:not(:disabled) {
            opacity: .88;
            color: #fff;
        }

        .btn-run:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .btn-reset {
            border: 1px solid var(--hrm-border);
            color: #555;
            font-size: .875rem;
        }

        /* ── Alerts ── */
        .alert-hrm-error {
            background: #fff3f3;
            border-left: 4px solid #dc3545;
            color: #842029;
            font-size: .82rem;
            border-radius: 0 5px 5px 0;
            padding: .5rem .75rem;
        }

        .alert-hrm-success {
            background: #f0fff4;
            border-left: 4px solid #198754;
            color: #155724;
            font-size: .82rem;
            border-radius: 0 5px 5px 0;
            padding: .5rem .75rem;
        }

        /* ── Skeleton ── */
        .skeleton {
            background: linear-gradient(90deg, #e8ecf0 25%, #d0dae4 50%, #e8ecf0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 4px;
            height: 34px;
            margin-bottom: .75rem;
        }

        @keyframes shimmer {
            from {
                background-position: 200% 0
            }

            to {
                background-position: -200% 0
            }
        }

        /* ── Info bar ── */
        .report-info-bar {
            background: #eef4fc;
            border: 1px solid var(--hrm-border);
            border-radius: 5px;
            padding: .5rem .85rem;
            font-size: .78rem;
            color: var(--hrm-secondary);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ── Footer ── */
        .footer-bar {
            background: var(--hrm-primary);
            color: rgba(255, 255, 255, .55);
            text-align: center;
            font-size: .72rem;
            padding: .5rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .date-display-txt {
            font-size: .72rem;
            color: var(--hrm-secondary);
            margin-top: 2px;
            display: block;
        }
    </style>
@endpush

@section('content')

    {{-- ── Navbar ── --}}
    <nav class="navbar navbar-hrm">
        <span class="navbar-brand">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>Four Design (Pvt.) Ltd.
        </span>
        <span class="nav-badge"><i class="bi bi-person-badge me-1"></i>HRM Module</span>
    </nav>

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-file-earmark-bar-graph me-2" style="color:var(--hrm-accent)"></i>Report Center</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" style="color:var(--hrm-secondary)">HRM</a></li>
                    <li class="breadcrumb-item active">Report Center</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ── Main Content ── --}}
    <div class="container-fluid px-4 py-3" style="max-width:960px; padding-bottom:3rem;">

        @if (session('error'))
            <div class="alert-hrm-error mb-3">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header-hrm">
                <i class="bi bi-sliders"></i>Report Selection &amp; Parameters
                {{-- Engine badge — populated by JS after report is chosen --}}
                <span id="engine-badge" class="engine-badge ms-auto d-none"></span>
            </div>

            <div class="card-body p-3">

                {{-- ── Row 1: Report dropdown + Reset ── --}}
                <div class="row g-2 align-items-end mb-2">
                    <div class="col-md-9">
                        <label class="form-label" for="report_no">
                            <i class="bi bi-list-ul me-1" style="color:var(--hrm-secondary)"></i>Select Report
                        </label>
                        <select class="form-select" id="report_no" name="report_no">
                            <option value="">-- Select a Report --</option>
                            @foreach ($reports as $report)
                                <option value="{{ $report->report_id }}">{{ $report->report_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary btn-reset w-100" id="btn-reset" type="button">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>

                {{-- ── Row 2: Output Format pills (always visible) ── --}}
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <label class="form-label mb-0">
                            <i class="bi bi-file-earmark-arrow-down me-1" style="color:var(--hrm-secondary)"></i>
                            Output Format
                        </label>
                    </div>
                    <div class="col">
                        <div class="format-pill-group">
                            <input type="radio" id="fmt_pdf" name="_repFormat" value="pdf" checked>
                            <label for="fmt_pdf"><i class="bi bi-file-earmark-pdf-fill text-danger"></i> PDF</label>

                            <input type="radio" id="fmt_xls" name="_repFormat" value="xls">
                            <label for="fmt_xls"><i class="bi bi-file-earmark-excel-fill text-success"></i> XLSX</label>

                            <input type="radio" id="fmt_xml" name="_repFormat" value="xml">
                            <label for="fmt_xml"><i class="bi bi-file-earmark-code-fill text-primary"></i> XML</label>
                        </div>
                    </div>
                </div>

                {{-- ── Report Info Bar ── --}}
                <div class="report-info-bar d-none mt-2" id="report-info-bar">
                    <i class="bi bi-file-earmark-pdf-fill text-danger" id="info-icon"></i>
                    <span id="report-file-label"></span>
                    <span class="ms-auto text-muted" id="param-count-label"></span>
                </div>

                {{-- ── Dynamic Parameter Fields ── --}}
                <div id="param-section" class="d-none">
                    <div class="param-section-title mt-3">
                        <i class="bi bi-funnel me-1"></i>Report Parameters
                    </div>
                    <div id="loading-skeleton" class="d-none">
                        <div class="skeleton"></div>
                        <div class="skeleton" style="width:70%"></div>
                        <div class="skeleton" style="width:85%"></div>
                    </div>
                    <div id="dynamic-params" class="row g-3"></div>
                </div>

                {{-- ── Action Bar ── --}}
                <div class="d-flex gap-2 align-items-center mt-3 pt-3 border-top" id="action-bar"
                    style="display:none!important">
                    <button class="btn btn-run" id="btn-run" type="button">
                        <i class="bi bi-play-fill me-1"></i>Run Report
                    </button>
                    <div id="status-msg" class="ms-2"></div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card --}}
    </div>

    <div class="footer-bar">
        HRM Report Center &nbsp;|&nbsp;
        Oracle: {{ config('hrm.report_server_url') }} &nbsp;|&nbsp;
        JasperReports: http://192.168.210.205:8080
    </div>

@endsection

@push('scripts')
    <script>
        // ── Constants ─────────────────────────────────────────────────────────────────
        // No direct Jasper URL needed — both engines go through Laravel proxy
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const runReportUrl = @json(route('reports.run'));

        // ── DOM refs ──────────────────────────────────────────────────────────────────
        const reportSel = document.getElementById('report_no');
        const paramSec = document.getElementById('param-section');
        const dynDiv = document.getElementById('dynamic-params');
        const actionBar = document.getElementById('action-bar');
        const btnRun = document.getElementById('btn-run');
        const btnReset = document.getElementById('btn-reset');
        const statusMsg = document.getElementById('status-msg');
        const infoBar = document.getElementById('report-info-bar');
        const fileLabel = document.getElementById('report-file-label');
        const cntLabel = document.getElementById('param-count-label');
        const skeleton = document.getElementById('loading-skeleton');
        const engineBadge = document.getElementById('engine-badge');

        let flatpickrInstances = [];

        // Holds meta for the selected report (populated on report change)
        let currentReport = {
            js_report: null,
            report_file_name: null
        };

        // ── Init Select2 on report dropdown ──────────────────────────────────────────
        $(reportSel).select2({
            theme: 'bootstrap-5',
            placeholder: '-- Select a Report --',
            allowClear: true,
            width: '100%'
        });

        // ── WHEN-LIST-CHANGED ─────────────────────────────────────────────────────────
        $(reportSel).on('change', async function() {
            const reportId = this.value;
            clearAll();
            hideEngineBadge();
            if (!reportId) return;

            skeleton.classList.remove('d-none');
            paramSec.classList.remove('d-none');

            try {
                const res = await fetch(`/hrm/reports/${reportId}/parameters`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();

                skeleton.classList.add('d-none');

                // Store report meta for use at run time
                currentReport.js_report = data.js_report || null;
                currentReport.report_file_name = data.report_file_name || null;

                // Engine badge
                showEngineBadge(currentReport.js_report);

                // Info bar
                const engineLabel = currentReport.js_report ? 'JasperReports' : 'Oracle Reports';
                fileLabel.textContent = (data.report_file_name || '—') + '  [' + engineLabel + ']';
                cntLabel.textContent = data.parameters.length + ' parameter(s)';
                infoBar.classList.remove('d-none');

                // Render params
                if (!data.parameters.length) {
                    dynDiv.innerHTML = `<div class="col-12 text-muted small">
                <i class="bi bi-info-circle me-1"></i>No parameters required for this report.</div>`;
                } else {
                    dynDiv.innerHTML = data.parameters.map((p, i) => buildField(p, i)).join('');
                    initDynamicControls();
                }

                actionBar.style.removeProperty('display');
                clearStatus();
                focusFirstParameter();

            } catch (e) {
                skeleton.classList.add('d-none');
                showError('HRM-1006: ' + e.message);
            }
        });

        // ── Engine badge ──────────────────────────────────────────────────────────────
        function showEngineBadge(jsReport) {
            if (jsReport) {
                engineBadge.className = 'engine-badge jasper ms-auto';
                engineBadge.innerHTML = '<i class="bi bi-lightning-charge-fill"></i> JasperReports';
            } else {
                engineBadge.className = 'engine-badge oracle ms-auto';
                engineBadge.innerHTML = '<i class="bi bi-database-fill"></i> Oracle Reports';
            }
            engineBadge.classList.remove('d-none');
        }

        function hideEngineBadge() {
            engineBadge.classList.add('d-none');
        }

        // ── RUN REPORT button ─────────────────────────────────────────────────────────
        // Both Jasper and Oracle go through the SAME Laravel proxy (POST /reports/run).
        // The controller decides the engine based on js_report being set or null.
        btnRun.addEventListener('click', async function() {
            const reportId = reportSel.value;
            if (!reportId) return;

            const repFormat = document.querySelector('input[name="_repFormat"]:checked')?.value || 'pdf';

            // Collect active parameter values
            // Date fields → use data-oracle-value (DD-MM-YYYY from flatpickr)
            // Controller handles DD-MM-YYYY→DD-MON-YYYY for Oracle, passes as-is for Jasper
            const parameters = {};
            dynDiv.querySelectorAll('[data-param-key]').forEach(el => {
                if (el.classList.contains('hrm-date')) autoFormatDate(el);

                if (el.tagName === 'INPUT' && el.dataset.oracleValue !== undefined) {
                    if (el.dataset.oracleValue !== '') parameters[el.dataset.paramKey] = el.dataset
                        .oracleValue;
                } else if (el.value !== '') {
                    parameters[el.dataset.paramKey] = el.value;
                }
            });

            // Single proxy call — engine decision is made server-side
            await runReportProxy(reportId, repFormat, parameters);
        });

        // ── Single proxy runner — used for BOTH Jasper and Oracle ────────────────────
        async function runReportProxy(reportId, repFormat, parameters) {
            btnRun.disabled = true;
            const fmtLabel = {
                pdf: 'PDF',
                xls: 'Excel',
                xml: 'XML'
            } [repFormat] || 'PDF';
            const engine = currentReport.js_report ? 'JasperReports' : 'Oracle Reports';
            btnRun.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${engine} — ${fmtLabel}…`;
            clearStatus();

            try {
                const res = await fetch(runReportUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        report_id: reportId,
                        _repFormat: repFormat,
                        parameters
                    }),
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => ({
                        error: 'Server error'
                    }));
                    throw new Error(err.error || `HTTP ${res.status}`);
                }

                // PDF → open in new tab; XLS/XML → trigger file download
                const disposition = res.headers.get('Content-Disposition') || '';
                const fnMatch = disposition.match(/filename="?([^"]+)"?/);
                const filename = fnMatch ? fnMatch[1] : ('report.' + repFormat);
                const blob = await res.blob();
                const blobUrl = URL.createObjectURL(blob);

                if (repFormat === 'pdf') {
                    window.open(blobUrl, '_blank');
                } else {
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(() => URL.revokeObjectURL(blobUrl), 3000);
                }

                showSuccess('<i class="bi bi-check-circle-fill me-1"></i>Report generated successfully.');

            } catch (e) {
                showError('<i class="bi bi-exclamation-triangle-fill me-1"></i>HRM-9999: ' + e.message);
            } finally {
                btnRun.disabled = false;
                btnRun.innerHTML = '<i class="bi bi-play-fill me-1"></i>Run Report';
            }
        }

        // ── Build HTML for one parameter field ───────────────────────────────────────
        function buildField(p, index) {
            const id = 'prm_' + (p.block_item || index);
            const key = p.block_value_item || p.block_item;
            const delay = (index * 60) + 'ms';
            let input = '';

            if (p.input_type === 'lov') {
                const options = (p.lov_options || [])
                    .map(o => `<option value="${escHtml(o.value)}">${escHtml(o.label)}</option>`)
                    .join('');
                input = `
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chevron-expand"></i></span>
                <select class="form-select lov-select2" id="${id}" data-param-key="${key}">
                    <option value="" selected>-- Select --</option>${options}
                </select>
            </div>`;

            } else if (p.input_type === 'date') {
                input = `
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                <input type="text" class="form-control hrm-date" id="${id}"
                       data-param-key="${key}" data-oracle-value=""
                       placeholder="DD-MM-YYYY" autocomplete="off">
            </div>
            <small class="date-display-txt">Format: DD-MM-YYYY</small>`;

            } else if (p.input_type === 'number') {
                input = `
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                <input type="number" class="form-control" id="${id}"
                       data-param-key="${key}" placeholder="Enter ${escHtml(p.label)}">
            </div>`;
            } else {
                input = `
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-input-cursor-text"></i></span>
                <input type="text" class="form-control" id="${id}"
                       data-param-key="${key}" placeholder="Enter ${escHtml(p.label)}">
            </div>`;
            }

            return `
        <div class="col-md-6 param-row" style="animation-delay:${delay}">
            <label class="form-label" for="${id}">${escHtml(p.label || p.block_item)}</label>
            ${input}
        </div>`;
        }

        // ── Init controls after dynDiv is populated ───────────────────────────────────
        function initDynamicControls() {
            flatpickrInstances.forEach(fp => fp.destroy());
            flatpickrInstances = [];

            $('.lov-select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#dynamic-params')
            });

            document.querySelectorAll('.hrm-date').forEach(el => {
                const fp = flatpickr(el, {
                    dateFormat: 'd-m-Y',
                    allowInput: true,
                    disableMobile: true,
                    parseDate: function(dateStr) {
                        if (/^\d{6}$/.test(dateStr)) {
                            const dd = dateStr.substring(0, 2),
                                mm = dateStr.substring(2, 4),
                                yy = dateStr.substring(4, 6);
                            return new Date((parseInt(yy, 10) <= 30 ? '20' : '19') + yy, mm - 1, dd);
                        }
                        if (/^\d{8}$/.test(dateStr)) {
                            return new Date(dateStr.substring(4, 8), dateStr.substring(2, 4) - 1,
                                dateStr.substring(0, 2));
                        }
                        const p = dateStr.split('-');
                        return p.length === 3 ? new Date(p[2], p[1] - 1, p[0]) : null;
                    },
                    formatDate: function(date) {
                        return String(date.getDate()).padStart(2, '0') + '-' +
                            String(date.getMonth() + 1).padStart(2, '0') + '-' +
                            date.getFullYear();
                    },
                    onValueUpdate: function(_, dateStr) {
                        el.dataset.oracleValue = dateStr;
                    }
                });
                flatpickrInstances.push(fp);
                el.addEventListener('blur', () => autoFormatDate(el));
            });

            bindEnterNavigation();
        }

        // ── Reset ─────────────────────────────────────────────────────────────────────
        btnReset.addEventListener('click', function() {
            $(reportSel).val(null).trigger('change');
            clearAll();
            infoBar.classList.add('d-none');
            paramSec.classList.add('d-none');
            actionBar.style.setProperty('display', 'none', 'important');
            hideEngineBadge();
        });

        // ── Focus / keyboard helpers ──────────────────────────────────────────────────
        function getFocusableFields() {
            return Array.from(dynDiv.querySelectorAll('[data-param-key]'))
                .filter(el => !el.disabled && (el.offsetParent !== null || $(el).hasClass('select2-hidden-accessible')));
        }

        function focusControl(el) {
            if (!el) return;
            if ($(el).hasClass('select2-hidden-accessible')) {
                $(el).next('.select2-container').find('.select2-selection').trigger('focus');
                return;
            }
            el.focus();
            if (typeof el.select === 'function') el.select();
        }

        function focusFirstParameter() {
            const f = getFocusableFields();
            setTimeout(() => {
                if (f.length) focusControl(f[0]);
                else btnRun.focus();
            }, 50);
        }

        function bindEnterNavigation() {
            getFocusableFields().forEach(el => {
                el.addEventListener('keydown', function(event) {
                    if (event.key !== 'Enter') return;
                    event.preventDefault();
                    if (el.classList.contains('hrm-date')) autoFormatDate(el);
                    const f = getFocusableFields(),
                        next = f[f.indexOf(el) + 1];
                    if (next) focusControl(next);
                    else btnRun.focus();
                });
            });
            $('.lov-select2').on('select2:select', function() {
                const f = getFocusableFields(),
                    next = f[f.indexOf(this) + 1];
                setTimeout(() => {
                    if (next) focusControl(next);
                    else btnRun.focus();
                }, 0);
            });
        }

        function autoFormatDate(el) {
            const raw = el.value.replace(/\D/g, '');
            if (!raw) return;
            let day, mon, yr;
            if (raw.length === 6) {
                day = raw.substr(0, 2);
                mon = raw.substr(2, 2);
                yr = (parseInt(raw.substr(4, 2), 10) <= 30 ? '20' : '19') + raw.substr(4, 2);
            } else if (raw.length === 8) {
                day = raw.substr(0, 2);
                mon = raw.substr(2, 2);
                yr = raw.substr(4, 4);
            } else return;
            if (parseInt(day, 10) < 1 || parseInt(day, 10) > 31 || parseInt(mon, 10) < 1 || parseInt(mon, 10) > 12) return;
            const formatted = day + '-' + mon + '-' + yr;
            el.value = formatted;
            el.dataset.oracleValue = formatted;
            if (el._flatpickr) el._flatpickr.setDate(formatted, false, 'd-m-Y');
        }
        btnRun.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnRun.click();
            }
        });

        // ── Generic utils ─────────────────────────────────────────────────────────────
        function escHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function clearAll() {
            dynDiv.innerHTML = '';
            flatpickrInstances.forEach(fp => fp.destroy());
            flatpickrInstances = [];
            $('.lov-select2').each(function() {
                if ($(this).data('select2')) $(this).select2('destroy');
            });
            currentReport = {
                js_report: null,
                report_file_name: null
            };
            clearStatus();
        }

        function clearStatus() {
            statusMsg.innerHTML = '';
        }

        function showError(msg) {
            statusMsg.innerHTML = `<div class="alert-hrm-error">${msg}</div>`;
        }

        function showSuccess(msg) {
            statusMsg.innerHTML = `<div class="alert-hrm-success">${msg}</div>`;
        }
    </script>
@endpush
