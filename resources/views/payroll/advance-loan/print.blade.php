{{-- resources/views/hrm/advance-loan/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Loan Schedule – {{ $loan->loan_app_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #1a2f45;
            padding: 20px 28px;
        }

        /* Header */
        .rpt-header {
            text-align: center;
            margin-bottom: 14px;
        }

        .rpt-header .company-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a3a5c;
            letter-spacing: .5px;
        }

        .rpt-header .rpt-title {
            font-size: 12px;
            font-weight: 600;
            color: #1e6b8a;
            margin-top: 2px;
        }

        .rpt-header .rpt-sub {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        hr.rpt-rule {
            border: none;
            border-top: 2px solid #1a3a5c;
            margin: 8px 0;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px 16px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            gap: 4px;
            font-size: 10.5px;
        }

        .info-label {
            font-weight: 600;
            color: #344a60;
            white-space: nowrap;
            min-width: 110px;
        }

        .info-val {
            color: #1a2f45;
        }

        /* Table */
        table.sched {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.sched thead th {
            background: #1a3a5c;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 5px 7px;
            text-align: center;
            border: 1px solid #1a3a5c;
        }

        table.sched tbody td {
            font-size: 10px;
            padding: 4px 7px;
            border: 1px solid #c8dde8;
            text-align: right;
        }

        table.sched tbody td.tc {
            text-align: center;
        }

        table.sched tbody td.tl {
            text-align: left;
        }

        table.sched tbody tr:nth-child(odd) {
            background: #f2f8fb;
        }

        table.sched tbody tr:nth-child(even) {
            background: #fff;
        }

        table.sched tfoot td {
            font-weight: 700;
            font-size: 10.5px;
            background: #e0eef6;
            padding: 5px 7px;
            border: 1px solid #b8cfe0;
        }

        /* Status badges */
        .due {
            color: #c62828;
            font-weight: 600;
        }

        .paid {
            color: #2e7d32;
            font-weight: 600;
        }

        /* Signature block */
        .sig-block {
            display: flex;
            justify-content: space-between;
            margin-top: 28px;
        }

        .sig-line {
            text-align: center;
            width: 180px;
            border-top: 1px solid #1a3a5c;
            padding-top: 4px;
            font-size: 10px;
        }

        @media print {
            body {
                padding: 12px 18px;
            }

            .no-print {
                display: none !important;
            }

            .footer {
                position: fixed;
                bottom: 20px;
                left: 0;
                right: 0;
            }
        }
    </style>
</head>

<body>

    {{-- ── Report Header ───────────────────────────────────────────── --}}
    <div class="rpt-header">
        <div class="company-name">FOUR DESIGN (PVT.) LTD.</div>
        <div class="rpt-title">Advance / Loan Installment Schedule</div>
        <div class="rpt-sub">Loan Application No: <strong>{{ $loan->loan_app_no }}</strong>
            &nbsp;|&nbsp; Printed: {{ now()->format('d-M-Y H:i') }}</div>
    </div>
    <hr class="rpt-rule">

    {{-- ── Employee & Loan Info ───────────────────────────────────── --}}
    <div class="info-grid">
        <div class="info-row"><span class="info-label">Emp No:</span> <span class="info-val">{{ $loan->emp_no }}</span>
        </div>
        <div class="info-row"><span class="info-label">New Emp No:</span> <span
                class="info-val">{{ $loan->new_empno }}</span></div>
        <div class="info-row"><span class="info-label">Application Date:</span><span
                class="info-val">{{ $loan->application_date?->format('d-M-Y') }}</span></div>

        <div class="info-row"><span class="info-label">Employee Name:</span> <span
                class="info-val">{{ $loan->emp_name }}</span></div>
        <div class="info-row"><span class="info-label">Designation:</span> <span
                class="info-val">{{ $loan->des_name }}</span></div>
        <div class="info-row"><span class="info-label">Approved Date:</span> <span
                class="info-val">{{ $loan->loan_approved_date?->format('d-M-Y') }}</span></div>

        <div class="info-row"><span class="info-label">Department:</span> <span
                class="info-val">{{ $loan->dept_name }}</span></div>
        <div class="info-row"><span class="info-label">Section:</span> <span
                class="info-val">{{ $loan->section_name }}</span></div>
        <div class="info-row"><span class="info-label">Loan Type:</span> <span
                class="info-val">{{ $loan->loan_type }}</span></div>

        <div class="info-row"><span class="info-label">Gross Salary:</span> <span
                class="info-val">{{ number_format($loan->gross_amount, 2) }}</span></div>
        <div class="info-row"><span class="info-label">Sanction Amount:</span><span
                class="info-val">{{ number_format($loan->sanction_amount, 2) }}</span></div>
        <div class="info-row"><span class="info-label">Period (months):</span><span
                class="info-val">{{ $loan->period }}</span></div>

        <div class="info-row"><span class="info-label">Monthly Install:</span><span
                class="info-val">{{ number_format($loan->monthly_installment, 2) }}</span></div>
        <div class="info-row"><span class="info-label">First Install Date:</span><span
                class="info-val">{{ $loan->first_install_date?->format('d-M-Y') }}</span></div>
        <div class="info-row"><span class="info-label">Pre Balance:</span> <span
                class="info-val">{{ number_format($loan->pre_balance_amount, 2) }}</span></div>

        @if ($loan->ref_emp_no)
            <div class="info-row"><span class="info-label">Reference Emp:</span> <span
                    class="info-val">{{ $loan->ref_emp_no }} – {{ $loan->refference_name }}</span></div>
            <div class="info-row"><span class="info-label">Ref Designation:</span><span
                    class="info-val">{{ $loan->ref_des_name }}</span></div>
            <div></div>
        @endif
    </div>
    <hr class="rpt-rule">

    {{-- ── Installment Table ───────────────────────────────────────── --}}
    <table class="sched">
        <thead>
            <tr>
                <th>#</th>
                <th>Install No</th>
                <th>Install Date</th>
                <th>Install Amount</th>
                <th>Prin. Bal. BOM</th>
                <th>Prin. Bal. EOM</th>
                <th>Status</th>
                <th>Pay Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loan->details as $i => $d)
                <tr>
                    <td class="tc">{{ $i + 1 }}</td>
                    <td class="tc">{{ $d->install_no }}</td>
                    <td class="tc">{{ $d->install_date?->format('d-M-Y') }}</td>
                    <td>{{ number_format($d->install_amount, 2) }}</td>
                    <td>{{ number_format($d->pbbom, 2) }}</td>
                    <td>{{ number_format($d->pbeom, 2) }}</td>
                    <td class="tc {{ $d->status === 'Paid' ? 'paid' : 'due' }}">{{ $d->status }}</td>
                    <td class="tc">{{ $d->paydate?->format('d-M-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="tc" style="padding:8px;color:#888;">No installments generated.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Total:</td>
                <td>{{ number_format($loan->details->sum('install_amount'), 2) }}</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>


    {{-- ── Signature Block ─────────────────────────────────────────── --}}
    <footer class="footer">
        <div class="sig-block">
            <div class="sig-line">Employee Signature</div>
            <div class="sig-line">HR / Accounts</div>
            <div class="sig-line">Authorized Signatory</div>
        </div>
    </footer>

    {{-- Print Button (hidden on print) --}}
    <div class="no-print" style="text-align:center;margin-top:20px;">
        <button onclick="window.print()"
            style="padding:7px 22px;background:#1a3a5c;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:12px;">
            🖨 Print
        </button>
    </div>

</body>

</html>
