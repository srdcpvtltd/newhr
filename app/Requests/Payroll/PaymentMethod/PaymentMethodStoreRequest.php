<?php

namespace App\Requests\Payroll\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodStoreRequest extends FormRequest
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
    public function rules()
    {

        $rules = [
            'name' => ['required', 'array', 'min:1'],
            'name.*' => ['required', 'string', 'max:500'],
        ];

        return $rules;

    }
}
