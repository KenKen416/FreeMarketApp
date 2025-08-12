<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:20'],
            'post_code' => ['required', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,', 'max:2048'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',
            'post_code.required' => '郵便番号は必須です。',
            'post_code.size' => '郵便番号はハイフンを含めて8文字でなければなりません。',
            'post_code.regex' => '郵便番号の形式が正しくありません。例: 123-4567',
            'address.required' => '住所は必須です。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
            'image.image' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像の形式はjpegまたはpngでなければなりません。',
            'image.max' => '画像のサイズは2MB以下でなければなりません。',
        ];
    }
}
