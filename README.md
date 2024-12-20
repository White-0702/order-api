# 資料庫測驗
### 題目一
請寫出一條查詢語句 (SQL)，列出在 2023 年 5 月下訂的訂單，使用台幣付款且5月總金額最
多的前 10 筆的旅宿 ID (bnb_id), 旅宿名稱 (bnb_name), 5 月總金額 (may_amount)

``` SQL
SELECT bnb_id,bnb_name,SUM(amouont) as may_amount 
FROM bnbs 
JOIN orders ON bnbs.id = orders.bnb_id 
WHERE currency = 'TWD' AND (created_at BETWEEN '2023-05-01' AND '2023-05-31')
GROUP BY 
    bnb_id, bnb_name
ORDER BY 
    may_amount DESC
LIMIT 10;
```
### 題目二
在題目一的執行下，我們發現 SQL 執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優
化的方式

執行EXPLAIN確認索引跟JOIN的狀況，另外確認數據量大小，確保沒有處理過多的數據。
若SQL沒有索引則新增索引
``` SQL
CREATE INDEX idx_currency_created_at ON orders (currency, created_at);
CREATE INDEX idx_bnb_id ON orders (bnb_id);
```

如果JOIN的資料過多，使用子查詢先做資料排除再JOIN

```SQL
SELECT bnb_id, bnb_name, SUM(amount) AS may_amount
FROM 
    (SELECT * FROM orders WHERE currency = 'TWD' AND created_at BETWEEN '2023-05-01' AND '2023-05-31') filtered_orders
JOIN 
    bnbs 
ON 
    filtered_orders.bnb_id = bnbs.id
GROUP BY 
    bnb_id, bnb_name
ORDER BY 
    may_amount DESC
LIMIT 10;
```
如果未來有需要頻繁執行，可以建立view

---
# API 實作測驗
## Order API

一個基於 Laravel 的 API，提供訂單格式檢查與轉換的功能，涵蓋表單驗證、業務邏輯處理以及單元測試，並使用 Docker 環境部署。

---

## 功能特性

- **訂單格式驗證**：使用 Laravel FormRequest 驗證輸入資料。
- **訂單格式轉換**：將符合規範的訂單進行格式化並返回結果。
- **錯誤處理**：針對不同場景返回適當的 HTTP 狀態碼和錯誤訊息。
- **單元測試**：完整覆蓋成功與失敗的案例。
- **Docker 化部署**：使用 Docker 和 Docker Compose 提供一致的開發與執行環境。

---

## API 概要

### **Endpoint**
`POST /api/orders`

### **輸入範例**
```json
{
    "id": "A0000001",
    "name": "Melody Holiday Inn",
    "address": {
        "city": "taipei-city",
        "district": "da-an-district",
        "street": "fuxing-south-road"
    },
    "price": 2050,
    "currency": "TWD"
}
```

### **輸出範例**
#### 成功時
```json
{
    "order_id": "A0000001",
    "bnb_name": "Melody Holiday Inn",
    "price": {
        "amount": 2050,
        "currency": "TWD"
    },
    "address": {
        "city": "taipei-city",
        "district": "da-an-district",
        "street": "fuxing-south-road"
    }
}
```
#### 驗證錯誤時
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["名稱的第一個字必須是大寫英文字母。"],
        "price": ["價格不可超過 2000。"],
        "currency": ["幣別必須為 TWD, USD 或 JPY。"]
    }
}
```
---

## 環境需求

- PHP >= 8.0
- Composer
- Docker & Docker Compose

---

## 安裝與執行

1. 克隆此專案：
   ```bash
   git clone https://github.com/White-0702/order-api.git
   cd order-api
   ```

2. 使用 Composer 安裝依賴：
   ```bash
   composer install
   ```

3. 啟動 Docker 環境：
   ```bash
   ./vendor/bin/sail up -d
   ```

4. 執行資料庫遷移（若有需要）：
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

---

## 測試

1. 執行單元測試：
   ```bash
   ./vendor/bin/sail test
   ```

2. 測試覆蓋案例包括：
   - 驗證成功：有效的訂單資料。
   - 驗證失敗：針對以下場景：
     - `name` 必須大寫開頭。
     - `price` 不可超過 2000。
     - `currency` 必須為 `TWD`, `USD`, 或 `JPY`。

