# ReadFlow — Project Summary

> Laravel 13 · Bootstrap 5 · MySQL · Docker

---

## Database Architecture (8 Tables)

| # | Tabel | Kolom Kunci | Foreign Keys |
|---|-------|-------------|--------------|
| 1 | `users` | id, name, email, password | — |
| 2 | `authors` | id, name, biography | — |
| 3 | `categories` | id, name, description | — |
| 4 | `reading_materials` | id, user_id, category_id, author_id, title, source_type, status, total_pages, ... | user_id → users, category_id → categories, author_id → authors |
| 5 | `reading_sessions` | id, user_id, reading_material_id, session_date, start_time, end_time, duration_minutes, pages_read, notes | user_id → users, reading_material_id → reading_materials |
| 6 | `reading_notes` | id, user_id, reading_material_id, title, summary | user_id → users, reading_material_id → reading_materials |
| 7 | `reading_goals` | id, user_id, daily_target_minutes, weekly_target_minutes, yearly_target_books | user_id → users |
| 8 | `bookmarks` | id, user_id, reading_material_id, created_at | user_id → users, reading_material_id → reading_materials |

---

## Enums

| Enum | Backed By | Kasus |
|------|-----------|-------|
| `SourceType` | string | Book, Journal, Medium, Substack, Article, PDF |
| `ReadingStatus` | string | Not Started, Reading, Completed |

---

## Migrations (13 files)

| File | Isi |
|------|-----|
| `0001_01_01_000000_create_users_table` | users, password_reset_tokens, sessions (Laravel default) |
| `0001_01_01_000001_create_cache_table` | Cache (Laravel default) |
| `0001_01_01_000002_create_jobs_table` | Jobs (Laravel default) |
| `2025_07_24_000001_create_categories_table` | categories |
| `2025_07_24_000002_create_authors_table` | authors |
| `2025_07_24_000003_create_reading_materials_table` | reading_materials (dengan author_id FK) |
| `2025_07_24_000004_create_reading_sessions_table` | reading_sessions (base) |
| `2025_07_26_000003_add_start_time_end_time_notes_to_reading_sessions` | reading_sessions — add start_time, end_time, notes |
| `2025_07_24_000005_create_reading_notes_table` | reading_notes |
| `2025_07_24_000006_create_reading_goals_table` | reading_goals |
| `2025_07_24_000007_create_bookmarks_table` | bookmarks |
| `2025_07_26_000001_drop_author_id_add_author_to_reading_materials` | author_id → author (string) — **rolled forward by migration 2** |
| `2025_07_26_000002_replace_author_string_with_author_id` | author → author_id (FK) + migrasi data |

---

## Models (8)

| Model | Fillable | Relasi |
|-------|----------|--------|
| `User` | name, email, password | HasMany readingMaterials, readingSessions, readingNotes, readingGoals, bookmarks |
| `ReadingMaterial` | user_id, category_id, author_id, title, source_type, source_url, description, total_pages, total_reading_minutes, status, cover_image | BelongsTo user, category, author · HasMany readingSessions, readingNotes, bookmarks |
| `Category` | name, description | HasMany readingMaterials |
| `Author` | name, biography | HasMany readingMaterials |
| `ReadingSession` | user_id, reading_material_id, session_date, start_time, end_time, duration_minutes, pages_read, notes | BelongsTo user, readingMaterial |
| `ReadingNote` | user_id, reading_material_id, title, summary | BelongsTo user, readingMaterial |
| `ReadingGoal` | user_id, daily_target_minutes, weekly_target_minutes, yearly_target_books | BelongsTo user |
| `Bookmark` | user_id, reading_material_id | BelongsTo user, readingMaterial (`$timestamps = false`) |

---

## Controllers (6)

| Controller | Actions |
|------------|---------|
| `ReadingMaterialController` | index, create, store, show, edit, update, destroy (+ authorizeAccess private) |
| `ReadingSessionController` | index, create, store, edit, update, destroy (+ authorizeAccess private) |
| `CategoryController` | index, create, store, edit, update, destroy |
| `AuthorController` | index, create, store, edit, update, destroy |
| `ProfileController` | edit, update, destroy (Breeze bawaan) |
| `DashboardController` | index (legacy — masih pakai Movie/Genre) |

---

## Form Requests (9)

| Request | Validasi Utama |
|---------|----------------|
| `StoreReadingMaterialRequest` | title, author_id (exists:authors,id), category_id (exists:categories,id), source_type (enum), status (enum), total_pages, description, source_url |
| `UpdateReadingMaterialRequest` | Sama |
| `StoreReadingSessionRequest` | reading_material_id (exists), session_date, start_time, end_time (after:start_time), duration_minutes, pages_read, notes |
| `UpdateReadingSessionRequest` | Sama |
| `StoreCategoryRequest` | name (required, unique, max:100), description |
| `UpdateCategoryRequest` | name (required, unique ignore self, max:100), description |
| `StoreAuthorRequest` | name (required, max:255), biography |
| `UpdateAuthorRequest` | Sama |
| `ProfileUpdateRequest` | name, email (Breeze bawaan) |

---

## Blade Views (37 files)

### Layouts
| File | Keterangan |
|------|------------|
| `layouts/readflow.blade.php` | **Master layout** ReadFlow — sidebar kiri + topnav + content area, light theme |
| `layouts/app.blade.php` | Layout Breeze (untuk profile) — light theme, ReadFlow branding |
| `layouts/guest.blade.php` | Layout guest (login/register) — light theme, ReadFlow branding |
| `layouts/navigation.blade.php` | Top navbar Breeze — light theme, ReadFlow branding |

### Components
| File | Keterangan |
|------|------------|
| `components/sidebar.blade.php` | Sidebar responsif (offcanvas mobile + fixed desktop), 8 menu item |
| `components/topnav.blade.php` | Top navbar — user dropdown (Profile, Log Out) |
| *(8 Breeze components lainnya)* | application-logo, auth-session-status, danger-button, input-error, input-label, modal, primary-button, secondary-button, text-input |

### Halaman CRUD
| Module | Views |
|--------|-------|
| **Reading Materials** | `index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php` |
| **Reading Sessions** | Tidak ada — masih placeholder di sidebar |
| **Categories** | `index.blade.php`, `create.blade.php`, `edit.blade.php` |
| **Authors** | `index.blade.php`, `create.blade.php`, `edit.blade.php` |

### Halaman Lain
| File | Keterangan |
|------|------------|
| `dashboard.blade.php` | Dashboard — placeholder cards (statistik, recent activities, progress) |
| `welcome.blade.php` | Welcome page (tidak dipakai — root redirect ke login) |

### Auth & Profile (Breeze)
| File | Keterangan |
|------|------------|
| `auth/login.blade.php` | Login form |
| `auth/register.blade.php` | Register form |
| `auth/forgot-password.blade.php` | Lupa password |
| `auth/reset-password.blade.php` | Reset password |
| `auth/verify-email.blade.php` | Verifikasi email |
| `auth/confirm-password.blade.php` | Konfirmasi password |
| `profile/edit.blade.php` | Edit profil |
| `profile/partials/update-profile-information-form.blade.php` | Form update profil |
| `profile/partiaals/update-password-form.blade.php` | Form update password |
| `profile/partials/delete-user-form.blade.php` | Form hapus akun |

---

## Routes (28 endpoint terdaftar)

### Authenticated + Verified
| Method | URI | Nama Route |
|--------|-----|------------|
| GET | `/dashboard` | `dashboard` |
| GET/POST/PUT/DELETE | `/reading-materials` + `{readingMaterial}` | `reading-materials.*` (7 route) |
| GET/POST/PUT/DELETE | `/reading-sessions` + `{readingSession}` | `reading-sessions.*` (7 route) |
| GET/POST/PUT/DELETE | `/categories` + `{category}` | `categories.*` (6 route) |
| GET/POST/PUT/DELETE | `/authors` + `{author}` | `authors.*` (6 route) |

### Authenticated (tanpa verified)
| Method | URI | Nama Route |
|--------|-----|------------|
| GET/PATCH/DELETE | `/profile` | `profile.*` |

### Guest (dari auth.php)
| Method | URI | Keterangan |
|--------|-----|------------|
| GET/POST | `/login` | Login |
| GET/POST | `/register` | Register |
| GET/POST | `/forgot-password` | Lupa password |
| GET/POST | `/reset-password/{token}` | Reset password |
| POST | `/logout` | Logout |

---

## Sidebar Menu

```
Dashboard       → dashboard
Reading Materials → reading-materials.*
Categories      → categories.*
Authors         → authors.*
Reading Sessions  → reading-sessions.*
Reading Notes     → (placeholder)
Reading Goals     → (placeholder)
Bookmarks         → (placeholder)
Profile           → (placeholder footer)
```

---

## Fitur yang sudah berjalan

- [x] Autentikasi (Laravel Breeze) — login, register, forgot/reset password, email verification
- [x] Dashboard — placeholder content
- [x] **Reading Materials CRUD** — create, read (index + show), update, delete
  - Category dropdown + button "+ New" (target _blank)
  - Author dropdown + button "+ New" (target _blank)
  - Source Type & Status dropdown (dari enum)
  - Form Request validation, flash messages, pagination
- [x] **Reading Sessions Backend** — database, model, controller, routes
  - Database: start_time, end_time, notes columns added
  - Model: fillable, casts, belongsTo relationships
  - Controller: index, create, store, edit, update, destroy
  - Routes: 7 resource routes terdaftar
  - Blade views: belum diimplementasi
- [x] **Categories CRUD** — create, read, update, delete
  - Pagination 10, delete confirmation, flash messages
- [x] **Authors CRUD** — create, read, update, delete
  - Pagination 10, delete confirmation, flash messages
- [x] Responsive layout — sidebar offcanvas di mobile, fixed di desktop
- [x] Light theme — Bootstrap 5 + Bootstrap Icons

---

## Catatan Penting

1. **Legacy code**: `DashboardController` masih berisi kode Movie/Genre dari aplikasi sebelumnya. Tidak dipakai karena route dashboard sudah pakai closure.
2. **ProfileController error**: Referensi di routes tapi file mungkin tidak ada — ini isu pre-existing dari Breeze scaffold.
3. **Belum diimplementasi**: Reading Notes, Reading Goals, Bookmarks — masih placeholder di sidebar.
