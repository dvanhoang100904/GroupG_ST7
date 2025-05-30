<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveFromCartRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Bạn chưa chọn sản phẩm để xóa.',
            'product_id.integer' => 'Mã sản phẩm không hợp lệ.',
            'product_id.exists' => 'Sản phẩm không tồn tại trong hệ thống.',
        ];
    }
}
