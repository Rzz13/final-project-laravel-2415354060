# ERP API - Dokumentasi Project

<!-- PROJECT LEAD -->

- **Nama**: Muhamad Rizqi Assabiquunal Awwalun
- **Nim**: 2415354060
- **Prodi**: TRPL

## 📋 Deskripsi Project

ERP API adalah aplikasi manajemen berbasis Laravel yang menyediakan sistem untuk mengelola **Customers**, **Services**, dan **Subscriptions**. Aplikasi ini menyediakan REST API lengkap dan interface web untuk operasi CRUD (Create, Read, Update, Delete).

## 🛠️ Tech Stack

- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL/PostgreSQL
- **Frontend**: Blade Templates, TailwindCSS, Boxicons

## 📁 Struktur Project

```
erp-api/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/
│   │   │   ├── CustomerController.php
│   │   │   ├── ServiceController.php
│   │   │   └── SubscriptionController.php
│   │   ├── CustomerViewController.php
│   │   ├── ServiceViewController.php
│   │   └── SubscriptionViewController.php
│   └── Models/
│       ├── Customer.php
│       ├── Service.php
│       └── Subscription.php
├── resources/views/
│   ├── app.blade.php
│   ├── customer.blade.php
│   ├── services.blade.php
│   ├── subcription.blade.php
│   ├── function.blade.php
│   └── modal.blade.php
└── routes/
    ├── api.php
    └── web.php
```

---

# 🔌 Dokumentasi API

Base URL: `/api`

## 📦 Response Format

Semua response API menggunakan format JSON standar:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

---

## 👥 Customer API

### 1. Get All Customers
**Endpoint**: `GET /api/customers`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customers retrieved successfully",
  "data": [
    {
      "id": 1,
      "customer_id": "CUST001",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "08123456789",
      "address": "Jakarta",
      "status": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

### 2. Create Customer
**Endpoint**: `POST /api/customers`

**Request Body**:
```json
{
  "customer_id": "CUST001",
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "08123456789",
  "address": "Jakarta",
  "status": true
}
```

**Validation Rules**:
- `customer_id`: required, string, unique
- `name`: required, string
- `email`: nullable, string, email, unique
- `phone`: nullable, string
- `address`: nullable, string
- `status`: nullable, boolean (default: true)

**Response**: `201 Created`
```json
{
  "success": true,
  "message": "Customer created successfully",
  "data": { ... }
}
```

---

### 3. Get Customer by ID
**Endpoint**: `GET /api/customers/{id}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customer retrieved successfully",
  "data": { ... }
}
```

**Error Response**: `404 Not Found`
```json
{
  "success": false,
  "message": "Customer not found",
  "errors": []
}
```

---

### 4. Update Customer
**Endpoint**: `PUT/PATCH /api/customers/{id}`

**Request Body**:
```json
{
  "customer_id": "CUST001",
  "name": "John Doe Updated",
  "email": "john.updated@example.com",
  "phone": "08123456789",
  "address": "Jakarta Selatan",
  "status": true
}
```

**Validation Rules**:
- `customer_id`: sometimes, string, unique (except current record)
- `name`: sometimes, string
- `email`: nullable, string, email, unique (except current record)
- `phone`: nullable, string
- `address`: nullable, string
- `status`: nullable, boolean

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customer updated successfully",
  "data": { ... }
}
```

---

### 5. Delete Customer
**Endpoint**: `DELETE /api/customers/{id}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customer deleted successfully",
  "data": null
}
```

**Error Response (Has Subscriptions)**: `422 Unprocessable Entity`
```json
{
  "success": false,
  "message": "Customer cannot be deleted because it has subscriptions",
  "errors": []
}
```

**Business Rule**: Customer tidak dapat dihapus jika memiliki data subscription yang terkait.

---

### 6. Get Customers by Status
**Endpoint**: `GET /api/customers/status?status={active|inactive}`

**Query Parameters**:
- `status`: required, enum (active, inactive)

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customers with status 'active' retrieved successfully",
  "data": [ ... ]
}
```

**Error Response**: `400 Bad Request`
```json
{
  "success": false,
  "message": "Status parameter is required"
}
```

**Error Response**: `422 Unprocessable Entity`
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "status": ["The selected status is invalid."]
  }
}
```

---

### 7. Change Customer Status
**Endpoint**: `PATCH /api/customers/{id}/change-status?activate={true|false}`

**Query Parameters**:
- `activate`: required, string (true/false)

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Customer status updated successfully",
  "data": { ... }
}
```

---

## 🛎️ Service API

### 1. Get All Services
**Endpoint**: `GET /api/services`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Services retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Internet 100 Mbps",
      "price": 500000,
      "description": "Paket internet unlimited",
      "status": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

### 2. Create Service
**Endpoint**: `POST /api/services`

**Request Body**:
```json
{
  "name": "Internet 100 Mbps",
  "price": 500000,
  "description": "Paket internet unlimited",
  "status": true
}
```

**Validation Rules**:
- `name`: required, string, max:255
- `price`: required, integer, min:0
- `description`: nullable, string
- `status`: boolean

**Response**: `201 Created`
```json
{
  "success": true,
  "message": "Service created successfully",
  "data": { ... }
}
```

---

### 3. Get Service by ID
**Endpoint**: `GET /api/services/{id}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Service retrieved successfully",
  "data": { ... }
}
```

**Error Response**: `404 Not Found`
```json
{
  "success": false,
  "message": "Service not found"
}
```

---

### 4. Update Service
**Endpoint**: `PUT/PATCH /api/services/{id}`

**Request Body**:
```json
{
  "name": "Internet 200 Mbps",
  "price": 750000,
  "description": "Paket internet unlimited upgraded",
  "status": true
}
```

**Validation Rules**:
- `name`: sometimes, string
- `price`: sometimes, integer, min:0
- `description`: string, nullable
- `status`: nullable, boolean

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Service updated successfully",
  "data": { ... }
}
```

---

### 5. Delete Service
**Endpoint**: `DELETE /api/services/{id}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Service deleted successfully"
}
```

**Error Response (Has Active Subscriptions)**: `400 Bad Request`
```json
{
  "success": false,
  "message": "Cannot delete service with active subscriptions"
}
```

**Business Rule**: Service tidak dapat dihapus jika memiliki subscription yang aktif.

---

### 6. Get Services by Status
**Endpoint**: `GET /api/services/status?status={active|inactive}`

**Query Parameters**:
- `status`: required, enum (active, inactive)

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Services with status 'active' retrieved successfully",
  "data": [ ... ]
}
```

**Error Response**: `400 Bad Request`
```json
{
  "success": false,
  "message": "Status parameter is required"
}
```

---

### 7. Change Service Status
**Endpoint**: `PATCH /api/services/{id}/change-status?status={true|false}`

**Query Parameters**:
- `status`: required, string (true/false)

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Service status updated successfully",
  "data": { ... }
}
```

---

## 📅 Subscription API

### 1. Get All Subscriptions
**Endpoint**: `GET /api/subscriptions`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Subscriptions retrieved successfully",
  "data": [
    {
      "id": 1,
      "customer_id": 1,
      "service_id": 1,
      "start_date": "2024-01-01",
      "end_date": "2024-12-31",
      "status": "active",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

**Note**: Response tidak include relasi customer dan service (hidden).

---

### 2. Create Subscription
**Endpoint**: `POST /api/subscriptions`

**Request Body**:
```json
{
  "customer_id": 1,
  "service_id": 1,
  "start_date": "2024-01-01",
  "end_date": "2024-12-31",
  "status": "active"
}
```

**Validation Rules**:
- `customer_id`: required, integer, exists:customers,id
- `service_id`: required, integer, exists:services,id
- `start_date`: required, date
- `end_date`: required, date, after_or_equal:start_date
- `status`: required, string, in:active,inactive,trial,isolir,dismantle

**Response**: `201 Created`
```json
{
  "success": true,
  "message": "Subscription created successfully",
  "data": {
    "id": 1,
    "customer_id": 1,
    "service_id": 1,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "status": "active",
    "customer": { ... },
    "service": { ... }
  }
}
```

---

### 3. Get Subscription by ID
**Endpoint**: `GET /api/subscriptions/{id}`

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Subscription retrieved successfully",
  "data": { ... }
}
```

**Error Response**: `404 Not Found`
```json
{
  "success": false,
  "message": "Subscription not found",
  "errors": []
}
```

---

### 4. Get Subscriptions by Status
**Endpoint**: `GET /api/subscriptions/status?status={active|inactive|trial|isolir|dismantle}`

**Query Parameters**:
- `status`: required, enum (active, inactive, trial, isolir, dismantle)

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Subscriptions with status 'active' retrieved successfully",
  "data": [ ... ]
}
```

**Error Response**: `422 Unprocessable Entity`
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "status": ["The selected status is invalid."]
  }
}
```

---

### 5. Change Subscription Status
**Endpoint**: `PATCH /api/subscriptions/{id}/change-status`

**Request Body**:
```json
{
  "status": "active"
}
```

**Validation Rules**:
- `status`: required, string, in:active,inactive,trial,isolir,dismantle

**Response**: `200 OK`
```json
{
  "success": true,
  "message": "Subscription status updated successfully",
  "data": { ... }
}
```

**Error Response (Dismantle Status)**: `400 Bad Request`
```json
{
  "success": false,
  "message": "Cannot change status from dismantle to other status",
  "errors": []
}
```

**Business Rule**: Subscription dengan status "dismantle" tidak dapat diubah ke status lain.

---

# 🌐 Dokumentasi Web Views

Base URL: `/`

## Web Routes

### 1. Home Page
**Route**: `GET /`

**Redirect**: Redirect ke `/customers`

---

### 2. Customers Page
**Route**: `GET /customers`

**Controller**: `CustomerViewController@index`

**View**: `resources/views/customer.blade.php`

**Deskripsi**: Halaman untuk mengelola data customers dengan fitur:
- Menampilkan tabel list customers
- Add customer (modal form)
- Edit customer (modal form)
- Delete customer
- Activate/Deactivate customer status

**Data yang ditampilkan**:
- Customer ID
- Customer Name
- Email
- Address
- Status (Active/Inactive badge)
- Action buttons (Activate/Deactivate, Edit, Delete)

**Form Fields**:
- Customer ID (required)
- Customer Name (required)
- Email (optional)
- Phone (optional)
- Address (optional)
- Status (dropdown: Active/Inactive)

---

### 3. Services Page
**Route**: `GET /services`

**Controller**: `ServiceViewController@index`

**View**: `resources/views/services.blade.php`

**Deskripsi**: Halaman untuk mengelola data services dengan fitur:
- Menampilkan tabel list services
- Add service (modal form)
- Edit service (modal form)
- Delete service
- Activate/Deactivate service status

**Data yang ditampilkan**:
- Service Name
- Price (formatted: Rp xxx.xxx,00)
- Status (Active/Inactive badge)
- Action buttons (Activate/Deactivate, Edit, Delete)

**Form Fields**:
- Service Name (required)
- Price (required, number, min: 0)
- Description (optional, textarea)
- Status (dropdown: Active/Inactive)

---

### 4. Subscriptions Page
**Route**: `GET /subscriptions`

**Controller**: `SubscriptionViewController@index`

**View**: `resources/views/subcription.blade.php`

**Deskripsi**: Halaman untuk mengelola data subscriptions dengan fitur:
- Menampilkan tabel list subscriptions dengan relasi customer & service
- Add subscription (modal form)
- Change subscription status (multiple status options)
- Status badge dengan warna berbeda per status

**Data yang ditampilkan**:
- Customer Name (dari relasi)
- Service Name (dari relasi)
- Service Period (formatted: DD MMM YYYY – DD MMM YYYY)
- Status (badge dengan warna: Active, Trial, Isolir, Dismantle, Inactive)
- Action buttons (status change options)

**Form Fields**:
- Customer (dropdown, hanya customer dengan status active)
- Service (dropdown, hanya service dengan status active)
- Start Date (date picker)
- End Date (date picker, must be >= start_date)
- Status (dropdown: Active, Trial, Isolir, Dismantle, Inactive)

**Status Options**:
- **Active** (hijau): Subscription aktif
- **Trial** (kuning): Masa percobaan
- **Isolir** (merah): Subscription di-isolir
- **Dismantle** (abu-abu): Subscription dibongkar (final state)
- **Inactive** (abu-abu): Subscription tidak aktif

**Business Rule**: 
- Subscription dengan status "dismantle" tidak menampilkan action buttons (tidak bisa diubah lagi)
- Action dropdown hanya menampilkan status yang berbeda dari status saat ini

---

### 5. Users Page
**Route**: `GET /users`

**Status**: `404 Not Found`

**Deskripsi**: Route ini sengaja di-abort untuk menunjukkan halaman tidak tersedia.

---

### 6. Logout
**Route**: `POST /logout`

**Action**: Redirect ke `/`

---

# 🗄️ Database Models & Relationships

## Customer Model

**Table**: `customers`

**Fillable Fields**:
- `customer_id` (string, unique)
- `name` (string)
- `email` (string, nullable, unique)
- `phone` (string, nullable)
- `address` (string, nullable)
- `status` (boolean)

**Casts**:
- `status`: boolean

**Relationships**:
- `subscriptions()`: hasMany → Subscription

**Business Logic**:
- Customer tidak dapat dihapus jika memiliki subscriptions

---

## Service Model

**Table**: `services`

**Fillable Fields**:
- `name` (string)
- `price` (integer)
- `description` (string, nullable)
- `status` (boolean)

**Casts**:
- `status`: boolean
- `price`: integer

**Relationships**:
- `subscriptions()`: hasMany → Subscription

**Business Logic**:
- Service tidak dapat dihapus jika memiliki subscriptions aktif

---

## Subscription Model

**Table**: `subscriptions`

**Fillable Fields**:
- `customer_id` (integer, foreign key)
- `service_id` (integer, foreign key)
- `start_date` (date)
- `end_date` (date)
- `status` (string: active, inactive, trial, isolir, dismantle)

**Casts**:
- `start_date`: date
- `end_date`: date

**Relationships**:
- `customer()`: belongsTo → Customer
- `service()`: belongsTo → Service

**Business Logic**:
- Status "dismantle" adalah final state, tidak bisa diubah ke status lain
- End date harus >= start date

---

# 📊 Database Schema

## Customers Table
```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NULL,
    phone VARCHAR(255) NULL,
    address TEXT NULL,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Services Table
```sql
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price INTEGER NOT NULL,
    description TEXT NULL,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Subscriptions Table
```sql
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);
```

---

# 🔐 Business Rules Summary

## Customer
1. ✅ Customer ID harus unique
2. ✅ Email harus unique (jika diisi)
3. ✅ Status default adalah `true` (active)
4. ❌ Customer tidak dapat dihapus jika memiliki subscriptions

## Service
1. ✅ Price harus >= 0
2. ✅ Status default adalah `true` (active)
3. ❌ Service tidak dapat dihapus jika memiliki subscriptions aktif

## Subscription
1. ✅ Customer ID dan Service ID harus exist di database
2. ✅ End date harus >= start date
3. ✅ Status hanya boleh: active, inactive, trial, isolir, dismantle
4. ❌ Status "dismantle" tidak dapat diubah ke status lain (final state)
5. ✅ Hanya customer dan service dengan status active yang bisa dipilih saat create subscription (di web view)

---

# 🎨 Frontend Features

## Modal System
- Dynamic modal untuk Add/Edit data
- Form validation
- AJAX submission ke API endpoints
- Success/Error notifications

## Action Buttons
- Dropdown menu untuk multiple actions
- Conditional actions berdasarkan status
- Danger actions (delete, dismantle) dengan styling berbeda
- Icon-based actions menggunakan Boxicons

## Status Badges
- Color-coded badges untuk visual feedback
- Green: Active
- Yellow: Trial
- Red: Isolir/Inactive
- Gray: Dismantle/Inactive

## Table Features
- Responsive table design
- Hover effects
- Empty state messages
- Formatted data display (currency, dates)

---

# 🚀 Installation & Setup

## Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

## Installation Steps

1. Clone repository
```bash
git clone <repository-url>
cd erp-api
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_api
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations
```bash
php artisan migrate
```

6. Build assets
```bash
npm run build
```

7. Start development server
```bash
php artisan serve
```

8. Access application
- Web: http://localhost:8000
- API: http://localhost:8000/api

---

# 📝 API Testing Examples

## Using cURL

### Create Customer
```bash
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": "CUST001",
    "name": "John Doe",
    "email": "john@example.com",
    "status": true
  }'
```

### Get All Services
```bash
curl -X GET http://localhost:8000/api/services
```

### Update Subscription Status
```bash
curl -X PATCH http://localhost:8000/api/subscriptions/1/change-status \
  -H "Content-Type: application/json" \
  -d '{
    "status": "active"
  }'
```

---

# 📸 Screenshots

## API Testing (Postman)

### Customer API

#### 1. Get All Customers
![Get All Customers](docs/screenshots/api/customers/01-get-all-customers.png)

#### 2. Create Customer
![Create Customer](docs/screenshots/api/customers/02-create-customer.png)

#### 3. Get Customer by ID
![Get Customer by ID](docs/screenshots/api/customers/03-get-customer-by-id.png)

#### 4. Update Customer
![Update Customer](docs/screenshots/api/customers/04-update-customer.png)

#### 5. Delete Customer
![Delete Customer](docs/screenshots/api/customers/05-delete-customer.png)

#### 6. Get Customers by Status
![Get Customers by Status](docs/screenshots/api/customers/06-get-by-status.png)

#### 7. Change Customer Status
![Change Customer Status](docs/screenshots/api/customers/07-change-status.png)

### Service API

#### 1. Get All Services
![Get All Services](docs/screenshots/api/services/01-get-all-services.png)

#### 2. Create Service
![Create Service](docs/screenshots/api/services/02-create-service.png)

#### 3. Get Service by ID
![Get Service by ID](docs/screenshots/api/services/03-get-service-by-id.png)

#### 4. Update Service
![Update Service](docs/screenshots/api/services/04-update-service.png)

#### 5. Delete Service
![Delete Service](docs/screenshots/api/services/05-delete-service.png)

#### 6. Get Services by Status
![Get Services by Status](docs/screenshots/api/services/06-get-by-status.png)

#### 7. Change Service Status
![Change Service Status](docs/screenshots/api/services/07-change-status.png)

### Subscription API

#### 1. Get All Subscriptions
![Get All Subscriptions](docs/screenshots/api/subscriptions/01-get-all-subscriptions.png)

#### 2. Create Subscription
![Create Subscription](docs/screenshots/api/subscriptions/02-create-subscription.png)

#### 3. Get Subscription by ID
![Get Subscription by ID](docs/screenshots/api/subscriptions/03-get-subscription-by-id.png)

#### 4. Get Subscriptions by Status
![Get Subscriptions by Status](docs/screenshots/api/subscriptions/04-get-by-status.png)

#### 5. Change Subscription Status
![Change Subscription Status](docs/screenshots/api/subscriptions/05-change-status.png)

## Web Interface

### 1. Customers Page - Table View
![Customers Page - Table View](docs/screenshots/web/01-customers-table.png)

### 2. Customers Page - Add Modal
![Customers Page -![alt text](image.png) Add Modal](docs/screenshots/web/02-customers-add-modal.png)

### 3. Customers Page - Edit Modal
![Customers Page - Edit Modal](docs/screenshots/web/03-customers-edit-modal.png)

### 4. Services Page - Table View
![Services Page - Table View](docs/screenshots/web/04-services-table.png)

### 5. Services Page - Add Modal
![Services Page - Add Modal](docs/screenshots/web/05-services-add-modal.png)

### 6. Subscriptions Page - Table View
![Subscriptions Page - Table View](docs/screenshots/web/06-subscriptions-table.png)

### 7. Subscriptions Page - Add Modal
![Subscriptions Page - Add Modal](docs/screenshots/web/07-subscriptions-add-modal.png)

### 8. Subscriptions Page - Status Dropdown
![Subscriptions Page - Status Dropdown](docs/screenshots/web/08-subscriptions-status-dropdown.png)

---

# 📞 Support

Untuk pertanyaan atau issue, silakan hubungi tim development atau buat issue di repository.

---

**Last Updated**: May 2026
**Version**: 1.0.0
