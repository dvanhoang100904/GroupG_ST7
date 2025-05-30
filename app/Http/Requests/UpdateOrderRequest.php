<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'status' => 'required|in:chờ_xử_lý,đang_xử_lý,đang_vận_chuyển,đã_giao_hàng,đã_hủy',
            'payment_status' => 'required|in:đang_chờ,đã_thanh_toán,thất_bại,đã_hoàn_tiền',
            'payment_method' => 'required|in:COD,MoMo',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'payment_status.required' => 'Vui lòng chọn trạng thái thanh toán.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
