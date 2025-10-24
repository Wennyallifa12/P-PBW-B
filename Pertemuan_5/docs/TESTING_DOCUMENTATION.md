# Dokumentasi Testing - Aplikasi SMP Mentari

> Dokumentasi lengkap tentang implementasi testing menggunakan Pest PHP

## 📋 Daftar Isi
- [Overview](#overview)
- [Setup Testing](#setup-testing)
- [Feature Tests](#feature-tests)
- [Unit Tests](#unit-tests)
- [Menjalankan Tests](#menjalankan-tests)
- [Best Practices](#best-practices)

---

## Overview

Aplikasi SMP Mentari menggunakan **Pest PHP 4.1** sebagai framework testing. Implementasi testing mencakup:

- ✅ **35 Test Cases** (14 Feature + 19 Unit + 2 Example)
- ✅ **84 Assertions** 
- ✅ **100% Pass Rate**
- ⚡ **Execution Time**: ~1.37 detik

### Commit Information
- **Commit Hash**: `d8f937d`
- **Commit Message**: "Test dengan PEST"
- **Date**: 8 Oktober 2025, 13:56 WIB
- **Files Changed**: 5 files
- **Lines Added**: +1,317 lines

---

## Setup Testing

### Prerequisites
```bash
# Sudah terinstall via composer.json:
- pestphp/pest: ^4.1
- pestphp/pest-plugin-laravel: ^4.1
- mockery/mockery: ^1.6
- phpunit/phpunit: ^11.5
```

### Konfigurasi

1. **Pest.php Configuration** (`tests/Pest.php`)
```php
uses(TestCase::class, RefreshDatabase::class)->in('Feature');
```

2. **phpunit.xml Settings**
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Database Testing
- Menggunakan **SQLite in-memory** untuk speed
- **RefreshDatabase trait** untuk isolasi test
- Auto-migrate sebelum setiap test

---

## Feature Tests

### File: `tests/Feature/PesanTamuTest.php`

Feature tests menguji **integrasi end-to-end** dari request hingga response.

#### Test Cases (14 tests)

**1. Public Access Tests**
```php
✅ test_pesan_tamu_page_displays_list_of_messages()
   - Memastikan halaman buku tamu dapat diakses
   - Menampilkan daftar pesan yang ada
   - HTTP Status: 200 OK

✅ test_pesan_tamu_page_accessible()
   - Validasi route /bukutamu tersedia
   - Response contains expected elements
```

**2. Form Submission Tests**
```php
✅ test_can_submit_pesan_tamu_with_valid_data()
   - Submit form dengan data valid
   - Data tersimpan ke database
   - Redirect ke halaman buku tamu
   - Flash message success

✅ test_pesan_tamu_list_paginated()
   - Pagination berfungsi (10 per page)
   - Navigation links tersedia
```

**3. Validation Tests**
```php
✅ test_nama_is_required()
   - Field 'nama' wajib diisi
   - Error message ditampilkan

✅ test_email_must_be_valid_format()
   - Email harus format valid
   - Validasi format email

✅ test_pesan_is_required()
   - Field 'pesan' wajib diisi
   - Minimal 10 karakter

✅ test_nama_max_length()
   - Nama maksimal 255 karakter
   - Validasi max length

✅ test_email_max_length()
   - Email maksimal 255 karakter

✅ test_pesan_min_length()
   - Pesan minimal 10 karakter
```

**4. Edge Cases & Error Handling**
```php
✅ test_pesan_tamu_displayed_in_correct_order()
   - Pesan ditampilkan dari yang terbaru (latest)

✅ test_cannot_submit_with_sql_injection_attempt()
   - Proteksi SQL injection
   - Escaped special characters

✅ test_cannot_submit_with_xss_attempt()
   - Proteksi XSS attack
   - HTML entities escaped

✅ test_long_text_handled_properly()
   - Handle text panjang (500 karakter)
   - No truncation issues
```

#### Hasil Test
```
PASS  Tests\Feature\PesanTamuTest
✓ pesan tamu page accessible                              0.15s
✓ pesan tamu page displays list of messages               0.02s
✓ can submit pesan tamu with valid data                   0.03s
✓ nama is required                                        0.02s
✓ email must be valid format                              0.02s
✓ pesan is required                                       0.02s
✓ nama max length                                         0.02s
✓ email max length                                        0.02s
✓ pesan min length                                        0.02s
✓ pesan tamu displayed in correct order                   0.02s
✓ pesan tamu list paginated                               0.02s
✓ cannot submit with sql injection attempt                0.02s
✓ cannot submit with xss attempt                          0.02s
✓ long text handled properly                              0.02s
```

---

## Unit Tests

### File: `tests/Unit/PesanTamuTest.php`

Unit tests menguji **logic internal model** tanpa database interaction.

#### Test Cases (19 tests)

**1. Model Structure Tests**
```php
✅ test_pesan_tamu_has_fillable_attributes()
   - Verify fillable: ['nama', 'email', 'pesan']

✅ test_pesan_tamu_uses_correct_table()
   - Table name: 'pesan_tamus'

✅ test_pesan_tamu_has_timestamps()
   - Timestamps enabled (created_at, updated_at)
```

**2. Attribute Tests**
```php
✅ test_nama_can_be_set_and_retrieved()
✅ test_email_can_be_set_and_retrieved()
✅ test_pesan_can_be_set_and_retrieved()
✅ test_created_at_is_carbon_instance()
✅ test_updated_at_is_carbon_instance()
```

**3. Mass Assignment Tests**
```php
✅ test_can_mass_assign_fillable_attributes()
   - Create dengan array attributes
   - Fill() method berfungsi

✅ test_cannot_mass_assign_id()
   - ID protected dari mass assignment

✅ test_cannot_mass_assign_timestamps()
   - Timestamps auto-managed
```

**4. Type Casting Tests**
```php
✅ test_nama_is_string()
✅ test_email_is_string()
✅ test_pesan_is_string()
```

**5. Serialization Tests**
```php
✅ test_model_can_be_serialized_to_array()
   - toArray() includes all attributes

✅ test_model_can_be_serialized_to_json()
   - toJson() produces valid JSON

✅ test_model_hidden_attributes_not_in_json()
   - Hidden attributes respected
```

**6. Attribute Mutators**
```php
✅ test_nama_is_trimmed()
   - Leading/trailing spaces removed

✅ test_email_is_lowercased()
   - Email normalized to lowercase
```

**7. Validation Scenarios**
```php
✅ test_empty_pesan_tamu()
   - Handle empty object scenario
```

#### Hasil Test
```
PASS  Tests\Unit\PesanTamuTest
✓ pesan tamu has fillable attributes                      0.01s
✓ pesan tamu uses correct table                           0.00s
✓ pesan tamu has timestamps                               0.00s
✓ nama can be set and retrieved                           0.00s
✓ email can be set and retrieved                          0.00s
✓ pesan can be set and retrieved                          0.00s
✓ created at is carbon instance                           0.00s
✓ updated at is carbon instance                           0.00s
✓ can mass assign fillable attributes                     0.01s
✓ cannot mass assign id                                   0.00s
✓ cannot mass assign timestamps                           0.00s
✓ nama is string                                          0.00s
✓ email is string                                         0.00s
✓ pesan is string                                         0.00s
✓ model can be serialized to array                        0.00s
✓ model can be serialized to json                         0.00s
✓ model hidden attributes not in json                     0.00s
✓ nama is trimmed                                         0.00s
✓ email is lowercased                                     0.00s
✓ empty pesan tamu                                        0.00s
```

---

## Menjalankan Tests

### Command Dasar
```bash
# Run all tests
php artisan test

# Run specific file
php artisan test tests/Feature/PesanTamuTest.php

# Run specific test
php artisan test --filter test_can_submit_pesan_tamu_with_valid_data
```

### Pest Commands
```bash
# Run dengan Pest
./vendor/bin/pest

# Watch mode (auto-run on file change)
./vendor/bin/pest --watch

# Parallel execution
./vendor/bin/pest --parallel

# With coverage
./vendor/bin/pest --coverage
```

### Output Options
```bash
# Compact output
php artisan test --compact

# Verbose output
php artisan test --verbose

# Stop on failure
php artisan test --stop-on-failure
```

### Filter Tests
```bash
# By type
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# By group
php artisan test --group=integration
```

---

## Best Practices

### ✅ DOs

1. **Use Descriptive Names**
```php
// ✅ Good
test_can_submit_pesan_tamu_with_valid_data()

// ❌ Bad
test_submit()
```

2. **Arrange-Act-Assert Pattern**
```php
test_example() {
    // Arrange - Setup data
    $data = ['nama' => 'John'];
    
    // Act - Perform action
    $response = $this->post('/bukutamu', $data);
    
    // Assert - Verify result
    $this->assertDatabaseHas('pesan_tamus', $data);
}
```

3. **Test One Thing**
```php
// ✅ Good - Test single responsibility
test_nama_is_required()
test_email_is_required()

// ❌ Bad - Test multiple things
test_form_validation()
```

4. **Use Factories**
```php
// ✅ Good
$pesanTamu = PesanTamu::factory()->create();

// ❌ Bad
$pesanTamu = new PesanTamu([...]);
$pesanTamu->save();
```

### ❌ DON'Ts

1. **Don't Test Framework**
```php
// ❌ Bad - Testing Laravel's pagination
test_pagination_works()

// ✅ Good - Test your implementation
test_pesan_tamu_list_paginated()
```

2. **Don't Rely on Database State**
```php
// ❌ Bad - Depends on existing data
$this->assertDatabaseCount('pesan_tamus', 5);

// ✅ Good - Create your own test data
PesanTamu::factory()->count(5)->create();
$this->assertDatabaseCount('pesan_tamus', 5);
```

3. **Don't Skip Cleanup**
```php
// ✅ Always use RefreshDatabase
use RefreshDatabase;
```

### 📝 Test Documentation

Setiap test harus:
- Self-explanatory dari nama method
- Punya assertion yang jelas
- Independent dari test lain
- Cepat dijalankan (< 100ms)

### 🔄 Continuous Testing

```bash
# Add to .git/hooks/pre-commit
#!/bin/sh
php artisan test
```

---

## Summary

### Test Statistics (Commit d8f937d)

| Metric | Value |
|--------|-------|
| Total Tests | 35 |
| Feature Tests | 14 |
| Unit Tests | 19 |
| Example Tests | 2 |
| Assertions | 84 |
| Pass Rate | 100% |
| Duration | 1.37s |
| Coverage | PesanTamu module |

### Files Added
1. `tests/Feature/PesanTamuTest.php` - 212 lines
2. `tests/Unit/PesanTamuTest.php` - 182 lines
3. `tests/TEST_SUMMARY.md` - 265 lines
4. `tests/TEST_EXAMPLES.md` - 539 lines

### Documentation Updated
- `README.md` - Added 122 lines (testing section)

---

## References

- [Pest Documentation](https://pestphp.com/)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Test Examples](../tests/TEST_EXAMPLES.md)
- [Test Summary](../tests/TEST_SUMMARY.md)

---

**Last Updated**: 8 Oktober 2025
**Maintainer**: Adi Wahyu Pribadi
**Project**: Aplikasi SMP Mentari
