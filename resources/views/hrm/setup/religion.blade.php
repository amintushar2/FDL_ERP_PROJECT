@extends('layouts.app') @section('title', 'Religion Setup') @include('hrm.setup.partials.styles')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="setup-page-wrap">
        @include('hrm.setup.partials.sidebar')
        <div class="setup-main">
            <div class="setup-page-header">
                <h4>Religion Setup</h4>
                <p class="bc">HRM <span>›</span> Setup <span>›</span> Religion</p>
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
                            <path d="M8 2l1.5 4.5H14l-3.7 2.7 1.4 4.3L8 11l-3.7 2.5 1.4-4.3L2 6.5h4.5z" />
                        </svg>Religion</div>
                    <div class="sec-line"></div>
                </div>
                <div class="setup-card">
                    <div class="setup-card-hd">
                        <div class="card-hd-left">
                            <div class="card-dot"></div><span class="card-ttl">Religion Setup</span>
                        </div>
                        <div class="d-flex align-items-center gap-2"><input class="card-search" id="cardSearch"
                                placeholder="Search…"><button class="btn-add-record" onclick="openAddModal()"><svg
                                    width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="8" y1="2" x2="8" y2="14" />
                                    <line x1="2" y1="8" x2="14" y2="8" />
                                </svg>+ Add Religion</button></div>
                    </div>
                    <div class="table-responsive">
                        <table class="setup-table">
                            <thead>
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th style="width:120px">RELIGION ID</th>
                                    <th>RELIGION NAME</th>
                                    <th style="width:140px">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $i=>$row)
                                    <tr data-id="{{ $row->religion_id }}">
                                        <td class="mono">{{ str_pad($records->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td><span class="mono">{{ $row->religion_id }}</span></td>
                                        <td style="font-weight:700">{{ $row->religion_name }}</td>

                                        <td><button class="btn-row-edit"
                                                onclick="openEditModal({{ $row->religion_id }})"><svg width="11"
                                                    height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M11 2l3 3-9 9H2v-3z" />
                                                </svg>Edit</button><button class="btn-row-del"
                                                onclick="askDelete({{ $row->religion_id }},'{{ addslashes($row->religion_name) }}')"><svg
                                                    width="11" height="11" viewBox="0 0 16 16" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3,4 13,4" />
                                                    <path d="M5 4V2h6v2M4 4l1 10h6l1-10" />
                                                </svg>Delete</button></td>
                                    </tr>
                                @empty<tr>
                                        <td colspan="5">
                                            <div class="empty-state"><svg width="42" height="42" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                                    <path d="M8 10h8M8 14h5" />
                                                </svg>
                                                <p>No religions found.</p>
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
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
            <div class="modal-content">
                <div class="modal-header modal-header-navy"><span class="modal-title" id="modalTitle"><svg
                            width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2.2">
                            <line x1="8" y1="2" x2="8" y2="14" />
                            <line x1="2" y1="8" x2="14" y2="8" />
                        </svg> Add Religion</span><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4"><input type="hidden" id="recordId">
                    <div class="mb-3"><label class="f-lbl">Religion ID <span
                                class="text-danger">*</span></label><input type="number" id="fId" class="f-inp"
                            placeholder="e.g. 5">
                        <div class="err-msg" id="fId_err"></div>
                    </div>
                    <div class="mb-3"><label class="f-lbl">Religion Name <span
                                class="text-danger">*</span></label><input type="text" id="fName" class="f-inp"
                            placeholder="e.g. Islam">
                        <div class="err-msg" id="fName_err"></div>
                    </div>
                    <div class="mb-0"><label class="f-lbl">Status</label><select id="fStatus" class="f-sel">
                            <option value="Y">Active</option>
                            <option value="N">Inactive</option>
                        </select></div>
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
        const BASE_URL = '{{ url('setup/religion') }}';
        const crudModal = new bootstrap.Modal(document.getElementById('crudModal'));

        function openAddModal() {
            ['recordId', 'fId', 'fName'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('fStatus').value = 'Y';
            document.getElementById('fId').readOnly = false;
            document.getElementById('modalTitle').innerHTML =
                `<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/></svg> Add Religion`;
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
                document.getElementById('recordId').value = r.religion_id;
                document.getElementById('fId').value = r.religion_id;
                document.getElementById('fId').readOnly = true;
                document.getElementById('fName').value = r.religion_name || '';
                document.getElementById('fStatus').value = r.is_active || 'Y';
                document.getElementById('modalTitle').innerHTML =
                    `<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 2l3 3-9 9H2v-3z"/></svg> Edit Religion`;
                clearModalErrors('crudModal');
                crudModal.show();
            } catch (e) {
                showSetupToast('Server error.', 'red');
            }
        }
        async function saveRecord() {
            clearModalErrors('crudModal');
            let valid = true;
            if (!validateModalField('fId', 'Religion ID is required.')) valid = false;
            if (!validateModalField('fName', 'Name is required.')) valid = false;
            if (!valid) return;
            const id = document.getElementById('recordId').value;
            const btn = saveBtnLoading('btnSave');
            try {
                const {
                    ok,
                    data
                } = await setupFetch(id ? `${BASE_URL}/${id}` : BASE_URL, id ? 'PUT' : 'POST', {
                    religion_id: document.getElementById('fId').value,
                    religion_name: document.getElementById('fName').value.trim(),
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
