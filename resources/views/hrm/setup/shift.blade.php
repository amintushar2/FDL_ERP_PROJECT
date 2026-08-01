@extends('layouts.app') @section('title', 'Shift Setup') @include('hrm.setup.partials.styles')
@push('styles')
    <style>
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: opacity(.4);
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="setup-page-wrap">
        @include('hrm.setup.partials.sidebar')
        <div class="setup-main">
            <div class="setup-page-header">
                <h4>Shift Setup</h4>
                <p class="bc">HRM <span>›</span> Setup <span>›</span> Shift</p>
            </div>
            <div class="setup-toolbar">
                <button class="btn-tb btn-tb-new" onclick="openAddModal()"><svg width="11" height="11"
                        viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="8" y1="2" x2="8" y2="14" />
                        <line x1="2" y1="8" x2="14" y2="8" />
                    </svg>New</button>
                <div class="t-sep"></div>
                <div class="srch-group">
                    <div class="srch-wrap"><input class="srch-inp" id="toolbarSearch" placeholder="Search…"></div><button
                        class="srch-btn" onclick="doServerSearch()">Search</button>
                </div>
            </div>
            <div class="setup-body">
                <div class="sec-hd">
                    <div class="sec-title"><svg width="15" height="15" viewBox="0 0 16 16" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="8" cy="8" r="6" />
                            <path d="M8 5v3l2 2" />
                        </svg>Shift</div>
                    <div class="sec-line"></div>
                </div>
                <div class="setup-card">
                    <div class="setup-card-hd">
                        <div class="card-hd-left">
                            <div class="card-dot"></div><span class="card-ttl">Shift Setup</span>
                        </div>
                        <div class="d-flex align-items-center gap-2"><input class="card-search" id="cardSearch"
                                placeholder="Search…"><button class="btn-add-record" onclick="openAddModal()"><svg
                                    width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="8" y1="2" x2="8" y2="14" />
                                    <line x1="2" y1="8" x2="14" y2="8" />
                                </svg>+ Add Shift</button></div>
                    </div>
                    <div class="table-responsive">
                        <table class="setup-table">
                            <thead>
                                <tr>
                                    <th style="width:52px">#</th>
                                    <th style="width:80px">CODE</th>
                                    <th>SHIFT NAME</th>
                                    <th style="width:90px">IN TIME</th>
                                    <th style="width:90px">OUT TIME</th>
                                    <th style="width:80px">GRACE</th>
                                    <th style="width:80px">OT (hr)</th>
                                    <th style="width:100px">STATUS</th>
                                    <th style="width:140px">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $i=>$row)
                                    <tr data-id="{{ $row->shift_code }}">
                                        <td class="mono">{{ str_pad($records->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td><span class="mono">{{ $row->shift_code }}</span></td>
                                        <td style="font-weight:700">{{ $row->shift_name }}</td>
                                        <td>{{ $row->sin_time }}</td>
                                        <td>{{ $row->sout_time }}</td>
                                        <td>{{ $row->grace_period }}</td>
                                        <td>{{ $row->ot_limit }}</td>
                                        <td>
                                            @if (($row->is_active ?? 'Y') === 'Y')
                                            <span class="badge-active">Active</span>@else<span
                                                    class="badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td><button class="btn-row-edit"
                                                onclick="openEditModal('{{ $row->shift_code }}')"><svg width="11"
                                                    height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M11 2l3 3-9 9H2v-3z" />
                                                </svg>Edit</button><button class="btn-row-del"
                                                onclick="askDelete('{{ $row->shift_code }}','{{ addslashes($row->shift_name) }}')"><svg
                                                    width="11" height="11" viewBox="0 0 16 16" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3,4 13,4" />
                                                    <path d="M5 4V2h6v2M4 4l1 10h6l1-10" />
                                                </svg>Delete</button></td>
                                    </tr>
                                @empty<tr>
                                        <td colspan="9">
                                            <div class="empty-state"><svg width="42" height="42"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <circle cx="12" cy="12" r="9" />
                                                    <path d="M12 7v5l3 3" />
                                                </svg>
                                                <p>No shifts found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('hrm.setup.partials.pagination', ['records' => $records])
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-navy"><span class="modal-title" id="modalTitle"><svg
                            width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <line x1="8" y1="2" x2="8" y2="14" />
                            <line x1="2" y1="8" x2="14" y2="8" />
                        </svg> Add Shift</span><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4"><input type="hidden" id="recordId">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="f-lbl">Shift Code <span
                                    class="text-danger">*</span></label><input type="text" id="fCode"
                                class="f-inp" placeholder="e.g. A"
                                style="font-family:'DM Mono',monospace;text-transform:uppercase">
                            <div class="err-msg" id="fCode_err"></div>
                        </div>
                        <div class="col-md-9"><label class="f-lbl">Shift Name <span
                                    class="text-danger">*</span></label><input type="text" id="fName"
                                class="f-inp" placeholder="e.g. Morning Shift">
                            <div class="err-msg" id="fName_err"></div>
                        </div>
                        <div class="col-md-3"><label class="f-lbl">In Time</label><input type="time" id="fIn"
                                class="f-inp"></div>
                        <div class="col-md-3"><label class="f-lbl">Out Time</label><input type="time" id="fOut"
                                class="f-inp"></div>
                        <div class="col-md-3"><label class="f-lbl">Grace Period (min)</label><input type="number"
                                id="fGrace" class="f-inp" placeholder="0" min="0"></div>
                        <div class="col-md-3"><label class="f-lbl">Grace Period 2</label><input type="number"
                                id="fGrace2" class="f-inp" placeholder="0" min="0"></div>
                        <div class="col-md-3"><label class="f-lbl">Meal Time (min)</label><input type="number"
                                id="fMeal" class="f-inp" placeholder="0" min="0"></div>
                        <div class="col-md-3"><label class="f-lbl">OT Limit (hr)</label><input type="number"
                                id="fOt" class="f-inp" placeholder="2" min="0" step="0.5"></div>
                        <div class="col-md-3"><label class="f-lbl">OT Limit 3</label><input type="number"
                                id="fOt3" class="f-inp" placeholder="0" min="0"></div>
                        <div class="col-md-3"><label class="f-lbl">Status</label><select id="fStatus" class="f-sel">
                                <option value="Y">Active</option>
                                <option value="N">Inactive</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-setup justify-content-end gap-2"><button class="btn-modal-cancel"
                        data-bs-dismiss="modal">Cancel</button><button class="btn-modal-save" id="btnSave"
                        onclick="saveRecord()"><svg width="11" height="11" viewBox="0 0 16 16" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M13 2H4L2 4v10h12V4z" />
                            <rect x="5" y="9" width="6" height="5" />
                            <rect x="5" y="2" width="5" height="3" />
                        </svg>Save Record</button></div>
            </div>
        </div>
    </div>
    @include('hrm.setup.partials.modals-common')
@endsection
@include('hrm.setup.partials.scripts')
@push('scripts')
    <script>
        const BASE_URL = '{{ url('setup/shift') }}';
        const crudModal = new bootstrap.Modal(document.getElementById('crudModal'));
        const SF = ['recordId', 'fCode', 'fName', 'fIn', 'fOut', 'fGrace', 'fGrace2', 'fMeal', 'fOt', 'fOt3'];

        function openAddModal() {
            SF.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('fStatus').value = 'Y';
            document.getElementById('fCode').readOnly = false;
            document.getElementById('modalTitle').innerHTML =
                `<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/></svg> Add Shift`;
            clearModalErrors('crudModal');
            crudModal.show();
        }
        async function openEditModal(id) {
            try {
                const {
                    ok,
                    data
                } = await setupFetch(`${BASE_URL}/${id}/edit`);
                if (!ok || !data.success) {
                    showSetupToast(data.message || 'Failed.', 'red');
                    return;
                }
                const r = data.record;
                document.getElementById('recordId').value = r.shift_code;
                document.getElementById('fCode').value = r.shift_code;
                document.getElementById('fCode').readOnly = true;
                document.getElementById('fName').value = r.shift_name || '';
                document.getElementById('fIn').value = r.sin_time || '';
                document.getElementById('fOut').value = r.sout_time || '';
                document.getElementById('fGrace').value = r.grace_period || '';
                document.getElementById('fGrace2').value = r.grace_period_2 || '';
                document.getElementById('fMeal').value = r.meal_time || '';
                document.getElementById('fOt').value = r.ot_limit || '';
                document.getElementById('fOt3').value = r.ot_limit_3 || '';
                document.getElementById('fStatus').value = r.is_active || 'Y';
                document.getElementById('modalTitle').innerHTML =
                    `<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 2l3 3-9 9H2v-3z"/></svg> Edit Shift`;
                clearModalErrors('crudModal');
                crudModal.show();
            } catch (e) {
                showSetupToast('Server error.', 'red');
            }
        }
        async function saveRecord() {
            clearModalErrors('crudModal');
            let valid = true;
            if (!validateModalField('fCode', 'Code is required.')) valid = false;
            if (!validateModalField('fName', 'Name is required.')) valid = false;
            if (!valid) return;
            const id = document.getElementById('recordId').value;
            const btn = saveBtnLoading('btnSave');
            try {
                const {
                    ok,
                    data
                } = await setupFetch(id ? `${BASE_URL}/${id}` : BASE_URL, id ? 'PUT' : 'POST', {
                    shift_code: document.getElementById('fCode').value.trim().toUpperCase(),
                    shift_name: document.getElementById('fName').value.trim(),
                    sin_time: document.getElementById('fIn').value,
                    sout_time: document.getElementById('fOut').value,
                    grace_period: document.getElementById('fGrace').value,
                    grace_period_2: document.getElementById('fGrace2').value,
                    meal_time: document.getElementById('fMeal').value,
                    ot_limit: document.getElementById('fOt').value,
                    ot_limit_3: document.getElementById('fOt3').value,
                    is_active: document.getElementById('fStatus').value
                });
                saveBtnReset(btn);
                if (!ok || !data.success) {
                    showSetupToast(data.message || 'Error.', 'red');
                    return;
                }
                crudModal.hide();
                showSetupToast(data.message, 'green');
                setTimeout(() => location.reload(), 600);
            } catch (e) {
                saveBtnReset(btn);
                showSetupToast('Server error.', 'red');
            }
        }

        function askDelete(id, name) {
            setupConfirmDelete(name, async () => {
                try {
                    const {
                        ok,
                        data
                    } = await setupFetch(`${BASE_URL}/${id}`, 'DELETE');
                    if (!ok || !data.success) {
                        showSetupToast(data.message || 'Failed.', 'red');
                        return;
                    }
                    showSetupToast('Record deleted.', 'red');
                    setTimeout(() => location.reload(), 600);
                } catch (e) {
                    showSetupToast('Server error.', 'red');
                }
            });
        }
    </script>
@endpush
