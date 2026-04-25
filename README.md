# SmartFarm

A multi-tenant farm management platform built with Laravel 13 and Filament 5. SmartFarm enables agricultural businesses to manage fields, crops, tasks, workforce, finances, and emergency alerts — all through a role-based admin interface with GPS tracking capabilities.

---

## Table of Contents

- [Requirements](#requirements)
- [Setup Instructions](#setup-instructions)
- [Default Credentials](#default-credentials)
- [Running the Application](#running-the-application)
- [Testing](#testing)
- [Design Decisions](#design-decisions)

---

## Requirements

- PHP 8.4+
- Composer
- MySQL 8.0+
- Node.js (for asset compilation)

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone <repository-url>
cd smartfarm
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartfarm
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Run migrations and seed demo data

```bash
php artisan migrate --seed
```

This creates three demo tenants with agents and field data (see [Default Credentials](#default-credentials)).

### 5. Build frontend assets

```bash
npm run build
```

### One-command setup (alternative)

```bash
composer run setup
```

This runs install, key generation, migration, and asset build in sequence.

---

## Default Credentials

After seeding, the following accounts are available:

| Role  | Email                    | Password | Tenant              |
|-------|--------------------------|----------|---------------------|
| Admin | admin@smartfarm.test     | password | —                   |
| Agent | john@greenvalley.test    | password | Green Valley Farm   |
| Agent | jane@sunrise.test        | password | Sunrise Agriculture |
| Agent | mike@bigfarm.test        | password | Big Farm Co.        |

Admin users have no tenant — they have system-wide access. Agent users are scoped to their tenant.

---

## Running the Application

### Development (concurrent processes)

```bash
composer run dev
```

Starts Laravel's dev server, queue worker, log watcher, and Vite in parallel.

### Production

```bash
npm run build
php artisan serve
```

### Panel URLs

| Panel  | URL      | Access       |
|--------|----------|--------------|
| Admin  | `/admin` | Admin users  |
| Agent  | `/agent` | Agent users  |

---

## Testing

```bash
php artisan test --compact
```

Filter to a specific test:

```bash
php artisan test --compact --filter=FieldTest
```

Tests use [Pest](https://pestphp.com/) with Laravel factories for test data. Do not use manual database setup in tests — factories are the source of truth.

---

## Design Decisions

### Multi-tenancy

All core domain entities — fields, tasks, work logs, expenses, revenues, payrolls, messages, and alerts — carry a `tenant_id` foreign key. This provides data isolation without a separate database per tenant. Admin users have `tenant_id = null` and are not scoped to any tenant, giving them system-wide visibility. Agents are always scoped to their tenant.

This approach was chosen over database-per-tenant to simplify operations and migrations, and over package-based solutions (e.g. Stancl Tenancy) to keep the architecture transparent and easy to reason about at the current scale.

### Role-based access with separate Filament panels

Rather than one panel with conditional visibility, SmartFarm uses two distinct Filament panels:

- **`/admin`** — full CRUD, financial management, user and tenant administration
- **`/agent`** — scoped to the authenticated agent's fields and tasks only

This eliminates complex conditional authorization logic in form/table schemas and makes each panel's intent clear. Custom middleware (`EnsureUserIsAdmin`, `EnsureUserIsAgent`) guard each panel at the route level.

### GPS verification on tasks and work logs

`Task` records store both a target GPS coordinate and a completion coordinate. A Haversine formula implementation on the model calculates whether the agent completed the task within a configurable `tolerance_meters` radius, setting `gps_verified = true/false`. Work logs similarly capture check-in and check-out coordinates. This enables accountability without requiring a separate location service.

### Status workflows on domain models

Rather than managing state in controllers, state transitions are model methods:

- `Task`: `start()`, `complete()`, `cancel()`, `markOverdue()`
- `WorkLog`: `checkIn()`, `checkOut()`, `approve()`, `reject()`
- `Expense` / `Payroll`: approval methods with `approved_by` and `approved_at` timestamps

This keeps business logic close to the data and makes it testable in isolation.

### Computed field status

`Field` has no stored `status` column. Instead, a computed attribute derives status at runtime:

- **active** — a field update was recorded within the last 7 days
- **at_risk** — no update in 7+ days
- **completed** — explicitly marked done

This avoids stale status values from missed updates or async jobs, at the cost of a small per-request computation (acceptable given field counts per tenant).

### Eloquent query scopes over raw queries

Domain models expose named scopes (`pending()`, `overdue()`, `forAgent()`, `thisWeek()`, etc.) to keep Filament resource queries readable and composable. This also makes the scopes reusable across admin and agent panels without duplication.

### Form and table schemas extracted into classes

Filament resources delegate their form and table definitions to dedicated classes (e.g. `FieldForm.php`, `FieldsTable.php`). This keeps resource files thin and allows schema logic to be tested and reused independently.

### Financial tracking with approval workflows

Expenses and revenues are tied to specific fields, enabling per-field profitability analysis. Expenses follow a `pending → approved → paid` workflow with an `approved_by` audit trail. Revenues record source type (harvest, livestock, rental, subsidy) for categorized income reporting.

### Database-backed sessions, cache, and queues

All three infrastructure concerns use the `database` driver by default. This removes Redis/Memcached as setup requirements for local development and staging, with a straightforward path to upgrading individual drivers as load demands it.

---

## Tech Stack

| Layer        | Technology                  |
|--------------|-----------------------------|
| Framework    | Laravel 13                  |
| Admin UI     | Filament 5                  |
| Reactivity   | Livewire 4                  |
| Frontend     | Tailwind CSS 4 + Vite       |
| Database     | MySQL 8                     |
| Testing      | Pest 4 + PHPUnit 12         |
| PHP          | 8.4                         |
