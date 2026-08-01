<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        return [
            // Master block
            'PUR_ORDER_DATE' => ['required', 'date', 'before_or_equal:today'],
            'SUPPLIER_NO'    => ['required', 'string'],
            'CURRENCY_RATE'  => ['nullable', 'numeric', 'min:0'],
            'PAY_TERMS'      => ['nullable', 'string', 'max:100'],
            'ORDER_BY'       => ['nullable', 'string', 'max:100'],
            'ORDER_BY_NUM'   => ['nullable', 'string', 'max:50'],
            'PUR_REF_NO'     => ['nullable', 'string', 'max:100'],
            'PUR_REQU_NO'    => ['nullable', 'string', 'max:100'],
            'PUR_TYPE'       => ['nullable', 'string', 'max:50'],
 
            // Style block (detail rows)
            'styles'                    => ['nullable', 'array'],
            'styles.*.PO_NUMBER_ID'     => ['required_with:styles', 'string'],
            'styles.*.PO_NUMBER'        => ['nullable', 'string'],
            'styles.*.ORDER_NO'         => ['nullable', 'string'],
            'styles.*.PO_QTY'           => ['nullable', 'numeric', 'min:0'],
 
            // Details block (line items)
            'details'                       => ['nullable', 'array'],
            'details.*.PO_NUMBER_ID'        => ['required_with:details', 'string'],
            'details.*.ITEM_ID'             => ['required_with:details', 'string'],
            'details.*.QUANTITY'            => ['nullable', 'numeric', 'min:0'],
            'details.*.PERCENTAGE'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'details.*.ITEM_RATE'           => ['nullable', 'numeric', 'min:0'],
            'details.*.CURRENCY_CODE'       => ['nullable', 'string', 'max:10'],
            'details.*.REMARKS'             => ['nullable', 'string', 'max:500'],
            'details.*.ITM_UNIT'            => ['nullable', 'string', 'max:50'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'PUR_ORDER_DATE.before_or_equal' => 'Purchase order date cannot be in the future.',
            'SUPPLIER_NO.required'           => 'Supplier is required.',
            'details.*.ITEM_ID.required_with' => 'Item ID is required for each detail line.',
            'details.*.PO_NUMBER_ID.required_with' => 'PO Number ID is required for each detail line.',
        ];
    }
}
