@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
    <!doctype html>
    <html lang="en">

    <head>
        <title>{{ isset($emp) ? 'Edit — ' . $emp->empno : 'New Employee' }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('erpcss/select2.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('erpcss/sweetalert2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('bootstrap_icon/bootstrap-icons.min.css') }}" media="print"
            onload="this.media='all'">

        <style>
            @font-face {
                font-family: SutonnyMJ;
                src: url('/fonts/SutonnyMJ.ttf') format('truetype');
                font-display: swap;
            }

            .bn-font {
                font-family: SutonnyMJ, sans-serif;
            }

            #b_name {
                font-family: SutonnyMJ, sans-serif !important;
                font-size: 18px !important;
                line-height: 1.1;
            }

            .select2.select2-container {
                width: 100% !important;
            }

            :root {
                --pri: #1a3a5c;
                --pri-lt: #2257a0;
                --accent: #1e7e34;
                --danger: #c0392b;
                --amber: #f59e0b;
                --bg: #f0f4f8;
                --card: #fff;
                --border: #cdd8e8;
                --lbl: #374a5a;
                --inp: #fafdff;
                --r: 5px;
                --h: 32px;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            html,
            body {
                height: 100%;
                font-family: 'Segoe UI', sans-serif;
                font-size: 13px;
                background: var(--bg);
                color: #222;
            }

            .wrapper {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            .content-wrapper {
                flex: 1;
                background: var(--bg);
                margin-left: 265px;
                transition: margin .25s;
            }

            body.sidebar-collapse .content-wrapper {
                margin-left: 60px;
            }

            @media(max-width:991px) {
                .content-wrapper {
                    margin-left: 0 !important;
                }
            }

            /* ── Banner ── */
            .mode-banner {
                background: linear-gradient(90deg, var(--pri), var(--pri-lt));
                color: #fff;
                padding: 10px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 3px solid var(--amber);
            }

            .badge-pill {
                background: var(--amber);
                color: #0b1828;
                font-weight: 700;
                padding: 3px 14px;
                border-radius: 20px;
                font-size: 12px;
            }

            .btn-back {
                background: rgba(255, 255, 255, .15);
                color: #fff;
                border: 1px solid rgba(255, 255, 255, .3);
                border-radius: 4px;
                padding: 4px 14px;
                font-size: 12px;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .btn-back:hover {
                background: rgba(255, 255, 255, .25);
                color: #fff;
            }

            /* ── Tabs ── */
            .emp-tabbar {
                background: var(--pri);
                display: flex;
                flex-wrap: wrap;
                padding: 0 8px;
                border-bottom: 3px solid #0c1f35;
                position: sticky;
                top: 0;
                z-index: 999;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .3);
            }

            .emp-tabbar .nav-link {
                color: #a8c8e8 !important;
                font-size: 11.5px;
                font-weight: 600;
                text-transform: uppercase;
                padding: 10px 12px;
                border: none;
                border-bottom: 3px solid transparent;
                border-radius: 0;
                margin-bottom: -3px;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: none;
                cursor: pointer;
                white-space: nowrap;
            }

            .emp-tabbar .nav-link:hover {
                color: #fff !important;
                background: rgba(255, 255, 255, .08);
            }

            .emp-tabbar .nav-link.active {
                color: #fff !important;
                border-bottom-color: var(--amber);
                background: rgba(255, 255, 255, .1);
            }

            /* ── Tab panes ── */
            .tab-content {
                padding: 16px 20px;
            }

            .tab-pane {
                display: none;
            }

            .tab-pane.active {
                display: block;
            }

            /* ── Cards ── */
            .sec-card {
                background: var(--card);
                border: 1px solid var(--border);
                border-radius: var(--r);
                box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
                margin-bottom: 14px;
            }

            .sec-card-head {
                background: var(--pri);
                color: #fff;
                padding: 7px 14px;
                border-radius: var(--r) var(--r) 0 0;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .5px;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .sec-card-body {
                padding: 12px 14px 8px;
            }

            .page-heading {
                font-size: 15px;
                font-weight: 700;
                color: var(--pri);
                margin-bottom: 12px;
                padding-bottom: 6px;
                border-bottom: 2px solid var(--pri-lt);
                display: flex;
                align-items: center;
                gap: 7px;
                flex-wrap: wrap;
            }

            /* ── Form elements ── */
            label.col-form-label {
                font-size: 11px !important;
                font-weight: 600 !important;
                color: var(--lbl) !important;
                padding-top: 6px !important;
            }

            .form-control,
            .form-select {
                height: var(--h) !important;
                font-size: 12.5px !important;
                border: 1px solid #bfcfdf !important;
                border-radius: 4px !important;
                background: var(--inp) !important;
                color: #1a2a3a !important;
                padding: 3px 8px !important;
                width: 100%;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--pri-lt) !important;
                box-shadow: 0 0 0 2px rgba(34, 87, 160, .12) !important;
                outline: none;
            }

            textarea.form-control {
                height: auto !important;
                min-height: 50px !important;
                resize: vertical;
            }

            .input-group {
                display: flex;
                gap: 0;
            }

            .input-group .form-control {
                border-radius: 4px 0 0 4px !important;
            }

            .input-group .btn {
                border-radius: 0 4px 4px 0 !important;
            }

            .empid-bar {
                background: #e6eff8;
                border: 1px solid #b8d0ea;
                border-radius: var(--r);
                padding: 10px 14px;
                margin-bottom: 14px;
                display: flex;
                align-items: flex-end;
                flex-wrap: wrap;
                gap: 12px;
            }

            .empid-bar>div {
                display: flex;
                flex-direction: column;
            }

            .empid-bar label {
                font-size: 11px;
                font-weight: 700;
                color: var(--pri);
                margin-bottom: 3px;
            }

            .ro {
                background: #dde8f5 !important;
                font-weight: 700;
                color: var(--pri) !important;
                cursor: not-allowed;
            }

            /* ── Buttons ── */
            .btn {
                font-size: 12px !important;
                font-weight: 600 !important;
                padding: 5px 16px !important;
                border-radius: 4px !important;
                cursor: pointer;
                border: 1px solid transparent;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .btn-save {
                background: var(--accent) !important;
                border-color: var(--accent) !important;
                color: #fff !important;
            }

            .btn-upd {
                background: #1565c0 !important;
                border-color: #1565c0 !important;
                color: #fff !important;
            }

            .btn-del {
                background: var(--danger) !important;
                border-color: var(--danger) !important;
                color: #fff !important;
            }

            .btn-clr {
                background: transparent !important;
                color: var(--danger) !important;
                border: 1.5px solid var(--danger) !important;
            }

            .btn-clr:hover {
                background: #ffeaea !important;
            }

            .btn-secondary {
                background: #546e7a !important;
                border-color: #546e7a !important;
                color: #fff !important;
            }

            .action-bar {
                padding: 10px 0 14px;
                display: flex;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            /* ── Sub-tables ── */
            .sub-table-wrap {
                overflow-x: auto;
                border-radius: var(--r);
                border: 1px solid var(--border);
                margin-top: 14px;
            }

            .emp-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            .emp-table thead th {
                background: var(--pri);
                color: #fff;
                padding: 8px 10px;
                font-size: 10.5px;
                font-weight: 700;
                letter-spacing: .4px;
                text-transform: uppercase;
                border: none;
                white-space: nowrap;
            }

            .emp-table tbody tr:nth-child(even) {
                background: #f2f7fc;
            }

            .emp-table tbody td {
                padding: 6px 10px;
                border-bottom: 1px solid #dde8f2;
                color: #2a3a4a;
                vertical-align: middle;
            }

            .emp-table tbody tr:hover {
                background: #e4f0fb;
            }

            .emp-table tbody tr:last-child td {
                border-bottom: none;
            }

            .btn-row-del {
                background: transparent;
                border: 1px solid var(--danger);
                color: var(--danger);
                border-radius: 3px;
                padding: 2px 7px;
                font-size: 11px;
                cursor: pointer;
            }

            .btn-row-del:hover {
                background: #ffeaea;
            }

            /* ── Select2 height fix ── */
            .select2-container--default .select2-selection--single {
                height: var(--h) !important;
                border: 1px solid #bfcfdf !important;
                border-radius: 4px !important;
                background: var(--inp) !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: calc(var(--h) - 2px) !important;
                font-size: 12.5px !important;
                color: #1a2a3a !important;
                padding-left: 8px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: var(--h) !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__clear {
                line-height: var(--h) !important;
            }

            /* ── Tab skeleton ── */
            .tab-skeleton {
                padding: 40px 20px;
                text-align: center;
                color: #94aec4;
                font-size: 13px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            /* ── Image box ── */
            .img-box {
                border: 1.5px dashed #a0bcd8;
                border-radius: 4px;
                overflow: hidden;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #f3f8fd;
            }

            /* ── Photo/Sign card ── */
            .img-card {
                border: 1px solid var(--border);
                border-radius: var(--r);
                background: var(--card);
                padding: 10px;
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }

            .img-card-label {
                font-size: 11px;
                font-weight: 700;
                color: var(--lbl);
                text-transform: uppercase;
                letter-spacing: .4px;
                width: 100%;
            }

            .img-card-label span {
                font-size: 10px;
                color: #94aec4;
                font-weight: 400;
                text-transform: none;
            }

            .img-preview-wrap {
                position: relative;
                background: #f3f8fd;
                border: 1.5px dashed #a0bcd8;
                border-radius: 4px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .img-preview-wrap img {
                object-fit: cover;
                display: block;
            }

            .img-placeholder {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #b0c4d8;
                font-size: 10px;
                text-align: center;
                gap: 4px;
                padding: 10px;
            }

            .btn-remove-img {
                display: none;
                position: absolute;
                top: 4px;
                right: 4px;
                background: rgba(192, 57, 43, .88);
                color: #fff;
                border: none;
                border-radius: 3px;
                font-size: 11px;
                padding: 2px 7px;
                cursor: pointer;
                line-height: 1.4;
                z-index: 2;
            }

            .img-filename {
                font-size: 10px;
                color: #6a8aaa;
                margin-top: 2px;
                word-break: break-all;
                max-width: 100%;
                text-align: center;
            }

            hr.sec-hr {
                border: none;
                border-top: 1.5px dashed #d0dceb;
                margin: 10px 0;
            }

            .row.p-1 {
                padding: 4px 0 !important;
            }

            .edit-flag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(245, 158, 11, .15);
                border: 1px solid var(--amber);
                color: #7a5800;
                border-radius: 4px;
                padding: 2px 10px;
                font-size: 11px;
                font-weight: 700;
            }

            footer.main-footer {
                background: #0b1828;
                border-top: 2px solid var(--amber);
                text-align: center;
                padding: 8px 0;
                margin-left: 265px;
                transition: margin .25s;
            }

            body.sidebar-collapse footer.main-footer {
                margin-left: 60px;
            }

            @media(max-width:991px) {
                footer.main-footer {
                    margin-left: 0 !important;
                }
            }

            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            ::-webkit-scrollbar-track {
                background: #eef2f8;
            }

            ::-webkit-scrollbar-thumb {
                background: #9ab4cc;
                border-radius: 3px;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert"
                    style="font-size:13px;border-left:4px solid #198754;">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert"
                    style="font-size:13px;border-left:4px solid #dc3545;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('fail') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- MODE BANNER --}}
            <div class="mode-banner">
                <div style="display:flex;align-items:center;gap:12px;">
                    @if (isset($emp))
                        <span class="badge-pill"><i class="fa-solid fa-user-pen"></i> EDIT</span>
                        <span style="font-size:14px;font-weight:600;">
                            {{ trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) ?: $emp->empno }}
                        </span>
                    @else
                        <span class="badge-pill">+ NEW EMPLOYEE</span>
                        <span style="font-size:14px;font-weight:600;">Employee Registration</span>
                    @endif
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <a href="{{ route('empnewentry') }}" class="btn-back">← Back</a>
                    <a href="{{ route('emplist') }}" class="btn-back">← Back to List</a>
                    @if (isset($emp))
                        <button type="button" class="btn btn-del btn-sm" id="deleteEmpBtn"
                            data-empno="{{ $emp->empno }}">
                            <i class="fa-solid fa-trash-can"></i> Delete
                        </button>
                    @endif
                </div>
            </div>

            {{-- TAB BAR --}}
            <div class="emp-tabbar" id="empTabs">
                <button class="nav-link active" data-tab="per"><i class="bi bi-person"></i> Personal</button>
                <button class="nav-link" data-tab="off"><i class="bi bi-briefcase"></i> Official</button>
                <button class="nav-link" data-tab="add"><i class="bi bi-geo-alt"></i> Location</button>
                <button class="nav-link" data-tab="edu"><i class="bi bi-graduation-cap"></i> Education</button>
                <button class="nav-link" data-tab="course"><i class="bi bi-book"></i> Short Course</button>
                <button class="nav-link" data-tab="train"><i class="bi bi-award"></i> Training</button>
                <button class="nav-link" data-tab="exp"><i class="bi bi-building"></i> Experience</button>
                <button class="nav-link" data-tab="nomi"><i class="bi bi-people"></i> Nominee</button>
                <button class="nav-link" data-tab="job"><i class="bi bi-clock"></i> Job History</button>
            </div>

            <div class="tab-content" id="tabContent">

                {{-- ════════════════════════════════════════════════
                 TAB 1 — PERSONAL
            ════════════════════════════════════════════════ --}}
                <div class="tab-pane active" id="per">
                    <form id="frmPersonal" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="empno" id="empno" value="{{ $emp->empno ?? '' }}">

                        <div class="page-heading">
                            <i class="bi bi-person-lines-fill"></i> Personal Information
                            @if (isset($emp))
                                <span class="edit-flag">ID: {{ $emp->empno }}</span>
                            @endif
                        </div>

                        {{-- ID Bar --}}
                        <div class="empid-bar">
                            <div>
                                <label>Employee ID</label>
                                @if (isset($emp))
                                    <input type="text" class="form-control ro" value="{{ $emp->empno }}" readonly
                                        style="width:180px;">
                                @else
                                    <div class="input-group" style="width:240px;">
                                        <input list="empno_list" type="text" class="form-control" id="empnoInput"
                                            placeholder="Type Employee ID" autocomplete="off">
                                        <a href="#" class="btn btn-secondary" id="findemp"
                                            style="background:#1a3a5c;color:#f59e0b;border:none;border-radius:4px;padding:3px 10px;font-size:11px;">
                                            <i class="bi bi-search"></i>
                                        </a>
                                        <datalist id="empno_list"></datalist>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label>Card No</label>
                                <input type="text" class="form-control" name="card_no"
                                    value="{{ $emp->card_no ?? '' }}" style="width:140px;">
                            </div>
                            <div style="min-width:190px;">
                                <label>Company</label>
                                <select class="form-select" name="company_id" id="company_id">
                                    <option value="">— Select —</option>
                                    @foreach ($companyList as $c)
                                        <option value="{{ $c->company_id }}"
                                            {{ isset($emp) && $emp->company_id == $c->company_id ? 'selected' : '' }}>
                                            {{ $c->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Name Details --}}
                        <div class="sec-card">
                            <div class="sec-card-head"><i class="bi bi-person"></i> Name Details</div>
                            <div class="sec-card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">First Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="first_name" id="first_name"
                                                    value="{{ $emp->first_name ?? '' }}" placeholder="First Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Last Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="last_name" id="last_name" value="{{ $emp->last_name ?? '' }}"
                                                    placeholder="Last Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Middle Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="middle_name" id="middle_name"
                                                    value="{{ $emp->middle_name ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Bangla Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="b_name" id="b_name" value="{{ $emp->b_name ?? '' }}"
                                                    placeholder="বাংলা নাম" style="font-family:SutonnyMJ,sans-serif;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Father Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="father_name" id="father_name"
                                                    value="{{ $emp->father_name ?? '' }}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Mother Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="mother_name" id="mother_name"
                                                    value="{{ $emp->mother_name ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Spouse Name</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="husband_name" id="husband_name"
                                                    value="{{ $emp->husband_name ?? '' }}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Guardian</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="gurdian_name" value="{{ $emp->gurdian_name ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Demographics --}}
                        <div class="sec-card">
                            <div class="sec-card-head"><i class="bi bi-calendar2-person"></i> Demographics</div>
                            <div class="sec-card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Date of Birth</label>
                                            <div class="col-sm-7"><input type="text" class="form-control date-pick"
                                                    name="dob" id="dob"
                                                    value="{{ isset($emp) && $emp->dob ? \Carbon\Carbon::parse($emp->dob)->format('d-m-Y') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Gender</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" name="sex" id="sex">
                                                    <option value="">— Select —</option>
                                                    <option value="Male"
                                                        {{ isset($emp) && $emp->sex == 'Male' ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="Female"
                                                        {{ isset($emp) && $emp->sex == 'Female' ? 'selected' : '' }}>
                                                        Female
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Marital Status</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" name="marial_status" id="marial_status">
                                                    <option value="">— Select —</option>
                                                    <option value="Single"
                                                        {{ isset($emp) && $emp->marial_status == 'Single' ? 'selected' : '' }}>
                                                        Single</option>
                                                    <option value="Married"
                                                        {{ isset($emp) && $emp->marial_status == 'Married' ? 'selected' : '' }}>
                                                        Married</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Religion</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" name="religion_id" id="religion_id">
                                                    <option value="">— Select —</option>
                                                    @foreach ($religion as $rel)
                                                        <option value="{{ $rel->religion_id }}"
                                                            {{ isset($emp) && $emp->religion_id == $rel->religion_id ? 'selected' : '' }}>
                                                            {{ $rel->religion_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Blood Group</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" name="blood_group">
                                                    <option value="">— Select —</option>
                                                    @foreach (['A (+) ve', 'A (-) ve', 'B (+) ve', 'B (-) ve', 'AB (+) ve', 'AB (-) ve', 'O (+) ve', 'O (-) ve'] as $bg)
                                                        <option value="{{ $bg }}"
                                                            {{ isset($emp) && $emp->blood_group == $bg ? 'selected' : '' }}>
                                                            {{ $bg }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Nationality</label>
                                            <div class="col-sm-7"><input type="text" class="form-control"
                                                    name="nationality_desc"
                                                    value="{{ $emp->nationality_desc ?? 'Bangladeshi' }}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Status</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" name="status" id="status">
                                                    <option value="Active"
                                                        {{ !isset($emp) || $emp->status == 'Active' ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="Inactive"
                                                        {{ isset($emp) && $emp->status == 'Inactive' ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">HBS Ag</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" name="hbs_test">
                                                    <option value="">— Choose —</option>
                                                    <option value="(+) ve"
                                                        {{ isset($emp) && $emp->hbs_test == '(+) ve' ? 'selected' : '' }}>
                                                        (+) ve</option>
                                                    <option value="(-) ve"
                                                        {{ isset($emp) && $emp->hbs_test == '(-) ve' ? 'selected' : '' }}>
                                                        (-) ve</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Office Food</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" name="office_food">
                                                    <option value="">— Choose —</option>
                                                    <option value="Y"
                                                        {{ isset($emp) && $emp->office_food == 'Y' ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="N"
                                                        {{ isset($emp) && $emp->office_food == 'N' ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Last Education</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" name="last_education">
                                                    <option value="">— Choose —</option>
                                                    @foreach ($lastedu as $passedExm)
                                                        <option value="{{ $passedExm->passed_exam }}"
                                                            {{ isset($emp) && $emp->passed_exam == $passedExm->passed_exam ? 'selected' : '' }}>
                                                            {{ $passedExm->passed_exam }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">As On Date</label>
                                            <div class="col-sm-7"><input type="text" class="form-control date-pick"
                                                    name="as_on"
                                                    value="{{ isset($emp) && $emp->as_on ? \Carbon\Carbon::parse($emp->as_on)->format('d-m-Y') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row p-1">
                                            <label class="col-sm-5 col-form-label">Age </label>
                                            <div class="col-sm-7"><input type="text" class="form-control"
                                                    name="age2" id="age2"
                                                    value="{{ isset($emp) && $emp->age2 ? $emp->age2 : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Identity --}}
                        <div class="sec-card">
                            <div class="sec-card-head"><i class="bi bi-card-text"></i> Identity Documents</div>
                            <div class="sec-card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">National ID</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="national_id_no" value="{{ $emp->national_id_no ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">ID Issue Date</label>
                                            <div class="col-sm-8"><input type="text" class="form-control date-pick"
                                                    name="id_card_issue"
                                                    value="{{ isset($emp) && $emp->id_card_issue ? \Carbon\Carbon::parse($emp->id_card_issue)->format('d-m-Y') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Valid Till</label>
                                            <div class="col-sm-8"><input type="text" class="form-control date-pick"
                                                    name="valid_till"
                                                    value="{{ isset($emp) && $emp->valid_till ? \Carbon\Carbon::parse($emp->valid_till)->format('d-m-Y') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Passport No</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="passport_no" value="{{ $emp->passport_no ?? '' }}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Place of Issue</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="place_of_issue" value="{{ $emp->place_of_issue ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Birth Cert</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="birthday_id" value="{{ $emp->birthday_id ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">ID Mark</label>
                                            <div class="col-sm-8"><input type="text" class="form-control"
                                                    name="id_mark" value="{{ $emp->id_mark ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact --}}
                        <div class="sec-card">
                            <div class="sec-card-head"><i class="bi bi-telephone"></i> Contact</div>
                            <div class="sec-card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">Mobile</label>
                                            <div class="col-sm-8"><input type="tel" class="form-control"
                                                    name="emp_mobile_no" value="{{ $emp->emp_mobile_no ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row p-1">
                                            <label class="col-sm-4 col-form-label">SMS Mobile</label>
                                            <div class="col-sm-8"><input type="tel" class="form-control"
                                                    name="sms_mobile_no" value="{{ $emp->sms_mobile_no ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ════════════════════════════════════════════════
                         PHOTO & SIGNATURE CARD
                         Y: drive = emp_photo  →  DB column Y
                         Z: drive = emp_sign   →  DB column Z
                         HTTP server: http://192.168.189.205:81/
                    ════════════════════════════════════════════════ --}}
                        <div class="sec-card">
                            <div class="sec-card-head"><i class="bi bi-image"></i> Photo &amp; Signature</div>
                            <div class="sec-card-body">
                                <div class="row align-items-start g-4">

                                    {{-- ── PHOTO (Y: drive) ── --}}
                                    @php
                                        // Y column stores filename e.g. EMP001.jpg
                                        // Served at http://192.168.210.205:81/EMP001.jpg
                                        $photoFile = $emp->emp_img ?? null;
                                        $photoUrl = $photoFile
                                            ? 'http://192.168.210.205:81/' . $photoFile . '?v=' . time()
                                            : null;
                                    @endphp
                                    <div class="col-md-3 col-sm-6">
                                        <div class="img-card">
                                            <div class="img-card-label">
                                                Photo (Y:)
                                                <span> — JPG/PNG, max 2 MB</span>
                                            </div>

                                            {{-- File input --}}
                                            <input class="form-control" type="file" id="photoInput" name="photo"
                                                accept="image/*" style="width:100%;">

                                            {{-- Preview box: 130×150 portrait --}}
                                            <div class="img-preview-wrap" id="photoBox"
                                                style="width:130px;height:150px;">
                                                <img id="photoPreview" src="{{ $photoUrl ?? '' }}" alt="Employee Photo"
                                                    width="130" height="150"
                                                    style="object-fit:cover;width:130px;height:150px;display:{{ $photoUrl ? 'block' : 'none' }};"
                                                    loading="lazy"
                                                    onerror="this.style.display='none';
                                                          document.getElementById('photoPlaceholder').style.display='flex';
                                                          document.getElementById('removePhoto').style.display='none';">
                                                <div id="photoPlaceholder" class="img-placeholder"
                                                    style="width:130px;height:150px;display:{{ $photoUrl ? 'none' : 'flex' }};">
                                                    <svg width="36" height="36" viewBox="0 0 24 24"
                                                        fill="none" stroke="#b0c4d8" stroke-width="1.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                                        <path d="M21 15l-5-5L5 21" />
                                                    </svg>
                                                    No Photo
                                                </div>
                                                <button type="button" id="removePhoto" class="btn-remove-img"
                                                    style="display:{{ $photoUrl ? 'block' : 'none' }};"
                                                    title="Remove photo">✕</button>
                                            </div>

                                            {{-- Filename hint --}}
                                            <div class="img-filename" id="photoFilename">
                                                @if ($photoFile)
                                                    📁 {{ $photoFile }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ── SIGNATURE (Z: drive) ── --}}
                                    @php
                                        // Z column stores filename e.g. EMP001.jpg
                                        // Served at http://192.168.210.205:81/emp_sign/EMP001.jpg
                                        $signFile = $emp->emp_sign ?? null;
                                        $signUrl = $signFile
                                            ? 'http://192.168.210.205:82/' . $signFile . '?v=' . time()
                                            : null;
                                    @endphp
                                    <div class="col-md-4 col-sm-6">
                                        <div class="img-card">
                                            <div class="img-card-label">
                                                Signature (Z:)
                                                <span> — JPG/PNG, max 2 MB</span>
                                            </div>

                                            {{-- File input --}}
                                            <input class="form-control" type="file" id="signInput" name="signature"
                                                accept="image/*" style="width:100%;">

                                            {{-- Preview box: 220×90 landscape --}}
                                            <div class="img-preview-wrap" id="signBox"
                                                style="width:220px;height:90px;">
                                                <img id="signPreview" src="{{ $signUrl ?? '' }}"
                                                    alt="Employee Signature" width="220" height="90"
                                                    style="object-fit:contain;width:220px;height:90px;display:{{ $signUrl ? 'block' : 'none' }};"
                                                    loading="lazy"
                                                    onerror="this.style.display='none';
                                                          document.getElementById('signPlaceholder').style.display='flex';
                                                          document.getElementById('removeSign').style.display='none';">
                                                <div id="signPlaceholder" class="img-placeholder"
                                                    style="width:220px;height:90px;display:{{ $signUrl ? 'none' : 'flex' }};">
                                                    <svg width="32" height="22" viewBox="0 0 24 18"
                                                        fill="none" stroke="#b0c4d8" stroke-width="1.5">
                                                        <rect x="1" y="1" width="22" height="16" rx="2" />
                                                        <path d="M5 13c3-6 5-6 7 0s4 6 7 0" />
                                                    </svg>
                                                    No Signature
                                                </div>
                                                <button type="button" id="removeSign" class="btn-remove-img"
                                                    style="display:{{ $signUrl ? 'block' : 'none' }};"
                                                    title="Remove signature">✕</button>
                                            </div>

                                            {{-- Filename hint --}}
                                            <div class="img-filename" id="signFilename">
                                                @if ($signFile)
                                                    📁 {{ $signFile }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /row --}}
                            </div>
                        </div>
                        {{-- /Photo & Signature --}}

                        <div class="action-bar">
                            <button class="btn {{ isset($emp) ? 'btn-upd' : 'btn-save' }}" type="submit">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                {{ isset($emp) ? 'Update Personal' : 'Save Personal' }}
                            </button>
                            <button class="btn btn-clr" type="button" id="clearPersonal"><i
                                    class="fa-solid fa-eraser"></i> Clear</button>
                            @if (isset($emp))
                                <button class="btn btn-del" type="button" id="deleteEmpBtn2"
                                    data-empno="{{ $emp->empno }}"><i class="fa-solid fa-trash-can"></i> Delete
                                    Employee</button>
                            @endif
                        </div>
                    </form>
                </div>{{-- /per --}}

                {{-- Tabs 2–9: lazy-loaded shells --}}
                <div class="tab-pane" id="off">
                    <div class="tab-skeleton">Loading Official Info…</div>
                </div>
                <div class="tab-pane" id="add">
                    <div class="tab-skeleton">Loading Location…</div>
                </div>
                <div class="tab-pane" id="edu">
                    <div class="tab-skeleton">Loading Education…</div>
                </div>
                <div class="tab-pane" id="course">
                    <div class="tab-skeleton">Loading Short Course…</div>
                </div>
                <div class="tab-pane" id="train">
                    <div class="tab-skeleton">Loading Training…</div>
                </div>
                <div class="tab-pane" id="exp">
                    <div class="tab-skeleton">Loading Experience…</div>
                </div>
                <div class="tab-pane" id="nomi">
                    <div class="tab-skeleton">Loading Nominee…</div>
                </div>
                <div class="tab-pane" id="job">
                    <div class="tab-skeleton">Loading Job History…</div>
                </div>
            </div>


        </div>{{-- /wrapper --}}

        {{-- ③ ALL scripts at bottom --}}

        <script src="{{ asset('flatpickr/dist/flatpickr.min.js') }}"></script>
        <script>
            // ════════════════════════════════════════════
            //  GLOBALS
            // ════════════════════════════════════════════
            const IS_EDIT = {{ isset($emp) ? 'true' : 'false' }};
            let EMPNO = '{{ $emp->empno ?? '' }}';
            const tabLoaded = {};

            // ── Simple tab switcher ──
            document.querySelectorAll('#empTabs .nav-link').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('#empTabs .nav-link').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('#tabContent .tab-pane').forEach(p => p.classList.remove(
                        'active'));
                    this.classList.add('active');
                    const id = this.dataset.tab;
                    document.getElementById(id).classList.add('active');
                    if (id !== 'per') lazyLoad(id);
                });
            });

            function lazyLoad(tabId) {
                if (tabLoaded[tabId]) return;
                if (!EMPNO) {
                    document.getElementById(tabId).innerHTML =
                        '<div class="tab-skeleton" style="color:#f59e0b;">⚠ Save Personal Info first to get Employee ID.</div>';
                    return;
                }
                const map = {
                    off: 'official',
                    add: 'location',
                    edu: 'education',
                    course: 'shortcourse',
                    train: 'training',
                    exp: 'experience',
                    nomi: 'nominee',
                    job: 'jobhistory'
                };
                fetch('/hrm/tab/' + map[tabId] + '/' + EMPNO, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    })
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById(tabId).innerHTML = html;
                        tabLoaded[tabId] = true;
                        document.getElementById(tabId).querySelectorAll('script').forEach(s => {
                            const ns = document.createElement('script');
                            ns.textContent = s.textContent;
                            document.body.appendChild(ns);
                        });
                    })
                    .catch(() => {
                        document.getElementById(tabId).innerHTML =
                            '<div class="tab-skeleton" style="color:red;">⚠ Failed to load. ' +
                            '<a href="#" onclick="tabLoaded[\'' + tabId + '\']=false;lazyLoad(\'' + tabId +
                            '\');return false;">Retry</a></div>';
                    });
            }

            function reloadTab(tabId) {
                tabLoaded[tabId] = false;
                lazyLoad(tabId);
            }

            // ════════════════════════════════════════════
            //  Wait for jQuery + plugins
            // ════════════════════════════════════════════
            window.addEventListener('load', function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });


                // ── Swal helpers ──
                window.swalOk = msg => Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: msg,
                    confirmButtonColor: '#1a3a5c',
                    timer: 2000,
                    showConfirmButton: false
                });
                window.swalErr = xhr => {
                    const msg = xhr.responseJSON?.errors ?
                        Object.values(xhr.responseJSON.errors).flat().join('\n') :
                        (xhr.responseJSON?.message || 'An error occurred.');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                };

                // ── Global helpers for tab scripts ──
                window.ajaxPost = function(url, formId, cb) {
                    const fd = {};
                    $('#' + formId).serializeArray().forEach(f => fd[f.name] = f.value);
                    fd.empno = fd.empno || EMPNO;
                    $.ajax({
                        url,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(fd),
                        dataType: 'json',
                        success: res => {
                            swalOk(res.message);
                            if (cb) cb();
                        },
                        error: swalErr
                    });
                };

                window.deleteSubRow = function(url, cb) {
                    Swal.fire({
                            icon: 'warning',
                            title: 'Delete?',
                            showCancelButton: true,
                            confirmButtonText: 'Delete',
                            confirmButtonColor: '#c0392b',
                            cancelButtonColor: '#1a3a5c'
                        })
                        .then(r => {
                            if (!r.isConfirmed) return;
                            $.ajax({
                                url,
                                method: 'DELETE',
                                dataType: 'json',
                                success: () => {
                                    swalOk('Deleted.');
                                    if (cb) cb();
                                },
                                error: swalErr
                            });
                        });
                };

                // ── CREATE MODE: emp ID lookup ──
                if (!IS_EDIT) {
                    $('#empnoInput').on('keyup', function() {
                        const k = $(this).val();
                        $.get('/hrm/empsearch', {
                            search_key: k
                        }, res => {
                            let h = '';
                            res.data.forEach(i => h += `<option value="${i.new_empno}">`);
                            $('#empno_list').empty().append(h);
                        });
                    });
                    $('#findemp').on('click', function() {
                        EMPNO = $('#empnoInput').val().trim().toUpperCase();
                        if (!EMPNO) return;
                        $('#empno').val(EMPNO);
                        $.get('/api/getEmpDetails', {
                            empno: EMPNO
                        }, res => {
                            const e = Array.isArray(res) ? res[0] : res.data;
                            if (!e) {
                                Swal.fire('Not Found', 'No employee found', 'warning');
                                return;
                            }
                            ['first_name', 'last_name', 'middle_name', 'b_name', 'father_name',
                                'mother_name', 'husband_name', 'gurdian_name', 'national_id_no',
                                'passport_no', 'emp_mobile_no', 'sms_mobile_no'
                            ]
                            .forEach(f => $('#' + f).val(e[f] || ''));
                            if (e.dob) $('#dob').val(moment(e.dob).format('DD-MM-YYYY'));
                            $('#sex').val(e.sex);
                            $('#marial_status').val(e.marial_status);
                            $('#religion_id').val(e.religion_id);
                            $('#status').val(e.status);
                            $('#company_id').val(e.company_id);

                            // ── Load existing photo / signature from network drive ──
                            if (e.Y) {
                                const pUrl = 'http://192.168.210.205:81/' + e.emp_img +
                                    '?v=' + Date
                                    .now();
                                $('#photoPreview').attr('src', pUrl).show();
                                $('#photoPlaceholder').hide();
                                $('#removePhoto').show();
                                $('#photoFilename').html('📁 ' + e.Y);
                            }
                            if (e.Z) {
                                const sUrl = 'http://192.168.210.205:82/' + e.emp_sign + '?v=' + Date
                                    .now();
                                $('#signPreview').attr('src', sUrl).show();
                                $('#signPlaceholder').hide();
                                $('#removeSign').show();
                                $('#signFilename').html('📁 ' + e.Z);
                            }

                            Object.keys(tabLoaded).forEach(k => tabLoaded[k] = false);
                        });
                    });
                }

                // ── PERSONAL SAVE / UPDATE ──
                $('#frmPersonal').on('submit', function(e) {
                    e.preventDefault();

                    if (!IS_EDIT) {
                        const v = $('#empnoInput').val();
                        if (!v) {
                            Swal.fire('Required', 'Please select an Employee', 'warning');
                            return;
                        }
                        EMPNO = v.trim().toUpperCase();
                        $('#empno').val(EMPNO);
                    }

                    // ── grab all fields + all Select2 names automatically ──
                    const formData = lovFormObjectWithNames('#frmPersonal');
                    formData.empno = EMPNO; // ensure empno is always correct

                    $.ajax({
                        url: '/api/saveEmpPersonal',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(formData),
                        dataType: 'json',
                        success: res => {
                            swalOk(res.message);
                        },
                        error: swalErr
                    });
                });

                // ── Flatpickr date pickers (dd-mm-yyyy) ──
                flatpickr('.date-pick', {
                    dateFormat: 'd-m-Y',
                    allowInput: true,
                    disableMobile: true,
                });

                // ── Set as_on to today if empty ──
                const asOnInput = document.querySelector('input[name="as_on"]');
                if (asOnInput && !asOnInput.value.trim()) {
                    const today = new Date();
                    const dd = String(today.getDate()).padStart(2, '0');
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const yyyy = today.getFullYear();
                    asOnInput.value = dd + '-' + mm + '-' + yyyy;
                    if (asOnInput._flatpickr) asOnInput._flatpickr.setDate(asOnInput.value, false, 'd-m-Y');
                }
                // ── Clear button ──
                $('#clearPersonal').on('click', () => {
                    if (!IS_EDIT) $('#frmPersonal')[0].reset();
                    resetPreview('photo');
                    resetPreview('sign');
                });
                // ── Age Calculator ──
                function calcAge() {
                    const dobVal = $('#dob').val().trim();
                    const asOnVal = $('input[name="as_on"]').val().trim();
                    if (!dobVal) return;

                    function parseDate(str) {
                        // expects dd-mm-yyyy
                        const parts = str.split('-');
                        if (parts.length !== 3) return null;
                        return new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
                    }

                    const dob = parseDate(dobVal);
                    const asOn = asOnVal ? parseDate(asOnVal) : new Date();
                    if (!dob || !asOn || isNaN(dob) || isNaN(asOn)) return;

                    let years = asOn.getFullYear() - dob.getFullYear();
                    let months = asOn.getMonth() - dob.getMonth();
                    let days = asOn.getDate() - dob.getDate();

                    if (days < 0) {
                        months--;
                        // days in previous month relative to asOn
                        const prevMonth = new Date(asOn.getFullYear(), asOn.getMonth(), 0);
                        days += prevMonth.getDate();
                    }
                    if (months < 0) {
                        years--;
                        months += 12;
                    }

                    const yy = String(years).padStart(2, '0');
                    const mm = String(months).padStart(2, '0');
                    const dd = String(days).padStart(2, '0');

                    $('#age2').val(`${yy} Year    ${mm} Months    ${dd} Days`);
                }

                // ── Trigger age calc on DOB or As-On change ──
                $(document).on('change', '#dob, input[name="as_on"]', calcAge);

                // ── On page load: calc if DOB already filled (edit mode) ──
                if ($('#dob').val().trim()) calcAge();


                // ── IMAGE PREVIEW SETUP ──────────────────────────────
                function setupPreview(inputId, previewId, placeholderId, removeBtnId, filenameId) {
                    const $input = $('#' + inputId);
                    const $preview = $('#' + previewId);
                    const $ph = $('#' + placeholderId);
                    const $removeBtn = $('#' + removeBtnId);
                    const $fname = $('#' + filenameId);

                    $input.on('change', function() {
                        const file = this.files[0];
                        if (!file) return;
                        if (!file.type.startsWith('image/')) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid file',
                                text: 'Please select an image file (JPG, PNG…)',
                                confirmButtonColor: '#1a3a5c'
                            });
                            this.value = '';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'File too large',
                                text: 'Maximum allowed size is 2 MB.',
                                confirmButtonColor: '#1a3a5c'
                            });
                            this.value = '';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            $preview.attr('src', ev.target.result).show();
                            $ph.hide();
                            $removeBtn.show();
                            $fname.html('📁 ' + file.name +
                                ' <span style="color:#f59e0b;">(pending save)</span>');
                        };
                        reader.readAsDataURL(file);
                    });

                    $removeBtn.on('click', function() {
                        $input.val('');
                        $preview.attr('src', '').hide();
                        $ph.show();
                        $(this).hide();
                        $fname.html('');
                    });
                }

                function resetPreview(which) {
                    const m = {
                        photo: {
                            input: 'photoInput',
                            preview: 'photoPreview',
                            ph: 'photoPlaceholder',
                            rm: 'removePhoto',
                            fn: 'photoFilename'
                        },
                        sign: {
                            input: 'signInput',
                            preview: 'signPreview',
                            ph: 'signPlaceholder',
                            rm: 'removeSign',
                            fn: 'signFilename'
                        }
                    } [which];
                    $('#' + m.input).val('');
                    $('#' + m.preview).attr('src', '').hide();
                    $('#' + m.ph).show();
                    $('#' + m.rm).hide();
                    $('#' + m.fn).html('');
                }

                // Wire both image pickers
                setupPreview('photoInput', 'photoPreview', 'photoPlaceholder', 'removePhoto', 'photoFilename');
                setupPreview('signInput', 'signPreview', 'signPlaceholder', 'removeSign', 'signFilename');

                // ── DELETE EMPLOYEE ──
                $('#deleteEmpBtn, #deleteEmpBtn2').on('click', function() {
                    const empno = $(this).data('empno');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Delete Employee?',
                        html: `Permanently delete <b>${empno}</b>? All records will be removed.`,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        confirmButtonColor: '#c0392b',
                        cancelButtonColor: '#1a3a5c',
                        reverseButtons: true
                    }).then(r => {
                        if (!r.isConfirmed) return;
                        $.ajax({
                            url: '/api/deleteEmployee/' + empno,
                            method: 'DELETE',
                            dataType: 'json',
                            success: () => Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    timer: 1600,
                                    showConfirmButton: false
                                })
                                .then(() => window.location.href = '{{ route('emplist') }}'),
                            error: swalErr
                        });
                    });
                });

            }); // end window.load




            /* ── Smart dd-mm-yyyy date picker for all .date-pick inputs ── */
            (function() {
                function initDatePick(scope) {
                    var scope = scope || document;
                    scope.querySelectorAll('input.date-pick').forEach(function(el) {
                        if (el.dataset.fpInit) return; // skip if already init
                        el.dataset.fpInit = '1';

                        // 1. Flatpickr calendar
                        if (typeof flatpickr !== 'undefined') {
                            flatpickr(el, {
                                dateFormat: 'd-m-Y',
                                allowInput: true,
                                disableMobile: true,
                                onReady: function(_, __, fp) {
                                    fp.calendarContainer && fp.calendarContainer.classList.add(
                                        'fp-sm');
                                }
                            });
                        }

                        // 2. Smart 6/8-digit auto-format on keyup
                        el.addEventListener('keydown', function(e) {
                            if (e.key === 'Tab' || e.key === 'Enter') {
                                autoFormatDate(this);
                            }
                        });
                        el.addEventListener('blur', function() {
                            autoFormatDate(this);
                        });
                    });
                }

                function autoFormatDate(el) {
                    var raw = el.value.replace(/\D/g, ''); // digits only
                    if (!raw) return;

                    var day, mon, yr;

                    if (raw.length === 6) {
                        // 020496 → 02-04-1996
                        day = raw.substr(0, 2);
                        mon = raw.substr(2, 2);
                        yr = raw.substr(4, 2);
                        yr = (parseInt(yr, 10) <= 30 ? '20' : '19') + yr;
                    } else if (raw.length === 8) {
                        // 02041996 → 02-04-1996
                        day = raw.substr(0, 2);
                        mon = raw.substr(2, 2);
                        yr = raw.substr(4, 4);
                    } else {
                        return; // don't reformat partial or already-formatted
                    }

                    // Validate range
                    var d = parseInt(day, 10),
                        m = parseInt(mon, 10),
                        y = parseInt(yr, 10);
                    if (d < 1 || d > 31 || m < 1 || m > 12 || y < 1900 || y > 2099) return;

                    var formatted = day + '-' + mon + '-' + yr;
                    el.value = formatted;

                    // Push value into Flatpickr instance if present
                    if (el._flatpickr) el._flatpickr.setDate(formatted, false, 'd-m-Y');
                }

                // Init on DOMContentLoaded (for empform Tab1)
                document.addEventListener('DOMContentLoaded', function() {
                    initDatePick(document);
                });

                // Expose for lazy-loaded AJAX tabs
                window.initDatePick = initDatePick;
            })();

            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Enter') return;
                const focusable =
                    'input:not([type=hidden]):not([readonly]):not([disabled]), select:not([disabled]), textarea:not([disabled])';
                const fields = Array.from(document.querySelectorAll(focusable)).filter(el => el.offsetParent !== null);
                const idx = fields.indexOf(document.activeElement);
                if (idx === -1) return;
                e.preventDefault();
                const next = fields[idx + 1];
                if (next) next.focus();
            });


            document.getElementById('findemp').addEventListener('click', function(e) {
                const empno = document.getElementById('empnoInput').value.trim();

                if (!empno) {
                    alert('Please enter Employee ID');
                    e.preventDefault();
                    return;
                }

                // redirect dynamically
                this.href = `/hrm/empedite/${empno}`;
            });
        </script>

    </body>

    </html>

@endsection
