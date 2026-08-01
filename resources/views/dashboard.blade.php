{{-- resources/views/hrm/dashboard.blade.php --}}
@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --hrm-primary: #1a3c5e;
            --hrm-accent: #0d6efd;
            --hrm-success: #198754;
            --hrm-warning: #fd7e14;
            --hrm-danger: #dc3545;
            --hrm-muted: #6c757d;
            --hrm-border: #dee2e6;
            --hrm-surface: #f8f9fa;
            --hrm-card-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 2px 8px rgba(0, 0, 0, .04);
        }

        .hrm-page {
            background: var(--hrm-surface);
            min-height: calc(100vh - 56px);
            padding: 1.5rem 1.75rem;
        }

        .hrm-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .hrm-page-title {
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--hrm-muted);
            margin-bottom: .15rem;
        }

        .hrm-page-sub {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--hrm-primary);
            line-height: 1.2;
        }

        .hrm-db-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .72rem;
            font-weight: 500;
            color: #7a3800;
            background: #fff3e0;
            border: 1px solid #ffe0b2;
            border-radius: 20px;
            padding: .28rem .85rem;
        }

        .hrm-db-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .35
            }
        }

        /* Stat cards */
        .hrm-stat {
            background: #fff;
            border: 1px solid var(--hrm-border);
            border-radius: 8px;
            padding: 1.1rem 1.25rem;
            box-shadow: var(--hrm-card-shadow);
            position: relative;
            overflow: hidden;
        }

        .hrm-stat-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--hrm-muted);
            margin-bottom: .5rem;
        }

        .hrm-stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: var(--hrm-primary);
        }

        .hrm-stat-meta {
            font-size: .72rem;
            color: var(--hrm-muted);
            margin-top: .4rem;
        }

        /* FIX: was broken HTML mixed into CSS */
        .hrm-stat-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: .07;
            font-size: 3.5rem;
        }

        .hrm-stat.border-start {
            border-left-width: 3px !important;
        }

        /* Cards */
        .hrm-card {
            background: #fff;
            border: 1px solid var(--hrm-border);
            border-radius: 8px;
            box-shadow: var(--hrm-card-shadow);
        }

        .hrm-card-header {
            padding: .85rem 1.1rem;
            border-bottom: 1px solid var(--hrm-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hrm-card-title {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--hrm-primary);
            margin: 0;
        }

        .hrm-card-body {
            padding: 1rem 1.1rem;
        }

        /* Tables */
        .hrm-table {
            font-size: .8rem;
            margin: 0;
        }

        .hrm-table thead th {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--hrm-muted);
            border-bottom: 2px solid var(--hrm-border);
            background: var(--hrm-surface);
            padding: .55rem .75rem;
            white-space: nowrap;
        }

        .hrm-table tbody td {
            padding: .55rem .75rem;
            border-color: var(--hrm-border);
            vertical-align: middle;
            color: #2c3e50;
        }

        .hrm-table tbody tr:hover {
            background: #f0f4ff;
        }

        /* Badges */
        .hrm-badge {
            font-size: .65rem;
            font-weight: 600;
            padding: .22rem .6rem;
            border-radius: 4px;
            letter-spacing: .03em;
        }

        /* Department bars */
        .dept-bar-wrap {
            background: #e9ecef;
            border-radius: 3px;
            height: 5px;
            flex: 1;
            overflow: hidden;
        }

        .dept-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width .6s ease;
        }

        /* OT chart */
        .ot-bar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
        }

        .ot-bar-outer {
            width: 100%;
            background: #e9ecef;
            border-radius: 3px 3px 0 0;
            position: relative;
            height: 80px;
            display: flex;
            align-items: flex-end;
        }

        .ot-bar-fill {
            width: 100%;
            border-radius: 3px 3px 0 0;
            background: var(--hrm-accent);
            transition: height .7s ease;
        }

        .ot-bar-label {
            font-size: .62rem;
            color: var(--hrm-muted);
            text-align: center;
            white-space: nowrap;
        }

        .ot-bar-val {
            font-size: .65rem;
            font-weight: 600;
            color: var(--hrm-primary);
        }

        @media print {
            body>*:not(#printModal) {
                display: none !important;
            }

            .modal-dialog {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-footer,
            .btn-close,
            .no-print {
                display: none !important;
            }

            .hrm-table tbody tr:hover {
                background: none !important;
            }
        }

        /* Gender donut */
        .gender-wrap {
            position: relative;
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .gender-wrap svg {
            transform: rotate(-90deg);
        }

        .gender-label {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: .62rem;
            font-weight: 700;
            color: var(--hrm-primary);
        }

        .hrm-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .leave-pill {
            background: var(--hrm-surface);
            border: 1px solid var(--hrm-border);
            border-radius: 6px;
            padding: .5rem .75rem;
            text-align: center;
            flex: 1;
            min-width: 100px;
        }

        .leave-pill-name {
            font-size: .62rem;
            font-weight: 600;
            color: var(--hrm-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .leave-pill-taken {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--hrm-primary);
            line-height: 1;
        }

        .leave-pill-bal {
            font-size: .65rem;
            color: var(--hrm-success);
        }

        .hrm-clock-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 30px;
            background: #fff3e0;
            border: 1px solid #ffe0b2;
            font-size: 1.1rem;
            font-weight: 700;
            color: #7a3800;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        /* Heatmap */
        .heatmap-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .heatmap-day-label {
            font-size: .58rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--hrm-muted);
            text-align: center;
            padding-bottom: 3px;
            letter-spacing: .04em;
        }

        .heatmap-cell {
            aspect-ratio: 1;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .55rem;
            font-weight: 600;
            cursor: default;
            position: relative;
            transition: transform .15s;
        }

        .heatmap-cell:hover {
            transform: scale(1.25);
            z-index: 10;
        }

        .heatmap-cell.empty {
            background: transparent;
        }

        .heatmap-cell.future {
            background: #f0f0f0;
            color: #bbb;
        }

        /* FIX: were broken HTML tags mixed into CSS */
        .hm-0 {
            background: #fee2e2;
            color: #b91c1c;
        }

        .hm-25 {
            background: #fed7aa;
            color: #c2410c;
        }

        .hm-50 {
            background: #fef9c3;
            color: #854d0e;
        }

        .hm-75 {
            background: #bbf7d0;
            color: #166534;
        }

        .hm-90 {
            background: #86efac;
            color: #14532d;
        }

        .hm-100 {
            background: #4ade80;
            color: #14532d;
        }

        /* Department attendance */
        .dept-att-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
        }

        .dept-att-name {
            font-size: .72rem;
            color: #2c3e50;
            min-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dept-att-bar-wrap {
            flex: 1;
            background: #e9ecef;
            border-radius: 3px;
            height: 7px;
            overflow: hidden;
        }

        .dept-att-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width .7s ease;
        }

        .dept-att-pct {
            font-size: .7rem;
            font-weight: 700;
            min-width: 36px;
            text-align: right;
        }

        /* Late trend */
        .late-bar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            flex: 1;
        }

        .late-bar-outer {
            width: 100%;
            background: #e9ecef;
            border-radius: 3px 3px 0 0;
            height: 70px;
            display: flex;
            align-items: flex-end;
        }

        .late-bar-fill {
            width: 100%;
            border-radius: 3px 3px 0 0;
            background: #fd7e14;
            transition: height .7s ease;
        }

        .late-bar-label {
            font-size: .6rem;
            color: var(--hrm-muted);
            text-align: center;
        }

        .late-bar-val {
            font-size: .65rem;
            font-weight: 700;
            color: #c2410c;
        }

        .ot-cost-table td,
        .ot-cost-table th {
            padding: .45rem .65rem;
        }

        .ot-cost-bar-wrap {
            background: #e9ecef;
            border-radius: 3px;
            height: 5px;
            width: 80px;
            display: inline-block;
            overflow: hidden;
            vertical-align: middle;
        }

        .ot-cost-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: #0d6efd;
            transition: width .7s ease;
        }

        /* Heatmap legend */
        .hm-legend {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
            align-items: center;
        }

        .hm-legend-item {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: .6rem;
            color: var(--hrm-muted);
        }

        .hm-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 2px;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="hrm-page">

        {{-- Page header --}}
        <div class="hrm-page-header">
            <div>
                <div class="hrm-page-title">Human Resource Management</div>
                <div class="hrm-page-sub">Workforce Dashboard</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="hrm-db-badge">
                    <span class="hrm-db-dot"></span>
                    Oracle HRM
                </span>
                <div class="hrm-clock-box">
                    <span class="hrm-db-dot"></span>
                    <span id="liveClock"></span>
                </div>
            </div>
        </div>

        {{-- Row 1: KPI stats --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="hrm-stat border-start border-primary">
                    <div class="hrm-stat-label">Total Employees</div>
                    <div class="hrm-stat-value" id="statTotalEmp">{{ number_format($totalEmployees) }}</div>
                    <div class="hrm-stat-meta">Active headcount</div>
                    <span class="hrm-stat-icon">👥</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hrm-stat border-start border-success">
                    <div class="hrm-stat-label">Present Today</div>
                    <div class="hrm-stat-value text-success" id="statPresent">{{ $todayAtt->present ?? 0 }}</div>
                    <div class="hrm-stat-meta" id="statAttendanceRate">
                        {{ $totalEmployees > 0 ? round((($todayAtt->present ?? 0) / $totalEmployees) * 100, 1) : 0 }}%
                        attendance rate
                    </div>
                    <span class="hrm-stat-icon">✅</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hrm-stat border-start border-warning">
                    <div class="hrm-stat-label">On Leave</div>
                    <div class="hrm-stat-value text-warning" id="statOnLeave">{{ $todayAtt->on_leave ?? 0 }}</div>
                    <div class="hrm-stat-meta">
                        Late arrivals: <span id="statLate">{{ $todayAtt->late ?? 0 }}</span>
                    </div>
                    <span class="hrm-stat-icon">🏖</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="hrm-stat border-start border-danger">
                    <div class="hrm-stat-label">Absent</div>
                    <div class="hrm-stat-value text-danger" id="statAbsent">{{ $todayAtt->absent ?? 0 }}</div>
                    <div class="hrm-stat-meta">Unauthorised absence</div>
                    <span class="hrm-stat-icon">⚠</span>
                </div>
            </div>
        </div>

        {{-- Row 2: OT chart, section attendance, gender --}}
        <div class="row g-3 mb-3">

            {{-- AVERAGE MONTHLY OT --}}
            <div class="col-md-5">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Avg Monthly Overtime (hrs)</span>
                        <span class="text-muted" style="font-size:.68rem">Last 6 months</span>
                    </div>
                    <div class="hrm-card-body">
                        @php $maxOT = collect($avgOT)->max('avg_ot') ?: 1; @endphp
                        <div class="d-flex align-items-flex-end gap-2" style="height:100px" id="otBarContainer">
                            @forelse($avgOT as $row)
                                <div class="ot-bar-col">
                                    <div class="ot-bar-val">{{ $row->avg_ot }}</div>
                                    <div class="ot-bar-outer">
                                        <div class="ot-bar-fill" style="height:{{ round(($row->avg_ot / $maxOT) * 75) }}px">
                                        </div>
                                    </div>
                                    <div class="ot-bar-label">{{ substr($row->att_month, 0, 3) }}</div>
                                </div>
                            @empty
                                <div class="text-muted" style="font-size:.75rem">No overtime data available.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION ATTENDANCE TODAY --}}
            <div class="col-md-4">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Section Attendance Today</span>
                        <span class="text-muted" style="font-size:.68rem">Best to Worst</span>
                    </div>
                    <div class="hrm-card-body">
                        <div id="sectionAttTodayContainer">
                            @forelse($sectionAttToday ?? [] as $i => $section)
                                @php
                                    $pct = round($section->pct, 1);
                                    $color =
                                        $pct >= 90
                                            ? '#22c55e'
                                            : ($pct >= 75
                                                ? '#84cc16'
                                                : ($pct >= 50
                                                    ? '#f59e0b'
                                                    : '#ef4444'));
                                @endphp
                                <div class="dept-att-row">
                                    <div class="dept-att-name" title="{{ $section->section_name }}">
                                        {{ $section->section_name }}
                                    </div>
                                    <div class="dept-att-bar-wrap">
                                        <div class="dept-att-bar-fill"
                                            style="width:{{ $pct }}%;background:{{ $color }}"></div>
                                    </div>
                                    <div class="dept-att-pct" style="color:{{ $color }}">{{ $pct }}%</div>
                                    <div style="font-size:.6rem;color:var(--hrm-muted);min-width:48px;text-align:right">
                                        {{ $section->present }}/{{ $section->total }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted" style="font-size:.75rem">No attendance data for today.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- GENDER + LEAVE SUMMARY --}}
            <div class="col-md-3">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Gender Split</span>
                    </div>
                    <div class="hrm-card-body">
                        @php
                            $male = collect($genderSplit)->firstWhere('sex', 'Male')->cnt ?? 0;
                            $female = collect($genderSplit)->firstWhere('sex', 'Female')->cnt ?? 0;
                            $gTotal = $male + $female ?: 1;
                            $mPct = round(($male / $gTotal) * 100);
                            $fPct = round(($female / $gTotal) * 100);
                            $circum = 2 * 3.14159 * 30;
                            $maleDash = round(($mPct / 100) * $circum, 2);
                            $femaleDash = $circum - $maleDash;
                        @endphp
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="gender-wrap">
                                <svg width="80" height="80" viewBox="0 0 80 80">
                                    <circle cx="40" cy="40" r="30" fill="none" stroke="#e9ecef"
                                        stroke-width="10" />
                                    <circle id="genderMaleArc" cx="40" cy="40" r="30" fill="none"
                                        stroke="#0d6efd" stroke-width="10"
                                        stroke-dasharray="{{ $maleDash }} {{ $femaleDash }}"
                                        stroke-linecap="butt" />
                                    <circle id="genderFemaleArc" cx="40" cy="40" r="30" fill="none"
                                        stroke="#f06595" stroke-width="10"
                                        stroke-dasharray="{{ $femaleDash }} {{ $maleDash }}"
                                        stroke-dashoffset="{{ -$maleDash }}" stroke-linecap="butt" />
                                </svg>
                                <div class="gender-label" id="genderTotal">{{ $gTotal }}</div>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:#0d6efd;display:inline-block"></span>
                                    <span style="font-size:.72rem;color:var(--hrm-muted)">Male</span>
                                    <span id="genderMaleCount"
                                        style="font-size:.72rem;font-weight:700;color:var(--hrm-primary);margin-left:4px">
                                        {{ $male }} ({{ $mPct }}%)
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:#f06595;display:inline-block"></span>
                                    <span style="font-size:.72rem;color:var(--hrm-muted)">Female</span>
                                    <span id="genderFemaleCount"
                                        style="font-size:.72rem;font-weight:700;color:var(--hrm-primary);margin-left:4px">
                                        {{ $female }} ({{ $fPct }}%)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            style="font-size:.62rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--hrm-muted);margin-bottom:.5rem">
                            Leave Taken ({{ now()->year }})
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($leaveSummary as $lv)
                                <div class="leave-pill">
                                    <div class="leave-pill-name">{{ Str::limit($lv->leave_name, 10) }}</div>
                                    <div class="leave-pill-taken">{{ $lv->total_taken }}</div>
                                    <div class="leave-pill-bal">Bal: {{ $lv->total_balance }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: recent joiners and actions --}}
        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <div class="hrm-card">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Recent Joiners
                            <span class="text-muted fw-normal" style="text-transform:none;letter-spacing:0">(last 30
                                days)</span>
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table hrm-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Join Date</th>
                                    <th>Gender</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJoiners as $i => $emp)
                                    <tr>
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td><span class="fw-semibold">{{ $emp->empno }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="hrm-avatar"
                                                    style="background:{{ $emp->sex === 'Male' ? '#dbeafe' : '#fce7f3' }};color:{{ $emp->sex === 'Male' ? '#1e40af' : '#9d174d' }}">
                                                    {{ strtoupper(substr($emp->emp_name, 0, 2)) }}
                                                </div>
                                                {{ $emp->emp_name }}
                                            </div>
                                        </td>
                                        <td>{{ $emp->dept_name }}</td>
                                        <td>{{ $emp->designation_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($emp->joining_date)->format('d M Y') }}</td>
                                        <td>
                                            <span
                                                class="hrm-badge {{ $emp->sex === 'Male' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-danger bg-opacity-10 text-danger' }}">
                                                {{ $emp->sex }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3" style="font-size:.78rem">
                                            No new joiners in the last 30 days.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- QUICK ACTION BUTTONS --}}
            <div class="col-md-4">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Alerts &amp; Actions</span>
                    </div>
                    <div class="hrm-card-body d-flex flex-column gap-2">
                        <button
                            class="btn btn-outline-warning btn-sm d-flex align-items-center justify-content-between w-100 text-start"
                            data-bs-toggle="modal" data-bs-target="#modalProbation">
                            <span><i class="bi bi-hourglass-split me-2"></i>Probation Ending This Month</span>
                            <span class="badge bg-warning text-dark"
                                id="badgeProbation">{{ count($probationEnd) }}</span>
                        </button>
                        <button
                            class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-between w-100 text-start"
                            data-bs-toggle="modal" data-bs-target="#modalIncrementThis">
                            <span><i class="bi bi-graph-up-arrow me-2"></i>Increment Due This Month -
                                {{ now()->format('F Y') }}</span>
                            <span class="badge bg-success" id="badgeIncrThis">{{ count($incrementThisMonth) }}</span>
                        </button>
                        <button
                            class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-between w-100 text-start"
                            data-bs-toggle="modal" data-bs-target="#modalIncrementNext">
                            <span><i class="bi bi-calendar2-plus me-2"></i>Increment Due Next Month -
                                {{ now()->addMonth()->format('F Y') }}</span>
                            <span class="badge bg-primary"
                                id="badgeIncrNext">{{ count($incrementNextMonth ?? []) }}</span>
                        </button>

                        <hr class="my-1">
                        <div class="text-muted"
                            style="font-size:.68rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase">Quick
                            Links</div>
                        <a href="#" class="btn btn-light btn-sm text-start border">
                            <i class="bi bi-person-plus me-2 text-primary"></i> Add New Employee
                        </a>
                        <a href="#" class="btn btn-light btn-sm text-start border">
                            <i class="bi bi-file-earmark-text me-2 text-success"></i> Leave Applications
                        </a>
                        <a href="#" class="btn btn-light btn-sm text-start border">
                            <i class="bi bi-clock-history me-2 text-warning"></i> Attendance Report
                        </a>
                        <a href="#" class="btn btn-light btn-sm text-start border">
                            <i class="bi bi-cash-stack me-2 text-danger"></i> Payroll Processing
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 4: heatmap + dept headcount --}}
        <div class="row g-3 mb-3">

            {{-- Attendance heatmap --}}
            <div class="col-md-7">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Attendance Heatmap</span>
                        <span class="text-muted" style="font-size:.68rem">{{ now()->format('F Y') }} — daily present
                            %</span>
                    </div>
                    <div class="hrm-card-body">
                        @php
                            $today = now()->day;
                            $firstDay = now()->startOfMonth()->dayOfWeek; // 0=Sun
                            $daysInMonth = now()->daysInMonth;
                            $heatByDay = collect($attendanceHeatmap ?? [])->keyBy('day');

                            function heatClass(float $pct): string
                            {
                                if ($pct >= 90) {
                                    return 'hm-90';
                                }
                                if ($pct >= 75) {
                                    return 'hm-75';
                                }
                                if ($pct >= 50) {
                                    return 'hm-50';
                                }
                                if ($pct >= 25) {
                                    return 'hm-25';
                                }
                                return 'hm-0';
                            }
                        @endphp

                        <div class="heatmap-grid">
                            @foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $d)
                                <div class="heatmap-day-label">{{ $d }}</div>
                            @endforeach

                            @for ($e = 0; $e < $firstDay; $e++)
                                <div class="heatmap-cell empty"></div>
                            @endfor

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $row = $heatByDay->get($day);
                                    $pct = $row ? (float) $row->pct : null;
                                @endphp
                                @if ($day > $today)
                                    <div class="heatmap-cell future" title="Day {{ $day }}">{{ $day }}
                                    </div>
                                @elseif($pct !== null)
                                    <div class="heatmap-cell {{ heatClass($pct) }}"
                                        title="Day {{ $day }}: {{ $pct }}% present">
                                        {{ $day }}</div>
                                @else
                                    <div class="heatmap-cell hm-0" title="Day {{ $day }}: No data">
                                        {{ $day }}</div>
                                @endif
                            @endfor
                        </div>

                        <div class="hm-legend">
                            <span style="font-size:.6rem;color:var(--hrm-muted);font-weight:600">Present %:</span>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot hm-0"></div>&lt;25%
                            </div>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot hm-25"></div>25–50%
                            </div>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot hm-50"></div>50–75%
                            </div>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot hm-75"></div>75–90%
                            </div>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot hm-90"></div>90%+
                            </div>
                            <div class="hm-legend-item">
                                <div class="hm-legend-dot" style="background:#f0f0f0;border:1px solid #ddd"></div>Future
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dept Headcount --}}
            <div class="col-md-5">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Dept Headcount</span>
                        <span class="text-muted" style="font-size:.68rem">By department</span>
                    </div>
                    <div class="hrm-card-body">
                        @php
                            $maxDept = collect($deptCount)->max('cnt') ?: 1;
                            $deptColors = ['#0d6efd', '#6610f2', '#0dcaf0', '#198754', '#fd7e14', '#dc3545'];
                        @endphp
                        <div id="deptBarContainer">
                            @foreach ($deptCount as $i => $dept)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="font-size:.72rem;color:#2c3e50;min-width:90px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                        title="{{ $dept->dept_name }}">{{ $dept->dept_name }}</div>
                                    <div class="dept-bar-wrap">
                                        <div class="dept-bar-fill"
                                            style="width:{{ round(($dept->cnt / $maxDept) * 100) }}%;background:{{ $deptColors[$i % count($deptColors)] }}">
                                        </div>
                                    </div>
                                    <div
                                        style="font-size:.72rem;font-weight:600;color:var(--hrm-primary);min-width:24px;text-align:right">
                                        {{ $dept->cnt }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 5: late trend and OT cost --}}
        <div class="row g-3 mb-3">

            {{-- Late arrival trend --}}
            <div class="col-md-5">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">Late Arrival Trend</span>
                        <span class="text-muted" style="font-size:.68rem">Last 7 days</span>
                    </div>
                    <div class="hrm-card-body">
                        @php $maxLate = collect($lateTrend ?? [])->max('late_count') ?: 1; @endphp
                        <div class="d-flex align-items-flex-end gap-2" style="height:90px">
                            @forelse($lateTrend ?? [] as $row)
                                <div class="late-bar-col">
                                    <div class="late-bar-val">{{ $row->late_count }}</div>
                                    <div class="late-bar-outer">
                                        <div class="late-bar-fill"
                                            style="height:{{ $maxLate > 0 ? round(($row->late_count / $maxLate) * 65) : 0 }}px">
                                        </div>
                                    </div>
                                    <div class="late-bar-label">
                                        {{ \Carbon\Carbon::parse($row->att_date)->format('D') }}<br>
                                        <span
                                            style="color:#94a3b8">{{ \Carbon\Carbon::parse($row->att_date)->format('d') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted" style="font-size:.75rem">No late arrival data.</div>
                            @endforelse
                        </div>

                        @if (!empty($lateTrend))
                            <div class="mt-2 pt-2 border-top d-flex gap-3" style="font-size:.7rem">
                                <span style="color:var(--hrm-muted)">7-day total:
                                    <strong style="color:#c2410c">{{ collect($lateTrend)->sum('late_count') }}</strong>
                                    late arrivals
                                </span>
                                <span style="color:var(--hrm-muted)">Daily avg:
                                    <strong
                                        style="color:#c2410c">{{ round(collect($lateTrend)->avg('late_count'), 1) }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- OT cost estimate by department --}}
            <div class="col-md-7">
                <div class="hrm-card h-100">
                    <div class="hrm-card-header">
                        <span class="hrm-card-title">OT Cost Estimate — {{ now()->format('F Y') }}</span>
                        <span class="text-muted" style="font-size:.68rem">OT hrs × hourly rate by dept</span>
                    </div>
                    <div class="hrm-card-body p-0">
                        @php
                            $maxCost = collect($otCostByDept ?? [])->max('estimated_cost') ?: 1;
                            $grandTotal = collect($otCostByDept ?? [])->sum('estimated_cost');
                        @endphp
                        <div class="table-responsive">
                            <table class="table hrm-table ot-cost-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Department</th>
                                        <th class="text-end">OT Hrs</th>
                                        <th class="text-end">Avg Rate</th>
                                        <th class="text-end">Est. Cost</th>
                                        <th>Share</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($otCostByDept ?? [] as $i => $row)
                                        @php
                                            $sharePct =
                                                $grandTotal > 0
                                                    ? round(($row->estimated_cost / $grandTotal) * 100, 1)
                                                    : 0;
                                            $barPct = $maxCost > 0 ? round(($row->estimated_cost / $maxCost) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="text-muted">{{ $i + 1 }}</td>
                                            <td>{{ $row->dept_name }}</td>
                                            <td class="text-end fw-semibold">{{ number_format($row->total_ot_hrs, 1) }}
                                            </td>
                                            <td class="text-end text-muted">{{ number_format($row->avg_hourly_rate, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                {{ number_format($row->estimated_cost, 0) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <div class="ot-cost-bar-wrap">
                                                        <div class="ot-cost-bar-fill" style="width:{{ $barPct }}%">
                                                        </div>
                                                    </div>
                                                    <span
                                                        style="font-size:.63rem;color:var(--hrm-muted)">{{ $sharePct }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3"
                                                style="font-size:.78rem">
                                                No OT cost data for this month.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if (!empty($otCostByDept))
                                    <tfoot>
                                        <tr style="background:var(--hrm-surface)">
                                            <td colspan="2" class="fw-bold" style="font-size:.72rem">TOTAL</td>
                                            <td class="text-end fw-bold" style="font-size:.72rem">
                                                {{ number_format(collect($otCostByDept)->sum('total_ot_hrs'), 1) }}
                                            </td>
                                            <td></td>
                                            <td class="text-end fw-bold text-primary" style="font-size:.72rem">
                                                {{ number_format($grandTotal, 0) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /hrm-page --}}

    {{-- ==================== MODALS ==================== --}}

    {{-- Modal: probation ending --}}
    <div class="modal fade" id="modalProbation" tabindex="-1" aria-labelledby="modalProbationLabel">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2 px-3" style="background:var(--hrm-primary)">
                    <h6 class="modal-title text-white mb-0" id="modalProbationLabel">
                        <i class="bi bi-hourglass-split me-2"></i>
                        Probation Period Ending — {{ now()->format('F Y') }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table hrm-table" id="tblProbation">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Join Date</th>
                                    <th>Probation Period</th>
                                    <th>Confirm Date</th>
                                    <th>Months Served</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($probationEnd as $i => $emp)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $emp->empno }}</td>
                                        <td>{{ $emp->emp_name }}</td>
                                        <td>{{ $emp->dept_name }}</td>
                                        <td>{{ $emp->designation_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($emp->joining_date)->format('d M Y') }}</td>
                                        <td>{{ $emp->provision_period }} months</td>
                                        <td>
                                            @if ($emp->conform_date)
                                                {{ \Carbon\Carbon::parse($emp->conform_date)->format('d M Y') }}
                                            @else
                                                <span class="text-warning fw-semibold">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $emp->months_served }}</td>
                                        <td><span class="hrm-badge bg-warning bg-opacity-15 text-warning">Due</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">No probation confirmations
                                            due this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 no-print">
                    <span class="text-muted me-auto" style="font-size:.72rem">{{ count($probationEnd) }} record(s)</span>
                    {{-- FIX: was broken raw text instead of onclick --}}
                    <button class="btn btn-outline-secondary btn-sm"
                        onclick="printModal('tblProbation','Probation Period Ending - {{ now()->format('F Y') }}')">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: increment this month --}}
    <div class="modal fade" id="modalIncrementThis" tabindex="-1" aria-labelledby="modalIncrThisLabel">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2 px-3" style="background:#198754">
                    <h6 class="modal-title text-white mb-0" id="modalIncrThisLabel">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Increment Due This Month — {{ now()->format('F Y') }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table hrm-table" id="tblIncrThis">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Increment Date</th>
                                    <th>Current Gross</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incrementThisMonth as $i => $emp)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $emp->empno }}</td>
                                        <td>{{ $emp->emp_name }}</td>
                                        <td>{{ $emp->dept_name }}</td>
                                        <td>{{ $emp->designation_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($emp->increment_date)->format('d M Y') }}</td>
                                        <td class="fw-semibold text-success">{{ number_format($emp->gross, 2) }}</td>
                                        <td class="no-print">
                                            <span class="hrm-badge bg-success bg-opacity-15 text-success">Process</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No increments due this
                                            month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 no-print">
                    <span class="text-muted me-auto" style="font-size:.72rem">{{ count($incrementThisMonth) }}
                        record(s)</span>
                    <button class="btn btn-outline-secondary btn-sm"
                        onclick="printModal('tblIncrThis','Increment Due - {{ now()->format('F Y') }}')">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: increment next month --}}
    <div class="modal fade" id="modalIncrementNext" tabindex="-1" aria-labelledby="modalIncrNextLabel">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2 px-3" style="background:#0d6efd">
                    <h6 class="modal-title text-white mb-0" id="modalIncrNextLabel">
                        <i class="bi bi-calendar2-plus me-2"></i>
                        Increment Due Next Month — {{ now()->addMonth()->format('F Y') }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table hrm-table" id="tblIncrNext">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Increment Date</th>
                                    <th>Current Gross</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incrementNextMonth as $i => $emp)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $emp->empno }}</td>
                                        <td>{{ $emp->emp_name }}</td>
                                        <td>{{ $emp->dept_name }}</td>
                                        <td>{{ $emp->designation_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($emp->increment_date)->format('d M Y') }}</td>
                                        <td class="fw-semibold">{{ number_format($emp->gross, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No increments due next
                                            month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3 no-print">
                    <span class="text-muted me-auto" style="font-size:.72rem">{{ count($incrementNextMonth) }}
                        record(s)</span>
                    {{-- FIX: was broken raw text instead of onclick --}}
                    <button class="btn btn-outline-secondary btn-sm"
                        onclick="printModal('tblIncrNext','Increment Due Next Month - {{ now()->addMonth()->format('F Y') }}')">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ── CONFIG ────────────────────────────────────────────────────────────
        const REFRESH_INTERVAL_MS = 60000;
        const LIVE_DATA_URL = '{{ route('hrm.dashboard.liveData') }}';

        // ── LAST-UPDATED BADGE ────────────────────────────────────────────────
        function injectUpdatedBadge() {
            if (document.getElementById('lastUpdatedBadge')) return;

            const badge = document.createElement('span');
            badge.id = 'lastUpdatedBadge';
            badge.style.cssText = `
                display:inline-flex;align-items:center;gap:.4rem;
                font-size:.72rem;font-weight:500;color:#155724;
                background:#d4edda;border:1px solid #c3e6cb;
                border-radius:20px;padding:.28rem .85rem;
            `;
            badge.innerHTML = '<i class="bi bi-arrow-repeat"></i> <span id="lastUpdatedText">Updating...</span>';

            const hrmBadge = document.querySelector('.hrm-db-badge');
            if (hrmBadge) hrmBadge.insertAdjacentElement('afterend', badge);
        }

        // ── KPI COUNTER ANIMATION ─────────────────────────────────────────────
        function animateValue(el, newVal) {
            if (!el) return;
            const current = parseInt(el.textContent.replace(/,/g, '')) || 0;
            const target = parseInt(String(newVal).replace(/,/g, '')) || 0;
            if (current === target) return;

            const step = target > current ? 1 : -1;
            const diff = Math.abs(target - current);
            const duration = Math.min(600, diff * 15);
            const interval = Math.max(10, duration / diff);

            let cur = current;
            const timer = setInterval(() => {
                cur += step;
                el.textContent = cur.toLocaleString();
                if (cur === target) clearInterval(timer);
            }, interval);
        }

        // ── DEPT BAR UPDATER ──────────────────────────────────────────────────
        const DEPT_COLORS = ['#0d6efd', '#6610f2', '#0dcaf0', '#198754', '#fd7e14', '#dc3545', '#6c757d', '#20c997'];

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function updateDeptBars(deptData) {
            const container = document.getElementById('deptBarContainer');
            if (!container || !deptData.length) return;

            const maxCnt = Math.max(...deptData.map(d => d.cnt), 1);

            container.innerHTML = deptData.map((dept, i) => `
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="font-size:.72rem;color:#2c3e50;min-width:90px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                         title="${escHtml(dept.dept_name)}">${escHtml(dept.dept_name)}</div>
                    <div class="dept-bar-wrap">
                        <div class="dept-bar-fill"
                             style="width:0%;background:${DEPT_COLORS[i % DEPT_COLORS.length]};transition:width .7s ease">
                        </div>
                    </div>
                    <div style="font-size:.72rem;font-weight:600;color:var(--hrm-primary);min-width:24px;text-align:right">
                        ${dept.cnt}
                    </div>
                </div>
            `).join('');

            requestAnimationFrame(() => {
                container.querySelectorAll('.dept-bar-fill').forEach((bar, i) => {
                    bar.style.width = Math.round((deptData[i].cnt / maxCnt) * 100) + '%';
                });
            });
        }

        // ── GENDER DONUT UPDATER ──────────────────────────────────────────────
        function updateGenderDonut(genderData) {
            const male = Number((genderData.find(g => g.sex?.toLowerCase() === 'male') || {}).cnt) || 0;
            const female = Number((genderData.find(g => g.sex?.toLowerCase() === 'female') || {}).cnt) || 0;
            const total = male + female || 1;
            const mPct = Math.round((male / total) * 100);
            const circum = 2 * Math.PI * 30;
            const maleDash = Math.round((mPct / 100) * circum * 100) / 100;
            const femDash = Math.round((circum - maleDash) * 100) / 100;

            const mArc = document.getElementById('genderMaleArc');
            const fArc = document.getElementById('genderFemaleArc');
            if (mArc) mArc.setAttribute('stroke-dasharray', `${maleDash} ${femDash}`);
            if (fArc) {
                fArc.setAttribute('stroke-dasharray', `${femDash} ${maleDash}`);
                fArc.setAttribute('stroke-dashoffset', `-${maleDash}`);
            }

            const totalEl = document.getElementById('genderTotal');
            if (totalEl) totalEl.textContent = total.toLocaleString();

            const mEl = document.getElementById('genderMaleCount');
            const fEl = document.getElementById('genderFemaleCount');
            if (mEl) mEl.textContent = `${male} (${mPct}%)`;
            if (fEl) fEl.textContent = `${female} (${100 - mPct}%)`;
        }

        // ── OT BAR CHART UPDATER ──────────────────────────────────────────────
        function updateOTChart(otData) {
            const container = document.getElementById('otBarContainer');
            if (!container || !otData.length) return;

            const maxOT = Math.max(...otData.map(d => parseFloat(d.avg_ot) || 0), 1);

            container.innerHTML = otData.map(row => `
                <div class="ot-bar-col">
                    <div class="ot-bar-val">${row.avg_ot}</div>
                    <div class="ot-bar-outer">
                        <div class="ot-bar-fill" style="height:0px;transition:height .7s ease"></div>
                    </div>
                    <div class="ot-bar-label">${escHtml(String(row.att_month).substring(0, 3))}</div>
                </div>
            `).join('');

            requestAnimationFrame(() => {
                container.querySelectorAll('.ot-bar-fill').forEach((bar, i) => {
                    bar.style.height = Math.round((parseFloat(otData[i].avg_ot) / maxOT) * 75) + 'px';
                });
            });
        }

        // ── SECTION ATTENDANCE UPDATER ────────────────────────────────────────
        function updateSectionAtt(sectionData) {
            const container = document.getElementById('sectionAttTodayContainer');
            if (!container) return;

            if (!sectionData || !sectionData.length) {
                container.innerHTML =
                    '<div class="text-muted" style="font-size:.75rem">No attendance data for today.</div>';
                return;
            }

            container.innerHTML = sectionData.map(section => {
                const pct = parseFloat(section.pct) || 0;
                const color = pct >= 90 ? '#22c55e' :
                    pct >= 75 ? '#84cc16' :
                    pct >= 50 ? '#f59e0b' :
                    '#ef4444';
                return `
                    <div class="dept-att-row">
                        <div class="dept-att-name" title="${escHtml(section.section_name)}">${escHtml(section.section_name)}</div>
                        <div class="dept-att-bar-wrap">
                            <div class="dept-att-bar-fill" style="width:0%;background:${color};transition:width .7s ease"></div>
                        </div>
                        <div class="dept-att-pct" style="color:${color}">${pct}%</div>
                        <div style="font-size:.6rem;color:var(--hrm-muted);min-width:48px;text-align:right">
                            ${section.present}/${section.total}
                        </div>
                    </div>`;
            }).join('');

            // Animate bars after paint
            requestAnimationFrame(() => {
                container.querySelectorAll('.dept-att-bar-fill').forEach((bar, i) => {
                    bar.style.width = (parseFloat(sectionData[i].pct) || 0) + '%';
                });
            });
        }

        // ── CARD FLASH ────────────────────────────────────────────────────────
        function flashRefresh() {
            document.querySelectorAll('.hrm-stat').forEach(card => {
                card.style.transition = 'opacity .2s';
                card.style.opacity = '.6';
                setTimeout(() => {
                    card.style.opacity = '1';
                }, 300);
            });
        }

        // ── MAIN FETCH ────────────────────────────────────────────────────────
        function fetchDashboardData() {
            fetch(LIVE_DATA_URL, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (!data.success) return;

                    flashRefresh();

                    animateValue(document.getElementById('statTotalEmp'), data.total_employees);
                    animateValue(document.getElementById('statPresent'), data.present);
                    animateValue(document.getElementById('statOnLeave'), data.on_leave);
                    animateValue(document.getElementById('statAbsent'), data.absent);
                    animateValue(document.getElementById('statLate'), data.late);

                    const rateEl = document.getElementById('statAttendanceRate');
                    if (rateEl) rateEl.textContent = data.attendance_rate + '% attendance rate';

                    updateDeptBars(data.deptCount);
                    updateGenderDonut(data.genderSplit);
                    updateOTChart(data.avg_ot);
                    updateSectionAtt(data.sectionAttToday);

                    const probBadge = document.getElementById('badgeProbation');
                    const incrThis = document.getElementById('badgeIncrThis');
                    const incrNext = document.getElementById('badgeIncrNext');
                    if (probBadge) probBadge.textContent = data.probationEnd.length;
                    if (incrThis) incrThis.textContent = data.incrementThisMonth.length;
                    if (incrNext) incrNext.textContent = data.incrementNextMonth.length;

                    const updEl = document.getElementById('lastUpdatedText');
                    if (updEl) updEl.textContent = 'Updated ' + data.updated_at;
                })
                .catch(() => {
                    // FIX: el was referenced before declaration in original
                    const updEl = document.getElementById('lastUpdatedText');
                    if (updEl) updEl.textContent = 'Refresh failed — retrying...';
                });
        }

        // ── BOOT ──────────────────────────────────────────────────────────────
        // FIX: original DOMContentLoaded block was never closed; setTimeout never
        //      had its interval call; REFRESH_INTERVAL_MS was declared twice with const.
        document.addEventListener('DOMContentLoaded', () => {
            injectUpdatedBadge();
            // First fetch after one interval; initial data already rendered by Blade.
            setTimeout(fetchDashboardData, REFRESH_INTERVAL_MS);
            setInterval(fetchDashboardData, REFRESH_INTERVAL_MS);
        });
    </script>

    <script>
        // ── PRINT HELPER ──────────────────────────────────────────────────────
        function printModal(tableId, title) {
            const table = document.getElementById(tableId);
            if (!table) {
                alert('Table not found: ' + tableId);
                return;
            }

            const safeTitle = title.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const clone = table.cloneNode(true);
            clone.querySelectorAll('.no-print').forEach(el => el.remove());

            const win = window.open('', '_blank', 'width=900,height=650');
            if (!win) {
                alert('Popup blocked! Allow popups for this site.');
                return;
            }

            win.document.write(`
                <!DOCTYPE html><html><head>
                <title>${safeTitle}</title>
                <style>
                    body { font-family:Arial; font-size:11px; margin:20px; }
                    table { width:100%; border-collapse:collapse; }
                    th { background:#1a3c5e; color:#fff; padding:6px; }
                    td { padding:5px; border-bottom:1px solid #ccc; }
                </style>
                </head><body>
                <h3>${safeTitle}</h3>${clone.outerHTML}
                </body></html>
            `);
            win.document.close();
            setTimeout(() => {
                win.focus();
                win.print();
            }, 500);
        }
    </script>

    <script>
        // ── LIVE CLOCK ────────────────────────────────────────────────────────
        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const time = now.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            document.getElementById('liveClock').textContent = date + ' ' + time;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
@endpush
