@extends('layouts.app')
@section('title', 'Leave Entry – View')

@push('styles')
@include('hrm.leave._styles')
@endpush

@section('content')
<div class="container-fluid py-3 hrm-page">

    <div class="hrm-page-title">
        <i class="bi bi-calendar2-check"></i> Leave Entry – View
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('leave.edit', [$master->empno, $master->year, $master->company_id]) }}"
               class="hrm-btn hrm-btn-primary" style="font-size:12px;padding:5px 13px">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('leave.index') }}" class="hrm-btn hrm-btn-secondary" style="font-size:12px;padding:5px 13px">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="hrm-card">
        <div class="hrm-card-header"><i class="bi bi-person-badge"></i> Employee Information</div>
        <div class="hrm-card-body">
            <div class="d-flex gap-3 align-items-start">
                <img src="http://192.168.210.205:81/{{ $master->empno }}.jpg"
                     class="hrm-emp-photo" alt="Photo"
                     data-fallback="{{ asset('images/no-photo.png') }}"
                     onerror="window.hrmPhotoFallback(this)">
                <div class="row g-2 flex-grow-1">
                    <div class="col-md-2">
                        <div class="hrm-label">Sys Emp No</div>
                        <span style="color:#6c757d;font-size:12px">{{ $master->empno }}</span>
                    </div>
                    <div class="col-md-2">
                        <div class="hrm-label">Emp No (New)</div>
                        <strong style="color:#1a3c5e">{{ $master->new_empno }}</strong>
                    </div>
                    <div class="col-md-3">
                        <div class="hrm-label">Name</div>
                        <strong>{{ trim($master->ename) }}</strong>
                    </div>
                    <div class="col-md-3">
                        <div class="hrm-label">Designation</div>
                        <span>{{ $master->des_name }}</span>
                    </div>
                    <div class="col-md-2">
                        <div class="hrm-label">Year</div>
                        <strong style="color:#1a3c5e">{{ $master->year }}</strong>
                    </div>
                </div>
            </div>

            <div class="hrm-bal-grid mt-3">
                <div class="hrm-bal-card casual">
                    <div class="hrm-bal-title casual">Casual Leave</div>
                    <div class="hrm-bal-nums">
                        <div><div class="hrm-bal-lbl">Total</div><div class="hrm-bal-val">{{ $balances['casual_total'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Enjoyed</div><div class="hrm-bal-val">{{ $balances['casual_enjoyed'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Balance</div><div class="hrm-bal-val casual">{{ $balances['casual_balance'] }}</div></div>
                    </div>
                </div>
                <div class="hrm-bal-card sick">
                    <div class="hrm-bal-title sick">Sick Leave</div>
                    <div class="hrm-bal-nums">
                        <div><div class="hrm-bal-lbl">Total</div><div class="hrm-bal-val">{{ $balances['sick_total'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Enjoyed</div><div class="hrm-bal-val">{{ $balances['sick_enjoyed'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Balance</div><div class="hrm-bal-val sick">{{ $balances['sick_balance'] }}</div></div>
                    </div>
                </div>
                <div class="hrm-bal-card earn">
                    <div class="hrm-bal-title earn">Earned Leave</div>
                    <div class="hrm-bal-nums">
                        <div><div class="hrm-bal-lbl">Total</div><div class="hrm-bal-val">{{ $balances['earn_total'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Enjoyed</div><div class="hrm-bal-val">{{ $balances['earn_enjoyed'] }}</div></div>
                        <div><div class="hrm-bal-lbl">Balance</div><div class="hrm-bal-val earn">{{ $balances['earn_balance'] }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hrm-card">
        <div class="hrm-card-header"><i class="bi bi-table"></i> Leave Details</div>
        <div class="hrm-card-body p-0">
            <div class="table-responsive">
                <table class="hrm-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Leave Type</th><th>From</th><th>To</th>
                            <th>Approve Days</th><th>Application Date</th><th>Approve Date</th>
                            <th>Approve By</th><th>Pre Bal</th><th>Balance</th>
                            <th>Information</th><th>Remarks</th><th>Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($details as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><strong>{{ $d->leave_name }}</strong></td>
                        <td>{{ $d->lv_from ? \Carbon\Carbon::parse($d->lv_from)->format('d M Y') : '—' }}</td>
                        <td>{{ $d->lv_to   ? \Carbon\Carbon::parse($d->lv_to)->format('d M Y')   : '—' }}</td>
                        <td style="text-align:center;color:#1a3c5e;font-weight:600">{{ $d->approve_days }}</td>
                        <td>{{ $d->application_date ? \Carbon\Carbon::parse($d->application_date)->format('d M Y') : '—' }}</td>
                        <td>{{ $d->approve_date     ? \Carbon\Carbon::parse($d->approve_date)->format('d M Y')     : '—' }}</td>
                        <td>{{ $d->approve_by ?? '—' }}</td>
                        <td style="text-align:center">{{ $d->pre_balance }}</td>
                        <td style="text-align:center;color:#198754;font-weight:600">{{ $d->balance }}</td>
                        <td>{{ $d->information ?? '—' }}</td>
                        <td>{{ $d->remax }}</td>
                        <td>
                            <a href="{{ route('leave.slip', [$d->auto, $d->empno]) }}"
                               target="_blank" class="hrm-btn-success-outline" title="Print Slip">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="13" class="text-center text-muted py-4">No details found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
