<?php

namespace App\Requests\Payroll\Payslip;

use Illuminate\Foundation\Http\FormRequest;

class PayslipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'year' => ['required'],
            'month' => ['required'],
        ];
    }
}
