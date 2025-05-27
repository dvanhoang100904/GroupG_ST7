<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Bạn chưa chọn sản phẩm.',
            'product_id.integer' => 'Mã sản phẩm không hợp lệ.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.required' => 'Bạn chưa nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
        ];
    }
}
