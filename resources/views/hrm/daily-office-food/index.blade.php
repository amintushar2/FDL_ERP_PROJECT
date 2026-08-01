@extends('layouts.app') {{-- swap to your existing HRM master layout if the name differs --}}

@section('title', 'Daily Food Update')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <style>
        /* ---- FDL ERP palette (kept consistent with other HRM modules) ---- */
        :root {
            --fdl-navy: #14304d;
            --fdl-navy-light: #1f4a73;
            --fdl-accent: #2f8fd1;
            --fdl-accent-soft: #eaf3fc;
            --fdl-success: #1f9d55;
            --fdl-success-soft: #e8f8ee;
            --fdl-danger: #d64545;
            --fdl-bg: #f3f6fa;
            --fdl-border: #e2e8f0;
            --fdl-text-muted: #6b7785;
        }

        body {
            background: var(--fdl-bg);
        }

        .dof-topbar {
            background: linear-gradient(135deg, var(--fdl-navy) 0%, var(--fdl-navy-light) 100%);
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: .5rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .dof-topbar h1 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
        }

        .dof-topbar small {
            color: #cfe0f0;
            display: block;
            font-size: .75rem;
            letter-spacing: .02em;
        }

        .dof-card {
            background: #fff;
            border: 1px solid var(--fdl-border);
            border-radius: .5rem;
            box-shadow: 0 1px 2px rgba(20, 48, 77, .05);
        }

        .dof-card .card-header {
            background: #fff;
            border-bottom: 1px solid var(--fdl-border);
            font-weight: 600;
            color: var(--fdl-navy);
        }

        .dof-toolbar .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--fdl-text-muted);
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .btn-fdl-primary {
            background: var(--fdl-accent) !important;
            border-color: var(--fdl-accent) !important;
            color: #fff !important;
        }

        .btn-fdl-primary:hover {
            background: #2679ba !important;
            border-color: #2679ba !important;
            color: #fff !important;
        }

        .btn-fdl-success {
            background: var(--fdl-success) !important;
            border-color: var(--fdl-success) !important;
            color: #fff !important;
        }

        .btn-fdl-success:hover {
            background: #18803f !important;
            border-color: #18803f !important;
            color: #fff !important;
        }

        .btn-fdl-success:disabled {
            background: #9bcfae !important;
            border-color: #9bcfae !important;
            color: #fff !important;
            opacity: 1 !important;
        }

        .btn-fdl-exit {
            background: #fff !important;
            border-color: var(--fdl-border) !important;
            color: var(--fdl-danger) !important;
        }

        .btn-fdl-exit:hover {
            background: #fdf0f0 !important;
            border-color: var(--fdl-danger) !important;
            color: var(--fdl-danger) !important;
        }

        .btn-fdl-delete {
            background: #fff !important;
            border-color: var(--fdl-danger) !important;
            color: var(--fdl-danger) !important;
        }

        .btn-fdl-delete:hover {
            background: var(--fdl-danger) !important;
            border-color: var(--fdl-danger) !important;
            color: #fff !important;
        }

        .dof-table thead th {
            background: var(--fdl-accent-soft);
            color: var(--fdl-navy);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            font-weight: 700;
            border-bottom: 2px solid var(--fdl-accent);
            white-space: nowrap;
        }

        .dof-table td {
            vertical-align: middle;
        }

        .dof-table tbody tr:hover {
            background: #fafcff;
        }

        .dof-badge-source {
            font-size: .7rem;
            font-weight: 600;
            padding: .3rem .6rem;
            border-radius: 1rem;
        }

        .dof-badge-saved {
            background: var(--fdl-success-soft);
            color: var(--fdl-success);
        }

        .dof-badge-draft {
            background: #fff4e0;
            color: #b87703;
        }

        .form-check-input.dof-food-toggle {
            width: 2.5em;
            height: 1.3em;
            cursor: pointer;
        }

        .dof-empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: var(--fdl-text-muted);
        }

        /* dofAlert / delPreviewBox / delErrorBox visibility is controlled ONLY
               via inline style.display from JS (see script below) — deliberately
               NOT using Bootstrap's d-flex / d-none utility classes here, because
               those carry `!important` and permanently override a plain inline
               style.display set from JS. That mismatch was the "alert always
               showing" bug: `.d-flex{display:flex!important}` on the alert beat
               any style.display='none' the JS tried to apply. */
        #dofAlert,
        #delPreviewBox,
        #delErrorBox {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">

        <div class="dof-topbar">
            <div>
                <h1><i class="bi bi-cup-hot me-2"></i>Daily Food Update</h1>
                <small>FOUR DESIGN (PVT.) LTD. &mdash; HRM / Attendance</small>
            </div>
            <span id="dofSourceBadge" class="dof-badge-source d-none"></span>
        </div>

        <div id="dofAlert" class="alert alert-success" role="alert" style="display:none; align-items:center; gap:.5rem;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <span id="dofAlertText"></span>
        </div>

        <div class="dof-card card mb-3">
            <div class="card-body">
                <div class="row dof-toolbar align-items-end g-3">
                    <div class="col-12 col-sm-4 col-md-3">
                        <label class="form-label" for="foodDate">Date</label>
                        <input type="text" id="foodDate" class="form-control" placeholder="DD-Mon-YYYY"
                            autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-8 col-md-9 d-flex gap-2 flex-wrap">
                        <button id="btnLoad" class="btn btn-fdl-primary px-3">
                            <i class="bi bi-cloud-download me-1"></i> Load
                        </button>
                        <button id="btnSave" class="btn btn-fdl-success px-3" disabled>
                            <i class="bi bi-save me-1"></i> Save
                        </button>
                        <button id="btnOpenDelete" class="btn btn-fdl-delete px-3" data-bs-toggle="modal"
                            data-bs-target="#deleteRangeModal">
                            <i class="bi bi-trash3 me-1"></i> Delete
                        </button>
                        <button id="btnExit" class="btn btn-fdl-exit px-3 ms-auto">
                            <i class="bi bi-box-arrow-right me-1"></i> Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="dof-card card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-1"></i> Employees</span>
                <span class="text-muted small" id="dofRowCount"></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dof-table mb-0" id="dofTable">
                        <thead>
                            <tr>
                                <th style="width:7%">Empno</th>
                                <th>Emp Name</th>
                                <th style="width:12%">Date</th>
                                <th style="width:12%">Status</th>
                                <th style="width:10%" class="text-center">Food</th>
                            </tr>
                        </thead>
                        <tbody id="dofTableBody">
                            {{-- rows injected by JS after Load --}}
                        </tbody>
                    </table>
                </div>
                <div id="dofEmptyState" class="dof-empty-state">
                    <i class="bi bi-calendar3 fs-2 d-block mb-2"></i>
                    Choose a date and click <strong>Load</strong> to bring in employees eligible for office food.
                </div>
            </div>
        </div>

        {{-- Delete by date range modal --}}
        <div class="modal fade" id="deleteRangeModal" tabindex="-1" aria-labelledby="deleteRangeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRangeModalLabel">
                            <i class="bi bi-trash3 text-danger me-1"></i> Delete food records
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Choose a date range. All <code>DAILLY_OOFICE_FOOD</code> records with a date inside this
                            range will be permanently removed.
                        </p>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label" for="delDateFrom">From date</label>
                                <input type="text" id="delDateFrom" class="form-control" placeholder="DD-Mon-YYYY"
                                    autocomplete="off">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="delDateTo">To date</label>
                                <input type="text" id="delDateTo" class="form-control" placeholder="DD-Mon-YYYY"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div id="delPreviewBox" class="alert alert-warning mt-3 mb-0" style="display:none;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <span id="delPreviewText"></span>
                        </div>
                        <div id="delErrorBox" class="alert alert-danger mt-3 mb-0" style="display:none;">
                            <span id="delErrorText"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btnCheckRange" class="btn btn-fdl-primary">
                            <i class="bi bi-search me-1"></i> Check
                        </button>
                        <button type="button" id="btnConfirmDelete" class="btn btn-danger" disabled>
                            <i class="bi bi-trash3 me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script>
        (function() {
            // Quick-entry date parser used across FDL ERP forms: typing digits only
            // (e.g. "300626" or "30062026") and tabbing/blurring out snaps to
            // DD-Mon-YYYY. Kept verbatim from the pattern used elsewhere so the
            // behavior matches other modules exactly.
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

            // Same digit rules as parseOracleDate(), but returns a JS Date object
            // instead of a formatted string — this is what Flatpickr needs
            // internally when a user types "300626" straight into the field.
            function parseOracleDateToJsDate(v) {
                if (!v) return null;
                const s = String(v).replace(/\D/g, '');
                let day, month, year;

                if (s.length === 6) {
                    day = s.substring(0, 2);
                    month = s.substring(2, 4);
                    let yy = parseInt(s.substring(4, 6), 10);
                    year = yy <= 30 ? 2000 + yy : 1900 + yy;
                } else if (s.length === 8) {
                    day = s.substring(0, 2);
                    month = s.substring(2, 4);
                    year = parseInt(s.substring(4, 8), 10);
                } else {
                    return null;
                }

                const d = parseInt(day, 10);
                const m = parseInt(month, 10);

                if (
                    isNaN(d) || isNaN(m) || isNaN(year) ||
                    d < 1 || d > 31 || m < 1 || m > 12 ||
                    year < 1900 || year > 2099
                ) {
                    return null;
                }

                return new Date(year, m - 1, d);
            }

            // altInput keeps the real underlying .value as Y-m-d (what the
            // controller expects) while showing DD-Mon-YYYY to the user. parseDate
            // is overridden so typing raw digits (e.g. "300626") and blurring
            // snaps to the same DD-Mon-YYYY your other FDL ERP forms use, on top
            // of Flatpickr's normal calendar-click and default parsing.
            const fpOptions = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-M-Y',
                allowInput: true,
                parseDate: (datestr, format) => {
                    const quickParsed = parseOracleDateToJsDate(datestr);
                    if (quickParsed) return quickParsed;
                    return flatpickr.parseDate(datestr, format);
                },
            };
            const fpFoodDate = flatpickr('#foodDate', {
                ...fpOptions,
                defaultDate: 'today'
            });
            const fpDelFrom = flatpickr('#delDateFrom', fpOptions);
            const fpDelTo = flatpickr('#delDateTo', fpOptions);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const loadUrl = "{{ route('hrm.daily-office-food.load') }}";
            const saveUrl = "{{ route('hrm.daily-office-food.save') }}";
            const deletePreviewUrl = "{{ route('hrm.daily-office-food.delete-preview') }}";
            const deleteUrl = "{{ route('hrm.daily-office-food.destroy') }}";

            const dateInput = document.getElementById('foodDate');
            const btnLoad = document.getElementById('btnLoad');
            const btnSave = document.getElementById('btnSave');
            const btnExit = document.getElementById('btnExit');
            const tableBody = document.getElementById('dofTableBody');
            const emptyState = document.getElementById('dofEmptyState');
            const rowCountEl = document.getElementById('dofRowCount');
            const sourceBadge = document.getElementById('dofSourceBadge');
            const alertBox = document.getElementById('dofAlert');
            const alertText = document.getElementById('dofAlertText');

            const deleteModalEl = document.getElementById('deleteRangeModal');
            const delDateFrom = document.getElementById('delDateFrom');
            const delDateTo = document.getElementById('delDateTo');
            const btnCheckRange = document.getElementById('btnCheckRange');
            const btnConfirmDelete = document.getElementById('btnConfirmDelete');
            const delPreviewBox = document.getElementById('delPreviewBox');
            const delPreviewText = document.getElementById('delPreviewText');
            const delErrorBox = document.getElementById('delErrorBox');
            const delErrorText = document.getElementById('delErrorText');
            let checkedRange = null; // {date_from, date_to} once Check has succeeded

            let currentRows = [];

            function showAlert(message) {
                alertText.textContent = message;
                alertBox.style.display = 'flex';
                clearTimeout(showAlert._t);
                showAlert._t = setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 4000);
            }

            function setSourceBadge(source) {
                sourceBadge.classList.remove('d-none', 'dof-badge-saved', 'dof-badge-draft');
                if (source === 'saved') {
                    sourceBadge.textContent = 'Saved for this date';
                    sourceBadge.classList.add('dof-badge-saved');
                } else {
                    sourceBadge.textContent = 'Not yet saved — review and Save';
                    sourceBadge.classList.add('dof-badge-draft');
                }
            }

            function renderRows(rows) {
                currentRows = rows;
                tableBody.innerHTML = '';

                if (!rows.length) {
                    emptyState.classList.remove('d-none');
                    document.getElementById('dofTable').classList.add('d-none');
                    rowCountEl.textContent = '';
                    btnSave.disabled = true;
                    return;
                }

                emptyState.classList.add('d-none');
                document.getElementById('dofTable').classList.remove('d-none');
                rowCountEl.textContent = rows.length + ' record(s)';
                btnSave.disabled = false;

                rows.forEach((row, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>${row.NEW_EMPNO ?? row.EMPNO ?? ''}</td>
                <td>${row.EMP_NAME ?? ''}</td>
                <td>${formatDisplayDate(row.ATT_DATE)}</td>
                <td>${row.STATUS ?? ''}</td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                        <input class="form-check-input dof-food-toggle" type="checkbox"
                               data-idx="${idx}" ${row.IS_FOOD === 'Y' ? 'checked' : ''}>
                    </div>
                </td>`;
                    tableBody.appendChild(tr);
                });

                tableBody.querySelectorAll('.dof-food-toggle').forEach(cb => {
                    cb.addEventListener('change', (e) => {
                        const i = Number(e.target.dataset.idx);
                        currentRows[i].IS_FOOD = e.target.checked ? 'Y' : 'N';
                    });
                });
            }

            function formatDisplayDate(raw) {
                if (!raw) return '';
                // raw is guaranteed 'Y-m-d' by the controller's normalizeRow() —
                // no Date object parsing here, since that's what silently dropped
                // ATT_DATE before (cross-browser Date() parsing of date strings is
                // unreliable, and a parse failure rendered as a blank cell).
                const parts = String(raw).split('-');
                if (parts.length !== 3) return raw;
                const [y, m, d] = parts;
                return `${d}-${m}-${y}`;
            }

            async function loadData() {
                const foodDate = dateInput.value;
                if (!foodDate) {
                    showAlert('Please choose a date first.');
                    return;
                }

                btnLoad.disabled = true;
                btnLoad.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';

                try {
                    const res = await fetch(loadUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            food_date: foodDate
                        }),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        showAlert(data.message || 'Could not load records.');
                        return;
                    }

                    setSourceBadge(data.source);
                    renderRows(data.rows || []);
                } catch (err) {
                    showAlert('Network error while loading records.');
                } finally {
                    btnLoad.disabled = false;
                    btnLoad.innerHTML = '<i class="bi bi-cloud-download me-1"></i> Load';
                }
            }

            async function saveData() {
                if (!currentRows.length) return;

                btnSave.disabled = true;
                btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

                try {
                    const res = await fetch(saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            food_date: dateInput.value,
                            rows: currentRows,
                        }),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        showAlert(data.message || 'Save failed.');
                        return;
                    }

                    showAlert(data.message); // "Record(s) successfully Saved."
                    setSourceBadge('saved');
                } catch (err) {
                    showAlert('Network error while saving.');
                } finally {
                    btnSave.disabled = false;
                    btnSave.innerHTML = '<i class="bi bi-save me-1"></i> Save';
                }
            }

            btnLoad.addEventListener('click', loadData);
            btnSave.addEventListener('click', saveData);
            btnExit.addEventListener('click', () => {
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = '/'; // adjust to your HRM dashboard route
                }
            });

            function resetDeleteModal() {
                checkedRange = null;
                delPreviewBox.style.display = 'none';
                delErrorBox.style.display = 'none';
                btnConfirmDelete.disabled = true;
            }

            function showDeleteError(message) {
                delErrorText.textContent = message;
                delErrorBox.style.display = 'block';
                delPreviewBox.style.display = 'none';
                btnConfirmDelete.disabled = true;
            }

            deleteModalEl?.addEventListener('show.bs.modal', () => {
                if (dateInput.value) {
                    fpDelFrom.setDate(dateInput.value, true);
                    fpDelTo.setDate(dateInput.value, true);
                }
                resetDeleteModal();
            });

            btnCheckRange.addEventListener('click', async () => {
                const dateFrom = delDateFrom.value;
                const dateTo = delDateTo.value;

                if (!dateFrom || !dateTo) {
                    showDeleteError('Choose both a from date and a to date.');
                    return;
                }
                if (dateTo < dateFrom) {
                    showDeleteError('"To date" cannot be earlier than "From date".');
                    return;
                }

                btnCheckRange.disabled = true;
                btnCheckRange.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';

                try {
                    const res = await fetch(deletePreviewUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            date_from: dateFrom,
                            date_to: dateTo
                        }),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        showDeleteError(data.message || 'Could not check the date range.');
                        return;
                    }

                    delErrorBox.style.display = 'none';

                    if (data.count === 0) {
                        delPreviewText.textContent =
                            'No records found in this date range. Nothing to delete.';
                        delPreviewBox.style.display = 'block';
                        btnConfirmDelete.disabled = true;
                        checkedRange = null;
                        return;
                    }

                    delPreviewText.textContent =
                        `This will permanently delete ${data.count} record(s) between ${dateFrom} and ${dateTo}.`;
                    delPreviewBox.style.display = 'block';
                    btnConfirmDelete.disabled = false;
                    checkedRange = {
                        date_from: dateFrom,
                        date_to: dateTo
                    };
                } catch (err) {
                    showDeleteError('Network error while checking the date range.');
                } finally {
                    btnCheckRange.disabled = false;
                    btnCheckRange.innerHTML = '<i class="bi bi-search me-1"></i> Check';
                }
            });

            // If the dates change after a successful Check, require re-checking before delete is allowed.
            [delDateFrom, delDateTo].forEach(input => {
                input.addEventListener('change', () => {
                    btnConfirmDelete.disabled = true;
                    delPreviewBox.style.display = 'none';
                    checkedRange = null;
                });
            });

            btnConfirmDelete.addEventListener('click', async () => {
                if (!checkedRange) return;

                btnConfirmDelete.disabled = true;
                btnConfirmDelete.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...';

                try {
                    const res = await fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(checkedRange),
                    });
                    const data = await res.json();

                    if (!res.ok) {
                        showDeleteError(data.message || 'Delete failed.');
                        btnConfirmDelete.disabled = false;
                        return;
                    }

                    bootstrap.Modal.getInstance(deleteModalEl)?.hide();
                    showAlert(data.message);

                    // If the currently loaded grid's date falls inside the deleted range, clear it.
                    if (dateInput.value >= checkedRange.date_from && dateInput.value <= checkedRange
                        .date_to) {
                        renderRows([]);
                        sourceBadge.classList.add('d-none');
                    }
                } catch (err) {
                    showDeleteError('Network error while deleting.');
                    btnConfirmDelete.disabled = false;
                } finally {
                    btnConfirmDelete.innerHTML = '<i class="bi bi-trash3 me-1"></i> Delete';
                }
            });
        })();
    </script>
@endpush
