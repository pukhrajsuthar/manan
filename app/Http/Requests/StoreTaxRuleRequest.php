<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRuleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100|unique:tax_rules,name',
            'type'        => 'required|in:GST,Exempt',
            'cgst_rate'   => 'required|numeric|min:0|max:50',
            'sgst_rate'   => 'required|numeric|min:0|max:50',
            'igst_rate'   => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $data = $this->validated();
            if (isset($data['cgst_rate'], $data['sgst_rate']) && $data['cgst_rate'] !== $data['sgst_rate']) {
                $validator->errors()->add('sgst_rate', 'CGST and SGST rates must be equal (each is half of total GST).');
            }
        });
    }
}
