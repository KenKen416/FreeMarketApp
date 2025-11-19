<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        // コントローラで権限を確認するため true
        return true;
    }

    public function rules()
    {
        return [
            'purchase_id' => 'required|integer|exists:purchases,id',
            'score' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'purchase_id.required' => '不正なリクエストです',
            'score.required' => '評価を選択してください',
            'score.integer' => '評価は整数で指定してください',
            'score.min' => '評価は1以上で指定してください',
            'score.max' => '評価は5以下で指定してください',
        ];
    }
}
