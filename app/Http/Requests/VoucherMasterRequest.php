<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Migrated from:
 *   - null_notified('AFM_VOUCHER_MASTER.VOUCHER_TYPE')
 *   - null_notified('AFM_VOUCHER_MASTER.VOUCHER_DATE')
 *   - WHEN-VALIDATE-ITEM on VOUCHER_DATE
 *   - PRE-INSERT checks in Oracle Forms
 */
class VoucherMasterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'VOUCHER_TYPE'   => 'required|string|max:10',
            'VOUCHER_DATE'   => 'required|date',
            'COMPANY_ID'     => 'required|string|max:10',
            'RECEIVE_PAY_TO' => 'nullable|string|max:200',
            'CASH_ACCOUNT'   => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'VOUCHER_TYPE.required' => 'Voucher Type is required.',
            'VOUCHER_DATE.required' => 'Voucher Date is required.',
            'COMPANY_ID.required'   => 'Company is required.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // CASHP requires CASH_ACCOUNT
        if ($this->input('VOUCHER_TYPE') === 'CASHP' && !$this->input('CASH_ACCOUNT')) {
            $this->merge(['_cash_account_required' => true]);
        }
    }
}
