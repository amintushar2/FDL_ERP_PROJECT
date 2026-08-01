<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempEmpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) session('LoggedUser');
    }

    public function rules(): array
    {
        return [
            /* ── EMP_PERSONAL ── */
            'first_name'             => 'required|string|max:100',
            'last_name'              => 'nullable|string|max:100',
            'middle_name'            => 'nullable|string|max:100',
            'company_name'           => 'required|string|max:100',
            'card_no'                => 'nullable|string|max:50',
            'dob'                    => 'nullable|date',
            'sex'                    => 'nullable|in:Male,Female',
            'status'                 => 'nullable|in:Active,Inactive',
            'permanent_empno'        => 'nullable|string|max:20',

            /* ── EMP_OFFICIAL (nested) ── */
            'official.company_name'  => 'nullable|string|max:100',
            'official.dept_name'     => 'nullable|string|max:100',
            'official.section_name'  => 'nullable|string|max:100',
            'official.emp_type'      => 'nullable|string|max:50',
            'official.floor_name'    => 'nullable|string|max:100',
            'official.ot_ent'        => 'nullable|in:Yes,No',
            'official.gross'         => 'nullable|numeric|min:0',
            'official.joining_date'  => 'nullable|date',
            'official.des_name'      => 'nullable|string|max:100',
            'official.shift_name'    => 'nullable|string|max:100',
            'official.weekly_off'    => 'nullable|string|max:30',
            'official.line'          => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'  => 'First Name is required.',
            'last_name.required'   => 'Last Name is required.',
            'company_name.required'=> 'Company Name is required.',
        ];
    }
}
