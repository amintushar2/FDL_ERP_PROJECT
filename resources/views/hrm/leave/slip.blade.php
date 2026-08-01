@extends('layouts.app')
@section('title', 'Leave Sanction Slip')

@push('styles')
    @include('hrm.leave._styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .slip-wrap {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
            }

            @page {
                size: A4;
                margin: 12mm 15mm;
            }
        }

        .slip-wrap {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
            padding: 26px 30px;
            font-size: 12.5px;
            color: #212529;
        }

        /* Header */
        .slip-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 2px solid #1a3c5e;
            padding-bottom: 11px;
            margin-bottom: 13px;
        }

        .slip-logo {
            width: 70px;
            flex-shrink: 0;
        }

        .slip-logo img {
            width: 100%;
        }

        .slip-co-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 2px;
        }

        .slip-co-unit {
            font-size: 12px;
            color: #444;
            margin: 0 0 1px;
        }

        .slip-co-address {
            font-size: 11px;
            color: #777;
            margin: 0;
        }

        .slip-title-bar {
            text-align: center;
            font-size: 13.5px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #1a3c5e;
            border: 1px solid #1a3c5e;
            padding: 5px 0;
            border-radius: 4px;
            margin-bottom: 13px;
        }

        /* Photo */
        .slip-emp-photo {
            width: 72px;
            height: 88px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            float: right;
            margin: 0 0 10px 14px;
        }

        /* Info */
        .slip-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            margin-bottom: 13px;
        }

        .slip-row {
            display: flex;
            padding: 3.5px 0;
            border-bottom: 1px dashed #e3e3e3;
        }

        .slip-lbl {
            width: 138px;
            flex-shrink: 0;
            font-size: 11.5px;
            color: #666;
            font-weight: 500;
        }

        .slip-val {
            flex: 1;
            font-size: 12px;
            font-weight: 600;
            color: #212529;
        }

        .slip-val.hi {
            color: #1a3c5e;
        }

        .slip-right-col {
            padding-left: 15px;
        }

        /* Leave detail box */
        .lv-box {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 13px;
        }

        .lv-box-head {
            background: #1a3c5e;
            color: #fff;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .lv-box-body {
            padding: 10px 12px;
        }

        .lv-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .lv-lbl {
            font-size: 10.5px;
            color: #888;
            margin-bottom: 1px;
        }

        .lv-val {
            font-size: 12.5px;
            font-weight: 600;
        }

        .lv-val.hi {
            color: #1a3c5e;
        }

        .lv-val.green {
            color: #1d8a5e;
        }

        .slip-remarks-bar {
            margin-top: 9px;
            padding-top: 7px;
            border-top: 1px dashed #dee2e6;
            font-size: 11.5px;
        }

        /* Balance summary */
        .slip-bal-title {
            font-size: 10.5px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 6px;
        }

        .slip-bal-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            margin-bottom: 14px;
        }

        .slip-bal-card {
            border-radius: 5px;
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            border-left: 3px solid;
        }

        .slip-bal-card.cl {
            border-left-color: #1a6bb5;
        }

        .slip-bal-card.ml {
            border-left-color: #6f42c1;
        }

        .slip-bal-card.el {
            border-left-color: #1d8a5e;
        }

        .sbc-title {
            font-size: 10.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .sbc-title.cl {
            color: #1a6bb5;
        }

        .sbc-title.ml {
            color: #6f42c1;
        }

        .sbc-title.el {
            color: #1d8a5e;
        }

        .sbc-nums {
            display: flex;
            gap: 10px;
        }

        .sbc-item .sbl {
            font-size: 9.5px;
            color: #888;
        }

        .sbc-item .sbv {
            font-size: 13px;
            font-weight: 600;
        }

        /* Signatures */
        .slip-sig-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 26px;
        }

        .slip-sig-box {
            text-align: center;
        }

        .slip-sig-line {
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 11px;
            color: #555;
        }

        .slip-sig-name {
            font-weight: 600;
            color: #1a3c5e;
            font-size: 11.5px;
        }

        /* Footer */
        .slip-footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #bbb;
        }
    </style>
@endpush

@section('content')
    <div class="container py-3 hrm-page">

        <div class="d-flex gap-2 mb-3 no-print">
            <button onclick="window.print()" class="hrm-btn hrm-btn-primary" style="font-size:12px;padding:5px 14px">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('leave.edit', [$master->empno, $master->year, $master->company_id]) }}"
                class="hrm-btn hrm-btn-secondary" style="font-size:12px;padding:5px 14px">
                <i class="bi bi-arrow-left"></i> Back to Entry
            </a>
        </div>

        <div class="slip-wrap">

            {{-- Company Header — COMPANY_NAME, COMPANY_UNIT, ADDRESS, LOGO_LOCATION --}}
            <div class="slip-header">
                @if (!empty($master->logo_location))
                    <div class="slip-logo">
                        <img src="{{ asset($master->logo_location) }}" alt="Logo" onerror="this.style.display='none'">
                    </div>
                @endif
                <div>
                    <p class="slip-co-name">{{ strtoupper($master->company_name ?? 'COMPANY NAME') }}</p>
                    @if (!empty($master->company_unit))
                        <p class="slip-co-unit">{{ $master->company_unit }}</p>
                    @endif
                    @if (!empty($master->address))
                        <p class="slip-co-address">{{ $master->address }}</p>
                    @endif
                </div>
            </div>

            <div class="slip-title-bar">Leave Sanction Slip</div>

            {{-- Employee Photo — image server: http://192.168.210.205:81 --}}
            <img src="http://192.168.210.205:81/{{ $master->empno }}.jpg" class="slip-emp-photo"
                alt="Photo" data-fallback="{{ asset('images/no-photo.png') }}" onerror="window.hrmPhotoFallback(this)">

            {{-- Employee Info --}}
            <div class="slip-grid">
                <div>
                    <div class="slip-row">
                        <span class="slip-lbl">Emp No (Sys)</span>
                        <span class="slip-val" style="color:#6c757d;font-size:11.5px">{{ $master->empno }}</span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Emp No (New)</span>
                        <span class="slip-val hi">{{ $master->new_empno }}</span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Employee Name</span>
                        <span class="slip-val">{{ trim($master->ename) }}</span>
                    </div>
                    @if (!empty($master->father_name))
                        <div class="slip-row">
                            <span class="slip-lbl">Father's Name</span>
                            <span class="slip-val">{{ $master->father_name }}</span>
                        </div>
                    @endif
                    <div class="slip-row">
                        <span class="slip-lbl">Designation</span>
                        <span class="slip-val">{{ $master->des_name }}</span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Department</span>
                        <span class="slip-val">{{ $master->dept_name ?? '—' }}</span>
                    </div>
                    @if (!empty($master->section_name))
                        <div class="slip-row">
                            <span class="slip-lbl">Section</span>
                            <span class="slip-val">{{ $master->section_name }}</span>
                        </div>
                    @endif
                </div>
                <div class="slip-right-col">
                    <div class="slip-row">
                        <span class="slip-lbl">Year</span>
                        <span class="slip-val hi">{{ $master->year }}</span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Joining Date</span>
                        <span class="slip-val">
                            {{ $master->joining_date ? \Carbon\Carbon::parse($master->joining_date)->format('d M Y') : '—' }}
                        </span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Confirm Date</span>
                        <span class="slip-val">
                            {{ $master->conform_date ? \Carbon\Carbon::parse($master->conform_date)->format('d M Y') : '—' }}
                        </span>
                    </div>
                    <div class="slip-row">
                        <span class="slip-lbl">Slip Date</span>
                        <span class="slip-val">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Leave Detail Box
             RDF fields: LEAVE_NAME, LV_FROM, LV_TO, APPROVE_DAYS,
             APPLICATION_DATE, APPROVE_DATE, APPROVE_BY,
             PRE_BALANCE, BALANCE, INFORMATION, REMAX --}}
            <div class="lv-box">
                <div class="lv-box-head">
                    Leave Details &nbsp;|&nbsp; <strong>{{ $detail->leave_name }}</strong>
                </div>
                <div class="lv-box-body">
                    <div class="lv-grid-4">
                        <div>
                            <div class="lv-lbl">From Date</div>
                            <div class="lv-val">
                                {{ $detail->lv_from ? \Carbon\Carbon::parse($detail->lv_from)->format('d M Y') : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="lv-lbl">To Date</div>
                            <div class="lv-val">
                                {{ $detail->lv_to ? \Carbon\Carbon::parse($detail->lv_to)->format('d M Y') : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="lv-lbl">Approved Days</div>
                            <div class="lv-val hi">{{ $detail->approve_days ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="lv-lbl">Max Days</div>
                            <div class="lv-val">{{ $detail->max_days ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="lv-lbl">Application Date</div>
                            <div class="lv-val">
                                {{ $detail->application_date ? \Carbon\Carbon::parse($detail->application_date)->format('d M Y') : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="lv-lbl">Approval Date</div>
                            <div class="lv-val">
                                {{ $detail->approve_date ? \Carbon\Carbon::parse($detail->approve_date)->format('d M Y') : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="lv-lbl">Pre Balance</div>
                            <div class="lv-val">{{ $detail->pre_balance ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="lv-lbl">Balance</div>
                            <div class="lv-val green">{{ $detail->balance ?? '—' }}</div>
                        </div>
                    </div>

                    @if (!empty($detail->approve_by))
                        <div class="slip-remarks-bar">
                            <span style="color:#555;font-weight:500">Approved By:</span>
                            {{ $detail->approve_by }}
                        </div>
                    @endif

                    @if (!empty($detail->information))
                        <div class="slip-remarks-bar">
                            <span style="color:#555;font-weight:500">Information:</span>
                            {{ $detail->information }}
                        </div>
                    @endif

                    @if (!empty($detail->remax))
                        <div class="slip-remarks-bar">
                            <span style="color:#555;font-weight:500">Remarks:</span>
                            {{ $detail->remax }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Balance Summary
             Mirrors RDF: CF_CL (L-01), CF_ML (L-02), CF_EL (L-04)
             CF_BALANCE_CL = max(L-01) - CF_CL
             CF_BALANCE_ML = max(L-02) - CF_ML
             balance_el    = emp_extra.earn_leave_balance --}}
            <div class="slip-bal-title">Annual Leave Balance Summary</div>
            <div class="slip-bal-grid">

                <div class="slip-bal-card cl">
                    <div class="sbc-title cl">Casual Leave (L-01)</div>
                    <div class="sbc-nums">
                        <div class="sbc-item">
                            <div class="sbl">Entitled</div>
                            <div class="sbv">{{ $leaveBalances['max_cl'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Enjoyed</div>
                            <div class="sbv">{{ $leaveBalances['cf_cl'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Balance</div>
                            <div class="sbv" style="color:#1a6bb5">{{ $leaveBalances['balance_cl'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="slip-bal-card ml">
                    <div class="sbc-title ml">Maternity / Medical (L-02)</div>
                    <div class="sbc-nums">
                        <div class="sbc-item">
                            <div class="sbl">Entitled</div>
                            <div class="sbv">{{ $leaveBalances['max_ml'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Enjoyed</div>
                            <div class="sbv">{{ $leaveBalances['cf_ml'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Balance</div>
                            <div class="sbv" style="color:#6f42c1">{{ $leaveBalances['balance_ml'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="slip-bal-card el">
                    <div class="sbc-title el">Earned Leave (L-04)</div>
                    <div class="sbc-nums">
                        <div class="sbc-item">
                            <div class="sbl">Entitled</div>
                            <div class="sbv">{{ $leaveBalances['max_el'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Enjoyed</div>
                            <div class="sbv">{{ $leaveBalances['cf_el'] }}</div>
                        </div>
                        <div class="sbc-item">
                            <div class="sbl">Balance</div>
                            <div class="sbv" style="color:#1d8a5e">{{ $leaveBalances['balance_el'] }}</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Signatures --}}
            <div class="slip-sig-row">
                <div class="slip-sig-box">
                    <div style="height:36px"></div>
                    <div class="slip-sig-line">
                        Employee Signature<br>
                        <span class="slip-sig-name">{{ trim($master->ename) }}</span>
                    </div>
                </div>
                <div class="slip-sig-box">
                    <div style="height:36px"></div>
                    <div class="slip-sig-line">
                        Recommended By
                        @if (!empty($detail->approve_by))
                            <br><span class="slip-sig-name">{{ $detail->approve_by }}</span>
                        @endif
                    </div>
                </div>
                <div class="slip-sig-box">
                    <div style="height:36px"></div>
                    <div class="slip-sig-line">
                        Approved By<br>
                        <span class="slip-sig-name">{{ $master->approve_authority ?? 'HR Manager' }}</span>
                    </div>
                </div>
            </div>

            <div class="slip-footer">
                System-generated leave sanction slip &nbsp;|&nbsp;
                {{ $master->company_name ?? '' }} &nbsp;|&nbsp;
                Printed: {{ now()->format('d M Y, h:i A') }}
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', () => window.print());
        }
    </script>
@endpush
