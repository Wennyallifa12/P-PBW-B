# 📖 Test Examples & Patterns

Kumpulan contoh test patterns yang digunakan dalam aplikasi SMP Mentari untuk referensi pengembangan selanjutnya.

## 📑 Table of Contents

1. [Feature Test Patterns](#feature-test-patterns)
2. [Unit Test Patterns](#unit-test-patterns)
3. [Common Assertions](#common-assertions)
4. [Test Helpers](#test-helpers)

---

## Feature Test Patterns

### 1. Testing GET Routes

```php
test('halaman dapat diakses', function () {
    $response = $this->get('/route');

    $response->assertStatus(200);
    $response->assertViewIs('view.name');
    $response->assertViewHas('variable');
});
```

### 2. Testing POST with Valid Data

```php
test('dapat submit form dengan data valid', function () {
    $data = [
        'field1' => 'value1',
        'field2' => 'value2',
    ];

    $response = $this->post('/route', $data);

    $response->assertRedirect('/success-route');
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('table_name', $data);
});
```

### 3. Testing Validation - Required Fields

```php
test('validasi field wajib diisi', function () {
    $data = [
        'field1' => '',  // Empty value
        'field2' => 'value2',
    ];

    $response = $this->post('/route', $data);

    $response->assertSessionHasErrors(['field1']);
    $this->assertDatabaseCount('table_name', 0);
});
```

### 4. Testing Validation - Format

```php
test('validasi format email', function () {
    $data = [
        'email' => 'invalid-email-format',
    ];

    $response = $this->post('/route', $data);

    $response->assertSessionHasErrors(['email']);
});
```

### 5. Testing Validation - Max Length

```php
test('validasi maksimal panjang field', function () {
    $data = [
        'field' => str_repeat('a', 256),  // Over max length
    ];

    $response = $this->post('/route', $data);

    $response->assertSessionHasErrors(['field']);
});
```

### 6. Testing Display Data

```php
test('dapat menampilkan data', function () {
    // Create test data
    Model::create([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);

    $response = $this->get('/route');

    $response->assertStatus(200);
    $response->assertSee('value1');
    $response->assertSee('value2');
});
```

### 7. Testing Data Order

```php
test('data ditampilkan dengan urutan tertentu', function () {
    $item1 = Model::create(['name' => 'First']);
    sleep(1);
    $item2 = Model::create(['name' => 'Second']);

    $response = $this->get('/route');
    $data = $response->viewData('items');

    expect($data->first()->id)->toBe($item2->id);
    expect($data->last()->id)->toBe($item1->id);
});
```

### 8. Testing Old Input After Validation Failure

```php
test('old input tersimpan setelah validasi gagal', function () {
    $data = [
        'field1' => 'value1',
        'field2' => 'invalid',
    ];

    $response = $this->post('/route', $data);

    $response->assertSessionHasErrors(['field2']);
    expect(session()->getOldInput('field1'))->toBe('value1');
});
```

### 9. Testing Empty State

```php
test('menampilkan pesan kosong jika tidak ada data', function () {
    $response = $this->get('/route');

    $response->assertStatus(200);
    $response->assertSee('Tidak ada data');
});
```

### 10. Testing Special Characters

```php
test('dapat menyimpan karakter khusus', function () {
    $data = [
        'name' => "O'Brien-Smith & Co.",
        'message' => 'Test with "quotes" and \'apostrophes\'',
    ];

    $response = $this->post('/route', $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('table', $data);
});
```

---

## Unit Test Patterns

### 1. Testing Model Instance

```php
test('model adalah instance dari class yang benar', function () {
    $model = new Model();
    
    expect($model)->toBeInstanceOf(BaseClass::class);
});
```

### 2. Testing Fillable Attributes

```php
test('fillable attributes terdefinisi', function () {
    $fillable = (new Model())->getFillable();

    expect($fillable)->toContain('field1');
    expect($fillable)->toContain('field2');
    expect($fillable)->toHaveCount(2);
});
```

### 3. Testing isFillable

```php
test('field dapat diisi menggunakan mass assignment', function () {
    $model = new Model();
    
    expect($model->isFillable('field_name'))->toBeTrue();
});
```

### 4. Testing Fill Method

```php
test('dapat menggunakan fill method', function () {
    $model = new Model();
    
    $model->fill([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);
    
    expect($model->field1)->toBe('value1');
    expect($model->field2)->toBe('value2');
});
```

### 5. Testing Constructor with Array

```php
test('dapat membuat instance dengan constructor array', function () {
    $model = new Model([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);
    
    expect($model->field1)->toBe('value1');
    expect($model->field2)->toBe('value2');
});
```

### 6. Testing Attribute Setters

```php
test('dapat set attribute individual', function () {
    $model = new Model();
    
    $model->field1 = 'value1';
    $model->field2 = 'value2';
    
    expect($model->field1)->toBe('value1');
    expect($model->field2)->toBe('value2');
});
```

### 7. Testing getAttribute Method

```php
test('dapat get attribute dengan method', function () {
    $model = new Model(['field' => 'value']);
    
    expect($model->getAttribute('field'))->toBe('value');
});
```

### 8. Testing setAttribute Method

```php
test('dapat set attribute dengan method', function () {
    $model = new Model();
    
    $model->setAttribute('field', 'value');
    
    expect($model->field)->toBe('value');
});
```

### 9. Testing Max Length

```php
test('dapat menyimpan data dengan panjang maksimal', function () {
    $model = new Model();
    $longValue = str_repeat('a', 255);
    
    $model->field = $longValue;
    
    expect($model->field)->toBe($longValue);
    expect(strlen($model->field))->toBe(255);
});
```

### 10. Testing Unicode & Special Characters

```php
test('dapat menyimpan unicode characters', function () {
    $model = new Model();
    
    $model->name = 'Müller 日本語';
    $model->message = 'Test 😊 emoji';
    
    expect($model->name)->toBe('Müller 日本語');
    expect($model->message)->toContain('😊');
});
```

### 11. Testing Serialization to Array

```php
test('dapat convert ke array', function () {
    $model = new Model([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);
    
    $array = $model->toArray();
    
    expect($array)->toBeArray();
    expect($array)->toHaveKey('field1');
    expect($array)->toHaveKey('field2');
});
```

### 12. Testing Serialization to JSON

```php
test('dapat convert ke JSON', function () {
    $model = new Model([
        'field1' => 'value1',
        'field2' => 'value2',
    ]);
    
    $json = $model->toJson();
    
    expect($json)->toBeString();
    expect(json_decode($json))->toBeObject();
});
```

### 13. Testing Table Name

```php
test('menggunakan table name yang benar', function () {
    $model = new Model();
    
    expect($model->getTable())->toBe('table_name');
});
```

### 14. Testing Primary Key

```php
test('memiliki primary key yang benar', function () {
    $model = new Model();
    
    expect($model->getKeyName())->toBe('id');
});
```

---

## Common Assertions

### Response Assertions

```php
// Status codes
$response->assertStatus(200);
$response->assertOk();
$response->assertCreated();
$response->assertNotFound();

// Redirects
$response->assertRedirect('/route');
$response->assertRedirectToRoute('route.name');

// View
$response->assertViewIs('view.name');
$response->assertViewHas('variable');
$response->assertViewHas('variable', 'value');
$response->assertViewMissing('variable');

// Content
$response->assertSee('text');
$response->assertDontSee('text');
$response->assertSeeText('text');
$response->assertJson(['key' => 'value']);
```

### Database Assertions

```php
// Has record
$this->assertDatabaseHas('table', ['field' => 'value']);

// Missing record
$this->assertDatabaseMissing('table', ['field' => 'value']);

// Count records
$this->assertDatabaseCount('table', 5);

// Soft deletes
$this->assertSoftDeleted('table', ['id' => 1]);
```

### Session Assertions

```php
// Has data
$response->assertSessionHas('key');
$response->assertSessionHas('key', 'value');

// Missing data
$response->assertSessionMissing('key');

// Has errors
$response->assertSessionHasErrors(['field']);
$response->assertSessionHasErrors(['field' => 'error message']);

// No errors
$response->assertSessionHasNoErrors();
```

### Pest Expectations

```php
// Type checks
expect($value)->toBeString();
expect($value)->toBeInt();
expect($value)->toBeBool();
expect($value)->toBeArray();
expect($value)->toBeObject();
expect($value)->toBeNull();

// Value checks
expect($value)->toBe('expected');
expect($value)->toEqual($expected);
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeEmpty();

// Array/Collection checks
expect($array)->toContain('value');
expect($array)->toHaveKey('key');
expect($array)->toHaveCount(5);

// String checks
expect($string)->toStartWith('prefix');
expect($string)->toEndWith('suffix');
expect($string)->toContain('substring');

// Numeric checks
expect($number)->toBeGreaterThan(5);
expect($number)->toBeLessThan(10);
expect($number)->toBeGreaterThanOrEqual(5);

// Instance checks
expect($object)->toBeInstanceOf(ClassName::class);
```

---

## Test Helpers

### Using RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('example', function () {
    // Database will be reset before this test
});
```

### Using Factories

```php
test('example dengan factory', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
    ]);
    
    expect($user->name)->toBe('John Doe');
});
```

### Setup and Teardown

```php
beforeEach(function () {
    // Runs before each test
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Runs after each test
});
```

### Grouping Tests

```php
describe('User Authentication', function () {
    test('can login', function () {
        // ...
    });
    
    test('can logout', function () {
        // ...
    });
});
```

### Skipping Tests

```php
test('example')->skip('Reason for skipping');

test('example')->skip(fn() => true, 'Conditional skip');
```

### Testing Exceptions

```php
test('throws exception', function () {
    expect(fn() => methodThatThrows())
        ->toThrow(ExceptionClass::class);
});
```

---

## 💡 Tips & Best Practices

1. **Test Naming**: Gunakan nama yang jelas dan deskriptif
2. **One Assertion Per Test**: Idealnya test fokus pada satu hal
3. **AAA Pattern**: Arrange → Act → Assert
4. **Avoid Sleep**: Gunakan time travel atau mock untuk test timing
5. **Independent Tests**: Setiap test harus bisa jalan sendiri
6. **Clear Test Data**: Gunakan data yang jelas dan mudah dipahami
7. **Test Edge Cases**: Jangan lupa test batas-batas dan kondisi ekstrim

---

**Reference Documentation**:
- [Pest PHP](https://pestphp.com/docs)
- [Laravel HTTP Tests](https://laravel.com/docs/http-tests)
- [Laravel Database Testing](https://laravel.com/docs/database-testing)
