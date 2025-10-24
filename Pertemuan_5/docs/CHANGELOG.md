# Changelog - Aplikasi SMP Mentari

Semua perubahan penting pada proyek ini akan didokumentasikan dalam file ini.

Format dokumen ini berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v2.0.0] - 2025-10-10

Commit: `74f0519` - "Summary Project"

### Added - Documentation Summary
- 📚 **DOCS_SUMMARY.md** di root project (220 lines)
  - Quick reference untuk semua dokumentasi
  - Ringkasan perubahan commit terakhir
  - Panduan navigasi dokumentasi
  - Test results summary

## [v1.3.0] - 2025-10-08

Commit: `17f9ba6` - "tambah dokumentasi"

### Added - Comprehensive Documentation
- 📚 **docs/README.md** (327 lines)
  - Index lengkap dokumentasi
  - Quick navigation links
  - Documentation best practices
  - Contribution guidelines

- 📝 **docs/CHANGELOG.md** (109 lines)
  - Version history tracking
  - Change categorization
  - Release notes

- 🧪 **docs/TESTING_DOCUMENTATION.md** (447 lines)
  - Pest PHP setup guide
  - Test cases explanation
  - Testing patterns & best practices
  - Commands reference

- 📄 **docs/COMMIT_d8f937d.md** (553 lines)
  - Detailed commit analysis
  - File-by-file changes
  - Impact assessment
  - Code examples

### Impact
- ✅ 1,436 lines of documentation added
- ✅ Complete project documentation
- ✅ Testing guide for developers
- ✅ Easy onboarding for new contributors

## [v1.2.0] - 2025-10-08

Commit: `585f6c3` - "Fitur Autentikasi, Dashboard, Konfigurasi Website"

### Added - Authentication System (Laravel Breeze)
- ✨ **Full Authentication Flow**
  - User registration dengan validasi
  - Login/Logout functionality
  - Password reset via email
  - Email verification
  - Remember me functionality

- 🔐 **Auth Controllers** (9 controllers, ~344 lines)
  - AuthenticatedSessionController
  - RegisteredUserController
  - PasswordResetLinkController
  - NewPasswordController
  - EmailVerificationPromptController
  - EmailVerificationNotificationController
  - VerifyEmailController
  - ConfirmablePasswordController
  - PasswordController

- 🎨 **Auth Views** (6 views, ~221 lines)
  - Login form
  - Registration form
  - Forgot password form
  - Reset password form
  - Email verification page
  - Confirm password page

- 🧪 **Auth Tests** (6 test files, ~238 lines)
  - AuthenticationTest (41 lines)
  - RegistrationTest (19 lines)
  - PasswordResetTest (60 lines)
  - PasswordUpdateTest (40 lines)
  - PasswordConfirmationTest (32 lines)
  - EmailVerificationTest (46 lines)

### Added - Dashboard & Admin Panel
- 📊 **Admin Dashboard**
  - Statistik real-time (kegiatan, pesan tamu, users)
  - Recent activities display
  - Quick action buttons
  - Responsive layout

- 🎨 **Admin Layout** (69 lines)
  - Sidebar navigation
  - Top navigation bar
  - Footer with copyright
  - Mobile-responsive design

### Added - CRUD Kegiatan
- 📝 **Kegiatan Management**
  - Create kegiatan dengan form validasi
  - Read/List dengan pagination (10 items/page)
  - Update kegiatan existing
  - Delete dengan konfirmasi

- 🗄️ **Database Migration**
  - kegiatans table (judul, deskripsi, gambar, tanggal)
  - Timestamps & soft deletes ready

- 🎨 **Kegiatan Views** (3 views, ~136 lines)
  - index.blade.php - List view
  - create.blade.php - Create form
  - edit.blade.php - Edit form

### Added - Settings System
- ⚙️ **Configuration Management**
  - Key-value based settings storage
  - Cache integration (1 hour TTL)
  - Dynamic configuration loading
  - Settings CRUD interface

- 💾 **Settings Model** (48 lines)
  - `Setting::get($key, $default)` - Get with cache
  - `Setting::set($key, $value)` - Set & invalidate cache
  - `Setting::clearCache()` - Clear cache
  - Auto-caching mechanism

- 🗄️ **Settings Migration**
  - settings table (key, value, type, description)
  - Default setting: home_kegiatan_per_page = 6

- 🎨 **Settings Admin Page** (105 lines)
  - Edit settings form
  - Type-based input fields
  - Validation & feedback

### Added - Profile Management
- 👤 **User Profile Features**
  - Update profile information (nama, email)
  - Change password dengan validasi
  - Delete account dengan konfirmasi
  - Email re-verification on change

- 🎨 **Profile Views** (3 partials, ~167 lines)
  - Update profile information form
  - Update password form
  - Delete account form

- 🧪 **Profile Tests** (85 lines)
  - Profile update tests
  - Password change tests
  - Account deletion tests

### Added - Admin Buku Tamu
- 📚 **Buku Tamu Management**
  - View all messages (admin only)
  - Delete inappropriate messages
  - Pagination (10 items/page)
  - Sort by newest first

- 🎨 **Admin Buku Tamu View** (74 lines)
  - Tabel pesan tamu
  - Delete buttons dengan konfirmasi
  - Pagination links

- 📝 **Controller Methods**
  - `adminIndex()` - List for admin
  - `destroy()` - Delete message

### Added - UI Components
- 🎨 **Reusable Components** (13 components, ~172 lines)
  - application-logo
  - auth-session-status
  - danger-button
  - dropdown & dropdown-link
  - input-error, input-label
  - modal
  - nav-link, responsive-nav-link
  - primary-button, secondary-button
  - text-input

- 🎨 **Layout Components**
  - AppLayout component
  - GuestLayout component
  - Navigation component (60 lines)

### Changed - Homepage & Pagination
- 🔄 **Dynamic Content**
  - Homepage data dari database (was: hardcoded)
  - Configurable pagination via Settings
  - Empty state handling
  - Latest kegiatan sorting

- 📄 **PageController Updated**
  ```php
  // Before: Hardcoded array
  $kegiatan_terbaru = [ ... ];
  
  // After: Database + Settings
  $perPage = Setting::get('home_kegiatan_per_page', 6);
  $kegiatan_terbaru = Kegiatan::latest()->paginate($perPage);
  ```

- 🎨 **Home View Updated** (+34 lines)
  - Database-driven kegiatan loop
  - Pagination links
  - Responsive grid layout
  - Empty state message

### Changed - Routes Structure
- 🔗 **Route Organization**
  - Separated auth routes (routes/auth.php)
  - Middleware groups (auth, verified)
  - Resource routes untuk Kegiatan
  - Admin routes dengan auth middleware

### Added - Dependencies
- � **Composer**
  - laravel/breeze: ^2.2

- 📦 **NPM**
  - @tailwindcss/forms: ^0.5.9
  - postcss: ^8.4.49

- ⚙️ **Config Files**
  - postcss.config.js
  - tailwind.config.js

### Statistics
- **Files Changed**: 78 files
- **Insertions**: +3,960 lines
- **Deletions**: -76 lines
- **Net Change**: +3,884 lines
- **New Controllers**: +6 controllers
- **New Models**: +2 models
- **New Views**: +55 views
- **New Tests**: +19 tests
- **Test Coverage**: 35 → 54 tests (54% increase)

## [v1.1.0] - 2025-10-08

Commit: `d8f937d` - "Test dengan PEST"

### Added - Testing Framework
- ✅ **Feature Tests untuk PesanTamu** (`tests/Feature/PesanTamuTest.php`)
  - 14 test cases untuk testing integrasi
  - Test CRUD operations
  - Test validasi form
  - Test edge cases dan error handling
  
- ✅ **Unit Tests untuk PesanTamu Model** (`tests/Unit/PesanTamuTest.php`)
  - 19 test cases untuk testing model
  - Test model properties dan relationships
  - Test fillable attributes
  - Test type casting dan serialization

- 📚 **Dokumentasi Testing** 
  - `tests/TEST_SUMMARY.md` - Ringkasan semua test cases (265 baris)
  - `tests/TEST_EXAMPLES.md` - Pattern dan contoh testing (539 baris)

### Changed
- 📖 **README.md diperluas** (+122 baris)
  - Tambah section Testing
  - Tambah instruksi setup yang lebih detail
  - Tambah dokumentasi struktur file
  - Tambah badge dan status proyek

### Test Results
```
Tests:    35 passed (84 assertions)
Duration: 1.37s
Status:   ✅ ALL TESTS PASSING
```

### Test Coverage
- **Feature Tests**: 14 tests
  - Public access tests
  - Form submission tests
  - Validation tests
  - Edge cases tests
  
- **Unit Tests**: 19 tests
  - Model structure tests
  - Attribute tests
  - Relationship tests
  - Serialization tests

## [v1.0.0] - 2025-10-08

Commit: `513de03` - "Aplikasi SMP Mentari"

### Added - Initial Release
- 🎉 Setup awal aplikasi Laravel
- 📝 Model PesanTamu untuk buku tamu
- 🗄️ Database migration untuk pesan_tamus
- 🌐 Halaman home dengan daftar kegiatan (hardcoded)
- 📬 Form buku tamu untuk pengunjung
- 🎨 Styling dengan Tailwind CSS
- 📱 Responsive layout

### Tech Stack
- Laravel 12.33.0
- PHP 8.3.26
- Tailwind CSS 4.0
- Vite 7.0
- SQLite Database
- Pest PHP 4.1 (Testing Framework)

---

## Legend
- ✨ = New Feature
- 🐛 = Bug Fix
- 📚 = Documentation
- ⚙️ = Configuration
- 🎨 = Styling
- 🔒 = Security
- ⚡ = Performance
- 🔥 = Removed
- 📝 = Content
- 🔄 = Changed
- ✅ = Test
