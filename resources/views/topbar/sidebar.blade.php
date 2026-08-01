<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP</title>
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="{{ URL::asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Noto+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════ */
        :root {
            --navy: #0b1828;
            --navy-2: #0f2035;
            --navy-3: #172d47;
            --navy-4: #1e3a5a;
            --amber: #f59e0b;
            --amber-lt: #fcd34d;
            --amber-dim: rgba(245, 158, 11, .15);
            --steel: #94aec4;
            --steel-lt: #c8daea;
            --white: #ffffff;
            --sidebar-w: 265px;
            --topbar-h: 54px;
            --transition: .2s cubic-bezier(.4, 0, .2, 1);
        }

        /* ═══════════════════════════════════════════
           RESET ADMINLTE OVERRIDES
        ═══════════════════════════════════════════ */
        body.hold-transition {
            font-family: 'Noto Sans', sans-serif !important;
        }

        /* ═══════════════════════════════════════════
           TOP NAVBAR
        ═══════════════════════════════════════════ */
        .main-header.navbar {
            background: var(--navy) !important;
            border-bottom: 2px solid var(--amber) !important;
            height: var(--topbar-h) !important;
            min-height: var(--topbar-h) !important;
            padding: 0 16px !important;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .45) !important;
        }

        /* Hamburger toggle */
        .main-header .nav-link[data-widget="pushmenu"] {
            color: var(--amber) !important;
            font-size: 18px !important;
            padding: 0 14px !important;
            line-height: var(--topbar-h) !important;
            transition: color var(--transition), background var(--transition);
        }

        .main-header .nav-link[data-widget="pushmenu"]:hover {
            background: var(--amber-dim) !important;
            border-radius: 4px;
        }


        .main-header .nav-link[data-widget="home"] {
            color: var(--amber) !important;
            font-size: 18px !important;
            padding: 0 14px !important;
            line-height: var(--topbar-h) !important;
            transition: color var(--transition), background var(--transition);
        }

        .main-header .nav-link[data-widget="home"]:hover {
            background: var(--amber-dim) !important;
            border-radius: 4px;
        }

        /* Center page title */
        .main-header .container-fluid {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .navbar-nav.ml-auto {
            margin-left: auto !important;
        }

        .navbar-nav.ml-auto {
            margin-left: auto !important;
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .main-header .container-fluid h4 {
            font-family: 'Rajdhani', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            letter-spacing: 2px !important;
            text-transform: uppercase !important;
            color: var(--steel-lt) !important;
            margin: 0 !important;
            position: relative;
            padding: 0 20px;
        }

        .main-header .container-fluid h4::before,
        .main-header .container-fluid h4::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 1.5px;
            background: var(--amber);
        }

        .main-header .container-fluid h4::before {
            left: -10px;
        }

        .main-header .container-fluid h4::after {
            right: -10px;
        }

        /* Right user dropdown */
        .main-header .navbar-nav.ml-auto .nav-link {
            color: var(--steel-lt) !important;
            font-family: 'Noto Sans', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 500 !important;
            padding: 0 14px !important;
            height: var(--topbar-h);
            display: flex !important;
            align-items: center !important;
            gap: 6px;
            border-left: 1px solid rgba(255, 255, 255, .07);
            transition: background var(--transition), color var(--transition);
        }

        .main-header .navbar-nav.ml-auto .nav-link:hover,
        .main-header .navbar-nav.ml-auto .nav-link.show {
            background: var(--amber-dim) !important;
            color: var(--amber) !important;
        }

        .main-header .navbar-nav.ml-auto .nav-link.log::before {
            content: '' !important;
            display: none !important;
            font-weight: 900;
            font-size: 13px;
            color: var(--amber);
        }

        .main-header .navbar-nav.ml-auto .nav-link::before {
            content: '\f007';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 13px;
            color: var(--amber);
        }



        .dropdown-menu {
            background: var(--navy-2) !important;
            border: 1px solid rgba(245, 158, 11, .25) !important;
            border-radius: 4px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .5) !important;
            padding: 4px 0 !important;
            min-width: 140px !important;
        }

        .dropdown-menu .dropdown-item {
            color: var(--steel-lt) !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px;
            transition: background var(--transition), color var(--transition);
        }

        .dropdown-menu .dropdown-item:hover {
            background: var(--amber-dim) !important;
            color: var(--amber) !important;
        }

        .dropdown-menu .dropdown-item::before {
            content: '\f2f5';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 11px;
            color: var(--amber);
        }

        /* ═══════════════════════════════════════════
           SIDEBAR SHELL
        ═══════════════════════════════════════════ */
        .main-sidebar {
            background: var(--navy) !important;
            width: var(--sidebar-w) !important;
            border-right: 1px solid rgba(245, 158, 11, .15) !important;
            box-shadow: 3px 0 20px rgba(0, 0, 0, .4) !important;
        }

        /* ── Brand / Logo Area ── */
        .user-panel {
            background: var(--navy-2) !important;
            border-bottom: 1px solid rgba(245, 158, 11, .2) !important;
            padding: 14px 16px !important;
            margin: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .user-panel::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--amber), transparent);
        }

        .user-panel .info a {
            font-family: 'Rajdhani', sans-serif !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            letter-spacing: 3px !important;
            text-transform: uppercase !important;
            color: var(--white) !important;
            text-decoration: none !important;
        }

        .user-panel .info a span {
            color: var(--amber);
        }

        .user-panel .info small {
            display: block;
            font-size: 9px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--steel);
            margin-top: 1px;
        }

        /* ── Nav container ── */
        .sidebar>nav {
            padding-top: 8px !important;
        }

        .nav-sidebar {
            padding-bottom: 20px !important;
        }

        /* ═══════════════════════════════════════════
           MENU ITEMS — LEVEL 1  (main sections)
        ═══════════════════════════════════════════ */
        .nav-sidebar>.nav-item {
            margin: 1px 8px !important;
            border-radius: 4px !important;
            overflow: visible !important;
        }

        .nav-sidebar>.nav-item>.nav-link {
            background: transparent !important;
            color: var(--steel) !important;
            font-family: 'Rajdhani', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
            padding: 9px 12px !important;
            border-radius: 4px !important;
            border-left: 3px solid transparent !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            transition: all var(--transition) !important;
            position: relative;
        }

        .nav-sidebar>.nav-item>.nav-link:hover {
            background: var(--amber-dim) !important;
            color: var(--amber-lt) !important;
            border-left-color: var(--amber) !important;
        }

        .nav-sidebar>.nav-item>.nav-link.active,
        .nav-sidebar>.nav-item.menu-open>.nav-link {
            background: var(--amber-dim) !important;
            color: var(--amber) !important;
            border-left-color: var(--amber) !important;
        }

        /* Level 1 icons */
        .nav-sidebar>.nav-item>.nav-link .nav-icon {
            font-size: 13px !important;
            width: 18px !important;
            text-align: center !important;
            color: var(--amber) !important;
            opacity: .7;
            flex-shrink: 0;
        }

        .nav-sidebar>.nav-item>.nav-link:hover .nav-icon,
        .nav-sidebar>.nav-item.menu-open>.nav-link .nav-icon {
            opacity: 1;
        }

        /* Arrow */
        .nav-sidebar>.nav-item>.nav-link>p>.right,
        .nav-sidebar .nav-link>p>.right {
            font-size: 10px !important;
            color: var(--steel) !important;
            transition: transform var(--transition) !important;
            margin-left: auto !important;
        }

        .nav-sidebar>.nav-item.menu-open>.nav-link>p>.right {
            transform: rotate(-90deg) !important;
        }

        /* p tag inside nav-link */
        .nav-sidebar .nav-link p {
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            letter-spacing: inherit !important;
            text-transform: inherit !important;
            color: inherit !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
        }

        /* ═══════════════════════════════════════════
           LEVEL 2 SUBMENU
        ═══════════════════════════════════════════ */
        .nav-sidebar .nav-treeview {
            background: var(--navy-2) !important;
            border-left: none !important;
            padding: 4px 0 !important;
            margin: 0 !important;
        }

        .nav-sidebar .nav-treeview>.nav-item {
            margin: 0 !important;
            border-radius: 0 !important;
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link {
            color: var(--steel) !important;
            font-family: 'Noto Sans', sans-serif !important;
            font-size: 12px !important;
            font-weight: 400 !important;
            letter-spacing: .3px !important;
            text-transform: none !important;
            padding: 7px 12px 7px 36px !important;
            border-left: none !important;
            border-radius: 0 !important;
            background: transparent !important;
            transition: all var(--transition) !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            position: relative;
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--navy-4);
            transition: background var(--transition);
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link:hover {
            background: rgba(245, 158, 11, .08) !important;
            color: var(--amber-lt) !important;
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link:hover::before {
            background: var(--amber);
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link.active {
            color: var(--amber) !important;
            background: rgba(245, 158, 11, .12) !important;
        }

        .nav-sidebar .nav-treeview>.nav-item>.nav-link.active::before {
            background: var(--amber);
        }

        /* L2 icons (circle) */
        .nav-sidebar .nav-treeview>.nav-item>.nav-link .nav-icon {
            font-size: 7px !important;
            width: 14px !important;
            text-align: center !important;
            color: var(--steel) !important;
            display: none;
            /* hidden — we use ::before dot instead */
        }

        /* ── L2 has-treeview (parent of L3) ── */
        .nav-sidebar .nav-treeview .nav-item.has-treeview>.nav-link {
            font-weight: 500 !important;
            color: var(--steel-lt) !important;
        }

        .nav-sidebar .nav-treeview .nav-item.has-treeview>.nav-link:hover {
            color: var(--amber-lt) !important;
        }

        .nav-sidebar .nav-treeview .nav-item.has-treeview.menu-open>.nav-link {
            color: var(--amber) !important;
        }

        /* ═══════════════════════════════════════════
           LEVEL 3 SUBMENU
        ═══════════════════════════════════════════ */
        .nav-sidebar .nav-treeview .nav-treeview {
            background: var(--navy-3) !important;
            padding: 2px 0 !important;
        }

        .nav-sidebar .nav-treeview .nav-treeview>.nav-item>.nav-link {
            padding-left: 52px !important;
            font-size: 11.5px !important;
            color: var(--steel) !important;
        }

        .nav-sidebar .nav-treeview .nav-treeview>.nav-item>.nav-link::before {
            left: 38px;
            width: 4px;
            height: 4px;
            background: var(--navy-4);
        }

        .nav-sidebar .nav-treeview .nav-treeview>.nav-item>.nav-link:hover {
            background: rgba(245, 158, 11, .06) !important;
            color: var(--amber-lt) !important;
        }

        .nav-sidebar .nav-treeview .nav-treeview>.nav-item>.nav-link:hover::before {
            background: var(--amber);
        }

        .main-header {
            position: relative;
        }

        /* CENTER TITLE (KEEP SAME STYLE) */
        .main-header .container-fluid h4 {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* FIX RIGHT MENU */
        .navbar-nav.ml-auto {
            margin-left: auto !important;
        }

        /* PREVENT TEXT CUT */
        .navbar-nav .nav-link {
            white-space: nowrap;
        }

        /* L3 icons */
        .nav-sidebar .nav-treeview .nav-treeview>.nav-item>.nav-link .nav-icon {
            display: none;
        }

        /* ═══════════════════════════════════════════
           STATIC GATE PASS ITEM
        ═══════════════════════════════════════════ */
        .nav-sidebar>.nav-item:last-child {
            border-top: 1px solid rgba(245, 158, 11, .15);
            margin-top: 8px !important;
            padding-top: 4px;
        }

        .nav-sidebar>.nav-item:last-child>.nav-link {
            color: var(--steel-lt) !important;
        }

        .nav-sidebar>.nav-item:last-child>.nav-link:hover,
        .nav-sidebar>.nav-item:last-child>.nav-link.active {
            color: var(--amber) !important;
        }

        /* ═══════════════════════════════════════════
           SIDEBAR COLLAPSED STATE
        ═══════════════════════════════════════════ */
        .sidebar-collapse .main-sidebar:not(:hover) .user-panel .info small,
        .sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-link p {
            display: none;
        }

        .sidebar-collapse .main-sidebar:hover {
            width: var(--sidebar-w) !important;
        }

        .sidebar-collapse .main-sidebar {
            width: 60px !important;
            transition: width .25s ease !important;
        }

        @media (min-width: 768px) {

            body.sidebar-collapse:not(.sidebar-open) .main-header,
            body.sidebar-collapse:not(.sidebar-open) .content-wrapper,
            body.sidebar-collapse:not(.sidebar-open) .main-footer {
                margin-left: 60px !important;
            }

            body:not(.sidebar-collapse) .main-header,
            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .main-footer {
                margin-left: var(--sidebar-w) !important;
            }
        }

        /* ═══════════════════════════════════════════
           SCROLLBAR
        ═══════════════════════════════════════════ */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--navy);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--navy-4);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--amber);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

    {{-- ══════════════════════════════════════
     TOP NAVBAR  (data unchanged)
══════════════════════════════════════ --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        {{-- Left: toggle --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="home" href="/">
                    <i class="fas fa-home"></i>
                </a>
            </li>
        </ul>

        {{-- Center: page title --}}
        <div class="container-fluid justify-content-center">
            @if (($headeer ?? collect())->isNotEmpty())
                @foreach ($headeer as $h)
                    <h4 id="headername">{{ $h->sub_menu_name ?? 'l' }}</h4>
                @endforeach
            @else
                <h4 id="headername">Dashboard</h4>
            @endif
        </div>

        {{-- Right: user dropdown --}}
        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    {{ $data->employee_name ?? 'User' }}
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('auth.logout') }}" class="dropdown-item">
                        Log Out
                    </a>
                </div>

            </li>
            <li class="nav-item">
                <a class="nav-link log" href="{{ route('auth.logout') }}" class="dropdown-item">
                    <i class="bi bi-power"></i>
                </a>
            </li>

        </ul>


    </nav>

    {{-- ══════════════════════════════════════
     SIDEBAR  (data unchanged)
══════════════════════════════════════ --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <div class="sidebar">

            {{-- Brand / Logo --}}
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block"><span></span> ERP<small>Enterprise Resource
                            Planning</small></a>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false">

                    @if ($data)
                        @include('topbar.menu')
                    @endif

                    {{-- Static Gate Pass --}}
                    <li class="nav-item">
                        <a href="{{ route('common.gatepass') }}"
                            class="nav-link {{ request()->is('common/gatepass') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-gauge-simple-high"></i>
                            <p>Gate Pass</p>
                        </a>
                    </li>

                </ul>
            </nav>

        </div>
    </aside>
