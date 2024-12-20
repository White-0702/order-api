<?php

namespace App\Services;

class OrderService
{
    public function processOrder(array $data)
    {
        // 執行檢查與格式轉換邏輯
        return [
            'order_id' => $data['id'],
            'bnb_name' => $data['name'],
            'price' => [
                'amount' => $data['price'],
                'currency' => $data['currency'],
            ],
            'address' => $data['address'],
        ];
    }
}
