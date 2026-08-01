@extends('layouts.app')
@section('title', 'User Company Permission')
@section('page-title', 'User Company Permission')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Company Permission</li>
@endsection

@push('styles')
    <style>
        /* ── Layout ── */
        .perm-wrapper {
            display: flex;
            height: calc(100vh - 130px);
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }

        .col-users {
            width: 240px;
            flex-shrink: 0;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
        }

        .col-companies {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8f9fb;
        }

        .col-header {
            padding: 12px 14px;
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #6c757d;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .col-body {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .col-footer {
            padding: 10px 14px;
            background: #fff;
            border-top: 1px solid #e9ecef;
            flex-shrink: 0;
        }

        /* ── User cards ── */
        .user-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 12px;
            cursor: pointer;
            transition: all .12s;
            background: #fff;
            margin-bottom: 6px;
        }

        .user-card:hover {
            border-color: #86b7fe;
            background: #f8f9ff;
        }

        .user-card.selected {
            border-color: #0d6efd;
            background: #f0f6ff;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, .12);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
        }

        .user-meta {
            font-size: 10px;
            color: #6c757d;
            margin-top: 1px;
        }

        /* ── Company rows ── */
        .company-row {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 11px 14px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all .12s;
        }

        .company-row:hover {
            border-color: #86b7fe;
        }

        .company-row.disabled-row {
            opacity: .55;
        }

        .company-name {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
        }

        .company-id-tag {
            font-size: 10px;
            background: #f1f3f5;
            border: 1px solid #dee2e6;
            padding: 1px 6px;
            border-radius: 4px;
            color: #495057;
            font-family: monospace;
            margin-top: 2px;
            display: inline-block;
        }

        /* ── Toggles ── */
        .form-check-input[type=checkbox] {
            width: 36px;
            height: 20px;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── Source badges ── */
        .source-badge {
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 20px;
            font-weight: 500;
            flex-shrink: 0;
        }

        .source-group {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .source-user {
            background: #d1e7dd;
            color: #0a3622;
            border: 1px solid #198754;
        }

        .badge-count {
            font-size: 10px;
            background: #e9ecef;
            color: #495057;
            border-radius: 20px;
            padding: 1px 7px;
        }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex;
            gap: 6px;
            margin-bottom: 8px;
        }

        .filter-bar input,
        .filter-bar select {
            font-size: 12px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid #dee2e6;
            padding: 0 8px;
        }

        .filter-bar input {
            flex: 1;
            min-width: 0;
        }

        /* ── Empty / spinner states ── */
        .empty-state {
            text-align: center;
            padding: 40px 16px;
            color: #adb5bd;
        }

        .empty-state i {
            font-size: 36px;
            display: block;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 13px;
            margin: 0;
        }

        .loading-state {
            text-align: center;
            padding: 40px 16px;
            color: #6c757d;
        }

        .loading-state .spinner-border {
            width: 1.6rem;
            height: 1.6rem;
        }

        .loading-state p {
            font-size: 13px;
            margin-top: 10px;
        }

        /* ── Banner strip ── */
        .user-banner {
            background: #f0f6ff;
            border-bottom: 1px solid #86b7fe;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .user-banner .uid {
            font-size: 13px;
            font-weight: 600;
        }

        .user-banner .umeta {
            font-size: 11px;
            color: #6c757d;
        }

        /* ── Compact grid inside companies column ── */
        .company-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 6px;
        }

        /* ── Scrollbar ── */
        .col-body::-webkit-scrollbar { width: 4px; }
        .col-body::-webkit-scrollbar-track { background: transparent; }
        .col-body::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 4px; }
    </style>
@endpush

@section('content')
    <div class="perm-wrapper">

        {{-- ══ COL 1: USER SELECTOR ══ --}}
        <div class="col-users">
            <div class="col-header">
                <span>Users</span>
                <span class="badge-count" id="user-count">{{ $users->count() }}</span>
            </div>
            <div class="col-body" style="padding:10px;">
                <div class="filter-bar">
                    <input type="text" id="userSearch" placeholder="Search…" oninput="filterUsers()">
                    <select id="groupFilter" onchange="filterUsers()" style="width:90px;">
                        <option value="">All</option>
                        @foreach ($groups as $g)
                            <option value="{{ $g->user_group_id }}">{{ $g->user_group_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="userList">
                    @forelse($users as $user)
                        @php
                            $colors = ['#0d6efd', '#198754', '#fd7e14', '#dc3545', '#6610f2'];
                            $color  = $colors[abs(crc32($user->user_id)) % 5];
                        @endphp
                        <div class="user-card"
                             data-user-id="{{ $user->user_id }}"
                             data-group="{{ $user->user_group_id }}"
                             data-color="{{ $color }}"
                             data-role="{{ $user->user_role }}"
                             data-label="{{ strtolower($user->user_id . ' ' . $user->employee_id . ' ' . $user->user_group_id) }}"
                             onclick="selectUser(this)">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:{{ $color }};">
                                    {{ strtoupper(substr($user->user_id, 0, 2)) }}
                                </div>
                                <div style="min-width:0;">
                                    <div class="user-name text-truncate">{{ $user->user_id }}</div>
                                    <div class="user-meta">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                              style="font-size:9px;padding:1px 5px;">{{ $user->user_group_id }}</span>
                                        {{ $user->employee_id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state"><i class="bi bi-people"></i><p>No users</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ══ COL 2: COMPANY PERMISSIONS ══ --}}
        <div class="col-companies">

            {{-- User banner --}}
            <div class="user-banner" id="company-banner" style="display:none;">
                <div class="user-avatar" id="banner-avatar" style="width:30px;height:30px;font-size:11px;"></div>
                <div>
                    <div class="uid" id="banner-uid"></div>
                    <div class="umeta" id="banner-meta"></div>
                </div>
                <span id="perm-count-badge"
                      class="badge bg-success-subtle text-success border border-success-subtle ms-auto"
                      style="font-size:10px;"></span>
            </div>

            <div class="col-header">
                <span>Company Access</span>
                <div class="d-flex gap-1">
                    <input type="text" id="companySearch" placeholder="Filter companies…"
                           oninput="filterCompanies()"
                           style="font-size:12px;height:26px;border-radius:7px;border:1px solid #dee2e6;padding:0 8px;width:180px;">
                    <button class="btn btn-outline-success" style="font-size:10px;padding:2px 8px;height:26px;"
                            onclick="toggleAll(true)">All On</button>
                    <button class="btn btn-outline-secondary" style="font-size:10px;padding:2px 8px;height:26px;"
                            onclick="toggleAll(false)">All Off</button>
                </div>
            </div>

            <div class="col-body" id="company-list">
                <div class="empty-state">
                    <i class="bi bi-building"></i>
                    <p>Select a user to manage company permissions</p>
                </div>
            </div>

            <div class="col-footer" id="save-footer" style="display:none;">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary btn-sm flex-grow-1" onclick="savePermissions(event)">
                        <i class="bi bi-save me-1"></i>Save
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="resetUserOverrides(event)"
                            title="Delete user overrides and revert to group defaults">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <span class="d-block mt-1" style="font-size:11px;" id="save-status"></span>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // ═══════════════════════════════════════════════════════════════════════════════
        //  STATE
        // ═══════════════════════════════════════════════════════════════════════════════
        let selectedUser = null;   // { userId, groupId, role, color }

        // currentPerms : { [companyId]: { enabled: bool, source: 'user'|'group', company_name } }
        // dirtyPerms   : { [companyId]: bool }  — unsaved changes
        let currentPerms = {};
        let dirtyPerms   = {};

        // ─── CSRF ─────────────────────────────────────────────────────────────────────
        function getCsrf() {
            const el = document.querySelector('meta[name="csrf-token"]');
            return el ? el.getAttribute('content') : '';
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  FILTER USERS (col 1)
        // ═══════════════════════════════════════════════════════════════════════════════
        function filterUsers() {
            const q   = document.getElementById('userSearch').value.toLowerCase();
            const grp = document.getElementById('groupFilter').value;
            document.querySelectorAll('#userList .user-card').forEach(card => {
                const ok = (!q || card.dataset.label.includes(q)) &&
                           (!grp || card.dataset.group === grp);
                card.style.display = ok ? 'block' : 'none';
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  FILTER COMPANIES (col 2 inline search)
        // ═══════════════════════════════════════════════════════════════════════════════
        function filterCompanies() {
            const q = document.getElementById('companySearch').value.toLowerCase();
            document.querySelectorAll('#company-list .company-row').forEach(row => {
                const ok = !q || row.dataset.label.includes(q);
                row.style.display = ok ? 'flex' : 'none';
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  SELECT USER → AJAX load companies
        // ═══════════════════════════════════════════════════════════════════════════════
        function selectUser(el) {
            document.querySelectorAll('.user-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');

            const userId  = el.dataset.userId;
            const groupId = el.dataset.group;
            const role    = el.dataset.role;
            const color   = el.dataset.color;

            selectedUser = { userId, groupId, role, color };

            // Reset state
            currentPerms = {};
            dirtyPerms   = {};

            // Update banner
            const av = document.getElementById('banner-avatar');
            av.textContent    = userId.slice(0, 2).toUpperCase();
            av.style.background = color;
            document.getElementById('banner-uid').textContent  = userId;
            document.getElementById('banner-meta').textContent = `${groupId} · ${role}`;
            document.getElementById('company-banner').style.display = 'flex';
            document.getElementById('save-footer').style.display    = 'block';
            document.getElementById('perm-count-badge').textContent = '';
            document.getElementById('companySearch').value           = '';

            // Show spinner
            document.getElementById('company-list').innerHTML = `
                <div class="loading-state">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p>Loading companies…</p>
                </div>`;

            fetch(`/user-company/${encodeURIComponent(userId)}/companies`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(data => {
                (data.companies || []).forEach(c => {
                    currentPerms[c.company_id] = {
                        enabled:      c.enabled,
                        source:       c.source,
                        company_name: c.company_name,
                    };
                });
                renderCompanyList(data.companies || []);
                updateCount();
            })
            .catch(err => {
                document.getElementById('company-list').innerHTML =
                    `<div class="empty-state"><i class="bi bi-exclamation-triangle text-danger"></i><p>${err.message}</p></div>`;
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  RENDER COMPANY LIST
        // ═══════════════════════════════════════════════════════════════════════════════
        function renderCompanyList(companies) {
            const container = document.getElementById('company-list');

            if (!companies || !companies.length) {
                container.innerHTML = '<div class="empty-state"><i class="bi bi-building"></i><p>No companies found</p></div>';
                return;
            }

            let html = '<div class="company-grid">';
            companies.forEach(c => {
                const cid     = c.company_id;
                const perm    = currentPerms[cid] || {};
                const enabled = isDirty(cid) ? dirtyPerms[cid] : perm.enabled;

                const srcBadge = (perm.source === 'user')
                    ? '<span class="source-badge source-user">User</span>'
                    : '<span class="source-badge source-group">Group</span>';

                html += `
<div class="company-row ${!enabled ? 'disabled-row' : ''}"
     data-company-id="${cid}"
     data-label="${(c.company_name + ' ' + cid).toLowerCase()}">
    <div style="min-width:0;flex:1;">
        <div class="company-name text-truncate">${c.company_name}</div>
        <span class="company-id-tag">${cid}</span>
        <span class="ms-1" id="src-badge-${cid}">${srcBadge}</span>
    </div>
    <div class="form-check form-switch mb-0" style="flex-shrink:0;">
        <input class="form-check-input company-toggle" type="checkbox"
               data-company-id="${cid}"
               id="toggle-${cid}"
               ${enabled ? 'checked' : ''}>
    </div>
</div>`;
            });
            html += '</div>';

            container.innerHTML = html;

            // Attach listeners
            container.querySelectorAll('.company-toggle').forEach(inp => {
                inp.onchange = () => onToggle(inp.dataset.companyId, inp.checked);
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  TOGGLE HANDLER
        // ═══════════════════════════════════════════════════════════════════════════════
        function onToggle(cid, enabled) {
            dirtyPerms[cid] = enabled;

            // Update row visual in-place
            const row = document.querySelector(`.company-row[data-company-id="${cid}"]`);
            if (row) {
                row.classList.toggle('disabled-row', !enabled);
                // Update source badge to 'user' since it's been changed manually
                const badgeEl = document.getElementById(`src-badge-${cid}`);
                if (badgeEl) badgeEl.innerHTML = '<span class="source-badge source-user">User</span>';
            }

            updateCount();
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  TOGGLE ALL
        // ═══════════════════════════════════════════════════════════════════════════════
        function toggleAll(enabled) {
            Object.keys(currentPerms).forEach(cid => {
                dirtyPerms[cid] = enabled;
            });
            updateCount();

            // Re-render with fresh dirty state
            const companies = Object.entries(currentPerms).map(([cid, p]) => ({
                company_id:   cid,
                company_name: p.company_name,
                enabled:      p.enabled,
                source:       p.source,
            }));
            renderCompanyList(companies);
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  COUNT BADGE
        // ═══════════════════════════════════════════════════════════════════════════════
        function updateCount() {
            if (!selectedUser) return;
            const total   = Object.keys(currentPerms).length;
            const enabled = Object.keys(currentPerms).filter(cid => {
                return isDirty(cid) ? dirtyPerms[cid] : currentPerms[cid].enabled;
            }).length;
            const badge = document.getElementById('perm-count-badge');
            if (badge) badge.textContent = `${enabled}/${total} companies`;
        }

        function isDirty(cid) {
            return dirtyPerms.hasOwnProperty(cid);
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  BUILD PAYLOAD
        // ═══════════════════════════════════════════════════════════════════════════════
        function buildPayload() {
            const payload = {};
            Object.keys(currentPerms).forEach(cid => {
                payload[cid] = isDirty(cid) ? dirtyPerms[cid] : currentPerms[cid].enabled;
            });
            return payload;
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  SAVE
        // ═══════════════════════════════════════════════════════════════════════════════
        function savePermissions(event) {
            if (!selectedUser) return;
            const btn = (event && (event.currentTarget || event.target)) || document.activeElement;
            btn.disabled  = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

            const fd = new FormData();
            fd.append('_token',        getCsrf());
            fd.append('user_group_id', selectedUser.groupId);
            fd.append('permissions',   JSON.stringify(buildPayload()));

            fetch(`/user-company/${encodeURIComponent(selectedUser.userId)}/save`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':     getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
            .then(async r => {
                if (!r.ok) throw new Error(r.status === 419 ? 'Session expired — refresh page.' : `Error ${r.status}`);
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    // Commit dirty into currentPerms
                    Object.entries(dirtyPerms).forEach(([cid, val]) => {
                        if (currentPerms[cid]) {
                            currentPerms[cid].enabled = val;
                            currentPerms[cid].source  = 'user';
                        }
                    });
                    dirtyPerms = {};
                    showStatus('Saved successfully');
                } else {
                    showStatus('Error: ' + (data.message || 'Save failed'), true);
                }
            })
            .catch(e => showStatus(e.message || 'Network error', true))
            .finally(() => {
                btn.disabled  = false;
                btn.innerHTML = '<i class="bi bi-save me-1"></i>Save';
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  RESET (delete user overrides → revert to group defaults)
        // ═══════════════════════════════════════════════════════════════════════════════
        function resetUserOverrides(event) {
            if (!selectedUser || !confirm('Delete user overrides and revert to group defaults?')) return;
            const btn = (event && (event.currentTarget || event.target)) || document.activeElement;
            btn.disabled = true;

            const fd = new FormData();
            fd.append('_token',        getCsrf());
            fd.append('user_group_id', selectedUser.groupId);

            fetch(`/user-company/${encodeURIComponent(selectedUser.userId)}/reset`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':     getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
            .then(async r => {
                if (!r.ok) throw new Error(r.status === 419 ? 'Session expired — refresh page.' : `Error ${r.status}`);
                return r.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Reset failed');

                currentPerms = {};
                dirtyPerms   = {};

                // Re-fetch companies (will now show group defaults)
                document.getElementById('company-list').innerHTML = `
                    <div class="loading-state">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Reloading…</p>
                    </div>`;
                document.getElementById('perm-count-badge').textContent = '';

                showStatus('Reset to group defaults');

                return fetch(`/user-company/${encodeURIComponent(selectedUser.userId)}/companies`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
            })
            .then(r => r && r.json())
            .then(data => {
                if (!data) return;
                (data.companies || []).forEach(c => {
                    currentPerms[c.company_id] = {
                        enabled:      c.enabled,
                        source:       c.source,
                        company_name: c.company_name,
                    };
                });
                renderCompanyList(data.companies || []);
                updateCount();
            })
            .catch(e => showStatus(e.message || 'Network error', true))
            .finally(() => { btn.disabled = false; });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  STATUS
        // ═══════════════════════════════════════════════════════════════════════════════
        function showStatus(msg, isError = false) {
            const el = document.getElementById('save-status');
            el.textContent  = msg;
            el.style.color  = isError ? '#dc3545' : '#198754';
            setTimeout(() => { el.textContent = ''; }, 4000);
        }
    </script>
@endpush
