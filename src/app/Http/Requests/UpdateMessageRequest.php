<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    public function authorize()
    {
        // コントローラで権限チェックするため true を返す
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
            'body.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
