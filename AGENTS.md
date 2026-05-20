# AGENTS.md - AI Development Guide for "Mother" Project

## Architecture Overview

This is a **Laravel 12 inventory management system** with a **Vue 3 + TypeScript frontend** via Inertia.js. It handles complex multi-warehouse operations including products, sales, purchases, inventory movements, and accounting.

### Tech Stack

- **Backend**: Laravel 12 (PHP 8.4+), Sanctum (API auth), Jetstream (scaffolding)
- **Frontend**: Vue 3 + TypeScript, Inertia.js, PrimeVue (UI), TailwindCSS
- **Build**: Vite (dev server + bundler)
- **Key Libraries**: Spatie Permissions, MoneyPHP, Excel exports (Maatwebsite), PDF generation (mPDF/TCPDF), Barcode generation
- **Database**: UUIDs as primary keys, SoftDeletes, Audit trail enabled
- **ORM**: Eloquent with Auditable trait tracking all changes

---

## Critical Component Architecture

### Data Transfer Objects (DTOs) - Request/Response Layer
Located in `app/Dtos/`. All DTOs inherit from `BaseDto` implementing `ArrayableDto` interface.
```php
class ProductDto extends BaseDto {
    public string $name;
    public float $price;
    // Automatically converts to array via toArray()
}
```
**Key Pattern**: DTOs decouple controllers from models and standardize external communication.

### Service Layer - Business Logic
- **Static service classes** in `app/Services/` (e.g., `configService`)
- Example: `configService::get('key')` retrieves config from `Settings` model with caching
- Services typically wrap model queries and complex workflows
- No public constructors; use static methods for all operations

### Enums - Type Definitions
Located in `app/Enums/` with backed integer values:
```php
Enum ProductTypeEnum:int {
    case Producto = 0;
    case Servicio = 1;
}
```
Used throughout for type safety (e.g., `InventoryMovementTypeEnum`, `SaleTypeEnum`, `PaymentTypeEnum`).

### Helper Classes - Utility Functions
Located in `app/Helpers/`. Large domain-specific logic organized by entity:
- `ProductHelper`: Product-related operations
- `InventoryHelper`: Inventory calculations
- `SaleHelper`: Sale workflow logic
- `WarehouseProductHelper`: Multi-warehouse stock management
- **Pattern**: Methods are usually non-static, instantiate with `new ProductHelper()`, then call methods

### Models - Database Layer
Located in `app/Models/`. Key traits used:
- `HasUuids`: Makes UUID the primary key (not auto-increment)
- `SoftDeletes`: Soft delete capability with `deleted_at` column
- `Auditable` (via OwenIT): Tracks all changes in `audits` table
- **UUIDs everywhere**: Foreign keys use `foreignUuid('table_uuid')`

Key models form complex inventory/sales domain:
- `Product`, `Category`, `Brand`, `Unit`, `Tax`
- `Warehouse`, `WarehouseProduct`, `Inventory`
- `Sale`, `SaleItem`, `CreditNote`
- `Purchase`, `PurchaseItem`, `PurchaseReceipts`
- `PriceList`, `PriceListProduct`
- Money amounts stored as numeric fields, handled via `MoneyPHP`

### HTTP Pattern - Controllers to Resources
1. **Controllers** (`app/Http/Controllers/`): Use `HasMiddleware` trait to define per-method auth
   ```php
   public static function middleware(): array {
       return ['auth:sanctum', IsAdminMiddleware::class];
   }
   ```
2. **FormRequests** (`app/Http/Requests/`): Validation + authorization
3. **Resources** (`app/Http/Resources/`): Format model output for API/Inertia
4. **Response Types**: Return `Inertia::render()` for web or `JsonResponse` for API

---

## Developer Workflows

### Local Development
```bash
# Start dev servers (both Laravel + Vite in separate terminals)
php artisan serve          # Laravel on localhost:8000
npm run dev                # Vite on localhost:5173 with HMR

# Generate IDE helpers for PhpStorm
php artisan ide-helper:generate

# Format code
npm run format            # Prettier for frontend
php artisan pint         # Laravel Pint for PHP
```

### Database & Migrations
```bash
# Fresh database with seeders
php artisan migrate:fresh --seed

# Create migration
php artisan make:migration create_table_name

# Key: Use foreignUuid() for all foreign keys
# All tables get timestamps() and softDeletes()
```

### Testing
```bash
# Run all tests (Unit + Feature)
php artisan test

# Specific test or pattern
php artisan test --filter=ProductTest
```

### Build & Deploy
```bash
# Frontend build
npm run build              # Creates public/build/ assets

# Production Laravel
php artisan optimize:clear
php artisan config:cache
```

---

## Project-Specific Conventions

### Naming Conventions
- **Models**: Singular (`Product`, not `Products`)
- **Database tables**: Plural snake_case (`products`, `price_lists`)
- **Controllers**: Singular + `Controller` suffix (`ProductController`)
- **DTOs**: Entity name + `Dto` suffix (`ProductDto`, `SaleItemDto`)
- **Enums**: Entity name + `Enum` suffix (`ProductTypeEnum`)
- **Requests**: Action + `Request` suffix (`StoreProductRequest`, `UpdateProductRequest`)
- **Resources**: Entity name + `Resource` suffix (`ProductResource`)
- **Helpers**: Entity name + `Helper` suffix (`ProductHelper`)

### Request Validation Pattern
- All POST/PUT/PATCH routes require a `FormRequest` class
- UUID validation: `['uuid', 'exists:table_name,uuid']`
- Boolean flags use `'required', 'boolean'`
- Nested arrays use dot notation: `'warehouse_product.*.warehouse_uuid'`

### Multi-Warehouse Architecture
Several models support multiple warehouses:
- `WarehouseProduct`: Stock levels per warehouse
- `Inventory`: Tracks movements with `InventoryMovementConceptEnum` (entrada, salida, etc.)
- Helpers (`WarehouseProductHelper`, `InventoryHelper`) handle warehouse-aware queries
- **Key Concept**: Products aren't directly in warehouses; `WarehouseProduct` is the junction

### Transactional Operations
Use `DB::transaction()` for multi-step workflows:
- Sale creation (create sale + items + inventory movements)
- Purchase receiving (update stock + create audit trail)
- Inventory adjustments across multiple locations

### Money Handling
- Uses `MoneyPHP` library for monetary calculations
- Stored as numeric fields in database (e.g., `price`, `cost`, `promotional_price`)
- Never use float arithmetic directly; let MoneyPHP handle it

### Audit Trail
All model changes are logged via `OwenIT\Auditing\Auditable`:
- `audits` table tracks `old_values`, `new_values`, `event`, `user_id`, `created_at`
- Automatically included; no extra code needed
- Access via `$model->audits` relationship

---

## External Integration Points

### Spatie Packages
- **laravel-permission**: Roles/Permissions system in `app/Policies/`
- **laravel-pdf**: PDF generation (used in `app/Pdfs/`)
- **laravel-auditing**: Change tracking (see Audit Trail section)

### Export/Report Features
- **Excel**: `app/Exports/` (CategoryExport, ClientExport, etc.) via Maatwebsite/Excel
- **PDF**: `app/Pdfs/ProductLabelV1.php`, `PurchaseV1.php` via mPDF/TCPDF
- **Barcode**: Generated via `picqer/php-barcode-generator` or `tecnickcom/tcpdf`

### Spanish Localization
- Uses `laraveles/spanish` package for Spanish translations
- Config in `config/permission.php` likely has Spanish locale setup

### Frontend State Management
- **Pinia** for shared state (not Vue context/props for complex state)
- **Ziggy**: Route helper for frontend access to Laravel routes
- **v-money3**: Currency input formatting

---

## Key File Reference

| File | Purpose |
|------|---------|
| `routes/web.php` | Main application routes (auth required, Inertia-based) |
| `routes/api.php` | API routes (minimal, mostly for Sanctum) |
| `app/Models/Product.php` | Central product model; inspect relationships |
| `app/Services/configService.php` | Configuration retrieval pattern |
| `app/Helpers/ProductHelper.php` | Complex product operations |
| `app/Http/Controllers/ProductController.php` | Controller pattern reference |
| `app/Http/Requests/StoreProductRequest.php` | Validation pattern reference |
| `database/migrations/` | Schema definitions (note UUID usage) |
| `resources/js/app.ts` | Frontend entry point (Inertia setup) |
| `phpunit.xml` | Test configuration |
| `vite.config.js` | Frontend build configuration |

---

## IDE Configuration

### PhpStorm Setup
- Run `php artisan ide-helper:generate` to enable autocomplete for facades/models
- Use Laravel inspections: Settings → Languages & Frameworks → PHP → Laravel
- Add path aliases: `@` = `resources/js`, `@components` = `resources/js/Components`

### TypeScript in Vue
- `resources/js/app.ts` is the entry point
- Vue files use:
  ```vue
  <script setup lang="ts">
  import type { Ref } from 'vue'
  </script>
  ```
- Run `npm run ts-check` before commits to catch type errors

---

## Common Tasks for AI Agents

### Adding a New Resource (CRUD)
1. Create Migration: `php artisan make:migration create_resource_table`
2. Create Model: `php artisan make:model ResourceModel`
3. Add traits: `HasUuids`, `SoftDeletes`, `implements Auditable`
4. Create Request: `php artisan make:request Store/UpdateResourceRequest`
5. Create Controller: Extend base Controller, use FormRequest validation
6. Create DTO: Extend BaseDto for request/response shape
7. Create Resource: Extend JsonResource for API response formatting
8. Add routes in `routes/web.php`
9. Wire up frontend component with Inertia

### Modifying Database Schema
- Use migrations; never modify tables directly
- Include explicit foreign key names: `foreignUuid('referenced_table_id', 'references', 'id', 'on', 'referenced_table')`
- Always add `->nullable()` or `->default()` where appropriate
- Test migration down/up cycle

### Adding Complex Business Logic
- Place in Helper class if < 100 lines
- Create Service class if reusable across controllers
- Use DTOs for parameter passing
- Wrap in `DB::transaction()` if multiple queries
- Add tests in `tests/Feature/` or `tests/Unit/`

### Debugging
- Use `Log::debug()` (configured in `config/logging.php`)
- Check `storage/logs/laravel.log`
- Vue DevTools extension for Inertia props
- PHPStorm debugger with Xdebug for step-through debugging
