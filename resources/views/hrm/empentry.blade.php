@extends('layouts.app')

@section('title', 'Employee Information')

@push('styles')
    <style>
        @font-face {
            font-family: SutonnyMJ;
            src: url('/fonts/SutonnyMJ.ttf');
            src: url('/fonts/SutonnyMJ.eot');
            src: url('/fonts/SutonnyMJ.eot?#iefix') format('embedded-opentype'),
                url('/fonts/SutonnyMJ.ttf') format('truetype'),
                url('/fonts/SutonnyMJ.svg#FortFoundry') format('svg');
        }

        #b_name,
        #depent_name_bangla,
        #relation_bn,
        #address_bn,
        #dep_name_bn {
            font-family: 'SutonnyMJ' !important;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .select2.select2-container {
            width: 100% !important;
        }

        #ui-datepicker-div {
            display: none;
        }

        @media only screen and (max-width: 600px) {
            html {
                font-size: .7rem;
            }
        }

        @media only screen and (min-width: 992px) {
            html {
                font-size: .5rem;
            }
        }

        @media only screen and (min-width: 1366px) {
            html {
                font-size: .8rem;
            }

            .select2-container .select2-selection--single {
                height: 34px !important;
            }
        }

        @media only screen and (min-width: 1440px) {
            html {
                font-size: 1rem;
            }

            .select2-container .select2-selection--single {
                height: 37px !important;
            }
        }

        :root {
            --primary: #1a3a5c;
            --primary-lt: #2257a0;
            --accent: #1e7e34;
            --danger: #c0392b;
            --tab-bar: #1a3a5c;
            --card-head: #1a3a5c;
            --body-bg: #f0f4f8;
            --card-bg: #ffffff;
            --border: #cdd8e8;
            --label-color: #374a5a;
            --input-bg: #fafdff;
            --radius: 5px;
            --input-h: 32px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            background: var(--body-bg);
            color: #222;
            margin: 0;
            padding: 0;
        }

        .emp-tabbar {
            background: var(--tab-bar);
            display: flex;
            flex-wrap: wrap;
            padding: 0 8px;
            border-bottom: 3px solid #0c1f35;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .35);
        }

        .emp-tabbar .nav-link {
            color: #a8c8e8 !important;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
            padding: 10px 12px;
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            margin-bottom: -3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all .15s;
            background: none;
            cursor: pointer;
        }

        .emp-tabbar .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, .08);
        }

        .emp-tabbar .nav-link.active {
            color: #fff !important;
            border-bottom-color: #4fc3f7;
            background: rgba(255, 255, 255, .1);
        }

        .emp-tabbar .nav-link i {
            font-size: 13px;
        }

        .tab-content {
            padding: 18px 20px;
        }

        .sec-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 16px;
        }

        .sec-card-head {
            background: var(--card-head);
            color: #fff;
            padding: 8px 16px;
            border-radius: var(--radius) var(--radius) 0 0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sec-card-head i {
            font-size: 13px;
            opacity: .85;
        }

        .sec-card-body {
            padding: 14px 16px 6px;
        }

        .page-heading {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 14px;
            padding-bottom: 7px;
            border-bottom: 2px solid var(--primary-lt);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .page-heading i {
            color: var(--primary-lt);
            font-size: 18px;
        }

        .sub-title {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--primary-lt);
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 10px 0 6px;
            margin-top: 4px;
            border-bottom: 1.5px solid #dde7f2;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        label.col-form-label {
            font-size: 11px !important;
            font-weight: 600 !important;
            color: var(--label-color) !important;
            padding-top: 6px;
        }

        .form-control,
        .form-select,
        .select2-selection {
            height: var(--input-h) !important;
            font-size: 12.5px !important;
            border: 1px solid #bfcfdf !important;
            border-radius: 4px !important;
            background: var(--input-bg) !important;
            color: #1a2a3a !important;
            padding: 3px 8px !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-lt) !important;
            box-shadow: 0 0 0 2px rgba(34, 87, 160, .15) !important;
            background: #fff !important;
            outline: none;
        }

        textarea.form-control {
            height: auto !important;
            min-height: 56px !important;
            resize: vertical;
            padding: 5px 8px !important;
        }

        .btn {
            font-size: 12px !important;
            font-weight: 600 !important;
            padding: 6px 20px !important;
            border-radius: 4px !important;
            letter-spacing: .3px;
        }

        .btn-success {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
        }

        .btn-success:hover {
            filter: brightness(1.1);
        }

        .btn-primary {
            background: #1565c0 !important;
            border-color: #1565c0 !important;
        }

        .btn-danger {
            background: transparent !important;
            color: var(--danger) !important;
            border: 1.5px solid var(--danger) !important;
        }

        .btn-danger:hover {
            background: #ffeaea !important;
        }

        .btn-white {
            background: var(--primary) !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-white:hover {
            background: var(--primary-lt) !important;
        }

        .btn-secondary {
            background: #546e7a !important;
            border-color: #546e7a !important;
        }

        .action-bar {
            text-align: center;
            padding: 10px 0 14px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .empid-bar {
            background: #e6eff8;
            border: 1px solid #b8d0ea;
            border-radius: var(--radius);
            padding: 10px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 14px;
        }

        .empid-bar>div {
            display: flex;
            flex-direction: column;
        }

        .empid-bar label {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 3px;
        }

        .empid-bar .input-group {
            width: 240px;
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            margin-top: 16px;
        }

        .emp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .emp-table thead th {
            background: var(--primary);
            color: #fff;
            padding: 9px 11px;
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
            padding: 7px 11px;
            border-bottom: 1px solid #dde8f2;
            color: #2a3a4a;
        }

        .emp-table tbody tr:hover {
            background: #e4f0fb;
        }

        .emp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .img-box {
            border: 1.5px dashed #a0bcd8;
            border-radius: 4px;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f3f8fd;
        }

        .form-check-inline label {
            font-size: 12.5px;
        }

        hr.sec-hr {
            border: none;
            border-top: 1.5px dashed #d0dceb;
            margin: 12px 0;
        }

        .row.p-1 {
            padding: 4px 0 !important;
        }

        .mb-3 {
            margin-bottom: 10px !important;
        }

        .mb-5 {
            margin-bottom: 14px !important;
        }

        .p-5 {
            padding: 18px !important;
        }

        .p-3 {
            padding: 10px !important;
        }

        .p-6 {
            padding: 14px !important;
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
@endpush

@section('content')
    <main>

        {{-- TAB NAVIGATION BAR --}}
        <nav>
            <div class="emp-tabbar nav" id="nav-tab" role="tablist">
                <button class="nav-link active" id="per-tab" data-bs-toggle="pill" data-bs-target="#per" type="button"
                    role="tab" aria-controls="per" aria-selected="true">
                    <i class="bi bi-person"></i> Personal Info
                </button>
                <button class="nav-link" id="off-tab" data-bs-toggle="pill" data-bs-target="#off" type="button"
                    role="tab" aria-controls="off" aria-selected="false">
                    <i class="bi bi-briefcase"></i> Official Info
                </button>
                <button class="nav-link" id="add-tab" data-bs-toggle="pill" data-bs-target="#add" type="button"
                    role="tab" aria-controls="add" aria-selected="false">
                    <i class="bi bi-geo-alt"></i> Location
                </button>
                <button class="nav-link" id="academi-tab" data-bs-toggle="pill" data-bs-target="#academi" type="button"
                    role="tab" aria-controls="academi" aria-selected="false">
                    <i class="bi bi-mortarboard"></i> Education
                </button>
                <button class="nav-link" id="course-tab" data-bs-toggle="pill" data-bs-target="#course" type="button"
                    role="tab" aria-controls="course" aria-selected="false">
                    <i class="bi bi-journal-bookmark"></i> Short Course
                </button>
                <button class="nav-link" id="train-tab" data-bs-toggle="pill" data-bs-target="#train" type="button"
                    role="tab" aria-controls="train" aria-selected="false">
                    <i class="bi bi-award"></i> Training
                </button>
                <button class="nav-link" id="exp-tab" data-bs-toggle="pill" data-bs-target="#exp" type="button"
                    role="tab" aria-controls="exp" aria-selected="false">
                    <i class="bi bi-buildings"></i> Experience
                </button>
                <button class="nav-link" id="nomi-tab" data-bs-toggle="pill" data-bs-target="#nomi" type="button"
                    role="tab" aria-controls="nomi" aria-selected="false">
                    <i class="bi bi-people"></i> Nominee
                </button>
                <button class="nav-link" id="job-tab" data-bs-toggle="pill" data-bs-target="#job" type="button"
                    role="tab" aria-controls="job" aria-selected="false">
                    <i class="bi bi-clock-history"></i> Job History
                </button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">

            {{-- ── TAB 1: PERSONAL INFORMATION ── --}}
            <div class="tab-pane fade show active" id="per" role="tabpanel" aria-labelledby="per-tab" tabindex="1">
                <input type="text" name="updateorsave" id="updateorsave" value="new" tabindex="1" hidden>

                <form id="emppersonalSave2" enctype="multipart/form-data">
                    @csrf
                    <div class="page-heading"><i class="bi bi-person-lines-fill"></i> Personal Information
                    </div>

                    <div id="error-list">
                        <ul></ul>
                    </div>

                    <div class="empid-bar">
                        <div>
                            <label>Employee ID</label>
                            <div class="input-group">
                                <input list="empno_list" type="text" class="form-control" name="empno"
                                    id="empno" value="{{ old('empno') }}" placeholder="Employee ID" tabindex="1"
                                    aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" name="findemp" id="findemp" type="button"><i
                                            class="bi bi-search"></i></button>
                                </div>
                                <span class="text-danger" id="massege"></span>
                                <datalist id="empno_list"></datalist>
                            </div>
                        </div>
                        <div>
                            <label>Proximity Card No</label>
                            <input type="text" class="form-control" name="card_no" id="card_no"
                                value="{{ old('card_no') }}" placeholder="Card No" style="width:180px;">
                        </div>
                        <div class="col-sm-4">
                            <label>Company</label>
                            <select class="form-select form-select-sm" id="comapnylist" name="company_id"
                                style="width:220px;">
                                <option selected value="">Select Company</option>
                                @foreach ($companyList as $company)
                                    <option value="{{ $company->company_id }}">{{ $company->company_name }}
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
                                        <label for="first_name" class="col-sm-4 col-form-label">First Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="first_name" id="first_name" placeholder="First Name"
                                                value="{{ old('first_name') }}"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="last_name" class="col-sm-4 col-form-label">Last Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="last_name" id="last_name" placeholder="Last Name"
                                                value="{{ old('last_name') }}"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="middle_name" class="col-sm-4 col-form-label">Middle Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="middle_name" id="middle_name" placeholder="Middle Name"
                                                value="{{ old('middle_name') }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="b_name" class="col-sm-4 col-form-label">Name (Bangla)
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control" name="b_name"
                                                id="b_name" value="{{ old('b_name') }}" placeholder="বাংলা নাম"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="father_name" class="col-sm-4 col-form-label">Father's Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="father_name" id="father_name" placeholder="Father's Name"
                                                value="{{ old('father_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="mother_name" class="col-sm-4 col-form-label">Mother's Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="mother_name" id="mother_name" placeholder="Mother's Name"
                                                value="{{ old('mother_name') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="husband_name" class="col-sm-4 col-form-label">Spouse Name
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="husband_name" id="husband_name" placeholder="Spouse Name"
                                                value="{{ old('husband_name') }}">
                                        </div>
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
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="dob" class="col-sm-4 col-form-label">Date of Birth
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control" name="dob"
                                                id="dob" value="{{ old('dob') }}" placeholder="Date of Birth">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="as_on_date" class="col-sm-4 col-form-label">As On
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control" name="as_on"
                                                id="as_on_date" value="{{ old('as_on') }}" placeholder=""></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="ageDet" class="col-sm-4 col-form-label">Age :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control" name="ageDet"
                                                id="ageDet" value="{{ old('age') }}" placeholder="Age"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row p-1">
                                        <label for="sex" class="col-sm-4 col-form-label">Gender :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="sex" name="sex">
                                                <option value="0">Select Gender</option>
                                                <option value="Female">Female</option>
                                                <option value="Male">Male</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row p-1">
                                        <label for="marial_status" class="col-sm-4 col-form-label">Marital
                                            Status :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="marial_status" name="marial_status">
                                                <option value="0">Select</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row p-1">
                                        <label for="religion_id" class="col-sm-4 col-form-label">Religion
                                            :</label>
                                        <div class="col-sm-8">
                                            {{-- FIX: renamed loop var from $religion to $rel to avoid overwriting the outer $religion collection --}}
                                            <select class="form-select" name="religion_id" id="religion_id">
                                                @foreach ($religion as $rel)
                                                    <option value="{{ $rel->religion_id }}">
                                                        {{ $rel->religion_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row p-1">
                                        <label for="blood_group" class="col-sm-4 col-form-label">Blood Group
                                            :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" name="blood_group" id="blood_group">
                                                <option value="">Select Blood Group</option>
                                                <option value="A (+) ve">A (+) ve</option>
                                                <option value="A (-) ve">A (-) ve</option>
                                                <option value="B (+) ve">B (+) ve</option>
                                                <option value="B (-) ve">B (-) ve</option>
                                                <option value="AB (+) ve">AB (+) ve</option>
                                                <option value="AB (-) ve">AB (-) ve</option>
                                                <option value="O (+) ve">O (+) ve</option>
                                                <option value="O (-) ve">O (-) ve</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="nationality_desc" class="col-sm-4 col-form-label">Nationality
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="nationality_desc" id="nationality_desc" value="Bangladeshi"
                                                placeholder="Nationality"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="status" class="col-sm-4 col-form-label">Status :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" name="status" id="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="hbs_test" class="col-sm-4 col-form-label">HBS Ag :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" name="hbs_test" id="hbs_test">
                                                <option value="">Choose</option>
                                                <option value="(+) ve">(+) ve</option>
                                                <option value="(-) ve">(-) ve</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Identity Documents --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-card-text"></i> Identity Documents</div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="national_id_no" class="col-sm-4 col-form-label">National
                                            ID :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="national_id_no" id="national_id_no"
                                                value="{{ old('national_id_no') }}" placeholder="National ID"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="id_card_issue" class="col-sm-4 col-form-label">ID Card
                                            Issue :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="id_card_issue" id="id_card_issue"
                                                value="{{ old('id_card_issue') }}" placeholder="Issue date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="valid_till" class="col-sm-4 col-form-label">Valid Till
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="valid_till" id="valid_till" value="{{ old('valid_till') }}"
                                                placeholder="Valid till">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="passport_no" class="col-sm-4 col-form-label">Passport No
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="passport_no" id="passport_no" value="{{ old('passport_no') }}"
                                                placeholder="Passport No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="place_of_issue" class="col-sm-4 col-form-label">Place of
                                            Issue :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="place_of_issue" id="place_of_issue"
                                                value="{{ old('place_of_issue') }}" placeholder="Place of Issue"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="id_mark" class="col-sm-4 col-form-label">ID Mark
                                            :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control" name="id_mark"
                                                id="id_mark" value="{{ old('id_mark') }}"
                                                placeholder="Identification mark"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="birthday_id" class="col-sm-4 col-form-label">Birth
                                            Certificate :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="birthday_id" id="birthday_id" value="{{ old('birthday_id') }}"
                                                placeholder="Birth Certificate"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="last_education" class="col-sm-4 col-form-label">Last
                                            Education :</label>
                                        <div class="col-sm-8"><input type="text" class="form-control"
                                                name="last_education" id="last_education"
                                                value="{{ old('last_education') }}" placeholder="Last Education"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact & Photo --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-telephone"></i> Contact & Photo / Signature
                        </div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="emp_mobile_no" class="col-sm-4 col-form-label">Emp Mobile
                                            :</label>
                                        <div class="col-sm-8"><input type="tel" class="form-control"
                                                name="emp_mobile_no" id="emp_mobile_no"
                                                value="{{ old('emp_mobile_no') }}" placeholder="Mobile No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="sms_mobile_no" class="col-sm-4 col-form-label">SMS Mobile
                                            :</label>
                                        <div class="col-sm-8"><input type="tel" class="form-control"
                                                name="sms_mobile_no" id="sms_mobile_no"
                                                value="{{ old('sms_mobile_no') }}" placeholder="SMS Mobile No"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="office_food" class="col-sm-4 col-form-label">Office Food
                                            :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" name="office_food" id="office_food">
                                                <option value="">Choose One</option>
                                                <option value="N">No</option>
                                                <option value="Y">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="sec-hr">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="photo" class="col-sm-4 col-form-label">Photo :</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="file" id="photo" name="photo">
                                            <div class="img-box mt-2" style="height:100px;width:100px;">
                                                <img id="preview-image" src="#" alt="Preview" width="100"
                                                    height="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row p-1">
                                        <label for="signature" class="col-sm-4 col-form-label">Signature
                                            :</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="file" id="signature" name="signature">
                                            <div class="img-box mt-2" style="height:80px;width:200px;">
                                                <img id="preview-sign" src="#" alt="Preview" width="200"
                                                    height="80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-bar">
                        <button class="btn btn-success" type="submit" id="emppersonalSave"><i
                                class="bi bi-check-circle"></i> Save</button>
                        <button class="btn btn-primary" type="submit" id="emppersonalUpdate"><i
                                class="bi bi-pencil-square"></i> Update</button>
                        <button class="btn btn-danger" type="button" id="clearFieldsBtn"><i class="bi bi-x-circle"></i>
                            Clear</button>
                    </div>
                </form>
            </div>

            {{-- ── TAB 2: Official Information ── --}}
            <div class="tab-pane fade" id="off" role="tabpanel" aria-labelledby="off-tab" tabindex="0">
                <form action="" method="" id="empgetempofficialSave">
                    @csrf
                    <div class="page-heading"><i class="bi bi-briefcase-fill"></i> Official Information
                    </div>

                    <div class="empid-bar">
                        <div>
                            <label>Employee ID</label>
                            <input type="text" class="form-control" name="empof_id" id="empof_id"
                                value="{{ old('empno') }}" placeholder="Employee ID" style="width:220px;">
                        </div>
                    </div>

                    {{-- Base Information --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-building"></i> Base Information</div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="comapnylist_of" class="col-sm-4 col-form-label">Company
                                            :</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" name="company_id" id="comapnylist_of">
                                                <option selected value="">Select Company</option>
                                                @foreach ($companyList as $company)
                                                    <option value="{{ $company->company_id }}">
                                                        {{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="deptList" class="col-sm-4 col-form-label">Department
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $deptList to $dept --}}
                                            <select class="form-select" name="dept_no" id="deptList">
                                                <option selected value="">Select One</option>
                                                @foreach ($deptList as $dept)
                                                    <option value="{{ $dept->dept_no }}">
                                                        {{ $dept->dept_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="section_no" class="col-sm-4 col-form-label">Section
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $section_list to $section --}}
                                            <select class="form-select" id="section_no" name="section_no">
                                                <option selected value="">Select One</option>
                                                @foreach ($section_list as $section)
                                                    <option value="{{ $section->section_no }}">
                                                        {{ $section->section_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="floorList" class="col-sm-4 col-form-label">Floor No
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $floorList to $floor --}}
                                            <select class="form-select" name="floor_id" id="floorList">
                                                <option value="">Select One</option>
                                                @foreach ($floorList as $floor)
                                                    <option value="{{ $floor->floor_id }}">
                                                        {{ $floor->floor_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="line" class="col-sm-4 col-form-label">Line No
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $lineInfo to $line --}}
                                            <select class="form-select" name="line" id="line">
                                                <option selected value="">Select One</option>
                                                @foreach ($lineInfo as $ln)
                                                    <option value="{{ $ln->line_no }}">{{ $ln->line }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="des_id" class="col-sm-4 col-form-label">Designation
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $designation to $des --}}
                                            <select class="form-select" name="des_id" id="des_id">
                                                <option selected value="">Select One</option>
                                                @foreach ($designation as $des)
                                                    <option value="{{ $des->des_id }}">
                                                        {{ $des->designation_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="emp_type" class="col-sm-4 col-form-label">Emp Type
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $empType to $type --}}
                                            <select class="form-select" name="emp_type" id="emp_type">
                                                <option selected value="">Select One</option>
                                                @foreach ($empType as $type)
                                                    <option value="{{ $type->emp_type }}">
                                                        {{ $type->emp_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="grade_id" class="col-sm-4 col-form-label">Grade :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $gradeInfo to $grade --}}
                                            <select class="form-select" name="grade_id" id="grade_id">
                                                <option selected value="">Select One</option>
                                                @foreach ($gradeInfo as $grade)
                                                    <option value="{{ $grade->grade_id }}">
                                                        {{ $grade->grade_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="shift_code" class="col-sm-4 col-form-label">Shift
                                            :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $shiftInfo to $shift --}}
                                            <select class="form-select" name="shift_code" id="shift_code">
                                                <option selected value="">Select One</option>
                                                @foreach ($shiftInfo as $shift)
                                                    <option value="{{ $shift->shift_code }}">
                                                        {{ $shift->shift_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="shift_group" class="col-sm-4 col-form-label">Shift Group
                                            :</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" name="shift_group" id="shift_group">
                                                <option selected value="">Select One</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="cal_code" class="col-sm-4 col-form-label">Calendar
                                            :</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" name="cal_code" id="cal_code">
                                                <option selected value="">Select One</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="weekly_off" class="col-sm-4 col-form-label">Weekly Off
                                            :</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" name="weekly_off" id="weekly_off">
                                                <option selected value="">Select One</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Joining & Date Info --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-calendar-check"></i> Joining & Date
                            Information</div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="opt_no" class="col-sm-4 col-form-label">OPT No :</label>
                                        <div class="col-sm-7"><select class="form-select" name="opt_no" id="opt_no">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="joining_date" class="col-sm-4 col-form-label">Joining Date
                                            :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="joining_date" id="joining_date" value="{{ old('joining_date') }}"
                                                placeholder=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="as_on_join" class="col-sm-4 col-form-label">As On Join
                                            :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="as_on_join" id="as_on_join" value="{{ old('as_on_join') }}"
                                                placeholder=""></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="conform_date" class="col-sm-4 col-form-label">Confirmation
                                            Date :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="conform_date" id="conform_date" value="{{ old('conform_date') }}"
                                                placeholder=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="provision_period" class="col-sm-4 col-form-label">Provision Period
                                            :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="provision_period" id="provision_period"
                                                value="{{ old('provision_period') }}" placeholder="Provision Period">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="increment_date" class="col-sm-4 col-form-label">Increment
                                            Date :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="increment_date" id="increment_date"
                                                value="{{ old('increment_date') }}" placeholder=""></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label for="appraisal_cal" class="col-sm-4 col-form-label">Appraisal
                                            Calendar :</label>
                                        <div class="col-sm-7"><select class="form-select" name="appraisal_cal"
                                                id="appraisal_cal">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Category & Entitlements --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-list-check"></i> Category & Entitlements
                        </div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Leave Category
                                            :</label>
                                        <div class="col-sm-7"><select class="form-select" name="lv_cat_id"
                                                id="lv_cat_id">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Allowance
                                            Category :</label>
                                        <div class="col-sm-7"><select class="form-select" name="allw_cat_id"
                                                id="allw_cat_id">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Work
                                            Entitlement :</label>
                                        <div class="col-sm-7"><select class="form-select" name="work_ent"
                                                id="work_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">OT Entitlement
                                            :</label>
                                        <div class="col-sm-7"><select class="form-select" name="ot_ent" id="ot_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Residence
                                            Entitlement :</label>
                                        <div class="col-sm-7"><select class="form-select" name="res_ent" id="res_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Transport
                                            Entitlement :</label>
                                        <div class="col-sm-7"><select class="form-select" name="tran_ent"
                                                id="tran_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">PF Entitlement
                                            :</label>
                                        <div class="col-sm-7"><select class="form-select" name="pf_ent" id="pf_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Tax
                                            Entitlement :</label>
                                        <div class="col-sm-7"><select class="form-select" name="tax_ent" id="tax_ent">
                                                <option value="">Select One</option>
                                            </select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Provident Fund
                                            :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control" name="pro_fund"
                                                id="pro_fund" value="{{ old('pro_fund') }}"
                                                placeholder="Provident Fund"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Salary & Bank --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-bank"></i> Salary & Banking</div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Gross Salary
                                            :</label>
                                        <div class="col-sm-7"><input type="number" class="form-control" name="gross"
                                                id="gross" value="{{ old('gross') }}" placeholder="Gross Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Other
                                            Allowance :</label>
                                        <div class="col-sm-7"><input type="number" class="form-control"
                                                name="other_allowance" id="other_allowance"
                                                value="{{ old('other_allowance') }}" placeholder="Other Allowance"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1">
                                        <label class="col-sm-4 col-form-label">Bank Name :</label>
                                        <div class="col-sm-7">
                                            {{-- FIX: renamed loop var from $bankNmae to $bank --}}
                                            <select class="form-select" name="bank_name" id="bank_name">
                                                <option value="">Select Bank</option>
                                                @foreach ($bankNmae as $bank)
                                                    <option value="{{ $bank->bank_name }}">
                                                        {{ $bank->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Account No
                                            :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="bank_ac_no" id="bank_ac_no" value="{{ old('bank_ac_no') }}"
                                                placeholder="Account No">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">TIN No
                                            :</label>
                                        <div class="col-sm-7"><input type="number" class="form-control" name="tin_no"
                                                id="tin_no" value="{{ old('tin_no') }}" placeholder="TIN No"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Tax Deduction
                                            :</label>
                                        <div class="col-sm-7"><input type="number" class="form-control"
                                                name="tax_deduction" id="tax_deduction"
                                                value="{{ old('tax_deduction') }}" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Release Information --}}
                    <div class="sec-card">
                        <div class="sec-card-head"><i class="bi bi-box-arrow-right"></i> Release Information
                        </div>
                        <div class="sec-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Dismissal Date
                                            :</label>
                                        {{-- FIX: id was "dismisal_date" but name was "termination_date" — aligned both --}}
                                        <div class="col-sm-7"><input type="date" class="form-control"
                                                name="termination_date" id="dismisal_date"
                                                value="{{ old('termination_date') }}"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Resigned Date
                                            :</label>
                                        <div class="col-sm-7"><input type="date" class="form-control"
                                                name="resigned_date" id="resigned_date"
                                                value="{{ old('resigned_date') }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Reason
                                            :</label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" name="reason" id="reason" placeholder="Reason in details...">{{ old('reason') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Is Lefty
                                            :</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" name="is_lefty" id="is_lefty">
                                                <option value="">Choose</option>
                                                <option value="L">Lefty</option>
                                                <option value="R">Resigned</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">Service Book
                                            No :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control"
                                                name="service_book_number" id="service_book_number"
                                                value="{{ old('service_book_number') }}" placeholder="Service Book No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row p-1"><label class="col-sm-4 col-form-label">A/C :</label>
                                        <div class="col-sm-7"><input type="text" class="form-control" name="ac_no"
                                                id="ac_no" value="{{ old('ac_no') }}" placeholder="A/C"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-bar">
                        <button class="btn btn-success" type="submit" id="offc_save"><i class="bi bi-check-circle"></i>
                            Save</button>
                        <button class="btn btn-primary" type="submit" id="offc_update"><i
                                class="bi bi-pencil-square"></i> Update</button>
                        <button class="btn btn-danger" type="button" id="clearoff"><i class="bi bi-x-circle"></i>
                            Clear</button>
                    </div>
                </form>
            </div>

            {{-- ── TAB 3–9: INCLUDED PARTIALS ── --}}
            @include('hrm.empentry.empadress')
            @include('hrm.empentry.empacademic')
            @include('hrm.empentry.empshortcourse')
            @include('hrm.empentry.emptrain')
            @include('hrm.empentry.empexperience')
            @include('hrm.empentry.empnomeenee')
            @include('hrm.empentry.empjobhistory')

        </div>{{-- /tab-content --}}
    </main>

@endsection

@push('scripts')
    <script type="text/javascript">
        // FIX: set CSRF token globally once — removed 8 repeated $.ajaxSetup() calls inside each handler
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // FIX: prevent Enter key from submitting forms
        $('input, select').on('keydown', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });

        $(document).ready(function() {

            // Load employee when selected
            $('#findemp').on('click', function() {
                const empno = $('#empno').val();

                if (!empno) return;

                $.ajax({
                    url: '/api/getEmpDetails',
                    method: 'GET',
                    data: {
                        empno: empno
                    },
                    dataType: 'json',
                    success: function(response) {
                        const emp = response.data;
                        $('#first_name').val(emp.first_name);
                        $('#middle_name').val(emp.middle_name);
                        $('#last_name').val(emp.last_name);
                        $('#card_no').val(emp.card_no);
                        $('#comapnylist').val(emp.company_id).change();
                        $('#father_name').val(emp.father_name);
                        $('#mother_name').val(emp.mother_name);
                        $('#husband_name').val(emp.husband_name);
                        $('#dob').val(moment(emp.dob).format("YYYY-MM-DD"));
                        $('#as_on_date').val(moment(emp.as_on).format("YYYY-MM-DD"));



                        $('#as_on').val('emp.as_on');
                        $('#age').val(emp.age);
                        $('#b_name').val(emp.b_name);
                        $('#religion_id').val(emp.religion_id);
                        $('#nationality_desc').val(emp.nationality_desc);
                        $('#status').val(emp.status);
                        $('#sex').val(emp.sex);
                        $('#national_id_no').val(emp.national_id_no);
                        if (emp.id_card_issue == null) {
                            $('#id_card_issue').val(null);
                        } else {
                            $('#id_card_issue').val(moment(emp.id_card_issue).format(
                                "YYYY-MM-DD"));
                        }
                        $('#passport_no').val(emp.passport_no);
                        $('#last_education').val(emp.last_education);


                        if (emp.valid_till == null) {
                            $('#valid_till').val(null);
                        } else {
                            $('#valid_till').val(moment(emp.valid_till).format("YYYY-MM-DD"));
                        }
                        $('#place_of_issue').val(emp.place_of_issue);
                        $('#id_mark').val(emp.id_mark);
                        $('#birthday_id').val(emp.birthday_id);
                        $('#blood_group').val(emp.blood_group);
                        $('#hbs_test').val(emp.hbs_test);
                        $('#emp_mobile_no').val(emp.emp_mobile_no);
                        $('#sms_mobile_no').val(emp.sms_mobile_no);
                        $('#office_food').val(emp.office_food);
                        $('#marial_status').val(emp.marial_status).change();
                        $('#ageDet').val(emp.age2);


                        var emp_pic_link = "http://192.168.189.205:81/emp_photo/" + emp
                            .empno + ".jpg";
                        $("#preview-image").attr("src", emp_pic_link);

                        var emp_sign_link = "http://192.168.189.205:81/emp_sign/" + emp
                            .empno + ".jpg";
                        $("#preview-sign").attr("src", emp_sign_link);
                        console.log(emp.getempofficial);

                        // getempofficial tab if exists
                        if (emp.getempofficial && emp.getempofficial.length > 0) {
                            console.log(emp.getempofficial);
                            const getempofficial = emp.getempofficial[0];
                            $('#comapnylist_of').val(getempofficial.company_id)
                                .change();
                            $('#deptList').val(getempofficial.dept_no).change();
                            $('#section_no').val(getempofficial.section_no).change();
                            // $('#floorList').val(getempofficial.floor_id).change();
                            $('#line').val(getempofficial.line).change();
                            $('#des_id').val(getempofficial.des_id).change();
                            $('#emp_type').val(getempofficial.emp_type).change();
                            $('#grade_id').val(getempofficial.grade_id);
                            $('#shift_code').val(getempofficial.shift_code).change();
                            $('#cal_code').val(getempofficial.cal_code);
                            $('#weekly_off').val(getempofficial.weekly_off);
                            $('#opt_no').val(getempofficial.opt_no);
                            $('#joining_date').val(moment(getempofficial.joining_date)
                                .format("YYYY-MM-DD"));
                            $('#as_on_join').val(getempofficial.as_on_join);


                            if (getempofficial.conform_date == null) {
                                $('#conform_date').val(null);
                            } else {
                                $('#conform_date').val((moment(getempofficial
                                    .conform_date).format("YYYY-MM-DD")));
                            }


                            if (getempofficial.increment_date == null) {
                                $('#increment_date').val('');
                            } else {
                                $('#increment_date').val(moment(getempofficial
                                    .increment_date).format("YYYY-MM-DD"));
                            }

                            //$('#increment_date').val(moment(getempofficial.increment_date).format("YYYY-MM-DD"));
                            $('#provision_period').val(getempofficial.provision_period);
                            $('#lv_cat_id').val(getempofficial.lv_cat_id);
                            $('#allw_cat_id').val(getempofficial.allw_cat_id);
                            $('#s_group_name').val(getempofficial.s_group_name);
                            $('#work_ent').val(getempofficial.work_ent);
                            $('#ot_ent').val(getempofficial.ot_ent);
                            $('#res_ent').val(getempofficial.res_ent);
                            $('#tran_ent').val(getempofficial.tran_ent);
                            $('#pf_ent').val(getempofficial.pf_ent);
                            $('#tax_ent').val(getempofficial.tax_ent);
                            $('#pro_fund').val(getempofficial.pro_fund);
                            // $('#increment_date').val(moment(getempofficial
                            //     .increment_date).format("YYYY-MM-DD"));
                            $('#gross').val(getempofficial.gross);
                            $('#other_allowance').val(getempofficial.other_allowance);
                            $('#bank_ac_no').val(getempofficial.bank_ac_no);
                            $('#tin_no').val(getempofficial.tin_no);
                            $('#tax_deduction').val(getempofficial.tax_deduction);
                            $('#termination_date').val(getempofficial.termination_date);
                            $('#resigned_date').val(getempofficial.resigned_date);
                            $('#reason').val(getempofficial.reason);
                            $('#service_book_number').val(getempofficial
                                .service_book_number);
                            $('#ac_no').val(getempofficial.ac_no);
                        }

                        // Address tab if exists
                        if (emp.getemploc && emp.getemploc.length > 0) {
                            const getemploc = emp.getemploc[0];
                            $('#p_address').val(getemploc.p_address);
                            $('#p_village').val(getemploc.p_village);
                            $('#p_post_office').val(getemploc.p_post_off);
                            $('#p_police_station').val(getemploc.p_police_station);
                            $('#p_city').val(getemploc.p_city);
                            $('#p_district').val(getemploc.p_district);
                            $('#p_phone').val(getemploc.p_phone);
                            $('#p_fax').val(getemploc.p_fax);
                            $('#p_pin_code').val(getemploc.p_pin_code);
                            $('#p_cperson').val(getemploc.p_cperson);
                            $('#r_address').val(getemploc.r_address);
                            $('#r_city').val(getemploc.r_city);
                            $('#r_district').val(getemploc.r_district);
                            $('#r_phone').val(getemploc.r_phone);
                            $('#r_fax').val(getemploc.r_fax);
                            $('#r_mobile').val(getemploc.r_mobile);
                            $('#r_email').val(getemploc.r_email);
                            $('#r_cperson').val(getemploc.r_cperson);
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Could not load employee data', 'error');
                    }
                });
            });
        });

        // Add this to your blade template script section
        $(document).ready(function() {

            // ────── PERSONAL INFORMATION SAVE ──────
            $("#emppersonalSave2").on("submit", function(e) {
                e.preventDefault();
                alert($('#empno').val());
                const formData = new FormData(this);
                console.log([...formData.entries()]);

                //console.log(formData);


                $.ajax({
                    url: '/api/saveEmpPersonal',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonColor: '#1a3a5c'
                        });
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred';

                        if (Object.keys(errors).length > 0) {
                            errorMessage = 'Please fix the following errors:\n';
                            Object.keys(errors).forEach(field => {
                                errorMessage +=
                                    `• ${field}: ${errors[field].join(', ')}\n`;
                            });
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonColor: '#c0392b'
                        });
                    }
                });
            });
        });


        $(document).ready(function() {

            // ────── getempofficial INFORMATION SAVE ──────
            $("#empgetempofficialSave").on("submit", function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/api/saveEmpgetempofficial',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });
        $(document).ready(function() {

            // ────── ADDRESS INFORMATION SAVE ──────
            $("#empadressSave").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnoadress').val() || $('#empno').val(),
                    p_address: $('#p_address1').val(),
                    p_city: $('#p_city').val(),
                    p_district: $('#p_district2').val(),
                    p_pin_code: $('#p_postcode').val(),
                    p_phone: $('#p_phone').val(),
                    p_fax: $('#p_fax').val(),
                    p_cperson: $('#p_cperson').val(),
                    p_village: $('#p_village').val(),
                    p_post_off: $('#p_post_off').val(),
                    p_police_station: $('#p_police_station11').val(),
                    r_address: $('#r_address1').val(),
                    r_city: $('#r_city').val(),
                    r_district: $('#r_district').val(),
                    r_pin_cod: $('#r_postcode').val(),
                    r_phone: $('#r_phone').val(),
                    r_fax: $('#r_fax').val(),
                    r_mobile: $('#r_mobile').val(),
                    r_email: $('#r_email').val(),
                    r_cperson: $('#r_cperson').val()
                };

                $.ajax({
                    url: '/api/saveEmpLocation',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });
        $(document).ready(function() {

            // ────── EDUCATION/QUALIFICATION SAVE ──────
            $("#empEduInsert").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnoedu').val() || $('#empno').val(),
                    name_of_ins: $('#institution').val(),
                    passed_exam: $('#exam_name').val(),
                    division: $('#division').val(),
                    year: $('#pass_year').val(),
                    board: $('#board').val(),
                    marks: $('#marks').val(),
                    subject: $('#subject').val()
                };

                $.ajax({
                    url: '/saveEmpQualification',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');

                        // Refresh education list
                        fetAllEmpEduData();

                        // Clear form
                        $('#empEduInsert')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });


        $(document).ready(function() {

            // ────── SHORT COURSE SAVE ──────
            $("#empshort").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnoshtcourse').val() || $('#empno').val(),
                    course_name: $('#course_name').val(),
                    conducted_by: $('#course_provider').val(),
                    c_from: $('#course_from_date').val(),
                    c_to: $('#course_to_date').val(),
                    certificate: $('#certificate').val(),
                    total_day: $('#total_days').val()
                };

                $.ajax({
                    url: '/api/saveEmpShortCourse',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                        fetAllEmpCourseData();
                        $('#empshort')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });

        $(document).ready(function() {

            // ────── FAMILY INFORMATION SAVE ──────
            $("#empFamForm").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnoNominee').val() || $('#empno').val(),
                    depd_no: $('#depd_no').val(),
                    depd_name: $('#dep_name').val(),
                    relationship: $('#relation').val(),
                    d_dob: $('#d_dob').val(),
                    d_age: $('#d_age').val(),
                    d_sex: $('#d_sex').val(),
                    d_as_on: $('#d_as_on').val(),
                    percentage: $('#percentage').val(),
                    address: $('#depd_address').val(),
                    depent_name_bangla: $('#depent_name_bangla').val(),
                    relation_bn: $('#relation_bn').val(),
                    address_bn: $('#address_bn').val()
                };

                $.ajax({
                    url: '/api/saveEmpFamily',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                        fetAllEmpFamilyData();
                        $('#empFamForm')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });

        $(document).ready(function() {

            // ────── TRAINING SAVE ──────
            $("#empTrain").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnotraining').val() || $('#empno').val(),
                    t_title: $('#training_name').val(),
                    t_conducted_by: $('#training_provider').val(),
                    t_from: $('#training_from_date').val(),
                    t_to: $('#training_to_date').val(),
                    t_certificate: $('#training_certificate').val(),
                    skill_type: $('#skill_type').val(),
                    to_days: $('#training_days').val()
                };

                $.ajax({
                    url: '/api/saveEmpTraining',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                        fetchTrainData();
                        $('#empTrain')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });


        $(document).ready(function() {

            // ────── EXPERIENCE SAVE ──────
            $("#empExp").on("submit", function(e) {
                e.preventDefault();

                const formData = {
                    empno: $('#empnoexp').val() || $('#empno').val(),
                    organization: $('#organization').val(),
                    d_from: $('#exp_from_date').val(),
                    d_to: $('#exp_to_date').val(),
                    leave_reason: $('#leave_reason').val(),
                    prv_emp_no: $('#prv_emp_no').val(),
                    org_address: $('#org_address').val(),
                    org_tel: $('#org_phone').val(),
                    last_sal_drawn: $('#last_salary').val(),
                    total_years: $('#total_years').val(),
                    designation: $('#exp_designation').val()
                };

                $.ajax({
                    url: '/api/saveEmpWorkExp',
                    method: 'POST',
                    processData: false, // ✅ REQUIRED
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                        fetchExpData();
                        $('#empExp')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred',
                            'error');
                    }
                });
            });
        });


        $(document).ready(function() {
            $("#empno").on('keyup', function(e) {
                e.preventDefault();
                var empno = $("#empno").val();
                $('#empof_id').val(empno);
                $('#card_no').val(empno);
                $('#empadempno').val(empno);
                $('#empnoedu').val(empno);
                $('#empnoshtcourse').val(empno);
                $('#empnoNominee').val(empno);
                $('#empnojob').val(empno);
                $('#empnotraining').val(empno);
                $('#empnoexp').val(empno);



                // alert(empno);
                $.ajax({
                    url: 'empsearch',
                    method: 'get',
                    data: {
                        'search_key': empno
                    },
                    success: function(response) {
                        var res = response.data;
                        var html = '';
                        var htmldata = res.map(function(item) {

                            html += '<option value=' + item.new_empno + '>';

                            //console.log(item);
                        })
                        //console.log(htmldata)
                        $("#empno_list").empty();
                        $("#empno_list").append(html);

                    },
                    error: function(response) {

                    }
                });
            });
        });



        $(document).ready(function() {
            $('#updateorsave').val('new');

            $('#comapnylist').select2();
            $('#comapnylist_of').select2();
            $('#deptList').select2();
            $('#section_no').select2();
            $('#floorList').select2();
            $('#des_id').select2();
            $('#line').select2();
            $('#p_police_station').select2();
            $('#p_district2').select2();
            $('#r_district').select2();
            $('#emp_type').select2();
            $('#grade_id').select2();
            $('#shift_code').select2();
            $('#cal_code').select2();
            $('#opt_no').select2();
            $('#weekly_off').select2();






            $('#emppersonalUpdate').hide();


            $("#empno").on('keyup', function(e) {
                //alert('ss');
                fetAllEmpEduData();
                fetAllEmpCourseData();
                fetAllEmpFamilyData();
                fetchEmpJob();
                fetchTrainData();
                fetchExpData();
            });

        });



        function fetAllEmpEduData() {
            var empno = $('#empnoedu').val();
            //alert(empno);
            $.get("{{ URL::to('getEdu') }}" + '/' + empno, function(data)

                {
                    $('#emp_edu_data').empty().html(data);

                })
        }

        function fetAllEmpCourseData() {
            var empno = $('#empnoshtcourse').val();
            //alert(empno);
            $.get("{{ URL::to('getCourse') }}" + '/' + empno, function(data)

                {
                    $('#emp_course_data').empty().html(data);

                })
        }

        function fetAllEmpCourseData() {
            var empno = $('#empnoshtcourse').val();
            $.get("{{ URL::to('getCourse') }}" + '/' + empno, function(data) {
                $('#emp_course_data').empty().html(data);
            })
        }

        function fetAllEmpFamilyData() {
            var empno = $('#empnoNominee').val();
            $.get("{{ URL::to('getNome') }}" + '/' + empno, function(data) {
                $('#emp_nom_data').empty().html(data);
            })
        }

        function fetchEmpJob() {
            var empno = $('#empnojob').val();
            $.get("{{ URL::to('getJob') }}" + '/' + empno, function(data) {
                $('#emp_job_data').empty().html(data);
            })
        }

        function fetchTrainData() {
            var empno = $('#empnotraining').val();
            $.get("{{ URL::to('getTrain') }}" + '/' + empno, function(data) {
                $('#emp_train_data').empty().html(data);
            })
        }

        function fetchExpData() {
            var empno = $('#empnoexp').val();
            $.get("{{ URL::to('empExper') }}" + '/' + empno, function(data) {
                $('#emp_exp_data').empty().html(data);
            })
        }
    </script>
@endpush
