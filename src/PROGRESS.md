# ReadFlow — Session Progress

> File ini menyimpan status progress project. Baca dulu sebelum melanjutkan.

---

## LAST SESSION STATUS

- **Tanggal**: 25 Juli 2026
- **Phase**: Phase 3 — CRUD Reading Materials ✅
- **Status**: Semua CRUD Reading Materials, Categories, dan Authors sudah selesai.

---

## WHAT HAS BEEN DONE

### Database Foundation (Sprint 1) ✅
- [x] 12 migrations (3 Laravel default + 7 ReadFlow + 2 author migration)
- [x] 8 models dengan Eloquent relationships
- [x] 2 enums (SourceType, ReadingStatus)
- [x] Foreign keys dengan cascadeOnDelete
- [x] Author terakhir diubah dari string → author_id (FK) via migration `2025_07_26_000002`

### UI Foundation (Sprint 2) ✅
- [x] Master layout: `layouts/readflow.blade.php` — sidebar + topnav, light theme
- [x] Sidebar component — 8 menu + active state
- [x] Topnav component — user dropdown
- [x] Dashboard placeholder
- [x] Bootstrap Icons via CDN

### Authentication Branding (Sprint 2.5) ✅
- [x] Guest layout → ReadFlow branding, light theme
- [x] App layout (Breeze) → ReadFlow branding, light theme
- [x] Navigation → ReadFlow branding, light theme

### Reading Materials CRUD (Sprint 3) ✅
- [x] ReadingMaterialController (full resource, user ownership check)
- [x] StoreReadingMaterialRequest + UpdateReadingMaterialRequest (enum validation, author_id FK)
- [x] 4 views: index, create, edit, show
- [x] Pagination 12
- [x] Flash messages
- [x] "+ New Author" button (target _blank → `authors.create`)
- [x] "+ New Category" button (target _blank → `categories.create`)
- [x] Empty categories warning with disabled submit

### Categories CRUD ✅
- [x] CategoryController (full resource)
- [x] StoreCategoryRequest + UpdateCategoryRequest (unique name)
- [x] 3 views: index, create, edit
- [x] Pagination 10

### Authors CRUD ✅
- [x] AuthorController (full resource)
- [x] StoreAuthorRequest + UpdateAuthorRequest
- [x] 3 views: index, create, edit
- [x] Pagination 10

---

## CURRENT STATE

### Sidebar Menu
```
Dashboard       → dashboard ✅
Reading Materials → reading-materials.* ✅
Categories      → categories.* ✅
Authors         → authors.* ✅
Reading Sessions  → placeholder #
Reading Notes     → placeholder #
Reading Goals     → placeholder #
Bookmarks         → placeholder #
Profile           → placeholder (footer)
```

### Registered Routes
| Route | Auth | Status |
|-------|------|--------|
| `/` | Guest | ✅ Redirect to login |
| `/dashboard` | auth+verified | ✅ Closure |
| `/reading-materials/*` | auth+verified | ✅ Resource (7 routes) |
| `/categories/*` | auth+verified | ✅ Resource (6 routes — no show) |
| `/authors/*` | auth+verified | ✅ Resource (6 routes — no show) |
| `/profile/*` | auth | ✅ Breeze bawaan |
| Breeze auth routes | Guest/Auth | ✅ Breeze bawaan |

---

## KNOWN ISSUES

1. **DashboardController** — legacy code (masih pakai Movie/Genre models). Tidak dipakai karena route dashboard pakai closure.
2. **ProfileController** — referenced di routes tapi mungkin bermasalah. Pre-existing dari Breeze scaffold.
3. **Legacy Requests**: `StoreMovieRequest`, `UpdateMovieRequest`, `StoreGenreRequest`, `UpdateGenreRequest` — tidak dipakai, aman dihapus nanti.

---

## NEXT TASKS (Belum dikerjakan)

### Phase 4 nanti:
- [ ] Reading Sessions CRUD
- [ ] Reading Notes CRUD
- [ ] Reading Goals CRUD
- [ ] Bookmarks CRUD
- [ ] Dashboard real data (statistik dari database)
- [ ] Bersihkan legacy code (DashboardController, Movie/Genre requests)
