{{-- ============================================================
     resources/views/temp-emp/index.blade.php
     Temp Employee Entry — Bootstrap 5 + Select2 (AJAX LOV)
     - Page load = NEW mode (auto ID, fields open)
     - LOV select = VIEW mode (fields locked, edit/delete enabled)
     - New button = NEW mode again
     - All LOV fields save BOTH id AND name
     - fillForm sets Select2 AJAX options correctly
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Temp Employee Entry')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        @font-face {
            font-family: 'SutonnyMJ';
            src: url('{{ asset('fonts/SutonnyMJ.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --navy: #1B3A5E;
            --navy2: #162F4D;
            --navy3: #0F2240;
            --gold: #F5A623;
            --gold2: #D9900F;
            --blue: #1558A8;
            --blue2: #0D4490;
            --red: #E03B3B;
            --green: #1A6E3C;
            --bg: #F0F2F6;
            --bd: #E0E6EF;
            --bd2: #C8D4E0;
            --t2: #4A5A6E;
            --t3: #8A97A8;
            --inp: #F5F7FA;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box
        }

        html,
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: var(--bg);
            font-size: 13px;
            margin: 0;
            padding: 0
        }



        .bangla-text {
            font-family: 'SutonnyMJ', Arial, sans-serif;
            font-size: 18px !important;
            line-height: 1.1;
            font-weight: bold !important;
        }

        /* TOP BAR */
        .topbar {
            background: var(--navy);
            height: 50px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 14px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .28)
        }

        .topbar .btn-gold {
            background: var(--gold);
            border: none;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: .04em;
            height: 34px;
            padding: 0 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: background .13s;
            flex-shrink: 0
        }

        .topbar .btn-gold:hover {
            background: var(--gold2)
        }

        .topbar .btn-gold:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .topbar .emp-name {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .topbar .btn-ghost {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, .4);
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 32px;
            padding: 0 14px;
            border-radius: 4px;
            cursor: pointer;
            transition: all .13s
        }

        .topbar .btn-ghost:hover {
            background: rgba(255, 255, 255, .1);
            border-color: rgba(255, 255, 255, .7)
        }

        .topbar .btn-del {
            background: var(--red);
            border: none;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 32px;
            padding: 0 14px;
            border-radius: 4px;
            cursor: pointer;
            transition: background .13s
        }

        .topbar .btn-del:hover {
            background: #C02828
        }

        .topbar .btn-del:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .topbar .mode-tag {
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            letter-spacing: .05em;
            border: 1px solid transparent;
            display: none
        }

        .topbar .mode-tag.show {
            display: inline-flex;
            align-items: center
        }

        /* TOOLBAR */
        .toolbar {
            background: #fff;
            border-bottom: 1px solid var(--bd);
            height: 44px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 6px;
            position: sticky;
            top: 50px;
            z-index: 1020;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04)
        }

        .toolbar .t-sep {
            width: 1px;
            height: 20px;
            background: var(--bd);
            margin: 0 3px;
            flex-shrink: 0
        }

        .toolbar .btn-new {
            background: var(--navy);
            border: 1px solid var(--navy);
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 30px;
            padding: 0 13px;
            border-radius: 4px;
            cursor: pointer;
            transition: background .13s;
            display: inline-flex;
            align-items: center;
            gap: 5px
        }

        .toolbar .btn-new:hover {
            background: var(--navy2)
        }

        .toolbar .btn-save {
            background: #EBF7F0;
            border: 1px solid #8ECFAA;
            color: var(--green);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 30px;
            padding: 0 13px;
            border-radius: 4px;
            cursor: pointer;
            transition: background .13s;
            display: inline-flex;
            align-items: center;
            gap: 5px
        }

        .toolbar .btn-save:hover {
            background: #d0ecdd
        }

        .toolbar .btn-save:disabled {
            opacity: .38;
            cursor: not-allowed
        }

        .toolbar .btn-cancel {
            background: #F5F7FA;
            border: 1px solid var(--bd2);
            color: var(--t2);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 30px;
            padding: 0 13px;
            border-radius: 4px;
            cursor: pointer
        }

        .toolbar .btn-cancel:disabled {
            opacity: .38;
            cursor: not-allowed
        }

        .toolbar .btn-migrate {
            background: #F0F4FF;
            border: 1px solid #ADC0F0;
            color: #1B3A9E;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 30px;
            padding: 0 13px;
            border-radius: 4px;
            cursor: pointer;
            transition: background .13s;
            display: inline-flex;
            align-items: center;
            gap: 5px
        }

        .toolbar .btn-migrate:hover {
            background: #dde6ff
        }

        .toolbar .btn-migrate:disabled {
            opacity: .38;
            cursor: not-allowed
        }

        .toolbar .srch-group {
            margin-left: auto;
            display: flex;
            align-items: center
        }

        .toolbar .srch-wrap {
            position: relative
        }

        .toolbar .srch-wrap::before {
            content: '⌕';
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: var(--t3);
            pointer-events: none
        }

        .toolbar .srch-inp {
            height: 30px;
            width: 210px;
            font-size: 12px;
            border: 1px solid var(--bd2);
            border-right: none;
            border-radius: 4px 0 0 4px;
            background: #F5F7FA;
            color: #1C2B3A;
            padding: 0 10px 0 30px;
            outline: none;
            transition: border-color .13s;
            font-family: 'Nunito Sans', sans-serif
        }

        .toolbar .srch-inp:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21, 88, 168, .1);
            background: #fff
        }

        .toolbar .srch-btn {
            height: 30px;
            padding: 0 16px;
            background: var(--blue);
            border: 1px solid var(--blue);
            border-radius: 0 4px 4px 0;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer
        }

        .toolbar .srch-btn:hover {
            background: var(--blue2)
        }

        /* PAGE */
        .page-body {
            padding: 16px 24px 36px;
            max-width: 1200px;
            margin: 0 auto
        }

        .sec-hd {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px
        }

        .sec-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap
        }

        .sec-id-badge {
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 20px;
            background: #FFF8E1;
            color: #7A5500;
            border: 1px solid #F0CC60
        }

        .sec-line {
            flex: 1;
            height: 1.5px;
            background: linear-gradient(90deg, var(--bd2), transparent);
            border-radius: 2px
        }

        /* CARD */
        .card {
            background: #fff;
            border: 1px solid var(--bd);
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06), 0 4px 18px rgba(0, 0, 0, .05);
            margin-bottom: 14px;
            overflow: visible
        }

        .card-hd {
            padding: 12px 20px;
            border-bottom: 1px solid var(--bd);
            display: flex;
            align-items: center;
            gap: 9px;
            border-radius: 6px 6px 0 0
        }

        .card-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--blue);
            flex-shrink: 0
        }

        .card-ttl {
            font-size: 11px;
            font-weight: 800;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .09em
        }

        .card-body {
            padding: 24px 24px 20px
        }

        /* TWO-COL GRID — never collapses */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1px 1fr;
            gap: 0 40px;
            align-items: start
        }

        .col-divider {
            background: var(--bd);
            min-height: 100%;
            width: 1px
        }

        .field-col {
            display: flex;
            flex-direction: column;
            gap: 18px
        }

        .field-pair {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px
        }

        /* FIELDS */
        .f {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .f .lbl {
            font-size: 11px;
            font-weight: 700;
            color: var(--t2);
            letter-spacing: .06em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px
        }

        .f .lbl .req {
            color: var(--red)
        }

        .f .lbl .note {
            font-size: 10px;
            font-weight: 600;
            color: var(--blue);
            text-transform: none;
            letter-spacing: 0
        }

        .f input,
        .f select {
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--bd2);
            border-radius: 4px;
            background: var(--inp);
            color: #1C2B3A;
            font-size: 13px;
            font-family: 'Nunito Sans', sans-serif;
            transition: border-color .13s, box-shadow .13s;
            width: 100%;
            appearance: none;
            outline: none
        }

        .f input:focus,
        .f select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21, 88, 168, .1);
            background: #fff
        }

        .f input::placeholder {
            color: var(--t3);
            font-size: 12.5px
        }

        .f input.auto-id {
            background: var(--navy);
            color: #fff;
            font-family: 'DM Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .05em;
            cursor: default;
            border-color: #2A5080
        }

        .f input.hi {
            background: #EBF4FF;
            border-color: #A8C8EE
        }

        .f input.is-invalid,
        .f select.is-invalid {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(224, 59, 59, .08)
        }

        .sel-wrap {
            position: relative
        }

        .sel-wrap::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--t3);
            pointer-events: none
        }

        /* SELECT2 */
        .select2-container--bootstrap-5 .select2-selection {
            height: 40px !important;
            border: 1px solid var(--bd2) !important;
            border-radius: 4px !important;
            background: var(--inp) !important;
            font-family: 'Nunito Sans', sans-serif !important
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            font-size: 13px !important;
            color: #1C2B3A !important;
            padding-left: 12px !important
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: var(--t3) !important;
            font-size: 12.5px !important
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 38px !important
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--blue) !important;
            box-shadow: 0 0 0 3px rgba(21, 88, 168, .1) !important;
            background: #fff !important
        }

        .select2-dropdown {
            border: 1px solid var(--bd2);
            border-radius: 4px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 13px
        }

        .select2-results__option {
            padding: 9px 12px;
            font-weight: 600
        }

        .select2-results__option--highlighted {
            background: #EBF4FF !important;
            color: var(--blue2) !important
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

        .select2-container {
            width: 100% !important
        }

        /* LOV TABLE */
        #lovTbody td:first-child {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            color: var(--navy)
        }

        /* TOAST */
        #hrmToast {
            position: fixed;
            bottom: 22px;
            right: 22px;
            min-width: 250px;
            max-width: 340px;
            z-index: 9999;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700
        }
    </style>
@endpush

@section('content')

    {{-- TOP BAR --}}
    <div class="topbar">
        <button class="btn-gold" id="btnEdit" onclick="cmdEdit()" disabled>
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2"
                style="margin-right:5px">
                <path d="M11 2l3 3-9 9H2v-3z" />
            </svg>
            EDIT
        </button>
        <span class="emp-name" id="tbEmpName">— New Record —</span>
        <span class="mode-tag" id="modeTag"></span>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <button class="btn-ghost" onclick="cmdClear()">Clear</button>
            <div style="display:flex;align-items:center;gap:8px;">
                <a href="{{ route('empnewentry') }}" class="btn-back">← Employee Entry</a>
                <a href="{{ route('emplist') }}" class="btn-back">← Back to List</a>
                @if (isset($emp))
                    <button type="button" class="btn btn-del btn-sm" id="deleteEmpBtn" data-empno="{{ $emp->empno }}">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                @endif
            </div>
            <button class="btn-del" id="btnDelete" onclick="confirmDelete()" disabled>
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"
                    style="margin-right:4px">
                    <polyline points="3,4 13,4" />
                    <path d="M5 4V2h6v2" />
                    <path d="M4 4l1 10h6l1-10" />
                </svg>
                Delete
            </button>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <button class="btn-new" id="btnNew" onclick="cmdNew()">
            <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="8" y1="2" x2="8" y2="14" />
                <line x1="2" y1="8" x2="14" y2="8" />
            </svg>
            New
        </button>
        <button class="btn-save" id="btnSave" onclick="cmdSave()">
            <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 2H4L2 4v10h12V4z" />
                <rect x="5" y="9" width="6" height="5" />
                <rect x="5" y="2" width="5" height="3" />
            </svg>
            Save
        </button>
        <div class="t-sep"></div>

        <button class="btn-cancel" id="btnCancel" onclick="cmdCancel()" disabled>Cancel</button>
        <div class="t-sep"></div>
        <button class="btn-migrate" id="btnMigrate" onclick="cmdMigrate()" disabled
            title="Convert Temp → Permanent (PB_TRANSFER)">
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 8h12M10 4l4 4-4 4" />
                <path d="M6 12l-4-4 4-4" opacity=".4" />
            </svg>
            Migration
        </button>
        <div class="srch-group">
            <div class="srch-wrap">
                <input class="srch-inp" type="text" id="searchInp" placeholder="Search Emp ID or Name…"
                    onkeydown="if(event.key==='Enter')cmdSearch()">
            </div>
            <button class="srch-btn" onclick="cmdSearch()">Search</button>
        </div>
    </div>

    {{-- PAGE BODY --}}
    <div class="page-body">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2">{{ session('success') }}<button
                    type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-2">{{ session('error') }}<button type="button"
                    class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="sec-hd">
            <div class="sec-title">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="8" cy="5" r="3" />
                    <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" />
                </svg>
                Personal Information
            </div>
            <span class="sec-id-badge" id="secIdBadge">ID: —</span>
            <div class="sec-line"></div>
        </div>

        {{-- CARD 1: Employee Information --}}
        <div class="card">
            <div class="card-hd">
                <div class="card-dot"></div><span class="card-ttl">Employee Information</span>
            </div>
            <div class="card-body">
                <div class="two-col">

                    {{-- LEFT --}}
                    <div class="field-col">
                        <div class="f">
                            <label class="lbl">Team Employee ID <span class="note">(Auto Generated)</span></label>
                            <input type="text" id="emp_id" name="empno" class="auto-id"
                                placeholder="Auto on Save…" readonly>
                        </div>
                        <div class="f">
                            <label class="lbl">First Name <span class="req">*</span></label>
                            <input type="text" id="first_name" name="first_name" placeholder="First name"
                                oninput="updateTopName()">
                        </div>
                        <div class="f">
                            <label class="lbl">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" placeholder="Middle name"
                                oninput="updateTopName()">
                        </div>
                        <div class="f">
                            <label class="lbl">Last Name <span class="req">*</span></label>
                            <input type="text" id="last_name" name="last_name" placeholder="Last name"
                                oninput="updateTopName()">
                        </div>
                        <div class="f">
                            <label class="lbl">Bangla Name <span class="req">*</span></label>
                            <input type="text" class="bangla-text" id="b_name" name="b_name"
                                placeholder="বাংলা নাম">
                        </div>
                        <div class="f">
                            <label class="lbl">Permanent Emp No</label>
                            <input type="text" id="perm_emp_no" name="permanent_empno" class="hi"
                                placeholder="Assign on confirmation">
                        </div>
                    </div>

                    <div class="col-divider"></div>

                    {{-- RIGHT --}}
                    <div class="field-col">
                        <div class="f">
                            <label class="lbl">Company Name <span class="req">*</span></label>
                            {{-- Stores COMPANY_ID as value, COMPANY_NAME as text --}}
                            <select id="company_top" name="company_id" class="select2-ajax"
                                data-url="{{ route('lov.company') }}" data-placeholder="Search company…">
                                <option value="">Search company…</option>
                            </select>
                            <input type="hidden" id="company_name" name="company_name">
                        </div>
                        <div class="f">
                            <label class="lbl">Card No</label>
                            <input type="text" id="card_no" name="card_no" placeholder="Card number">
                        </div>
                        <div class="f">
                            <label class="lbl">Date of Birth</label>
                            <input type="text" id="dob" name="dob" class="flatpickr-date"
                                placeholder="dd-mm-yyyy">
                        </div>
                        <div class="f">
                            <label class="lbl">Gender</label>
                            <div class="sel-wrap">
                                <select id="gender" name="sex">
                                    <option value="">— Select —</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="f">
                            <label class="lbl">Status</label>
                            <div class="sel-wrap">
                                <select id="status" name="status">
                                    <option value="">— Select —</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- CARD 2: Official Details --}}
        <div class="card">
            <div class="card-hd">
                <div class="card-dot"></div><span class="card-ttl">Official Details</span>
            </div>
            <div class="card-body">
                <div class="two-col">

                    {{-- LEFT --}}
                    <div class="field-col">
                        <div class="f">
                            <label class="lbl">Company Name</label>
                            {{-- Stores COMPANY_ID, hidden company_name --}}
                            <select id="company_bot" name="official[company_id]" class="select2-ajax"
                                data-url="{{ route('lov.companyHrm') }}" data-placeholder="Search company…">
                                <option value="">Search company…</option>
                            </select>
                            <input type="hidden" id="official_company_name" name="official[company_name]">
                        </div>
                        <div class="f">
                            <label class="lbl">Dept Name</label>
                            {{-- Stores DEPT_NO (id), hidden dept_name --}}
                            <select id="dept_no" name="official[dept_no]" class="select2-ajax"
                                data-url="{{ route('lov.dept') }}" data-placeholder="Search department…">
                                <option value="">Search department…</option>
                            </select>
                            <input type="hidden" id="dept_name" name="official[dept_name]">
                        </div>
                        <div class="f">
                            <label class="lbl">Section Name</label>
                            {{-- Stores SECTION_NO (id), hidden section_name --}}
                            <select id="section_no" name="official[section_no]" class="select2-ajax"
                                data-url="{{ route('lov.section') }}" data-placeholder="Search section…">
                                <option value="">Search section…</option>
                            </select>
                            <input type="hidden" id="section_name" name="official[section_name]">
                        </div>
                        <div class="f">
                            <label class="lbl">Emp Type</label>
                            <div class="sel-wrap">
                                <select id="emp_type" name="official[emp_type]" class="select2-ajax"
                                    data-url="{{ route('lov.emp_type') }}" data-placeholder="Search Emp Type">
                                </select>
                            </div>
                        </div>
                        <div class="f">
                            <label class="lbl">Floor Name</label>
                            {{-- Stores FLOOR_ID (id), hidden floor_desc --}}
                            <select id="floor_id" name="official[floor_id]" class="select2-ajax"
                                data-url="{{ route('lov.floor') }}" data-placeholder="Search floor…">
                                <option value="">N/A</option>
                            </select>
                            <input type="hidden" id="floor_desc" name="official[floor_desc]">
                        </div>
                        <div class="field-pair">
                            <div class="f">
                                <label class="lbl">OT</label>
                                <select id="ot" name="official[ot_ent]" class="select2-ajax"
                                    data-url="{{ route('lov.yesno') }}" data-placeholder="Select…">
                                    <option value="">— Select —</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                            <div class="f">
                                <label class="lbl">Gross</label>
                                <input type="text" id="gross" name="official[gross]" placeholder="0"
                                    inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                            </div>
                        </div>
                    </div>

                    <div class="col-divider"></div>

                    {{-- RIGHT --}}
                    <div class="field-col">
                        <div class="f">
                            <label class="lbl">Joining Date</label>
                            <input type="text" id="joining_date" name="official[joining_date]" class="flatpickr-date"
                                placeholder="dd-mm-yyyy">
                        </div>
                        <div class="f">
                            <label class="lbl">Designation Name</label>
                            {{-- Stores DES_ID (id), hidden des_name --}}
                            <select id="des_id" name="official[des_id]" class="select2-ajax"
                                data-url="{{ route('lov.designation') }}" data-placeholder="Search designation…">
                                <option value="">Search designation…</option>
                            </select>
                            <input type="hidden" id="des_name" name="official[des_name]">
                        </div>
                        <div class="f">
                            <label class="lbl">Shift Name</label>
                            {{-- Stores SHIFT_CODE (id), hidden shift_name --}}
                            <select id="shift_code" name="official[shift_code]" class="select2-ajax"
                                data-url="{{ route('lov.shift') }}" data-placeholder="Search shift…">
                                <option value="">Search shift…</option>
                            </select>
                            <input type="hidden" id="shift_name" name="official[shift_name]">
                        </div>
                        <div class="f">
                            <label class="lbl">Weekly Off Day</label>
                            <select id="weekly_off" name="official[weekly_off]" class="select2-ajax"
                                data-url="{{ route('lov.weeklyoff') }}" data-placeholder="Select…">
                                <option value="">— Select —</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="f">
                            <label class="lbl">Work Ent</label>
                            <div class="sel-wrap">
                                <select id="work_ent" name="official[work_ent]">
                                    <option value="">— Select —</option>
                                    <option value="Officer">Officer</option>
                                    <option value="Worker">Worker</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="f">
                            <label class="lbl">Line</label>
                            {{-- Stores LINE_NO (id), hidden line --}}
                            <select id="line_no" name="official[line_no]" class="select2-ajax"
                                data-url="{{ route('lov.line') }}" data-placeholder="Search line…">
                                <option value="">Search line…</option>
                            </select>
                            <input type="hidden" id="line_name" name="official[line]">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- /page-body --}}


    {{-- LOV MODAL --}}
    <div class="modal fade" id="lovModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:8px;overflow:hidden;">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#0F2240,#1B3A5E);border:none;padding:14px 18px;">
                    <h5 class="modal-title text-white fw-bold fs-6">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2.2" class="me-2">
                            <circle cx="7" cy="7" r="5" />
                            <line x1="11" y1="11" x2="14" y2="14" />
                        </svg>
                        List of Values — Team Employee
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="p-3 border-bottom bg-light d-flex gap-2 align-items-center">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#8A97A8"
                            stroke-width="2">
                            <circle cx="7" cy="7" r="5" />
                            <line x1="11" y1="11" x2="14" y2="14" />
                        </svg>
                        <input class="form-control form-control-sm" id="lovSearch"
                            placeholder="Search by ID, name, department…" oninput="filterLOV(this.value)">
                    </div>
                    <div style="max-height:300px;overflow-y:auto;">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light position-sticky" style="top:0;z-index:1;">
                                <tr>
                                    <th
                                        style="font-size:10.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#4A5A6E;">
                                        Emp ID</th>
                                    <th
                                        style="font-size:10.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#4A5A6E;">
                                        Full Name</th>
                                    <th
                                        style="font-size:10.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#4A5A6E;">
                                        Department</th>
                                    <th
                                        style="font-size:10.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#4A5A6E;">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody id="lovTbody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Loading…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between py-2">
                    <small class="text-muted fw-semibold" id="lovCount">0 records</small>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light border fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-sm fw-bold text-white" style="background:var(--navy);"
                            onclick="confirmLOV()">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MIGRATION CONFIRM MODAL --}}
    <div class="modal fade" id="migrateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content" style="border-radius:8px;overflow:hidden;">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#0F2240,#1B3A5E);border:none;padding:14px 18px;">
                    <div class="d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#F5A623"
                            stroke-width="2.2">
                            <path d="M2 8h12M10 4l4 4-4 4" />
                        </svg>
                        <span class="text-white fw-bold" style="font-size:14px;">Confirm Migration</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="font-size:13px;font-weight:600;color:#4A5A6E;padding:20px;">
                    <p class="mb-3">Convert this <strong>Temp Employee</strong> to a <strong>Permanent Employee</strong>?
                    </p>
                    <table class="table table-sm table-borderless mb-0" style="font-size:12.5px;">
                        <tr>
                            <td class="text-muted fw-bold"
                                style="width:140px;text-transform:uppercase;font-size:11px;letter-spacing:.04em;">Temp
                                Employee ID</td>
                            <td><span id="migrateEmpId"
                                    style="font-family:'DM Mono',monospace;font-weight:700;color:#1B3A5E;"></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold"
                                style="text-transform:uppercase;font-size:11px;letter-spacing:.04em;">Employee Name</td>
                            <td><span id="migrateName" style="font-weight:700;color:#1C2B3A;"></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold"
                                style="text-transform:uppercase;font-size:11px;letter-spacing:.04em;">New Permanent ID</td>
                            <td><span id="migratePermNo"
                                    style="font-family:'DM Mono',monospace;font-weight:700;color:#1A6E3C;"></span></td>
                        </tr>
                    </table>
                    <div class="alert alert-warning mt-3 mb-0 py-2" style="font-size:12px;">
                        <strong>This will:</strong> Insert new Permanent record, copy Official details, update Attendance
                        records.
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button class="btn btn-sm btn-light border fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm fw-bold text-white" onclick="doMigrate()"
                        style="background:linear-gradient(135deg,#1B3A5E,#1558A8);">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2" class="me-1">
                            <path d="M2 8h12M10 4l4 4-4 4" />
                        </svg>
                        Confirm Migration
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE CONFIRM MODAL --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
            <div class="modal-content" style="border-radius:8px;overflow:hidden;">
                <div class="modal-header" style="background:#FEF2F2;border-color:#FACACA;padding:14px 18px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:32px;height:32px;background:#FEE2E2;border:1.5px solid #FACACA;flex-shrink:0;">
                            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="#E03B3B"
                                stroke-width="2.2">
                                <path d="M8 2l6 12H2z" />
                                <line x1="8" y1="7" x2="8" y2="10" />
                                <circle cx="8" cy="12.5" r=".8" fill="#E03B3B" />
                            </svg>
                        </div>
                        <span class="fw-bold" style="font-size:14px;">Confirm Delete</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="deleteMsg" style="font-size:13px;font-weight:600;color:#4A5A6E;">Are you
                    sure? This action cannot be undone.</div>
                <div class="modal-footer bg-light py-2">
                    <button class="btn btn-sm btn-light border fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-danger fw-bold" onclick="doDelete()">Delete Record</button>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST --}}
    <div id="hrmToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-bold" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const TEMP_EMP_BASE = "{{ url('hrm/temp-emp') }}";
        const ROUTES = {
            nextId: "{{ route('temp-emp.next-id') }}",
            store: "{{ route('temp-emp.store') }}",
            show: (id) => `${TEMP_EMP_BASE}/${id}`,
            update: (id) => `${TEMP_EMP_BASE}/${id}`,
            destroy: (id) => `${TEMP_EMP_BASE}/${id}`,
            search: "{{ route('temp-emp.search') }}",
            lov: "{{ route('temp-emp.lov') }}",
            migrate: (id) => `${TEMP_EMP_BASE}/${id}/migrate`,
        };
        const CSRF = document.querySelector('meta[name=csrf-token]').content;

        /* ══════════════════════════════════════════
           COMPANY LIST (passed from controller)
        ══════════════════════════════════════════ */
        const COMPANIES = @json($companies ?? []);

        /* ══════════════════════════════════════════
           DATE HELPERS for dd-mm-yyyy ↔ yyyy-mm-dd
        ══════════════════════════════════════════ */
        function convertDateToDisplay(dbDate) {
            if (!dbDate) return '';

            // Remove time part if exists
            dbDate = dbDate.split(' ')[0];

            const parts = dbDate.split('-');

            // Handle YYYY-MM-DD
            if (parts[0].length === 4) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }

            // Already DD-MM-YYYY
            return dbDate;
        }

        function convertDateToDb(displayDate) {
            if (!displayDate) return '';
            const parts = displayDate.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return displayDate;
        }


        function autoFormatDate(el) {
            var raw = el.value.replace(/\D/g, '');
            if (!raw) return;

            var day, mon, yr;

            if (raw.length === 6) {
                day = raw.substring(0, 2);
                mon = raw.substring(2, 4);
                yr = raw.substring(4, 6);

                // smarter century logic
                var yearNum = parseInt(yr, 10);
                yr = (yearNum >= 50 ? '19' : '20') + yr;
            } else if (raw.length === 8) {
                day = raw.substring(0, 2);
                mon = raw.substring(2, 4);
                yr = raw.substring(4, 8);
            } else {
                return;
            }

            var d = parseInt(day, 10),
                m = parseInt(mon, 10),
                y = parseInt(yr, 10);

            if (d < 1 || d > 31 || m < 1 || m > 12 || y < 1900 || y > 2099) return;

            var formatted = `${day}-${mon}-${yr}`;

            // 🔥 important: only update if changed
            if (el.value !== formatted) {
                el.value = formatted;
            }

            // sync with flatpickr
            if (el._flatpickr) {
                el._flatpickr.setDate(formatted, false, 'd-m-Y');
            }
        }

        /* ══════════════════════════════════════════
           STATE
        ══════════════════════════════════════════ */
        let _mode = 'idle';
        let _curId = null;
        let _lovSel = null;
        let _lovData = [];

        /* ══════════════════════════════════════════
           SELECT2 INIT
           Each AJAX select also has a hidden input
           to store the TEXT (name) alongside the ID.
           Map: select#id  →  hidden#hiddenId
        ══════════════════════════════════════════ */

        /*
         * Hidden-field map: when a Select2 changes,
         * copy the selected OPTION TEXT into the
         * corresponding hidden input so the controller
         * always receives BOTH id and name.
         */
        const HIDDEN_MAP = {
            'company_top': 'company_name',
            'company_bot': 'official_company_name',
            'dept_no': 'dept_name',
            'section_no': 'section_name',
            'floor_id': 'floor_desc',
            'des_id': 'des_name',
            'shift_code': 'shift_name',
            'line_no': 'line_name',
        };

        function initSelect2() {
            $('.select2-ajax').each(function() {
                const $sel = $(this);
                const url = $sel.data('url');
                const ph = $sel.data('placeholder') || 'Search…';
                const selId = $sel.attr('id');

                $sel.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: ph,
                    minimumInputLength: 0,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        },
                        data: (params) => ({
                            q: (params && params.term) || ''
                        }),
                        processResults: (data) => ({
                            results: data.results
                        }),
                        cache: true,
                    },
                });

                /* When selection changes → copy text into hidden field */
                $sel.on('select2:select', function(e) {
                    const hiddenId = HIDDEN_MAP[selId];
                    if (hiddenId) {
                        const text = e.params.data.text || '';
                        document.getElementById(hiddenId).value = text;
                    }
                });

                /* When cleared → also clear hidden field */
                $sel.on('select2:unselect select2:clear', function() {
                    const hiddenId = HIDDEN_MAP[selId];
                    if (hiddenId) document.getElementById(hiddenId).value = '';
                });
            });
        }

        $(function() {
            initSelect2();

            /* ── Initialize Flatpickr date fields ── */
            flatpickr('.flatpickr-date', {
                dateFormat: 'd-m-Y',
                allowInput: true,
                closeOnSelect: true,
            });

            /* ── Attach autoFormatDate to date inputs ── */
            $('.flatpickr-date').on('input', function() {
                autoFormatDate(this);
            });

            /* ── Page loads in NEW mode automatically ── */
            initNewMode();
        });

        /* ══════════════════════════════════════════
           INIT NEW MODE (page load + New button)
        ══════════════════════════════════════════ */
        async function initNewMode() {
            _mode = 'new';
            _curId = null;
            resetForm();
            unlockForm();
            setBtns({
                save: true,
                cancel: false
            });
            setTopBar({
                edit: false,
                del: false,
                migrate: false
            });
            setModeTag('NEW', '#F5A623');
        }

        /* ══════════════════════════════════════════
           CONTROLLER
        ══════════════════════════════════════════ */

        /* NEW */
        async function cmdNew() {
            await initNewMode();
            showToast('New record ready.', 'navy');
        }

        /* SAVE */
        async function cmdSave() {
            if (!validateForm()) return;
            const payload = collectForm();
            const isNew = _mode === 'new';
            const url = isNew ? ROUTES.store : ROUTES.update(_curId);
            const method = isNew ? 'POST' : 'PUT';
            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast('⚠ ' + (data.message || 'Save failed.'), 'red');
                    return;
                }
                _curId = data.empno || _curId;
                _mode = 'view';
                lockForm();
                setBtns({
                    save: false,
                    cancel: false
                });
                setTopBar({
                    edit: true,
                    del: true,
                    migrate: true
                });
                setModeTag('VIEW', '#1A6E3C');
                document.getElementById('emp_id').value = _curId;
                document.getElementById('secIdBadge').textContent = 'ID: ' + _curId;
                showToast('✓ Record ' + (isNew ? 'created' : 'updated') + ' — ID ' + _curId, 'green');
            } catch (e) {
                showToast('⚠ ' + e.message, 'red');
            }
        }

        /* EDIT */
        function cmdEdit() {
            if (!_curId) {
                showToast('Load a record first.', 'warn');
                return;
            }
            _mode = 'edit';
            unlockForm();
            document.getElementById('emp_id').readOnly = true;
            setBtns({
                save: true,
                cancel: true
            });
            setTopBar({
                edit: false,
                del: false,
                migrate: false
            });
            setModeTag('EDIT', '#F5A623');
            showToast('Edit mode — modify and Save.', 'navy');
        }

        /* CANCEL */
        async function cmdCancel() {
            if (_mode === 'edit' && _curId) {
                await loadRecord(_curId);
            } else {
                await initNewMode();
                return;
            }
            lockForm();
            setBtns({
                save: false,
                cancel: false
            });
            showToast('Cancelled.', 'navy');
        }

        /* DELETE */
        function confirmDelete() {
            if (!_curId) return;
            document.getElementById('deleteMsg').textContent = `Delete Employee ID: ${_curId}? This cannot be undone.`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        async function doDelete() {
            bootstrap.Modal.getInstance(document.getElementById('deleteModal'))?.hide();
            try {
                const res = await fetch(ROUTES.destroy(_curId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast('⚠ ' + data.message, 'red');
                    return;
                }
                await initNewMode();
                showToast('✓ Deleted.', 'green');
            } catch (e) {
                showToast('⚠ ' + e.message, 'red');
            }
        }

        /* SEARCH */
        async function cmdSearch() {
            const q = document.getElementById('searchInp').value.trim();
            if (!q) {
                openLOV();
                return;
            }
            try {
                const res = await fetch(ROUTES.search + '?q=' + encodeURIComponent(q), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    showToast('No record found for: ' + q, 'warn');
                    return;
                }
                fillForm(data.data);
                document.getElementById('searchInp').value = '';
                showToast('✓ Loaded ID ' + data.data.empno, 'green');
            } catch (e) {
                showToast(e.message, 'red');
            }
        }

        /* CLEAR */
        async function cmdClear() {
            await initNewMode();
            showToast('Form cleared.', 'navy');
        }

        /* MIGRATE */
        function cmdMigrate() {
            if (!_curId) {
                showToast('Load a temp employee record first.', 'warn');
                return;
            }
            const permNo = gi('perm_emp_no').trim();
            if (!permNo) {
                showToast('⚠ Permanent Emp No is required before migration.', 'red');
                document.getElementById('perm_emp_no').classList.add('is-invalid');
                document.getElementById('perm_emp_no').focus();
                return;
            }
            document.getElementById('migrateEmpId').textContent = _curId;
            document.getElementById('migratePermNo').textContent = permNo;
            document.getElementById('migrateName').textContent = [gi('first_name'), gi('middle_name'), gi('last_name'), gi(
                    'b_name')]
                .filter(Boolean).join(' ');
            new bootstrap.Modal(document.getElementById('migrateModal')).show();
        }
        async function doMigrate() {
            bootstrap.Modal.getInstance(document.getElementById('migrateModal'))?.hide();
            showToast('Processing migration…', 'navy');
            const oldId = _curId; /* Store the original temp employee ID */
            try {
                const res = await fetch(ROUTES.migrate(_curId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        permanent_empno: gi('perm_emp_no').trim()
                    }),
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast('⚠ ' + (data.message || 'Migration failed.'), 'red');
                    return;
                }
                showToast('✓ Migration complete — Permanent ID: ' + data.permanent_empno, 'green');
                document.getElementById('btnMigrate').disabled = true;
                document.getElementById('btnMigrate').title = 'Already migrated to: ' + data.permanent_empno;

                /* Reload the original temp employee record with updated data */
                await new Promise(resolve => setTimeout(resolve, 500)); /* Brief delay for DB sync */
                await loadRecord(oldId);
                updateTopName();
                showToast('✓ Migrated employee data loaded — ID: ' + oldId, 'green');
            } catch (e) {
                showToast('⚠ ' + e.message, 'red');
            }
        }

        async function loadRecord(id) {
            try {
                const res = await fetch(ROUTES.show(id), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) fillForm(data.data);
            } catch {}
        }

        /* ══════════════════════════════════════════
           COLLECT FORM
           Sends BOTH id (from select value) AND
           name (from hidden input) for every LOV field
           Converts display dates to db format (yyyy-mm-dd)
        ══════════════════════════════════════════ */
        function collectForm() {
            return {
                empno: gi('emp_id'),
                first_name: gi('first_name'),
                middle_name: gi('middle_name'),
                last_name: gi('last_name'),
                b_name: gi('b_name'),
                /* company: id from select, name from hidden */
                company_id: $('#company_top').val() || '',
                company_name: gi('company_name'),
                card_no: gi('card_no'),
                dob: convertDateToDb(gi('dob')),
                sex: gi('gender'),
                status: gi('status'),
                permanent_empno: gi('perm_emp_no'),
                official: {
                    company_id: $('#company_bot').val() || '',
                    company_name: gi('official_company_name'),
                    dept_no: $('#dept_no').val() || '',
                    dept_name: gi('dept_name'),
                    section_no: $('#section_no').val() || '',
                    section_name: gi('section_name'),
                    emp_type: gi('emp_type'),
                    floor_id: $('#floor_id').val() || '',
                    floor_desc: gi('floor_desc'),
                    ot_ent: $('#ot').val() || '',
                    gross: gi('gross'),
                    joining_date: convertDateToDb(gi('joining_date')),
                    des_id: $('#des_id').val() || '',
                    des_name: gi('des_name'),
                    shift_code: $('#shift_code').val() || '',
                    shift_name: gi('shift_name'),
                    weekly_off: $('#weekly_off').val() || '',
                    work_ent: gi('work_ent'),
                    line_no: $('#line_no').val() || '',
                    line: gi('line_name'),
                }
            };
        }

        /* ══════════════════════════════════════════
           FILL FORM from server response
           s2set() creates the option in Select2
           AND sets the hidden input for the name
        ══════════════════════════════════════════ */
        function fillForm(r) {
            _curId = r.empno;
            _mode = 'view';
            const o = r.getempofficial || {};

            si('emp_id', r.empno);
            si('first_name', r.first_name);
            si('middle_name', r.middle_name || '');
            si('last_name', r.last_name);
            si('card_no', r.card_no || '');
            si('dob', r.dob ? convertDateToDisplay(r.dob) : '');
            si('gender', r.sex || '');
            si('status', r.status || '');
            si('perm_emp_no', r.permanent_empno || '');
            si('b_name', r.b_name || '');

            /* Company top: id=company_id, text=company_name, hidden=company_name */
            const topCompanyName = r.company_name || COMPANIES.find(c => c.id == r.company_id)?.text || '';
            s2set('#company_top', r.company_id, topCompanyName, 'company_name');

            /* Official fields: each gets id in select + text in hidden */
            const offCompanyName = o.company_name || COMPANIES.find(c => c.id == o.company_id)?.text || '';
            s2set('#company_bot', o.company_id, offCompanyName, 'official_company_name');
            s2set('#dept_no', o.dept_no, o.dept_name, 'dept_name');
            s2set('#section_no', o.section_no, o.section_name, 'section_name');
            s2set('#emp_type', o.emp_type, o.emp_type, null);
            s2set('#floor_id', o.floor_id, o.floor_desc, 'floor_desc');
            s2set('#ot', o.ot_ent, o.ot_ent === 'Y' ? 'Yes' : (o.ot_ent === 'N' ? 'No' : ''), null);
            si('gross', o.gross || '');
            si('joining_date', o.joining_date ? convertDateToDisplay(o.joining_date) : '');
            s2set('#des_id', o.des_id, o.des_name, 'des_name');
            s2set('#shift_code', o.shift_code, o.shift_name, 'shift_name');
            s2set('#weekly_off', o.weekly_off, o.weekly_off, null);
            si('work_ent', o.work_ent || '');
            s2set('#line_no', o.line, o.line_info, 'line_name');

            updateTopName();
            document.getElementById('secIdBadge').textContent = 'ID: ' + _curId;

            lockForm();
            setBtns({
                save: false,
                cancel: false
            });
            setTopBar({
                edit: true,
                del: true,
                migrate: true
            });
            setModeTag('VIEW', '#1A6E3C');
        }

        /* ══════════════════════════════════════════
           RESET FORM
        ══════════════════════════════════════════ */
        function resetForm() {
            document.querySelectorAll('.card .f input:not(.auto-id), .card .f select').forEach(el => {
                el.value = '';
                el.classList.remove('is-invalid');
            });
            /* Clear all hidden name fields */
            Object.values(HIDDEN_MAP).forEach(hid => {
                const el = document.getElementById(hid);
                if (el) el.value = '';
            });
            /* Reset all Select2 */
            $('.select2-ajax').select2('destroy');
            initSelect2();
            /* Reset plain selects */
            ['gender', 'status', 'emp_type', 'work_ent'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('emp_id').value = '';
            document.getElementById('secIdBadge').textContent = 'ID: —';
            document.getElementById('tbEmpName').textContent = '— New Record —';
        }

        /* ══════════════════════════════════════════
           VALIDATE
        ══════════════════════════════════════════ */
        function validateForm() {
            let ok = true;
            ['first_name'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.remove('is-invalid');
                if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    ok = false;
                }
            });
            if (!$('#company_top').val()) {
                document.getElementById('company_top').classList.add('is-invalid');
                ok = false;
            }
            if (!ok) showToast('⚠ Required fields are empty.', 'red');
            return ok;
        }

        /* ══════════════════════════════════════════
           LOCK / UNLOCK
        ══════════════════════════════════════════ */
        function lockForm() {
            document.querySelectorAll('.card .f input:not(.auto-id):not([type=hidden]), .card .f select').forEach(el => {
                el.setAttribute('readonly', '');
                if (el.tagName === 'SELECT') el.disabled = true;
                if (!el.classList.contains('hi')) {
                    el.style.background = '#EEF1F6';
                    el.style.color = '#8A97A8';
                }
            });
            $('.select2-ajax').prop('disabled', true).trigger('change');
            const eid = document.getElementById('emp_id');
            eid.style.background = '#EEF1F6';
            eid.style.color = '#8A97A8';
        }

        function unlockForm() {
            document.querySelectorAll('.card .f input:not(.auto-id):not([type=hidden]), .card .f select').forEach(el => {
                el.removeAttribute('readonly');
                el.disabled = false;
                el.style.background = '';
                el.style.color = '';
            });
            $('.select2-ajax').prop('disabled', false).trigger('change');
            const eid = document.getElementById('emp_id');
            eid.readOnly = true;
            eid.style.background = '#1B3A5E';
            eid.style.color = '#fff';
        }

        /* ══════════════════════════════════════════
           UI STATE HELPERS
        ══════════════════════════════════════════ */
        function setBtns({
            save,
            cancel
        }) {
            document.getElementById('btnSave').disabled = !save;
            document.getElementById('btnCancel').disabled = !cancel;
        }

        function setTopBar({
            edit,
            del,
            migrate
        }) {
            const eb = document.getElementById('btnEdit');
            eb.disabled = !edit;
            eb.style.opacity = edit ? '1' : '0.45';
            document.getElementById('btnDelete').disabled = !del;
            document.getElementById('btnMigrate').disabled = !migrate;
        }

        function setModeTag(label, color) {
            const t = document.getElementById('modeTag');
            t.textContent = label;
            t.style.background = color + '22';
            t.style.color = color;
            t.style.border = '1px solid ' + color + '55';
            t.classList.add('show');
        }

        function updateTopName() {
            const fn = gi('first_name'),
                mn = gi('middle_name'),
                ln = gi('last_name'),
                bn = gi('b_name');
            document.getElementById('tbEmpName').textContent = [fn, mn, ln, bn].filter(Boolean).join(' ') ||
                '— New Record —';
        }

        /* ══════════════════════════════════════════
           s2set — set a Select2 AJAX field
           Creates the option (id+text) if missing,
           sets the select value, AND fills the
           corresponding hidden input with the text.
        ══════════════════════════════════════════ */
        function s2set(selector, id, text, hiddenId) {
            if (id === null || id === undefined || id === '') return;
            const $s = $(selector);
            const selId = $s.attr('id');

            /* For AJAX Select2 instances, temporarily disable AJAX to set the value */
            const isAjaxSelect = $s.hasClass('select2-ajax') && !['company_top', 'company_bot'].includes(selId);

            if (isAjaxSelect) {
                /* For AJAX selects, just set the hidden field and create a display-only option */
                $s.empty();
                const opt = new Option(text || id, id, true, true);
                $s.append(opt).val(id).trigger('change');

                /* Disable AJAX for this instance to prevent it from clearing the value */
                $s.select2('destroy').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: $s.data('placeholder') || 'Search…',
                    minimumInputLength: 0,
                    data: [{
                        id: id,
                        text: text || id
                    }],
                });
            } else {
                /* For non-AJAX selects, use the original method */
                $s.find(`option[value="${id}"]`).remove();
                const opt = new Option(text || id, id, true, true);
                $s.append(opt).val(id).trigger('change');
            }

            /* Sync hidden field */
            if (hiddenId) {
                const hel = document.getElementById(hiddenId);
                if (hel) hel.value = text || '';
            }
        }

        /* ══════════════════════════════════════════
           LOV MODAL (employee lookup)
        ══════════════════════════════════════════ */
        async function openLOV() {
            _lovSel = null;
            document.getElementById('lovSearch').value = '';
            new bootstrap.Modal(document.getElementById('lovModal')).show();
            setTimeout(async () => {
                await fetchLOV('');
                document.getElementById('lovSearch').focus();
            }, 150);
        }
        async function fetchLOV(q) {
            document.getElementById('lovTbody').innerHTML =
                '<tr><td colspan="4" class="text-center text-muted py-4">Loading…</td></tr>';
            try {
                const res = await fetch(ROUTES.lov + '?q=' + encodeURIComponent(q), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!data.success) {
                    document.getElementById('lovTbody').innerHTML =
                        '<tr><td colspan="4" class="text-center text-danger py-4">Error: ' + (data.message ||
                            'Unknown error') + '</td></tr>';
                    return;
                }
                renderLOV(data.data || []);
            } catch (error) {
                document.getElementById('lovTbody').innerHTML =
                    '<tr><td colspan="4" class="text-center text-danger py-4">Failed to load: ' + error.message +
                    '</td></tr>';
            }
        }

        function renderLOV(rows) {
            const tbody = document.getElementById('lovTbody');
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No records found.</td></tr>';
                document.getElementById('lovCount').textContent = '0 records';
                return;
            }
            const c = {
                Active: '#1A7A45',
                Resigned: '#E03B3B',
                Terminated: '#8B2020',
                Retired: '#7A5500'
            };
            tbody.innerHTML = rows.map(r => `
        <tr onclick="lovClick(this,'${r.id}')" ondblclick="lovDbl('${r.id}')" style="cursor:pointer;" data-id="${r.id}">
            <td style="font-family:'DM Mono',monospace;font-size:12px;font-weight:600;color:#1B3A5E;">${r.id}</td>
            <td style="font-weight:600;">${r.first} ${r.last} ${r.perm} </td>
            <td>${r.dept||'—'}</td>
            <td><span style="font-size:11px;font-weight:700;color:${c[r.status]||'#555'}">${r.status}</span></td>
        </tr>`).join('');
            document.getElementById('lovCount').textContent = rows.length + ' record' + (rows.length !== 1 ? 's' : '');
            _lovData = rows;
        }

        function filterLOV(q) {
            fetchLOV(q.trim());
        }

        function lovClick(tr, id) {
            document.querySelectorAll('#lovTbody tr').forEach(r => r.classList.remove('table-primary'));
            tr.classList.add('table-primary');
            _lovSel = _lovData.find(r => r.id == id);
        }
        async function lovDbl(id) {
            _lovSel = _lovData.find(r => r.id == id);
            await confirmLOV();
        }
        async function confirmLOV() {
            if (!_lovSel) {
                showToast('Select a record first.', 'warn');
                return;
            }
            bootstrap.Modal.getInstance(document.getElementById('lovModal'))?.hide();
            await loadRecord(_lovSel.id);
            showToast('✓ Loaded ID ' + _lovSel.id, 'green');
        }

        /* ══════════════════════════════════════════
           HELPERS
        ══════════════════════════════════════════ */
        const gi = (id) => {
            const e = document.getElementById(id);
            return e ? e.value : ''
        };
        const si = (id, v) => {
            const e = document.getElementById(id);
            if (e) e.value = v ?? ''
        };

        const BG = {
            navy: '#0F2240',
            green: '#1A6E3C',
            red: '#E03B3B',
            warn: '#7A5500'
        };

        function showToast(msg, type = 'navy') {
            const el = document.getElementById('hrmToast');
            el.style.background = BG[type] || BG.navy;
            document.getElementById('toastMsg').textContent = msg;
            bootstrap.Toast.getOrCreateInstance(el, {
                delay: 3000
            }).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('headername').innerText = 'Employee Official Information';
        });
    </script>
@endpush
