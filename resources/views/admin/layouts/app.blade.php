<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Permission System') — F_STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --sidebar-w: 240px;
            --topbar-h: 56px;
            --primary: #0d6efd;
            --sidebar-bg: #1a1d23;
            --sidebar-text: #adb5bd;
            --sidebar-active: #ffffff;
            --sidebar-hover: #2a2d35;
            --sidebar-active-bg: #0d6efd;
            --border-color: #e9ecef;
        }
        body { background: #f4f6fb; font-size: 14px; }
        /* Sidebar */
        #sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w);
            height: 100vh; background: var(--sidebar-bg);
            overflow-y: auto; z-index: 1030; transition: transform .25s;
        }
        #sidebar .brand {
            height: var(--topbar-h); display: flex; align-items: center;
            padding: 0 20px; border-bottom: 1px solid #2a2d35;
        }
        #sidebar .brand span { color: #fff; font-weight: 600; font-size: 15px; letter-spacing: .3px; }
        #sidebar .brand small { color: #6c757d; font-size: 11px; display: block; margin-top: 1px; }
        #sidebar .nav-section {
            font-size: 10px; font-weight: 600; letter-spacing: .08em;
            text-transform: uppercase; color: #495057;
            padding: 18px 20px 6px;
        }
        #sidebar .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px; color: var(--sidebar-text);
            font-size: 13.5px; border-radius: 0; transition: all .15s;
            text-decoration: none;
        }
        #sidebar .nav-link i { font-size: 15px; width: 18px; text-align: center; }
        #sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        #sidebar .nav-link.active { background: var(--sidebar-active-bg); color: #fff; }
        #sidebar .nav-link .badge-count {
            margin-left: auto; background: #2a2d35;
            font-size: 10px; padding: 2px 7px; border-radius: 20px; color: #adb5bd;
        }
        #sidebar .nav-link.active .badge-count { background: rgba(255,255,255,.2); color: #fff; }
        /* Topbar */
        #topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--topbar-h); background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; padding: 0 24px;
            z-index: 1020; gap: 16px;
        }
        #topbar .page-title { font-weight: 600; font-size: 15px; color: #212529; }
        #topbar .breadcrumb { margin: 0; font-size: 12px; }
        #topbar .breadcrumb-item + .breadcrumb-item::before { color: #adb5bd; }
        /* Main content */
        #main { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); min-height: 100vh; }
        #content { padding: 24px; }
        /* Cards */
        .card { border: 1px solid var(--border-color); border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .card-header { background: #fff; border-bottom: 1px solid var(--border-color); padding: 14px 20px; border-radius: 10px 10px 0 0 !important; font-weight: 600; font-size: 14px; }
        /* Tables */
        .table th { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: #6c757d; border-top: none; }
        .table td { vertical-align: middle; font-size: 13px; }
        /* Badges */
        .badge { font-size: 11px; font-weight: 500; padding: 3px 9px; }
        /* Stat cards */
        .stat-card { border-radius: 10px; border: 1px solid var(--border-color); padding: 18px 20px; background: #fff; }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .stat-card .stat-val { font-size: 26px; font-weight: 700; line-height: 1; }
        .stat-card .stat-lbl { font-size: 12px; color: #6c757d; margin-top: 4px; }
        /* Form labels */
        .form-label { font-size: 12px; font-weight: 600; color: #495057; margin-bottom: 5px; }
        .form-control, .form-select { font-size: 13px; border-radius: 7px; }
        .form-control:focus, .form-select:focus { border-color: #86b7fe; box-shadow: 0 0 0 3px rgba(13,110,253,.12); }
        /* Toggle switch */
        .form-check-input[type=checkbox] { cursor: pointer; }
        /* Code tag */
        code.id-tag { background: #f1f3f5; color: #495057; border: 1px solid #dee2e6; padding: 2px 7px; border-radius: 5px; font-size: 11px; }
        /* Alerts */
        .alert { border-radius: 8px; font-size: 13px; }
        /* Buttons */
        .btn { border-radius: 7px; font-size: 13px; font-weight: 500; }
        .btn-sm { font-size: 12px; padding: 3px 10px; }
        /* Section label */
        .section-label { font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #adb5bd; margin: 0 0 10px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color); }
        /* Mobile toggle */
        #sidebar-toggle { display: none; }
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar { left: 0; }
            #main { margin-left: 0; }
            #sidebar-toggle { display: flex; }
        }
        /* DataTables override */
        div.dataTables_wrapper div.dataTables_filter input { border-radius: 7px; border: 1px solid #dee2e6; font-size: 13px; padding: 4px 10px; }
        div.dataTables_wrapper div.dataTables_length select { border-radius: 7px; border: 1px solid #dee2e6; font-size: 13px; }
        /* Menu tree */
        .menu-tree-item { padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 6px; background: #fff; }
        .menu-tree-item:hover { border-color: #86b7fe; background: #f8f9ff; }
        .menu-tree-item.level-1 { margin-left: 0; }
        .menu-tree-item.level-2 { margin-left: 24px; border-left: 3px solid #dee2e6; }
        .menu-tree-item.level-3 { margin-left: 48px; border-left: 3px solid #bee3f8; }
        /* Permission toggle row */
        .perm-menu-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid var(--border-color); transition: background .1s; }
        .perm-menu-row:last-child { border-bottom: none; }
        .perm-menu-row:hover { background: #f8f9ff; }
        .perm-menu-row .menu-info .menu-title { font-size: 13px; font-weight: 600; color: #212529; }
        .perm-menu-row .menu-info .menu-meta { font-size: 11px; color: #6c757d; margin-top: 1px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<nav id="sidebar">
    <div class="brand">
        <div>
            <span><i class="bi bi-shield-lock me-2"></i>F_STORE</span>
            <small>Permission System</small>
        </div>
    </div>

    <div class="nav-section">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="nav-section">Menu Management</div>
    <a href="{{ route('menus.index') }}" class="nav-link {{ request()->routeIs('menus.*') ? 'active' : '' }}">
        <i class="bi bi-layout-sidebar"></i> Menu Hierarchy
    </a>
    <a href="{{ route('routes.index') }}" class="nav-link {{ request()->routeIs('routes.*') ? 'active' : '' }}">
        <i class="bi bi-signpost-split"></i> Route Entry
    </a>

    <div class="nav-section">Access Control</div>
    <a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> User Groups
    </a>
    <a href="{{ route('group-menu.index') }}" class="nav-link {{ request()->routeIs('group-menu.*') ? 'active' : '' }}">
        <i class="bi bi-toggles"></i> Group Menu Access
    </a>
    <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
        <i class="bi bi-key"></i> Route Permissions
    </a>

    <div class="nav-section">Users</div>
    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> User Management
    </a>
    <a href="{{ route('user-menu.index') }}" class="nav-link {{ request()->routeIs('user-menu.*') ? 'active' : '' }}">
        <i class="bi bi-person-check"></i> User Menu Permission
    </a>
</nav>

{{-- Topbar --}}
<header id="topbar">
    <button id="sidebar-toggle" class="btn btn-sm btn-outline-secondary me-2" onclick="document.getElementById('sidebar').classList.toggle('open')">
        <i class="bi bi-list"></i>
    </button>
    <div>
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="badge bg-success-subtle text-success border border-success-subtle">
            <i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>Oracle Connected
        </span>
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;border-radius:50%;background:#0d6efd;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600;">A</div>
            <span style="font-size:13px;font-weight:500;">Admin</span>
        </div>
    </div>
</header>

{{-- Main --}}
<main id="main">
    <div id="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    // Init all datatables
    document.addEventListener('DOMContentLoaded', function() {
        $('.datatable').each(function() {
            $(this).DataTable({ pageLength: 10, responsive: true, dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between mt-3"ip>' });
        });
    });
</script>
@stack('scripts')
</body>
</html>
