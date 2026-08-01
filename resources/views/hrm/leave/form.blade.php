@extends('layouts.app')
@section('title', isset($master) ? 'Edit Leave Entry' : 'New Leave Entry')

@push('styles')
    @include('hrm.leave._styles')
@endpush

@section('content')
    <div class="container-fluid py-3 hrm-page">

        {{-- Page title --}}
        <div class="hrm-page-title">
            <i class="bi bi-calendar2-check"></i>
            {{ isset($master) ? 'Edit Leave Entry' : 'New Leave Entry' }}
            <a href="{{ route('leave.index') }}" class="hrm-btn hrm-btn-secondary ms-auto"
                style="font-size:12px;padding:5px 12px">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        {{-- Inline save notification --}}
        <div id="saveAlert"></div>

        <form id="leaveForm">
            @csrf
            @if (isset($master))
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="formEmpno" value="{{ $master->empno }}">
                <input type="hidden" id="formYear" value="{{ $master->year }}">
                <input type="hidden" id="formCompany" value="{{ $master->company_id }}">
            @endif

            {{-- ══ MASTER ═══════════════════════════════════════════════ --}}
            <div class="hrm-card">
                <div class="hrm-card-header">
                    <i class="bi bi-person-badge"></i> Employee Information
                </div>
                <div class="hrm-card-body">
                    <div class="row g-3 mb-3">

                        <div class="col-md-3">
                            <label class="hrm-label">Company <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="hrm-control" style="height:30px"
                                {{ isset($master) ? 'disabled' : '' }} required>
                                <option value="">-- Select --</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->company_id }}"
                                        {{ old('company_id', $master->company_id ?? '') == $c->company_id ? 'selected' : '' }}>
                                        {{ $c->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($master))
                                <input type="hidden" name="company_id" value="{{ $master->company_id }}">
                            @endif
                        </div>

                        <div class="col-md-2">
                            <label class="hrm-label">Year <span class="text-danger">*</span></label>
                            <select name="year" id="year" class="hrm-control" style="height:30px"
                                {{ isset($master) ? 'disabled' : '' }} required>
                                <option value="">-- Select --</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}"
                                        {{ old('year', $master->year ?? '') == $y ? 'selected' : '' }}>{{ $y }}
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($master))
                                <input type="hidden" name="year" value="{{ $master->year }}">
                            @endif
                        </div>

                        {{-- Search by NEW_EMPNO --}}
                        <div class="col-md-2">
                            <label class="hrm-label">Emp No (New) <span class="text-danger">*</span></label>
                            <div class="hrm-input-group">
                                <input type="text" id="new_empno_search" class="hrm-control"
                                    value="{{ old('new_empno', $master->new_empno ?? '') }}"
                                    {{ isset($master) ? 'readonly' : '' }} placeholder="e.g. 1001" autocomplete="off">
                                @if (!isset($master))
                                    <button type="button" class="hrm-ig-btn" id="btnLookup" title="Search">
                                        <i class="bi bi-search"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- System EMPNO (read-only display) --}}
                        <div class="col-md-2">
                            <label class="hrm-label">Sys Emp No</label>
                            <input type="text" id="empno_display" class="hrm-control readonly"
                                value="{{ $master->empno ?? '' }}" readonly>
                            <input type="hidden" name="empno" id="empno" value="{{ $master->empno ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="hrm-label">Employee Name</label>
                            <input type="text" id="ename" class="hrm-control readonly"
                                value="{{ $master->ename ?? '' }}" readonly>
                        </div>

                        <input type="hidden" name="lv_cat_id" id="lv_cat_id"
                            value="{{ old('lv_cat_id', $master->lv_cat_id ?? '') }}">
                    </div>

                    {{-- Row 2: Designation (own row, no overlap) --}}
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="hrm-label">Designation</label>
                            <input type="text" id="des_name" class="hrm-control readonly"
                                value="{{ $master->des_name ?? '' }}" readonly>
                        </div>
                    </div>

                    {{-- Photo + Balances --}}
                    <div class="d-flex gap-3 align-items-start">
                        <div style="flex-shrink:0">
                            <img id="empPhoto"
                                src="{{ isset($master) ? 'http://192.168.210.205:81/' . $master->empno . '.jpg' : asset('images/no-photo.png') }}"
                                class="hrm-emp-photo" alt="Photo" data-fallback="{{ asset('images/no-photo.png') }}"
                                onerror="window.hrmPhotoFallback(this)">
                        </div>

                        @php
                            $b =
                                $balances ??
                                array_fill_keys(
                                    [
                                        'casual_total',
                                        'casual_enjoyed',
                                        'casual_balance',
                                        'sick_total',
                                        'sick_enjoyed',
                                        'sick_balance',
                                        'earn_total',
                                        'earn_enjoyed',
                                        'earn_balance',
                                    ],
                                    0,
                                );
                        @endphp
                        <div class="hrm-bal-grid flex-grow-1">
                            <div class="hrm-bal-card casual">
                                <div class="hrm-bal-title casual">Casual Leave</div>
                                <div class="hrm-bal-nums">
                                    <div>
                                        <div class="hrm-bal-lbl">Total</div>
                                        <div class="hrm-bal-val" id="casualTotal">{{ $b['casual_total'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Enjoyed</div>
                                        <div class="hrm-bal-val" id="casualEnjoy">{{ $b['casual_enjoyed'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Balance</div>
                                        <div class="hrm-bal-val casual" id="casualBal">{{ $b['casual_balance'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="hrm-bal-card sick">
                                <div class="hrm-bal-title sick">Sick Leave</div>
                                <div class="hrm-bal-nums">
                                    <div>
                                        <div class="hrm-bal-lbl">Total</div>
                                        <div class="hrm-bal-val" id="sickTotal">{{ $b['sick_total'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Enjoyed</div>
                                        <div class="hrm-bal-val" id="sickEnjoy">{{ $b['sick_enjoyed'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Balance</div>
                                        <div class="hrm-bal-val sick" id="sickBal">{{ $b['sick_balance'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="hrm-bal-card earn">
                                <div class="hrm-bal-title earn">Earned Leave</div>
                                <div class="hrm-bal-nums">
                                    <div>
                                        <div class="hrm-bal-lbl">Total</div>
                                        <div class="hrm-bal-val" id="earnTotal">{{ $b['earn_total'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Enjoyed</div>
                                        <div class="hrm-bal-val" id="earnEnjoy">{{ $b['earn_enjoyed'] }}</div>
                                    </div>
                                    <div>
                                        <div class="hrm-bal-lbl">Balance</div>
                                        <div class="hrm-bal-val earn" id="earnBal">{{ $b['earn_balance'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ DETAILS ════════════════════════════════════════════════ --}}
            <div class="hrm-card">
                <div class="hrm-card-header">
                    <i class="bi bi-table"></i> Leave Details
                    <span id="prevYearBadge" class="hrm-badge-info ms-2 d-none"></span>
                    <button type="button" class="hrm-btn-add ms-auto" id="btnAddRow">
                        <i class="bi bi-plus-circle"></i> Add Row
                    </button>
                </div>
                <div class="hrm-card-body p-0">
                    <div class="table-responsive">
                        <table class="hrm-detail-table" id="detailTable">
                            <thead>
                                <tr>
                                    <th style="min-width:148px">Leave Type</th>
                                    <th style="min-width:105px">From</th>
                                    <th style="min-width:105px">To</th>
                                    <th style="min-width:62px">Approve Days</th>
                                    <th style="min-width:105px">Application Date</th>
                                    <th style="min-width:105px">Approve Date</th>
                                    <th style="min-width:120px">Approve By</th>
                                    <th style="min-width:56px">Pre Bal</th>
                                    <th style="min-width:56px">Balance</th>
                                    <th style="min-width:90px">Information</th>
                                    <th style="min-width:110px">Remarks</th>
                                    <th style="min-width:105px">Action</th>
                                </tr>
                            </thead>
                            <tbody id="detailBody">
                                @if (isset($details) && $details->count())
                                    @foreach ($details as $i => $d)
                                        @include('hrm.leave._detail_row', [
                                            'i' => $i,
                                            'd' => $d,
                                            'leaveTypes' => $leaveTypes,
                                        ])
                                    @endforeach
                                @else
                                    @include('hrm.leave._detail_row', [
                                        'i' => 0,
                                        'd' => null,
                                        'leaveTypes' => $leaveTypes,
                                    ])
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Action bar --}}
            <div class="hrm-action-bar">
                <div class="hrm-action-group">
                    <button type="button" class="hrm-btn hrm-btn-primary" id="btnSave">
                        <i class="bi bi-floppy"></i> Save
                    </button>
                    <a href="{{ route('leave.index') }}" class="hrm-btn hrm-btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="button" class="hrm-btn hrm-btn-secondary" id="btnClear">
                        <i class="bi bi-eraser"></i> Clear
                    </button>
                </div>
                @if (isset($master))
                    <button type="button" class="hrm-btn hrm-btn-danger hrm-delete-master" id="btnDeleteMaster">
                        <i class="bi bi-trash3"></i> Delete Record
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Inline toast --}}
    <div id="hrmToast"></div>

    {{-- ── HRM Delete Confirmation Modal ── --}}
    <div id="hrmDeleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:10px;padding:26px 30px;max-width:360px;width:92%;box-shadow:0 8px 32px rgba(0,0,0,.18);text-align:center;">
            <div style="font-size:34px;margin-bottom:10px;">&#x1F5D1;</div>
            <div style="font-size:16px;font-weight:600;color:#1a3c5e;margin-bottom:7px;">Delete Leave Detail?</div>
            <div style="font-size:13px;color:#555;margin-bottom:20px;line-height:1.5;">
                This will permanently remove this leave record.<br>
                <strong>Attendance status will be reverted to Absent.</strong>
            </div>
            <div style="display:flex;gap:10px;justify-content:center;">
                <button id="hrmDeleteCancel" style="background:#f4f6f9;color:#555;border:1px solid #dee2e6;padding:8px 22px;border-radius:6px;font-size:13px;cursor:pointer;">Cancel</button>
                <button id="hrmDeleteConfirm" style="background:#dc3545;color:#fff;border:none;padding:8px 22px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;">Yes, Delete</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // ─── Config ──────────────────────────────────────────────────────
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const FP_CONFIG = {
            dateFormat: 'Y-m-d',
            allowInput: true
        };
        let rowIndex = {{ isset($details) ? $details->count() : 1 }};
        const IS_CREATE     = {{ isset($master) ? 'false' : 'true' }};
        const IS_EDIT = {{ isset($master) ? 'true' : 'false' }};
        const SLIP_URL_BASE = @json(url('hrm/leave-entry/slip'));
        const DETAIL_DELETE_URL_BASE = @json(url('hrm/leave-entry/detail'));

        function currentEmpno() {
            return document.getElementById('empno')?.value || '';
        }

        function leaveUrl(baseUrl, empno, leaveId, lvFrom) {
            return `${baseUrl}/${encodeURIComponent(empno || '')}/${encodeURIComponent(leaveId || '')}/${encodeURIComponent(lvFrom || '')}`;
        }

        function ensureSlipButtons() {
            document.querySelectorAll('#detailBody .detail-row').forEach(tr => {
                const actionCell = tr.querySelector('td:last-child');
                const empnoInput = tr.querySelector('[name*="[empno]"]');
                const leaveIdInput = tr.querySelector('[name*="[leave_id]"]');
                const origFromInput = tr.querySelector('[name*="[lv_from_orig]"]');
                const empno = empnoInput?.value || currentEmpno();
                const leaveId = leaveIdInput?.value || '';
                const lvFrom = tr.querySelector('.fp-lv-from')?.value || origFromInput?.value || '';
                const removeBtn = tr.querySelector('.remove-detail');

                if (empnoInput && !empnoInput.value) empnoInput.value = empno;
                if (origFromInput && !origFromInput.value && lvFrom) origFromInput.value = lvFrom;

                if (removeBtn) {
                    removeBtn.dataset.leaveId = leaveId;
                    removeBtn.dataset.lvFrom = lvFrom;
                }

                if (!actionCell || !empno || !leaveId || !lvFrom || actionCell.querySelector('.slip-link')) {
                    return;
                }

                actionCell.insertAdjacentHTML('afterbegin',
                    `<a href="${leaveUrl(SLIP_URL_BASE, empno, leaveId, lvFrom)}"
                        target="_blank" class="hrm-btn-success-outline slip-link me-1"
                        style="display:inline-flex;align-items:center;gap:3px;padding:3px 8px;font-size:11.5px"
                        title="Print Leave Slip"><i class="bi bi-printer"></i> Slip</a>`
                );
            });
        }

        function clearLeaveForm() {
            document.getElementById('leaveForm').reset();

            if (!IS_EDIT) {
                document.getElementById('empno').value = '';
                document.getElementById('empno_display').value = '';
                document.getElementById('ename').value = '';
                document.getElementById('des_name').value = '';
                document.getElementById('lv_cat_id').value = '';

                ['casualTotal', 'casualEnjoy', 'casualBal', 'sickTotal', 'sickEnjoy', 'sickBal', 'earnTotal', 'earnEnjoy', 'earnBal']
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = '0';
                    });

                const badge = document.getElementById('prevYearBadge');
                if (badge) {
                    badge.textContent = '';
                    badge.classList.add('d-none');
                }

                const photo = document.getElementById('empPhoto');
                if (photo) {
                    photo.dataset.fallbackTried = '0';
                    photo.src = photo.dataset.fallback || '';
                }

                const tbody = document.getElementById('detailBody');
                tbody.innerHTML = '';
                rowIndex = 0;
                const tr = buildRow(rowIndex++);
                tbody.appendChild(tr);
                bindRowEvents(tr);
            } else {
                document.querySelectorAll('#detailBody .detail-row').forEach(bindRowEvents);
            }

            showToast('Form cleared.');
        }

        // Master identifiers (edit mode)
        const EDIT_EMPNO = document.getElementById('formEmpno')?.value || '';
        const EDIT_YEAR = document.getElementById('formYear')?.value || '';
        const EDIT_COMPANY = document.getElementById('formCompany')?.value || '';

        // Leave type options string for dynamic rows
        const leaveTypeOptions = `<option value="">--</option>
@foreach ($leaveTypes as $lt)
<option value="{{ $lt->leave_name }}" data-leave-id="{{ $lt->leave_id }}" data-max="{{ $lt->max_days }}">{{ $lt->leave_name }}</option>
@endforeach`;

        const balanceIds = {
            'Casual Leave': 'casualBal',
            'Sick Leave': 'sickBal',
            'Earned Leave': 'earnBal'
        };

        // Init Flatpickr on all pre-existing rows
        document.querySelectorAll('.fp-date').forEach(el => {
            if (!el._flatpickr) flatpickr(el, FP_CONFIG);
        });

        // ─── Toast ────────────────────────────────────────────────────────
        function showToast(msg, type = 'success') {
            const t = document.getElementById('hrmToast');
            t.className = type;
            t.innerHTML = `<i class="bi bi-${type==='success'?'check-circle':'exclamation-triangle'}-fill me-2"></i>${msg}`;
            t.style.display = 'flex';
            clearTimeout(t._timer);
            t._timer = setTimeout(() => t.style.display = 'none', 3500);
        }

        // ─── Employee lookup by NEW_EMPNO ─────────────────────────────────
        function lookupEmployee() {
            const newEmpno = document.getElementById('new_empno_search').value.trim();
            const companyId = document.getElementById('company_id').value;
            const year = document.getElementById('year').value;
            if (!newEmpno || !companyId) {
                showToast('Select a company and enter employee number.', 'error');
                return;
            }

            fetch(
                    `{{ route('leave.ajax.employee') }}?new_empno=${encodeURIComponent(newEmpno)}&company_id=${encodeURIComponent(companyId)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        showToast(data.error, 'error');
                        return;
                    }
                    document.getElementById('empno').value = data.empno;
                    document.getElementById('empno_display').value = data.empno;
                    document.getElementById('ename').value = data.ename;
                    document.getElementById('des_name').value = data.des_name;
                    document.getElementById('lv_cat_id').value = data.lv_cat_id;
                    const photo = document.getElementById('empPhoto');
                    photo.dataset.fallbackTried = '0';
                    photo.style.visibility = 'visible';
                    photo.onerror = function() {
                        window.hrmPhotoFallback(this);
                    };
                    photo.src = `http://192.168.210.205:81/${data.empno}.jpg`;
                    if (year) {
                        refreshBalances(data.empno, year);
                        if (IS_CREATE) loadPreviousYearDetails(data.empno, year);
                    }
                })
                .catch(() => showToast('Could not fetch employee data.', 'error'));
        }

        document.getElementById('btnLookup')?.addEventListener('click', lookupEmployee);
        document.getElementById('new_empno_search')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupEmployee();
            }
        });
        document.getElementById('year')?.addEventListener('change', function() {
            const empno = document.getElementById('empno').value;
            if (empno) {
                refreshBalances(empno, this.value);
                if (IS_CREATE) loadPreviousYearDetails(empno, this.value);
            }
        });

        // ─── Balance refresh ──────────────────────────────────────────────
        function refreshBalances(empno, year) {
            if (!empno || !year) return;
            fetch(`{{ route('leave.ajax.balances') }}?empno=${encodeURIComponent(empno)}&year=${encodeURIComponent(year)}`)
                .then(r => r.json()).then(b => {
                    document.getElementById('casualTotal').textContent = b.casual_total;
                    document.getElementById('casualEnjoy').textContent = b.casual_enjoyed;
                    document.getElementById('casualBal').textContent = b.casual_balance;
                    document.getElementById('sickTotal').textContent = b.sick_total;
                    document.getElementById('sickEnjoy').textContent = b.sick_enjoyed;
                    document.getElementById('sickBal').textContent = b.sick_balance;
                    document.getElementById('earnTotal').textContent = b.earn_total;
                    document.getElementById('earnEnjoy').textContent = b.earn_enjoyed;
                    document.getElementById('earnBal').textContent = b.earn_balance;
                });
        }

        // ─── Load previous year details (CREATE only) ─────────────────────
        function loadPreviousYearDetails(empno, year) {
            fetch(
                    `{{ route('leave.ajax.prevYearDetails') }}?empno=${encodeURIComponent(empno)}&year=${encodeURIComponent(year)}`)
                .then(r => r.json()).then(data => {
                    if (!data.found || !data.details.length) return;
                    document.getElementById('lv_cat_id').value = data.lv_cat_id;
                    const badge = document.getElementById('prevYearBadge');
                    badge.textContent = `Loaded ${data.details.length} existing rows for ${data.prev_year}`;
                    badge.classList.remove('d-none');
                    const tbody = document.getElementById('detailBody');
                    tbody.innerHTML = '';
                    rowIndex = 0;
                    data.details.forEach(d => {
                        const tr = buildRow(rowIndex++, d);
                        tbody.appendChild(tr);
                        bindRowEvents(tr);
                    });
                });
        }

        // ─── Build a row ──────────────────────────────────────────────────
        // FIX 1: dates from prev-year data are now passed through
        // FIX 3: slip button added for saved rows (have d.auto)
        function buildRow(i, d = {}) {
            const tr = document.createElement('tr');
            tr.className = 'detail-row';
            tr.dataset.index = i;

            // Safe option selection
            let selOpts = leaveTypeOptions;
            if (d.leave_name) {
                selOpts = leaveTypeOptions.replace(
                    'value="' + d.leave_name + '"',
                    'value="' + d.leave_name + '" selected'
                );
            }
            const optionDoc = new DOMParser().parseFromString(`<select>${selOpts}</select>`, 'text/html');
            const selectedOption = Array.from(optionDoc.querySelectorAll('option'))
                .find(option => option.value === (d.leave_name || ''));
            const rowLeaveId = d.leave_id || selectedOption?.dataset?.leaveId || '';

            // Dates: carry over for edit, blank for new/prev-year
            const lvFrom   = d.lv_from          || '';
            const lvTo     = d.lv_to            || '';
            const appDate  = d.application_date || '';
            const appDate2 = d.approve_date     || '';

            // Slip button — for saved rows that have leave_id + lv_from
            const rowEmpno = d.empno || currentEmpno();
            const slipBtn = (rowEmpno && rowLeaveId && d.lv_from)
                ? `<a href="${leaveUrl(SLIP_URL_BASE, rowEmpno, rowLeaveId, d.lv_from)}"
                      target="_blank" class="hrm-btn-success-outline slip-link me-1"
                      style="display:inline-flex;align-items:center;gap:3px;padding:3px 8px;font-size:11.5px"
                      title="Print Leave Slip"><i class="bi bi-printer"></i> Slip</a>`
                : '';

            tr.innerHTML = `
        <input type="hidden" name="details[${i}][empno]"       value="${rowEmpno}" >
        <input type="hidden" name="details[${i}][leave_id]"    value="${rowLeaveId}" >
        <input type="hidden" name="details[${i}][lv_from_orig]" value="${d.lv_from||''}" >
        <input type="hidden" name="details[${i}][max_days]"    value="${d.max_days||''}" >
        <td><select name="details[${i}][leave_name]" class="td-select leave-name-sel" required>${selOpts}</select></td>
        <td style="min-width:105px"><div class="fp-wrap"><input type="text" name="details[${i}][lv_from]" class="fp-date fp-lv-from" value="${lvFrom}" autocomplete="off" placeholder="yyyy-mm-dd"></div></td>
        <td style="min-width:105px"><div class="fp-wrap"><input type="text" name="details[${i}][lv_to]"   class="fp-date fp-lv-to"   value="${lvTo}"   autocomplete="off" placeholder="yyyy-mm-dd"></div></td>
        <td style="min-width:62px"><input type="number" name="details[${i}][approve_days]" class="td-input approve-days" value="${d.approve_days||''}" step="0.5" min="0" style="text-align:center;width:56px"></td>
        <td style="min-width:105px"><div class="fp-wrap"><input type="text" name="details[${i}][application_date]" class="fp-date fp-app-date"  value="${appDate}"  autocomplete="off" placeholder="yyyy-mm-dd"></div></td>
        <td style="min-width:105px"><div class="fp-wrap"><input type="text" name="details[${i}][approve_date]"     class="fp-date fp-app-date2" value="${appDate2}" autocomplete="off" placeholder="yyyy-mm-dd"></div></td>
        <td style="min-width:120px"><input type="text" name="details[${i}][approve_by]"   class="td-input" value="${d.approve_by||''}"   placeholder="Approved by"  style="min-width:110px"></td>
        <td style="min-width:56px"><input type="number" name="details[${i}][pre_balance]" class="td-input pre-bal" value="${d.pre_balance||''}" step="0.5" readonly style="text-align:center;width:52px;color:#666"></td>
        <td style="min-width:56px"><input type="number" name="details[${i}][balance]"     class="td-input calc new-bal" value="${d.balance||''}" step="0.5" readonly style="text-align:center;width:52px"></td>
        <td style="min-width:90px"><input type="text"  name="details[${i}][information]"  class="td-input" value="${d.information||''}" placeholder="Info" style="min-width:85px"></td>
        <td style="min-width:110px"><input type="text" name="details[${i}][remax]"        class="td-input" value="${d.remax||''}"        placeholder="Remarks" style="min-width:100px"></td>
        <td class="text-center text-nowrap" style="padding:4px 6px;min-width:72px">
            ${slipBtn}
            <button type="button" class="hrm-btn-danger-outline remove-detail"
                    data-leave-id="${rowLeaveId}" data-lv-from="${d.lv_from||''}" title="Remove">
                <i class="bi bi-trash3"></i>
            </button>
        </td>`;
            return tr;
        }

        // ─── Add Row ──────────────────────────────────────────────────────
        document.getElementById('btnAddRow').addEventListener('click', () => {
            const tr = buildRow(rowIndex++);
            document.getElementById('detailBody').appendChild(tr);
            bindRowEvents(tr);
        });

        // ─── Bind row events ──────────────────────────────────────────────
        // FIX 2: bindRowEvents now wires onChange → calcDays for auto day count
        function bindRowEvents(tr) {
            const sel      = tr.querySelector('.leave-name-sel');
            const lvFrom   = tr.querySelector('.fp-lv-from');
            const lvTo     = tr.querySelector('.fp-lv-to');
            const appDate  = tr.querySelector('.fp-app-date');
            const appDate2 = tr.querySelector('.fp-app-date2');
            const appDays  = tr.querySelector('.approve-days');
            const preBal   = tr.querySelector('.pre-bal');
            const newBal   = tr.querySelector('.new-bal');

            // ── Auto day count whenever From or To changes ────────────────
            function calcDays() {
                const f = lvFrom.value, t = lvTo.value;
                if (f && t) {
                    const diff = Math.round(
                        (new Date(t) - new Date(f)) / 86400000
                    ) + 1;
                    if (diff > 0) {
                        appDays.value = diff;
                        recalc();
                    }
                }
            }

            flatpickr(lvFrom,   { ...FP_CONFIG, onChange: calcDays });
            flatpickr(lvTo,     { ...FP_CONFIG, onChange: calcDays });
            if (appDate)  flatpickr(appDate,  FP_CONFIG);
            if (appDate2) flatpickr(appDate2, FP_CONFIG);

            sel?.addEventListener('change', () => {
                const opt   = sel.options[sel.selectedIndex];
                const maxD  = parseFloat(opt?.dataset?.max || 0);
                const balId = balanceIds[sel.value];
                const autoInput = tr.querySelector('[name*="[leave_id]"]');
                const maxInput  = tr.querySelector('[name*="[max_days]"]');
                if (autoInput) autoInput.value = opt?.dataset?.leaveId || '';
                if (maxInput)  maxInput.value  = maxD;
                if (balId) {
                    preBal.value = document.getElementById(balId)?.textContent || '';
                    recalc();
                } else {
                    preBal.value = '';
                    newBal.value = '';
                }
            });

            appDays?.addEventListener('input', recalc);

            function recalc() {
                const pre = parseFloat(preBal.value) || 0;
                const ap  = parseFloat(appDays?.value) || 0;
                newBal.value = (pre - ap).toFixed(2);
            }
        }

        // Bind existing rows
        document.querySelectorAll('#detailBody .detail-row').forEach(bindRowEvents);
        document.getElementById('btnClear').addEventListener('click', clearLeaveForm);

        // ─── Remove row ───────────────────────────────────────────────────
        let _pendingDeleteTr      = null;
        let _pendingDeleteLeaveId = null;
        let _pendingDeleteLvFrom  = null;

        document.getElementById('detailBody').addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-detail');
            if (!btn) return;
            const tr      = btn.closest('tr');
            const leaveId = btn.dataset.leaveId;
            const lvFrom  = btn.dataset.lvFrom;

            if (leaveId && lvFrom) {
                // Saved row — show confirm modal before AJAX delete
                _pendingDeleteTr      = tr;
                _pendingDeleteLeaveId = leaveId;
                _pendingDeleteLvFrom  = lvFrom;
                document.getElementById('hrmDeleteModal').style.display = 'flex';
            } else {
                // Unsaved / blank row — remove from DOM directly (no restriction on count)
                tr.remove();
                showToast('Row removed.');
            }
        });

        // Confirm delete button in modal
        document.getElementById('hrmDeleteConfirm').addEventListener('click', function() {
            document.getElementById('hrmDeleteModal').style.display = 'none';
            if (!_pendingDeleteLeaveId || !_pendingDeleteLvFrom) return;

            const empno = document.getElementById('empno').value;
            const url   = leaveUrl(DETAIL_DELETE_URL_BASE, empno, _pendingDeleteLeaveId, _pendingDeleteLvFrom);

            fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    _pendingDeleteTr.remove();
                    const year = document.getElementById('year')?.value || EDIT_YEAR;
                    refreshBalances(empno, year);
                    showToast('Leave detail deleted and attendance reverted.');
                } else {
                    showToast(data.error || 'Could not delete.', 'error');
                }
                _pendingDeleteTr = _pendingDeleteLeaveId = _pendingDeleteLvFrom = null;
            })
            .catch(() => showToast('Network error.', 'error'));
        });

        // Cancel delete button
        document.getElementById('hrmDeleteCancel').addEventListener('click', function() {
            document.getElementById('hrmDeleteModal').style.display = 'none';
            _pendingDeleteTr = _pendingDeleteLeaveId = _pendingDeleteLvFrom = null;
        });

        // ─── SAVE (AJAX – no redirect) ────────────────────────────────────
        document.getElementById('btnSave').addEventListener('click', function() {
            const empno = document.getElementById('empno').value;
            if (!empno) {
                showToast('Please select an employee first.', 'error');
                return;
            }

            Swal.fire({
                    title: 'Save Leave Entry?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    confirmButtonColor: '#1a3c5e'
                })
                .then(r => {
                    if (!r.isConfirmed) return;

                    const form = document.getElementById('leaveForm');
                    const data = new FormData(form);
                    let url, method;

                    if (IS_EDIT) {
                        url = `{{ url('leave-entry') }}/${EDIT_EMPNO}/${EDIT_YEAR}/${EDIT_COMPANY}`;
                        method = 'POST'; // _method=PUT is in formdata
                    } else {
                        url = `{{ route('leave.store') }}`;
                        method = 'POST';
                    }

                    fetch(url, {
                            method,
                            body: data,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(resp => {
                            if (resp.success) {
                                showToast(resp.message || 'Saved successfully.');
                                ensureSlipButtons();
                                // Refresh balances
                                const year = document.getElementById('year')?.value || EDIT_YEAR;
                                refreshBalances(empno, year);
                                // If it was a new create and now we have a master, mark form as edit
                                if (IS_CREATE && resp.empno) {
                                    document.getElementById('empno').setAttribute('readonly', true);
                                }
                            } else {
                                showToast(resp.message || 'Could not save.', 'error');
                            }
                        })
                        .catch(() => showToast('Network error.', 'error'));
                });
        });

        // ─── Delete master ────────────────────────────────────────────────
        document.getElementById('btnDeleteMaster')?.addEventListener('click', () => {
            Swal.fire({
                    title: 'Delete Leave Entry?',
                    text: 'Remove all details first. Cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete'
                })
                .then(r => {
                    if (!r.isConfirmed) return;
                    fetch(`{{ url('leave-entry') }}/${EDIT_EMPNO}/${EDIT_YEAR}/${EDIT_COMPANY}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            showToast(data.message);
                            setTimeout(() => window.location.href = '{{ route('leave.index') }}',
                            1200);
                        } else showToast(data.message || 'Cannot delete.', 'error');
                    });
                });
        });
    </script>
@endpush
