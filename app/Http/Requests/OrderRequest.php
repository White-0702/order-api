<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|regex:/^[A-Z0-9]+$/',
            'name' => 'required|string|regex:/^[A-Z]/',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric|max:2000',
            'currency' => 'required|in:TWD,USD,JPY',
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'ID 是必填欄位。',
            'id.regex' => 'ID 必須為大寫字母與數字組成。',
            'name.required' => '名稱是必填欄位。',
            'name.regex' => '名稱的第一個字必須是大寫英文字母。',
            'price.max' => '價格不可超過 2000。',
            'currency.in' => '幣別必須為 TWD, USD 或 JPY。',
        ];
    }

}
