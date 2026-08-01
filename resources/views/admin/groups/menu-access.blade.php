@extends('layouts.app')
@section('title', 'Group Menu Access')
@section('page-title', 'Group Menu Access')
@section('breadcrumb')
    <li class="breadcrumb-item active">Group Menu Access</li>
@endsection

@push('styles')
    <style>
        .group-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            margin-bottom: 8px;
        }

        .group-card:hover {
            border-color: #86b7fe;
            background: #f8f9ff;
        }

        .group-card.selected {
            border-color: #0d6efd;
            background: #f0f6ff;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, .15);
        }

        .perm-menu-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f3f5;
            transition: background .1s;
        }

        .perm-menu-row:last-child {
            border-bottom: none;
        }

        .perm-menu-row:hover {
            background: #f8f9ff;
        }

        .perm-menu-row.sub-item {
            padding-left: 36px;
            background: #fafbfc;
        }

        .perm-menu-row.sub-item:hover {
            background: #f0f6ff;
        }

        .form-check-input[type=checkbox] {
            width: 38px;
            height: 22px;
            cursor: pointer;
        }

        #group-panel {
            min-height: 400px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3">
        {{-- Left: Groups --}}
        <div class="col-md-4 col-lg-3">
            <div class="card">
                <div class="card-header"><i class="bi bi-people me-2"></i>User Groups</div>
                <div class="card-body p-2">
                    @foreach ($groups as $g)
                        <div class="group-card" data-gid="{{ $g->user_group_id }}"
                            onclick="selectGroup('{{ $g->user_group_id }}', '{{ $g->group_titele }}', this)">
                            <div class="fw-500" style="font-size:13px;">{{ $g->group_title }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                    style="font-size:10px;">{{ $g->user_group_id }}</span>
                                <span style="font-size:11px;color:#6c757d;">{{ $g->group_description }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Menu toggles --}}
        <div class="col-md-8 col-lg-9">
            <div class="card" id="group-panel">
                <div class="card-header d-flex align-items-center justify-content-between" id="panel-header">
                    <span><i class="bi bi-toggles me-2"></i>Menu Access — <span id="selected-group-name"
                            class="text-muted">select a group</span></span>
                    <div class="d-flex gap-2" id="panel-actions" style="display:none!important;">
                        <button class="btn btn-sm btn-outline-success" onclick="enableAll()"><i
                                class="bi bi-check-all me-1"></i>Enable All</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="disableAll()"><i
                                class="bi bi-x-circle me-1"></i>Disable All</button>
                        <button class="btn btn-sm btn-primary" onclick="saveAccess()"><i class="bi bi-save me-1"></i>Save
                            Access</button>
                    </div>
                </div>
                <div id="panel-body">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-toggles" style="font-size:42px;color:#dee2e6;display:block;margin-bottom:12px;"></i>
                        Select a user group to configure menu access
                        <div style="font-size:12px;margin-top:6px;">Saves to ALL_USER_GROUP_DETAILS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const menus = @json($menus);
        const groupAccessData = @json($groupAccess); // {GROUP_ID: {MENU_ID: 'Y'/'N'}}
        let currentGroupId = null;
        let currentAccess = {};

        function selectGroup(gid, gtitle, el) {
            document.querySelectorAll('.group-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            currentGroupId = gid;

            document.getElementById('selected-group-name').textContent = gtitle + ' (' + gid + ')';
            document.getElementById('panel-actions').style.setProperty('display', 'flex', 'important');

            currentAccess = {};
            const saved = groupAccessData[gid] || {};
            menus.forEach(m => {
                currentAccess[m.CHILD_ID] = saved[m.CHILD_ID] === 'Y';
            });
            renderToggles();
        }

        function renderToggles() {
            const typeLabel = {
                0: 'Root',
                1: 'Menu',
                2: 'Sub Menu',
                3: 'Page'
            };
            let html = '';
            menus.forEach(m => {
                const enabled = currentAccess[m.CHILD_ID] ?? false;
                const isSub = m.ITEM_TYPE >= 2;
                html += `
        <div class="perm-menu-row ${isSub ? 'sub-item' : ''}">
            <div class="d-flex align-items-center gap-2">
                ${isSub ? '<i class="bi bi-arrow-return-right text-muted" style="font-size:12px;"></i>' : ''}
                <div>
                    <div style="font-size:13px;font-weight:600;">${m.title}</div>
                    <div style="font-size:11px;color:#6c757d;">
                        <code style="background:#f1f3f5;border:1px solid #dee2e6;padding:1px 6px;border-radius:4px;font-size:10px;">${m.child_id}</code>
                        &nbsp;${typeLabel[m.item_type]||m.item_type}
                        ${m.dir ? '&nbsp;·&nbsp;' + m.dir : ''}
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span style="font-size:12px;color:#6c757d;" id="lbl-${m.CHILD_ID}">${enabled ? 'Enabled' : 'Disabled'}</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="tog-${m.CHILD_ID}" ${enabled ? 'checked' : ''}
                           onchange="onToggle('${m.CHILD_ID}', this.checked)">
                </div>
            </div>
        </div>`;
            });
            document.getElementById('panel-body').innerHTML = html ||
                '<div class="text-center py-4 text-muted">No menus configured</div>';
        }

        function onToggle(menuId, enabled) {
            currentAccess[menuId] = enabled;
            const lbl = document.getElementById('lbl-' + menuId);
            if (lbl) lbl.textContent = enabled ? 'Enabled' : 'Disabled';
        }

        function enableAll() {
            menus.forEach(m => {
                currentAccess[m.CHILD_ID] = true;
            });
            renderToggles();
        }

        function disableAll() {
            menus.forEach(m => {
                currentAccess[m.CHILD_ID] = false;
            });
            renderToggles();
        }

        function saveAccess() {
            if (!currentGroupId) return;
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

            const payload = {};
            Object.keys(currentAccess).forEach(k => payload[k] = currentAccess[k] ? 'Y' : 'N');

            fetch(`/group-menu/${currentGroupId}/save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        access: payload
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        groupAccessData[currentGroupId] = payload;
                        showToast('Menu access saved for ' + currentGroupId, 'success');
                    } else {
                        showToast('Error saving access', 'danger');
                    }
                })
                .catch(() => showToast('Network error', 'danger'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save me-1"></i>Save Access';
                });
        }

        function showToast(msg, type = 'success') {
            const el = document.createElement('div');
            el.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-3`;
            el.style.zIndex = 9999;
            el.innerHTML =
                `<i class="bi bi-${type==='success'?'check-circle':'exclamation-circle'} me-2"></i>${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 3000);
        }
    </script>
@endpush
