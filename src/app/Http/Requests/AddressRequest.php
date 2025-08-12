<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        return [
            'post_code' => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'post_code.required' => '郵便番号は必須です。',
            'post_code.string' => '郵便番号は文字列でなければなりません。',
            'post_code.size' => '郵便番号はハイフンを含めて8文字で入力してください。',
            'post_code.regex' => '郵便番号の形式が正しくありません。例: 123-4567',
            'address.required' => '住所は必須です。',
            'address.string' => '住所は文字列でなければなりません。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building.string' => '建物名は文字列でなければなりません。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
