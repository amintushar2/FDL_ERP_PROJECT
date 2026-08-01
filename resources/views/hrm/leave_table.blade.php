@foreach($getLeaveDetails as $getLeaveDetails)
<tr>
    <td>{{ $getLeaveDetails->leave_name }}</td>
    <td>{{ $getLeaveDetails->max_days }}</td>
    <td>{{ $getLeaveDetails->pre_balance }}</td>
    <td>{{Illuminate\Support\Carbon::parse($getLeaveDetails->application_date)->format('Y-m-d')  }}</td>
    <td>{{ Illuminate\Support\Carbon::parse($getLeaveDetails->approve_date)->format('Y-m-d') }}</td>
    <td>{{ $getLeaveDetails->approve_days }}</td>
    <td>{{ Illuminate\Support\Carbon::parse($getLeaveDetails->lv_from)->format('Y-m-d') }}</td>
    <td>{{ Illuminate\Support\Carbon::parse($getLeaveDetails->lv_to)->format('Y-m-d') }}</td>
    <td>{{ $getLeaveDetails->approve_days }}</td>
    <td>{{ $getLeaveDetails->approve_by }}</td>
    <td>{{ $getLeaveDetails->remax }}</td>

    <td><button type="button" id="leaveDel" class="btn btn-danger" data-id='{{ $getLeaveDetails->lv_sl}}'data-first='{{ $getLeaveDetails->empno}}'data-second='{{ $getLeaveDetails->year}}'><i class="bi bi-trash3-fill"></i></button></td>
</tr>
@endforeach