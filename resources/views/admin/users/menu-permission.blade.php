@extends('layouts.app')
@section('title', 'User Menu Permission')
@section('page-title', 'User Menu Permission')
@section('breadcrumb')
    <li class="breadcrumb-item active">User Menu Permission</li>
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

        .col-menus {
            width: 320px;
            flex-shrink: 0;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            background: #f8f9fb;
        }

        .col-routes {
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

        /* ── Menu rows ── */
        .menu-row {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 6px;
            cursor: pointer;
            transition: all .12s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .menu-row:hover {
            border-color: #86b7fe;
        }

        .menu-row.selected {
            border-color: #0d6efd;
            background: #f0f6ff;
        }

        .menu-row.disabled-row {
            opacity: .55;
        }

        .menu-title {
            font-size: 13px;
            font-weight: 600;
        }

        .menu-meta {
            font-size: 11px;
            color: #6c757d;
            margin-top: 1px;
        }

        .menu-row.level-2 {
            margin-left: 16px;
        }

        .menu-row.level-3 {
            margin-left: 32px;
        }

        /* ── Route rows ── */
        .route-row {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            transition: all .12s;
        }

        .route-row:hover {
            border-color: #86b7fe;
        }

        .route-name {
            font-size: 13px;
            font-weight: 500;
        }

        .route-path {
            font-size: 11px;
            font-family: monospace;
            background: #f1f3f5;
            border: 1px solid #dee2e6;
            padding: 1px 6px;
            border-radius: 4px;
            color: #495057;
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

        /* ── Badges ── */
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

        /* ── Scrollbar ── */
        .col-body::-webkit-scrollbar {
            width: 4px;
        }

        .col-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .col-body::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 4px;
        }
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
                            $color = $colors[abs(crc32($user->user_id)) % 5];
                        @endphp
                        <div class="user-card" data-user-id="{{ $user->user_id }}" data-group="{{ $user->user_group_id }}"
                            data-color="{{ $color }}" data-role="{{ $user->user_role }}"
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
                        <div class="empty-state"><i class="bi bi-people"></i>
                            <p>No users</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ══ COL 2: MENU LIST ══ --}}
        <div class="col-menus">
            {{-- user banner --}}
            <div class="user-banner" id="menu-banner" style="display:none;">
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
                <span>Menu Access</span>
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-success" style="font-size:10px;padding:2px 8px;height:24px;"
                        onclick="toggleAll(true)">All On</button>
                    <button class="btn btn-outline-secondary" style="font-size:10px;padding:2px 8px;height:24px;"
                        onclick="toggleAll(false)">All Off</button>
                </div>
            </div>

            <div class="col-body" id="menu-list">
                <div class="empty-state"><i class="bi bi-person-check"></i>
                    <p>Select a user</p>
                </div>
            </div>

            <div class="col-footer" id="save-footer" style="display:none;">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary btn-sm flex-grow-1" onclick="savePermissions(event)">
                        <i class="bi bi-save me-1"></i>Save
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="resetUserOverrides(event)"
                        title="Delete overrides">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <span class="d-block mt-1" style="font-size:11px;" id="save-status"></span>
            </div>
        </div>

        {{-- ══ COL 3: ROUTE LIST ══ --}}
        <div class="col-routes">
            <div class="col-header" id="route-col-header">
                <span id="route-col-title">Routes</span>
            </div>
            <div class="col-body" id="route-list">
                <div class="empty-state"><i class="bi bi-signpost-split"></i>
                    <p>Select a menu to see its routes</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // ═══════════════════════════════════════════════════════════════════════════════
        //  STATE
        // ═══════════════════════════════════════════════════════════════════════════════
        let selectedUser = null; // { userId, groupId, role, color }
        let selectedMenuId = null;

        // In-memory permission state built from AJAX responses
        // currentMenuPerms  : { [menuId]: { enabled: bool, source: 'user'|'group', total_routes, enabled_routes } }
        // currentRoutePerms : { [menuId]: { [routeId]: { enabled: bool, source: 'user'|'group' } } }
        // dirtyMenuPerms    : { [menuId]: bool }           — changed menu toggles (unsaved)
        // dirtyRoutePerms   : { [menuId]: { [routeId]: bool } } — changed route toggles (unsaved)
        let currentMenuPerms = {};
        let currentRoutePerms = {};
        let dirtyMenuPerms = {};
        let dirtyRoutePerms = {};

        // ─── CSRF ─────────────────────────────────────────────────────────────────────
        function getCsrf() {
            const el = document.querySelector('meta[name="csrf-token"]');
            return el ? el.getAttribute('content') : '';
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  FILTER USERS (col 1)
        // ═══════════════════════════════════════════════════════════════════════════════
        function filterUsers() {
            const q = document.getElementById('userSearch').value.toLowerCase();
            const grp = document.getElementById('groupFilter').value;
            document.querySelectorAll('#userList .user-card').forEach(card => {
                const ok = (!q || card.dataset.label.includes(q)) &&
                    (!grp || card.dataset.group === grp);
                card.style.display = ok ? 'block' : 'none';
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  SELECT USER → AJAX load menus
        // ═══════════════════════════════════════════════════════════════════════════════
        function selectUser(el) {
            document.querySelectorAll('.user-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');

            const userId = el.dataset.userId;
            const groupId = el.dataset.group;
            const role = el.dataset.role;
            const color = el.dataset.color;

            selectedUser = {
                userId,
                groupId,
                role,
                color
            };
            selectedMenuId = null;

            // Reset state
            currentMenuPerms = {};
            currentRoutePerms = {};
            dirtyMenuPerms = {};
            dirtyRoutePerms = {};

            // Update banner
            const av = document.getElementById('banner-avatar');
            av.textContent = userId.slice(0, 2).toUpperCase();
            av.style.background = color;
            document.getElementById('banner-uid').textContent = userId;
            document.getElementById('banner-meta').textContent = `${groupId} · ${role}`;
            document.getElementById('menu-banner').style.display = 'flex';
            document.getElementById('save-footer').style.display = 'block';

            // Clear route column
            renderRouteListEmpty();

            // Show spinner in menu col
            document.getElementById('menu-list').innerHTML = `
        <div class="loading-state">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Loading menus…</p>
        </div>`;
            document.getElementById('perm-count-badge').textContent = '';

            // AJAX: fetch menus
            fetch(`/user-menu/${encodeURIComponent(userId)}/menus`, {
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
                    // Store into state
                    (data.menus || []).forEach(m => {
                        currentMenuPerms[m.child_id] = {
                            enabled: m.enabled,
                            source: m.source,
                            total_routes: m.total_routes,
                            enabled_routes: m.enabled_routes,
                            title: m.title,
                            item_type: m.item_type,
                        };
                    });
                    renderMenuList(data.menus || []);
                    updateCount();
                })
                .catch(err => {
                    document.getElementById('menu-list').innerHTML =
                        `<div class="empty-state"><i class="bi bi-exclamation-triangle text-danger"></i><p>${err.message}</p></div>`;
                });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  RENDER MENU LIST (col 2)
        // ═══════════════════════════════════════════════════════════════════════════════
        function renderMenuList(menus) {
            const container = document.getElementById('menu-list');
            if (!menus || !menus.length) {
                container.innerHTML = '<div class="empty-state"><i class="bi bi-layout-sidebar"></i><p>No menus</p></div>';
                return;
            }

            let html = '';
            menus.forEach(menu => {
                const mid = menu.child_id;
                const perm = currentMenuPerms[mid] || {};
                const enabled = isDirtyMenu(mid) ? dirtyMenuPerms[mid] : perm.enabled;
                const eRoutes = getEnabledRouteCount(mid);
                const tRoutes = perm.total_routes ?? 0;

                const levelClass = menu.item_type === 2 ? 'level-2' :
                    menu.item_type === 3 ? 'level-3' : '';
                const isSelected = selectedMenuId === mid;
                const srcBadge = perm.source === 'user' ?
                    '<span class="source-badge source-user">User</span>' :
                    '<span class="source-badge source-group">Group</span>';

                html += `
<div class="menu-row ${levelClass} ${isSelected ? 'selected' : ''} ${!enabled ? 'disabled-row' : ''}"
     data-menu-id="${mid}"
     onclick="selectMenu('${mid}')">
    <div style="min-width:0;flex:1;">
        <div class="menu-title text-truncate">${menu.title}</div>
        <div class="menu-meta d-flex align-items-center gap-1 flex-wrap" id="menu-meta-${mid}">
            <code style="font-size:10px;background:#f1f3f5;border:1px solid #dee2e6;padding:1px 5px;border-radius:3px;">${mid}</code>
            ${tRoutes > 0
                ? `<span style="color:#198754;font-size:10px;" id="route-count-${mid}">${eRoutes}/${tRoutes} routes</span>`
                : '<span style="font-size:10px;color:#adb5bd;">no routes</span>'
            }
            ${srcBadge}
        </div>
    </div>
    <div class="form-check form-switch mb-0" onclick="event.stopPropagation()">
        <input class="form-check-input menu-toggle" type="checkbox"
               id="menu-toggle-${mid}"
               data-menu-id="${mid}"
               ${enabled ? 'checked' : ''}>
    </div>
</div>`;
            });

            container.innerHTML = html;

            // Attach listeners
            container.querySelectorAll('.menu-toggle').forEach(inp => {
                inp.onchange = () => onMenuToggle(inp.dataset.menuId, inp.checked);
            });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  SELECT MENU → AJAX load routes (col 3)
        // ═══════════════════════════════════════════════════════════════════════════════
        function selectMenu(mid) {
            selectedMenuId = mid;

            // Highlight selected row in col 2 without full re-render
            document.querySelectorAll('#menu-list .menu-row').forEach(r => {
                r.classList.toggle('selected', r.dataset.menuId === mid);
            });

            // Show spinner in route col
            const perm = currentMenuPerms[mid] || {};
            document.getElementById('route-col-title').textContent = `Routes — ${perm.title || mid}`;
            document.getElementById('route-list').innerHTML = `
        <div class="loading-state">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Loading routes…</p>
        </div>`;

            // If routes already cached, render immediately
            if (currentRoutePerms[mid]) {
                renderRouteList(mid, Object.entries(currentRoutePerms[mid]).map(([rid, v]) => ({
                    route_id: rid,
                    route_path: v.route_path,
                    component: v.component,
                    enabled: v.enabled,
                    source: v.source,
                })));
                return;
            }

            // AJAX: fetch routes for this menu
            fetch(`/user-menu/${encodeURIComponent(selectedUser.userId)}/routes/${encodeURIComponent(mid)}`, {
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
                    const routes = data.routes || [];

                    // Cache into state
                    if (!currentRoutePerms[mid]) currentRoutePerms[mid] = {};
                    routes.forEach(r => {
                        currentRoutePerms[mid][r.route_id] = {
                            enabled: r.enabled,
                            source: r.source,
                            route_path: r.route_path,
                            component: r.component,
                        };
                    });

                    renderRouteList(mid, routes);
                })
                .catch(err => {
                    document.getElementById('route-list').innerHTML =
                        `<div class="empty-state"><i class="bi bi-exclamation-triangle text-danger"></i><p>${err.message}</p></div>`;
                });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  RENDER ROUTE LIST (col 3)
        // ═══════════════════════════════════════════════════════════════════════════════
        function renderRouteList(mid, routes) {
            const container = document.getElementById('route-list');

            if (!routes || !routes.length) {
                container.innerHTML =
                    '<div class="empty-state"><i class="bi bi-signpost"></i><p>No routes for this menu</p></div>';
                return;
            }

            let html = '';
            routes.forEach(r => {
                const rid = r.route_id;
                // Respect dirty (unsaved) toggle state if present
                const enabled = (dirtyRoutePerms[mid] && dirtyRoutePerms[mid][rid] !== undefined) ?
                    dirtyRoutePerms[mid][rid] :
                    r.enabled;

                const srcBadge = r.source === 'user' ?
                    '<span class="source-badge source-user">User</span>' :
                    '<span class="source-badge source-group">Group</span>';

                html += `
<div class="route-row">
    <div style="min-width:0;flex:1;">
        <div class="route-name text-truncate">${r.component || rid}</div>
        <span class="route-path">${r.route_path}</span>
    </div>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <span style="font-size:11px;color:#6c757d;">${rid}</span>
        ${srcBadge}
        <div class="form-check form-switch mb-0">
            <input class="form-check-input route-toggle" type="checkbox"
                   data-menu-id="${mid}"
                   data-route-id="${rid}"
                   ${enabled ? 'checked' : ''}>
        </div>
    </div>
</div>`;
            });

            container.innerHTML = html;

            // Attach listeners
            container.querySelectorAll('.route-toggle').forEach(inp => {
                inp.onchange = () => onRouteToggle(inp.dataset.menuId, inp.dataset.routeId, inp.checked);
            });
        }

        function renderRouteListEmpty() {
            document.getElementById('route-col-title').textContent = 'Routes';
            document.getElementById('route-list').innerHTML =
                '<div class="empty-state"><i class="bi bi-signpost-split"></i><p>Select a menu to see its routes</p></div>';
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  MENU TOGGLE
        // ═══════════════════════════════════════════════════════════════════════════════
        function onMenuToggle(mid, enabled) {
            // Mark dirty
            dirtyMenuPerms[mid] = enabled;

            // Also cascade to any cached routes for this menu
            if (currentRoutePerms[mid]) {
                if (!dirtyRoutePerms[mid]) dirtyRoutePerms[mid] = {};
                Object.keys(currentRoutePerms[mid]).forEach(rid => {
                    dirtyRoutePerms[mid][rid] = enabled;
                });
            }

            updateCount();
            updateMenuRowInPlace(mid);

            // If routes are showing for this menu, re-render them to reflect new toggle states
            if (selectedMenuId === mid && currentRoutePerms[mid]) {
                renderRouteList(mid, Object.entries(currentRoutePerms[mid]).map(([rid, v]) => ({
                    route_id: rid,
                    route_path: v.route_path,
                    component: v.component,
                    enabled: v.enabled,
                    source: v.source,
                })));
            }
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  ROUTE TOGGLE
        // ═══════════════════════════════════════════════════════════════════════════════
        function onRouteToggle(mid, rid, enabled) {
            if (!dirtyRoutePerms[mid]) dirtyRoutePerms[mid] = {};
            dirtyRoutePerms[mid][rid] = enabled;

            // Derive menu enabled state: if any route is on → menu on
            const allRouteStates = {
                ...((currentRoutePerms[mid] || {}))
            };
            // merge dirty on top
            Object.assign(allRouteStates, dirtyRoutePerms[mid] || {});

            const anyEnabled = Object.values(allRouteStates).some(v =>
                typeof v === 'boolean' ? v : v.enabled
            );

            dirtyMenuPerms[mid] = anyEnabled;
            updateCount();
            updateMenuRowInPlace(mid);
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  UPDATE A SINGLE MENU ROW IN-PLACE
        // ═══════════════════════════════════════════════════════════════════════════════
        function updateMenuRowInPlace(mid) {
            const row = document.querySelector(`#menu-list .menu-row[data-menu-id="${mid}"]`);
            if (!row) return;

            const perm = currentMenuPerms[mid] || {};
            const enabled = isDirtyMenu(mid) ? dirtyMenuPerms[mid] : perm.enabled;

            // Toggle checkbox
            const toggle = row.querySelector('.menu-toggle');
            if (toggle) toggle.checked = enabled;

            // Route count badge
            const countEl = document.getElementById(`route-count-${mid}`);
            if (countEl) {
                const eRoutes = getEnabledRouteCount(mid);
                const tRoutes = perm.total_routes ?? 0;
                countEl.textContent = `${eRoutes}/${tRoutes} routes`;
            }

            row.classList.toggle('disabled-row', !enabled);
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  HELPERS
        // ═══════════════════════════════════════════════════════════════════════════════
        function isDirtyMenu(mid) {
            return dirtyMenuPerms.hasOwnProperty(mid);
        }

        function getEnabledRouteCount(mid) {
            const cached = currentRoutePerms[mid] || {};
            const dirty = dirtyRoutePerms[mid] || {};

            // Merge dirty on top of cached
            const merged = {};
            Object.entries(cached).forEach(([rid, v]) => {
                merged[rid] = typeof v === 'boolean' ? v : v.enabled;
            });
            Object.entries(dirty).forEach(([rid, v]) => {
                merged[rid] = v;
            });

            return Object.values(merged).filter(Boolean).length;
        }

        function updateCount() {
            if (!selectedUser) return;
            const total = Object.keys(currentMenuPerms).length;
            const enabled = Object.entries(currentMenuPerms).filter(([mid]) => {
                return isDirtyMenu(mid) ? dirtyMenuPerms[mid] : currentMenuPerms[mid].enabled;
            }).length;
            const badge = document.getElementById('perm-count-badge');
            if (badge) badge.textContent = `${enabled}/${total} menus`;
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  TOGGLE ALL
        // ═══════════════════════════════════════════════════════════════════════════════
        function toggleAll(enabled) {
            Object.keys(currentMenuPerms).forEach(mid => {
                dirtyMenuPerms[mid] = enabled;
                if (currentRoutePerms[mid]) {
                    if (!dirtyRoutePerms[mid]) dirtyRoutePerms[mid] = {};
                    Object.keys(currentRoutePerms[mid]).forEach(rid => {
                        dirtyRoutePerms[mid][rid] = enabled;
                    });
                }
            });

            updateCount();

            // Re-render menu list preserving menu data
            const menus = Object.entries(currentMenuPerms).map(([mid, m]) => ({
                child_id: mid,
                title: m.title,
                item_type: m.item_type,
            }));
            renderMenuList(menus);

            if (selectedMenuId && currentRoutePerms[selectedMenuId]) {
                renderRouteList(selectedMenuId, Object.entries(currentRoutePerms[selectedMenuId]).map(([rid, v]) => ({
                    route_id: rid,
                    route_path: v.route_path,
                    component: v.component,
                    enabled: v.enabled,
                    source: v.source,
                })));
            }
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  BUILD PERMISSIONS PAYLOAD for save
        // ═══════════════════════════════════════════════════════════════════════════════
        function buildPermissionsPayload() {
            const payload = {};

            Object.keys(currentMenuPerms).forEach(mid => {
                const menuEnabled = isDirtyMenu(mid) ?
                    dirtyMenuPerms[mid] :
                    currentMenuPerms[mid].enabled;

                const routeMap = {};
                const cached = currentRoutePerms[mid] || {};
                const dirty = dirtyRoutePerms[mid] || {};

                // Start with cached
                Object.entries(cached).forEach(([rid, v]) => {
                    routeMap[rid] = typeof v === 'boolean' ? v : v.enabled;
                });
                // Override with dirty
                Object.entries(dirty).forEach(([rid, v]) => {
                    routeMap[rid] = v;
                });

                payload[mid] = {
                    enabled: menuEnabled,
                    routes: routeMap
                };
            });

            return payload;
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  SAVE
        // ═══════════════════════════════════════════════════════════════════════════════
        function savePermissions(event) {
            if (!selectedUser) return;
            const btn = (event && (event.currentTarget || event.target)) || document.activeElement;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

            const fd = new FormData();
            fd.append('_token', getCsrf());
            fd.append('user_group_id', selectedUser.groupId);
            fd.append('permissions', JSON.stringify(buildPermissionsPayload()));

            fetch(`/user-menu/${encodeURIComponent(selectedUser.userId)}/save`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrf(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(async r => {
                    if (!r.ok) throw new Error(r.status === 419 ? 'Session expired — refresh page.' :
                        `Error ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    if (data.success) {
                        // Commit dirty into currentMenuPerms / currentRoutePerms
                        Object.entries(dirtyMenuPerms).forEach(([mid, val]) => {
                            if (currentMenuPerms[mid]) currentMenuPerms[mid].enabled = val;
                        });
                        Object.entries(dirtyRoutePerms).forEach(([mid, routes]) => {
                            Object.entries(routes).forEach(([rid, val]) => {
                                if (currentRoutePerms[mid] && currentRoutePerms[mid][rid]) {
                                    currentRoutePerms[mid][rid].enabled = val;
                                    currentRoutePerms[mid][rid].source = 'user';
                                }
                            });
                        });
                        dirtyMenuPerms = {};
                        dirtyRoutePerms = {};
                        showStatus('Saved successfully');
                    } else {
                        showStatus('Error: ' + (data.message || 'Save failed'), true);
                    }
                })
                .catch(e => showStatus(e.message || 'Network error', true))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save me-1"></i>Save';
                });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  RESET
        // ═══════════════════════════════════════════════════════════════════════════════
        function resetUserOverrides(event) {
            if (!selectedUser || !confirm('Delete user overrides and revert to group defaults?')) return;
            const btn = (event && (event.currentTarget || event.target)) || document.activeElement;
            btn.disabled = true;

            const fd = new FormData();
            fd.append('_token', getCsrf());
            fd.append('user_group_id', selectedUser.groupId);

            fetch(`/user-menu/${encodeURIComponent(selectedUser.userId)}/reset`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrf(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(async r => {
                    if (!r.ok) throw new Error(r.status === 419 ? 'Session expired — refresh page.' :
                        `Error ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Reset failed');

                    // Clear all state and reload menus from server (now group defaults)
                    currentMenuPerms = {};
                    currentRoutePerms = {};
                    dirtyMenuPerms = {};
                    dirtyRoutePerms = {};
                    selectedMenuId = null;

                    renderRouteListEmpty();
                    showStatus('Reset to group defaults');

                    // Re-fetch menus (now will show group defaults since user rows deleted)
                    document.getElementById('menu-list').innerHTML = `
            <div class="loading-state">
                <div class="spinner-border text-primary" role="status"></div>
                <p>Reloading menus…</p>
            </div>`;

                    return fetch(`/user-menu/${encodeURIComponent(selectedUser.userId)}/menus`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                })
                .then(r => r && r.json())
                .then(data => {
                    if (!data) return;
                    (data.menus || []).forEach(m => {
                        currentMenuPerms[m.child_id] = {
                            enabled: m.enabled,
                            source: m.source,
                            total_routes: m.total_routes,
                            enabled_routes: m.enabled_routes,
                            title: m.title,
                            item_type: m.item_type,
                        };
                    });
                    renderMenuList(data.menus || []);
                    updateCount();
                })
                .catch(e => showStatus(e.message || 'Network error', true))
                .finally(() => {
                    btn.disabled = false;
                });
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        //  STATUS
        // ═══════════════════════════════════════════════════════════════════════════════
        function showStatus(msg, isError = false) {
            const el = document.getElementById('save-status');
            el.textContent = msg;
            el.style.color = isError ? '#dc3545' : '#198754';
            setTimeout(() => {
                el.textContent = '';
            }, 4000);
        }
    </script>
@endpush
