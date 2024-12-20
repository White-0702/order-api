# Order API

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

