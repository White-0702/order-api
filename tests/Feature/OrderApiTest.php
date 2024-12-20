<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    public function test_valid_order_request()
    {
        $payload = [
            'id' => 'A0000001',
            'name' => 'Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 1500,
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'order_id' => 'A0000001',
                     'bnb_name' => 'Holiday Inn',
                     'price' => [
                         'amount' => 1500,
                         'currency' => 'TWD',
                     ],
                     'address' => [
                         'city' => 'taipei-city',
                         'district' => 'da-an-district',
                         'street' => 'fuxing-south-road',
                     ],
                 ]);
    }

    public function test_invalid_order_request()
    {
        $payload = [
            'id' => 'invalid_id', // 無效的 id
            'name' => 'holiday inn', // 非大寫開頭
            'address' => [
                'city' => '',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 2500, // 價格超過 2000
            'currency' => 'EUR', // 不支援的幣別
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(422); // 驗證錯誤
    }
}
