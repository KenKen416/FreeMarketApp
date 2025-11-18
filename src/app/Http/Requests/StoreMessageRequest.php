<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize()
    {
        // 認可チェックはコントローラ側でも行うのでここは true
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|string|max:400',
            'image' => 'nullable|file|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '本文を入力してください',
            'body.string' => '本文は文字列で入力してください',
            'body.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.file' => '画像はファイルでアップロードしてください',
        ];
    }
}
