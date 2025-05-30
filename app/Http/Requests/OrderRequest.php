<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'paymentMethod' => 'required|in:COD,MoMo',
            'notes' => 'nullable|string|max:1000',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên người nhận.',
            'name.regex' => 'Tên chỉ được chứa chữ cái, khoảng trắng và dấu gạch ngang.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Phải có 10 chữ số và bắt đầu bằng số 0.',
            'email.email' => 'Email không hợp lệ.',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'paymentMethod.required' => 'Vui lòng chọn phương thức thanh toán.',
            'paymentMethod.in' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
