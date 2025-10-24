# 🧪 Test Summary - SMP Mentari

Dokumentasi lengkap untuk test suite aplikasi SMP Mentari.

## 📊 Overview

- **Total Tests**: 35
- **Total Assertions**: 84
- **Duration**: ~1.3s
- **Success Rate**: 100% ✅
- **Framework**: Pest PHP 4.1

## 📁 Test Structure

```
tests/
├── Feature/
│   ├── ExampleTest.php          # 1 test (contoh dasar)
│   └── PesanTamuTest.php        # 14 tests (fitur buku tamu)
├── Unit/
│   ├── ExampleTest.php          # 1 test (contoh dasar)
│   └── PesanTamuTest.php        # 19 tests (model pesan tamu)
├── Pest.php                     # Konfigurasi Pest
└── TestCase.php                 # Base test case
```

## 🎯 Feature Tests (14 Tests)

File: `tests/Feature/PesanTamuTest.php`

### Kategori: Akses Halaman (1 test)

| Test | Deskripsi |
|------|-----------|
| ✓ halaman buku tamu dapat diakses | Memastikan route `/bukutamu` dapat diakses dan mengembalikan view yang benar |

### Kategori: CRUD Operations (4 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ dapat menyimpan pesan tamu dengan data yang valid | Test POST data valid ke `/bukutamu` |
| ✓ dapat menampilkan daftar pesan tamu | Test menampilkan pesan yang sudah tersimpan |
| ✓ pesan tamu ditampilkan berurutan dari yang terbaru | Test ordering dengan `latest()` |
| ✓ form buku tamu menampilkan pesan kosong jika belum ada data | Test empty state |

### Kategori: Validasi (6 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ validasi nama wajib diisi | Test required validation untuk nama |
| ✓ validasi email wajib diisi | Test required validation untuk email |
| ✓ validasi email harus format yang benar | Test email format validation |
| ✓ validasi pesan wajib diisi | Test required validation untuk pesan |
| ✓ nama tidak boleh lebih dari 255 karakter | Test max length validation untuk nama |
| ✓ email tidak boleh lebih dari 255 karakter | Test max length validation untuk email |

### Kategori: Edge Cases (3 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ old input tetap ada setelah validasi gagal | Test session old input preservation |
| ✓ dapat mengirim pesan dengan nama yang mengandung karakter khusus | Test special characters handling |
| ✓ dapat mengirim pesan dengan teks panjang | Test long text handling |

## 🔧 Unit Tests (19 Tests)

File: `tests/Unit/PesanTamuTest.php`

### Kategori: Model Properties (4 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ model adalah instance dari Eloquent Model | Memastikan PesanTamu extends Model |
| ✓ fillable attributes terdefinisi dengan benar | Test $fillable property |
| ✓ model menggunakan table name yang benar | Test table name 'pesan_tamus' |
| ✓ model memiliki primary key yang benar | Test primary key 'id' |

### Kategori: Mass Assignment (5 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ model memiliki atribut nama yang dapat diisi | Test isFillable('nama') |
| ✓ model memiliki atribut email yang dapat diisi | Test isFillable('email') |
| ✓ model memiliki atribut pesan yang dapat diisi | Test isFillable('pesan') |
| ✓ model dapat menggunakan fill method untuk set attributes | Test fill() method |
| ✓ model dapat membuat instance dengan constructor array | Test new Model([...]) |

### Kategori: Attribute Management (3 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ model dapat set individual attributes | Test $model->attribute = value |
| ✓ model dapat get attributes dengan method getAttribute | Test getAttribute() method |
| ✓ model dapat set attributes dengan method setAttribute | Test setAttribute() method |

### Kategori: Data Types & Length (5 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ model dapat menyimpan nama dengan panjang 255 karakter | Test max length nama |
| ✓ model dapat menyimpan email dengan format panjang | Test max length email |
| ✓ model dapat menyimpan pesan dengan teks yang sangat panjang | Test TEXT field capacity |
| ✓ model dapat menyimpan karakter khusus dalam nama | Test special chars (O'Brien, &, etc) |
| ✓ model dapat menyimpan unicode characters | Test unicode & emoji support |

### Kategori: Serialization (2 tests)

| Test | Deskripsi |
|------|-----------|
| ✓ model dapat mengkonversi ke array | Test toArray() method |
| ✓ model dapat mengkonversi ke JSON | Test toJson() method |

## 🚀 Running Tests

### Basic Commands

```bash
# Run all tests
php artisan test

# Run with detailed output
php artisan test --parallel

# Run specific test file
php artisan test tests/Feature/PesanTamuTest.php

# Run specific test method
php artisan test --filter=PesanTamuTest

# Run only Feature tests
php artisan test tests/Feature

# Run only Unit tests
php artisan test tests/Unit
```

### Using Composer

```bash
# Run tests via composer script
composer test
```

### Using Pest Directly

```bash
# Run with Pest
./vendor/bin/pest

# Run with coverage (requires Xdebug/PCOV)
./vendor/bin/pest --coverage

# Run specific test
./vendor/bin/pest --filter="dapat menyimpan pesan"
```

## 📈 Test Assertions Breakdown

### Feature Tests Assertions
- Response status checks: 14 assertions
- View assertions: 14 assertions
- Database assertions: 8 assertions
- Session assertions: 8 assertions
- Content assertions: 6 assertions

### Unit Tests Assertions
- Type checks: 10 assertions
- Property checks: 15 assertions
- Value comparisons: 9 assertions

**Total: 84 assertions across 35 tests**

## 🎯 Coverage Areas

### ✅ Covered
- [x] HTTP Routes (GET/POST)
- [x] Controller methods
- [x] Model properties & methods
- [x] Form validation
- [x] Database operations
- [x] View rendering
- [x] Session handling
- [x] Error handling
- [x] Edge cases

### 🔄 Future Enhancements
- [ ] API endpoint tests (jika akan dibuat API)
- [ ] Authentication tests (jika akan ditambah auth)
- [ ] File upload tests (jika akan ditambah fitur upload)
- [ ] Email notification tests (jika akan ditambah notifikasi)
- [ ] Performance tests
- [ ] Security tests (XSS, CSRF, SQL Injection)

## 📝 Writing New Tests

### Feature Test Template

```php
<?php

use App\Models\PesanTamu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('nama test yang deskriptif', function () {
    // Arrange - Setup data
    $data = [...];
    
    // Act - Lakukan action
    $response = $this->post('/route', $data);
    
    // Assert - Verify results
    $response->assertStatus(200);
    $this->assertDatabaseHas('table', $data);
});
```

### Unit Test Template

```php
<?php

use App\Models\PesanTamu;

test('nama test yang deskriptif', function () {
    // Arrange
    $model = new PesanTamu();
    
    // Act
    $model->attribute = 'value';
    
    // Assert
    expect($model->attribute)->toBe('value');
});
```

## 🔍 Test Best Practices

1. **Naming**: Gunakan nama test yang deskriptif dalam bahasa Indonesia
2. **AAA Pattern**: Arrange, Act, Assert
3. **Isolation**: Setiap test harus independent
4. **Database**: Gunakan `RefreshDatabase` untuk feature tests
5. **Expectations**: Gunakan Pest expectations untuk readability

## 📚 Resources

- [Pest PHP Documentation](https://pestphp.com/docs)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Testing Best Practices](https://pestphp.com/docs/testing-practices)

## 📊 CI/CD Integration

Test suite ini dapat diintegrasikan dengan CI/CD pipeline:

```yaml
# GitHub Actions example
- name: Run tests
  run: php artisan test
```

---

**Last Updated**: October 8, 2025  
**Maintained by**: Adi Wahyu ([@adiwp](https://github.com/adiwp))
