# Vyap Platform API

Monolithic Laravel 12 SaaS backend for **Vyap** — a multi‑service business platform (Billing, Rental, etc.) with OTP authentication, RBAC, subscriptions, and account/workspace support.

---

## 🚀 Current Status (Up to Step 19)

This README reflects the system **as of now**, without future assumptions.

---

## 🧱 Architecture Overview

- **Framework**: Laravel 12 (Monolith, modular)
- **Auth**: OTP + Laravel Sanctum
- **RBAC**: Spatie Permissions (roles + permissions)
- **Modules**:
  - Platform/Auth
  - Platform/Subscriptions
  - Platform/Accounts (foundation)
- **Pattern**:
  - DTOs
  - Services
  - Repositories
  - Domain Exceptions
  - Middleware-based access control

---

## 👤 Authentication Flow (OTP)

1. `POST /api/v1/auth/otp/request`
2. `POST /api/v1/auth/otp/verify`
3. On verify:
   - Platform user created (if new)
   - Default role assigned: `platform-user`
   - Single active Sanctum token enforced (old tokens revoked)

---

## 🔐 RBAC (Role Based Access Control)

### Platform Roles (global)
- `platform-user`
- `platform-admin`

### Service Permissions (example: billing)
- `billing.access`
- `billing.sales.view`
- `billing.sales.create`
- `billing.sales.edit`
- `billing.sales.cancel`
- `billing.reports.view`
- `billing.settings.manage`

> ⚠️ Platform roles ≠ Account roles  
> Platform roles are rare and global.  
> Account roles are business-specific.

---

## 🏢 Accounts (Workspace Foundation)

### Tables
- `accounts`
- `account_users`
  - `platform_user_id`
  - `account_id`
  - `role` (owner, staff, cashier, etc.)
  - `active` (current working account)

### Key Rules
- One platform user → many accounts
- User works in **one active account at a time**
- Account context is required for all service actions

---

## 📦 Subscriptions

### Scope
- Subscriptions are **per account**, not per user
- Each account can subscribe to multiple services

### Status
- `active`
- `expired`
- `revoked`

### Middleware
```php
subscribed:{serviceCode}
```

Prevents access if the active account has no valid subscription.

---

## 🛍 Store API (Developer Phase)

### List services
```
GET /api/v1/store/services
```

### My services
```
GET /api/v1/store/my-services
```

### Subscribe (dev-only)
```
POST /api/v1/store/subscribe
```

> Purchases are disabled in production via environment check.

---

## 🧩 /me Endpoint (Step 19)

Returns **user + active account context**:

```json
{
  "ok": true,
  "user": {
    "id": 1,
    "phone": "+91XXXXXXX"
  },
  "account": {
    "id": 10,
    "name": "ABC Traders",
    "role": "owner"
  }
}
```

This allows frontend to show:

> “You are logged into ABC Traders (Owner)”

---

## 🧠 Design Principles

- No cross-account data leakage
- No user-only subscriptions
- Clean separation:
  - Platform concerns
  - Account concerns
  - Service concerns
- Incremental, MNC-grade architecture

---

## 🛠 Environment Notes

- Store purchases enabled only in:
  - `local`
  - `staging`
- Production requires payment integration (future)

---

## ⏭ Next Planned Steps

- Step 20: Account Switching API
- Move subscriptions fully to `account_id`
- Service-level user roles (cashier, staff, etc.)
- Payment gateway integration
- Audit logs

---

## ✅ Current State Summary

✔ OTP Auth  
✔ Sanctum token control  
✔ RBAC  
✔ Subscription engine  
✔ Store catalog + pricing  
✔ Account foundation  
✔ Subscription gating middleware  

---

**Vyap Platform API**  
Designed for real-world Indian SMB SaaS use cases.
