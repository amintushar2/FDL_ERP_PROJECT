{{-- resources/views/hrm/leave/_detail_row.blade.php
     Natural key: EMPNO + LEAVE_ID + LV_FROM  (no AUTO column used)
--}}
<tr class="detail-row" data-index="{{ $i }}">

    {{-- Natural composite key fields --}}
    <input type="hidden" name="details[{{ $i }}][empno]"    value="{{ $d->empno    ?? '' }}">
    <input type="hidden" name="details[{{ $i }}][leave_id]" value="{{ $d->leave_id ?? '' }}">
    <input type="hidden" name="details[{{ $i }}][lv_from_orig]" value="{{ $d->lv_from ?? '' }}">
    <input type="hidden" name="details[{{ $i }}][max_days]" value="{{ $d->max_days  ?? '' }}">

    {{-- Leave Type --}}
    <td>
        <select name="details[{{ $i }}][leave_name]" class="td-select leave-name-sel" required>
            <option value="">--</option>
            @foreach($leaveTypes as $lt)
            <option value="{{ $lt->leave_name }}"
                    data-leave-id="{{ $lt->leave_id }}"
                    data-max="{{ $lt->max_days }}"
                {{ isset($d) && $d->leave_name == $lt->leave_name ? 'selected' : '' }}>
                {{ $lt->leave_name }}
            </option>
            @endforeach
        </select>
    </td>

    {{-- From --}}
    <td style="min-width:105px">
        <div class="fp-wrap">
            <input type="text" name="details[{{ $i }}][lv_from]"
                   class="fp-date fp-lv-from"
                   value="{{ $d->lv_from ?? '' }}"
                   autocomplete="off" placeholder="yyyy-mm-dd">
        </div>
    </td>

    {{-- To --}}
    <td style="min-width:105px">
        <div class="fp-wrap">
            <input type="text" name="details[{{ $i }}][lv_to]"
                   class="fp-date fp-lv-to"
                   value="{{ $d->lv_to ?? '' }}"
                   autocomplete="off" placeholder="yyyy-mm-dd">
        </div>
    </td>

    {{-- Approve Days --}}
    <td style="min-width:64px">
        <input type="number" name="details[{{ $i }}][approve_days]"
               class="td-input approve-days"
               value="{{ $d->approve_days ?? '' }}"
               step="0.5" min="0" style="text-align:center;width:56px">
    </td>

    {{-- Application Date --}}
    <td style="min-width:105px">
        <div class="fp-wrap">
            <input type="text" name="details[{{ $i }}][application_date]"
                   class="fp-date fp-app-date"
                   value="{{ $d->application_date ?? '' }}"
                   autocomplete="off" placeholder="yyyy-mm-dd">
        </div>
    </td>

    {{-- Approve Date --}}
    <td style="min-width:105px">
        <div class="fp-wrap">
            <input type="text" name="details[{{ $i }}][approve_date]"
                   class="fp-date fp-app-date2"
                   value="{{ $d->approve_date ?? '' }}"
                   autocomplete="off" placeholder="yyyy-mm-dd">
        </div>
    </td>

    {{-- APPROVE_BY --}}
    <td style="min-width:120px">
        <input type="text" name="details[{{ $i }}][approve_by]"
               class="td-input"
               value="{{ $d->approve_by ?? '' }}"
               placeholder="Approved by">
    </td>

    {{-- PRE_BALANCE --}}
    <td style="min-width:56px">
        <input type="number" name="details[{{ $i }}][pre_balance]"
               class="td-input pre-bal"
               value="{{ $d->pre_balance ?? '' }}"
               step="0.5" readonly
               style="text-align:center;width:52px;color:#666">
    </td>

    {{-- BALANCE = PRE_BALANCE - APPROVE_DAYS --}}
    <td style="min-width:56px">
        <input type="number" name="details[{{ $i }}][balance]"
               class="td-input calc new-bal"
               value="{{ $d->balance ?? '' }}"
               step="0.5" readonly
               style="text-align:center;width:52px">
    </td>

    {{-- INFORMATION --}}
    <td style="min-width:100px">
        <input type="text" name="details[{{ $i }}][information]"
               class="td-input"
               value="{{ $d->information ?? '' }}"
               placeholder="Info">
    </td>

    {{-- REMAX --}}
    <td style="min-width:110px">
        <input type="text" name="details[{{ $i }}][remax]"
               class="td-input"
               value="{{ $d->remax ?? '' }}"
               placeholder="Remarks">
    </td>

    {{-- Actions --}}
    <td class="text-nowrap" style="padding:4px 8px;min-width:105px">
        @if(isset($d) && !empty($d->empno) && !empty($d->leave_id) && !empty($d->lv_from))
        <a href="{{ route('leave.slip', [$d->empno, $d->leave_id, $d->lv_from]) }}"
           target="_blank"
           class="hrm-btn-success-outline me-1"
           style="display:inline-flex;align-items:center;gap:3px;padding:3px 8px;font-size:11.5px"
           title="Print Leave Slip">
            <i class="bi bi-printer"></i> Slip
        </a>
        @endif
        <button type="button"
                class="hrm-btn-danger-outline remove-detail"
                data-empno="{{ $d->empno ?? '' }}"
                data-leave-id="{{ $d->leave_id ?? '' }}"
                data-lv-from="{{ $d->lv_from ?? '' }}"
                style="padding:3px 7px;font-size:11.5px"
                title="Delete row">
            <i class="bi bi-trash3"></i>
        </button>
    </td>
</tr>
