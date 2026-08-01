<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherDetailRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'ACCOUNT_HEAD_ID' => 'required|string|max:20',
            'COMPANY_ID'      => 'required|string|max:10',
            'DEBIT_AMOUNT'    => 'nullable|numeric|min:0',
            'CREDIT_AMOUNT'   => 'nullable|numeric|min:0',
            'USD_DEBIT'       => 'nullable|numeric|min:0',
            'USD_CREDIT'      => 'nullable|numeric|min:0',
            'REFERENCE_NO'    => 'nullable|string|max:100',
            'CREDITOR_CODE'   => 'nullable|string|max:20',
            'DETOR_CODE'      => 'nullable|string|max:20',
            'EMP_CODE'        => 'nullable|string|max:20',
            'NEW_EMP_CODE'    => 'nullable|string|max:20',
            'ASSET_CODE'      => 'nullable|string|max:20',
            'COST_CENTER'     => 'nullable|string|max:20',
            'LC_NO'           => 'nullable|string|max:50',
            'MASTER_LC_NO'    => 'nullable|string|max:50',
            'CHE_RECP_NO'     => 'nullable|string|max:50',
            'CHEQUE_DATE'     => 'nullable|date',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $debit  = (float) $this->input('DEBIT_AMOUNT',  0);
            $credit = (float) $this->input('CREDIT_AMOUNT', 0);

            if ($debit === 0.0 && $credit === 0.0) {
                $v->errors()->add('DEBIT_AMOUNT', 'Either Debit or Credit amount must be entered.');
            }

            if ($debit > 0 && $credit > 0) {
                $v->errors()->add('DEBIT_AMOUNT', 'A line cannot have both Debit and Credit amounts.');
            }
        });
    }
}
