# 📚 Flow Konfigurasi Setting Website

> Dokumentasi lengkap tentang sistem setting dinamis di Aplikasi SMP Mentari

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Model Setting](#1-model-setting)
4. [Controller Setting](#2-controller-setting)
5. [View Setting](#3-view-setting)
6. [Controller PageController](#4-controller-pagecontroller)
7. [Flow Diagram](#flow-diagram)
8. [Cara Kerja](#cara-kerja)
9. [Contoh Penggunaan](#contoh-penggunaan)
10. [Best Practices](#best-practices)

---

## Overview

Sistem setting website memungkinkan **admin** untuk mengatur konfigurasi aplikasi secara **dinamis** tanpa perlu mengubah code. Setting disimpan di **database** dan di-**cache** untuk performa optimal.

### ✨ Fitur Utama

- ✅ **Dynamic Configuration**: Ubah setting tanpa edit code
- ✅ **Cache System**: Performa tinggi dengan caching
- ✅ **Type Support**: Support number, boolean, text
- ✅ **Easy to Use**: Helper method yang mudah
- ✅ **Admin Interface**: UI yang user-friendly

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────┐
│                   SETTING SYSTEM                         │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  ┌──────────┐      ┌──────────┐      ┌──────────┐      │
│  │ Database │ <──> │  Model   │ <──> │  Cache   │      │
│  │ settings │      │ Setting  │      │  Redis   │      │
│  └──────────┘      └──────────┘      └──────────┘      │
│                            ▲                             │
│                            │                             │
│                            ▼                             │
│  ┌─────────────────────────────────────────────────┐   │
│  │         Controller                               │   │
│  ├─────────────────────────────────────────────────┤   │
│  │  SettingController  │  PageController           │   │
│  │  - index()          │  - home()                 │   │
│  │  - update()         │  - uses Setting::get()    │   │
│  └─────────────────────────────────────────────────┘   │
│                            ▲                             │
│                            │                             │
│                            ▼                             │
│  ┌─────────────────────────────────────────────────┐   │
│  │         View                                     │   │
│  ├─────────────────────────────────────────────────┤   │
│  │  admin/settings/index.blade.php                 │   │
│  │  - Form untuk edit settings                     │   │
│  └─────────────────────────────────────────────────┘   │
│                                                           │
└─────────────────────────────────────────────────────────┘
```

---

## 1. Model Setting

**File**: `app/Models/Setting.php`

### 📝 Struktur Database

```sql
settings
├── id (bigint, primary key)
├── key (string, unique)      -- Kunci setting (e.g., 'home_kegiatan_per_page')
├── value (text)              -- Nilai setting
├── type (string, nullable)   -- Tipe data: 'number', 'boolean', 'text'
├── description (text, null)  -- Deskripsi untuk admin
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 🔧 Method-Method Penting

#### 1. `get(string $key, $default = null)`

**Fungsi**: Mengambil nilai setting berdasarkan key

```php
/**
 * Get setting value by key
 * 
 * @param string $key - Kunci setting yang akan diambil
 * @param mixed $default - Nilai default jika key tidak ditemukan
 * @return mixed - Nilai setting atau default
 */
public static function get(string $key, $default = null)
{
    // Cache setting selama 3600 detik (1 jam)
    return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    });
}
```

**Cara Kerja**:
1. Cek apakah setting ada di **cache** dengan key `setting.{key}`
2. Jika **ada di cache**: return nilai dari cache (cepat!)
3. Jika **tidak ada di cache**:
   - Query database mencari setting berdasarkan key
   - Jika ditemukan: return `value`
   - Jika tidak ditemukan: return `$default`
   - Simpan hasil ke cache selama 1 jam

**Contoh Penggunaan**:
```php
// Ambil setting dengan default 6
$perPage = Setting::get('home_kegiatan_per_page', 6);

// Output:
// - Jika ada di DB: nilai dari DB (e.g., 9)
// - Jika tidak ada: 6 (default)
```

**Keuntungan Cache**:
```
Without Cache:
Request 1: Query DB (10ms) ← Slow
Request 2: Query DB (10ms) ← Slow
Request 3: Query DB (10ms) ← Slow
Total: 30ms untuk 3 request

With Cache:
Request 1: Query DB + Save Cache (10ms) ← First time
Request 2: Get from Cache (0.1ms) ← Fast!
Request 3: Get from Cache (0.1ms) ← Fast!
Total: 10.2ms untuk 3 request (3x lebih cepat!)
```

---

#### 2. `set(string $key, $value)`

**Fungsi**: Menyimpan/update nilai setting

```php
/**
 * Set setting value
 * 
 * @param string $key - Kunci setting
 * @param mixed $value - Nilai baru setting
 * @return bool - True jika berhasil
 */
public static function set(string $key, $value): bool
{
    // Update jika key sudah ada, Create jika belum
    $setting = self::updateOrCreate(
        ['key' => $key],           // Cari berdasarkan key
        ['value' => $value]        // Update/Create dengan value
    );
    
    // Hapus cache agar nilai baru langsung terambil
    Cache::forget("setting.{$key}");
    
    return $setting->exists;
}
```

**Cara Kerja**:
1. **updateOrCreate**: Laravel akan cek apakah key sudah ada
   - Jika **ada**: update value-nya
   - Jika **belum ada**: buat record baru
2. **Cache::forget**: Hapus cache lama agar request berikutnya ambil dari DB
3. Return true jika berhasil

**Contoh**:
```php
// Set nilai baru
Setting::set('home_kegiatan_per_page', 9);

// Yang terjadi di database:
// UPDATE settings SET value = '9' WHERE key = 'home_kegiatan_per_page'
// atau
// INSERT INTO settings (key, value) VALUES ('home_kegiatan_per_page', '9')

// Yang terjadi di cache:
// Cache::forget('setting.home_kegiatan_per_page') ✅
```

---

#### 3. `clearCache()`

**Fungsi**: Hapus semua cache setting

```php
/**
 * Clear all settings cache
 * 
 * @return void
 */
public static function clearCache(): void
{
    $settings = self::all();  // Ambil semua setting dari DB
    
    foreach ($settings as $setting) {
        Cache::forget("setting.{$setting->key}");
    }
}
```

**Kapan Digunakan**:
- Setelah update banyak setting sekaligus
- Saat ingin memastikan semua nilai fresh dari DB

---

### 💡 Kenapa Pakai Cache?

**Problem**:
```php
// Tanpa cache, setiap request ke homepage akan query DB
Route: / → PageController@home() → Setting::get() → Query DB

Jika ada 1000 visitor per menit:
1000 request × 10ms query = 10,000ms = 10 detik load time!
```

**Solution**:
```php
// Dengan cache, query DB hanya sekali per jam
Request 1: Query DB (10ms) + Save to Cache
Request 2-1000: Read from Cache (0.1ms each)

1000 request × 0.1ms = 100ms = 0.1 detik!
Performance: 100x lebih cepat! 🚀
```

---

## 2. Controller Setting

**File**: `app/Http/Controllers/SettingController.php`

### 📋 Method Overview

```php
SettingController
├── index()     → Tampilkan halaman settings
└── update()    → Simpan perubahan settings
```

---

### Method 1: `index()`

**Fungsi**: Menampilkan halaman admin settings

```php
public function index()
{
    // Ambil semua setting dari DB, urutkan berdasarkan key
    $settings = Setting::orderBy('key')->get();
    
    // Return view dengan data settings
    return view('admin.settings.index', compact('settings'));
}
```

**Flow**:
```
Admin klik "Pengaturan" di sidebar
       ↓
Route: /admin/settings (GET)
       ↓
SettingController@index()
       ↓
Query: SELECT * FROM settings ORDER BY key
       ↓
Return: View dengan data settings
       ↓
User melihat form dengan nilai current
```

**Data yang Dikirim ke View**:
```php
$settings = [
    [
        'id' => 1,
        'key' => 'home_kegiatan_per_page',
        'value' => '9',
        'type' => 'number',
        'description' => 'Jumlah kegiatan per halaman di homepage',
        'created_at' => '2025-10-09 12:00:00',
        'updated_at' => '2025-10-09 12:00:00'
    ],
    // ... setting lainnya
];
```

---

### Method 2: `update()`

**Fungsi**: Menyimpan perubahan settings

```php
public function update(Request $request)
{
    // 1. VALIDASI INPUT
    $validated = $request->validate([
        'settings' => 'required|array',      // Harus array
        'settings.*' => 'required',          // Setiap item harus ada
    ]);

    // 2. LOOP & SIMPAN
    foreach ($validated['settings'] as $key => $value) {
        Setting::set($key, $value);  // Simpan satu per satu
    }

    // 3. CLEAR CACHE
    Setting::clearCache();  // Hapus semua cache setting

    // 4. REDIRECT DENGAN PESAN
    return redirect()->route('settings.index')
        ->with('success', 'Pengaturan berhasil disimpan!');
}
```

**Flow Detail**:

```
1. USER SUBMIT FORM
   ┌─────────────────────────────────────┐
   │ POST /admin/settings                │
   │                                     │
   │ settings[home_kegiatan_per_page]=9  │
   │ settings[site_name]=SMP Mentari     │
   └─────────────────────────────────────┘
                    ↓
                    
2. VALIDASI
   ┌─────────────────────────────────────┐
   │ Cek apakah 'settings' adalah array  │
   │ Cek setiap item tidak kosong        │
   └─────────────────────────────────────┘
                    ↓
                    
3. LOOP SETTINGS
   ┌─────────────────────────────────────┐
   │ foreach ($validated['settings']) {  │
   │   Setting::set($key, $value);       │
   │ }                                   │
   │                                     │
   │ Iterasi 1: key='home_kegiatan_...'  │
   │            value='9'                │
   │            → UPDATE DB              │
   │            → Cache::forget()        │
   │                                     │
   │ Iterasi 2: key='site_name'          │
   │            value='SMP Mentari'      │
   │            → UPDATE DB              │
   │            → Cache::forget()        │
   └─────────────────────────────────────┘
                    ↓
                    
4. CLEAR ALL CACHE
   ┌─────────────────────────────────────┐
   │ Setting::clearCache()               │
   │ → Hapus cache semua setting         │
   └─────────────────────────────────────┘
                    ↓
                    
5. REDIRECT
   ┌─────────────────────────────────────┐
   │ Redirect ke /admin/settings         │
   │ Flash message: "Berhasil disimpan!" │
   └─────────────────────────────────────┘
                    ↓
                    
6. USER MELIHAT
   ┌─────────────────────────────────────┐
   │ Halaman settings dengan alert hijau │
   │ "Pengaturan berhasil disimpan!"     │
   └─────────────────────────────────────┘
```

**Data Request**:
```php
// Data yang dikirim dari form
$request->all() = [
    '_token' => 'abc123...',
    '_method' => 'PUT',
    'settings' => [
        'home_kegiatan_per_page' => '9',
        'site_name' => 'SMP Mentari',
        'maintenance_mode' => '0'
    ]
];
```

**Validasi Rules**:
```php
'settings' => 'required|array'
// ✅ Valid: ['key' => 'value']
// ❌ Invalid: null, 'string', 123

'settings.*' => 'required'
// ✅ Valid: ['key' => 'value']
// ❌ Invalid: ['key' => ''] atau ['key' => null]
```

---

## 3. View Setting

**File**: `resources/views/admin/settings/index.blade.php`

### 🎨 Struktur View

```blade
@extends('layouts.admin')

@section('admin_content')
    
    <!-- 1. SUCCESS MESSAGE -->
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 2. FORM -->
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 3. LOOP SETTINGS -->
        @foreach($settings as $setting)
            <div>
                <label>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                
                <!-- 4. INPUT BERDASARKAN TYPE -->
                @if($setting->type === 'number')
                    <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                    
                @elseif($setting->type === 'boolean')
                    <select name="settings[{{ $setting->key }}]">
                        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    
                @else
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                @endif
            </div>
        @endforeach

        <!-- 5. BUTTONS -->
        <button type="submit">Simpan Perubahan</button>
    </form>
    
@endsection
```

### 🔍 Penjelasan Detail

#### 1. Success Message

```blade
@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif
```

**Cara Kerja**:
- Setelah update berhasil, controller kirim flash message:
  ```php
  ->with('success', 'Pengaturan berhasil disimpan!')
  ```
- Flash message disimpan di session untuk 1 request
- View cek dengan `session('success')`
- Jika ada, tampilkan alert

**Timeline**:
```
POST /admin/settings → Update DB → Redirect with flash
                                           ↓
GET /admin/settings → Read flash → Display alert → Flash gone
```

---

#### 2. Form Structure

```blade
<form action="{{ route('settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    ...
</form>
```

**Penjelasan**:
- `action`: Submit ke route `settings.update` (POST /admin/settings)
- `@csrf`: Token security Laravel (wajib!)
- `@method('PUT')`: Spoofing method ke PUT (untuk update)

**HTML Output**:
```html
<form action="http://localhost/admin/settings" method="POST">
    <input type="hidden" name="_token" value="abc123...">
    <input type="hidden" name="_method" value="PUT">
    ...
</form>
```

---

#### 3. Loop Settings

```blade
@foreach($settings as $setting)
    <div>
        <label>
            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
        </label>
        ...
    </div>
@endforeach
```

**Transformasi Label**:
```php
// Input: 'home_kegiatan_per_page'
str_replace('_', ' ', $setting->key)  // → 'home kegiatan per page'
ucwords(...)                          // → 'Home Kegiatan Per Page'
```

**Data yang Di-loop**:
```php
$settings = [
    ['key' => 'home_kegiatan_per_page', 'value' => '9', 'type' => 'number'],
    ['key' => 'site_name', 'value' => 'SMP Mentari', 'type' => 'text'],
    ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean']
];
```

---

#### 4. Dynamic Input Based on Type

```blade
@if($setting->type === 'number')
    <input type="number" 
           name="settings[{{ $setting->key }}]" 
           value="{{ $setting->value }}"
           min="1" max="50">

@elseif($setting->type === 'boolean')
    <select name="settings[{{ $setting->key }}]">
        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option>
        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option>
    </select>

@else
    <input type="text" 
           name="settings[{{ $setting->key }}]" 
           value="{{ $setting->value }}">
@endif
```

**HTML Output Contoh**:

Setting 1: `home_kegiatan_per_page` (type: number, value: 9)
```html
<input type="number" 
       name="settings[home_kegiatan_per_page]" 
       value="9"
       min="1" max="50">
```

Setting 2: `maintenance_mode` (type: boolean, value: 0)
```html
<select name="settings[maintenance_mode]">
    <option value="1">Ya</option>
    <option value="0" selected>Tidak</option>
</select>
```

Setting 3: `site_name` (type: text, value: 'SMP Mentari')
```html
<input type="text" 
       name="settings[site_name]" 
       value="SMP Mentari">
```

**Kenapa Pakai Array Name**?

```blade
name="settings[{{ $setting->key }}]"
```

**Output**:
```html
name="settings[home_kegiatan_per_page]"
name="settings[site_name]"
name="settings[maintenance_mode]"
```

**Request Data**:
```php
// Akan jadi array di controller:
$request->settings = [
    'home_kegiatan_per_page' => '9',
    'site_name' => 'SMP Mentari',
    'maintenance_mode' => '0'
];

// Mudah di-loop di controller:
foreach ($request->settings as $key => $value) {
    Setting::set($key, $value);
}
```

---

## 4. Controller PageController

**File**: `app/Http/Controllers/PageController.php`

### 🏠 Method `home()`

```php
public function home()
{
    // 1. AMBIL SETTING
    $perPage = Setting::get('home_kegiatan_per_page', 6);
    
    // 2. QUERY KEGIATAN
    $kegiatan_terbaru = Kegiatan::latest()->paginate($perPage);
    
    // 3. RETURN VIEW
    return view('home', ['kegiatan_terbaru' => $kegiatan_terbaru]);
}
```

### 🔍 Penjelasan Step by Step

#### Step 1: Ambil Setting

```php
$perPage = Setting::get('home_kegiatan_per_page', 6);
```

**Flow**:
```
1. Cek cache: "setting.home_kegiatan_per_page"
   ├─ Jika ADA di cache: return '9' ← Fast! (0.1ms)
   └─ Jika TIDAK ada:
      ├─ Query DB: SELECT * FROM settings WHERE key = 'home_kegiatan_per_page'
      ├─ Jika ditemukan: return '9'
      ├─ Jika tidak: return 6 (default)
      └─ Save to cache for 1 hour
```

**Result**:
```php
$perPage = 9;  // Atau 6 jika tidak ada setting
```

---

#### Step 2: Query Kegiatan

```php
$kegiatan_terbaru = Kegiatan::latest()->paginate($perPage);
```

**Breakdown**:
```php
Kegiatan::latest()              // ORDER BY created_at DESC
        ->paginate($perPage);   // LIMIT $perPage OFFSET 0
```

**SQL Yang Dijalankan**:
```sql
-- Query 1: Ambil data
SELECT * FROM kegiatans 
ORDER BY created_at DESC 
LIMIT 9 OFFSET 0;

-- Query 2: Hitung total (untuk pagination)
SELECT COUNT(*) FROM kegiatans;
```

**Result Object**:
```php
$kegiatan_terbaru = [
    'current_page' => 1,
    'data' => [
        ['id' => 1, 'judul' => 'Kegiatan 1', ...],
        ['id' => 2, 'judul' => 'Kegiatan 2', ...],
        // ... 9 items
    ],
    'per_page' => 9,
    'total' => 25,
    'last_page' => 3,
    'links' => [...]  // Untuk pagination links
];
```

---

#### Step 3: Return View

```php
return view('home', ['kegiatan_terbaru' => $kegiatan_terbaru]);
```

**View File**: `resources/views/home.blade.php`

**Bisa Akses**:
```blade
@foreach($kegiatan_terbaru as $kegiatan)
    <h3>{{ $kegiatan->judul }}</h3>
@endforeach

{{ $kegiatan_terbaru->links() }}  <!-- Pagination links -->
```

---

### 🔗 Keterkaitannya dengan Setting

```
┌────────────────────────────────────────────────────────┐
│                    FLOW LENGKAP                         │
├────────────────────────────────────────────────────────┤
│                                                          │
│  1. ADMIN UBAH SETTING                                  │
│     ┌─────────────────────────────────────────┐        │
│     │ Admin buka /admin/settings              │        │
│     │ SettingController@index()               │        │
│     │ → Tampilkan form dengan nilai current   │        │
│     │                                          │        │
│     │ Admin ubah 'home_kegiatan_per_page'     │        │
│     │ dari 6 → 9                              │        │
│     │                                          │        │
│     │ Submit form                              │        │
│     │ SettingController@update()              │        │
│     │ → Setting::set('home_kegiatan_...', 9)  │        │
│     │ → UPDATE settings SET value='9'...      │        │
│     │ → Cache::forget('setting.home_...')     │        │
│     │ → Redirect dengan success message       │        │
│     └─────────────────────────────────────────┘        │
│                         ↓                                │
│                                                          │
│  2. USER VISIT HOMEPAGE                                 │
│     ┌─────────────────────────────────────────┐        │
│     │ User buka /                             │        │
│     │ PageController@home()                   │        │
│     │                                          │        │
│     │ Step 1: Ambil setting                   │        │
│     │ $perPage = Setting::get('home_...', 6)  │        │
│     │ → Cek cache                             │        │
│     │ → Cache KOSONG (karna di-forget)       │        │
│     │ → Query DB                              │        │
│     │ → Dapat value = '9'                     │        │
│     │ → Save to cache                         │        │
│     │ → Return 9                              │        │
│     │                                          │        │
│     │ Step 2: Query kegiatan                  │        │
│     │ Kegiatan::latest()->paginate(9)         │        │
│     │ → SELECT * ... LIMIT 9                  │        │
│     │ → Return 9 kegiatan                     │        │
│     │                                          │        │
│     │ Step 3: Return view                     │        │
│     │ → User melihat 9 kegiatan per page      │        │
│     └─────────────────────────────────────────┘        │
│                         ↓                                │
│                                                          │
│  3. SUBSEQUENT REQUESTS (CEPAT!)                        │
│     ┌─────────────────────────────────────────┐        │
│     │ User lain buka /                        │        │
│     │ PageController@home()                   │        │
│     │                                          │        │
│     │ $perPage = Setting::get('home_...', 6)  │        │
│     │ → Cek cache                             │        │
│     │ → Cache ADA! value = '9' ← FAST!        │        │
│     │ → Return 9 (tanpa query DB)            │        │
│     │                                          │        │
│     │ Kegiatan::latest()->paginate(9)         │        │
│     │ → Return 9 kegiatan                     │        │
│     └─────────────────────────────────────────┘        │
│                                                          │
└────────────────────────────────────────────────────────┘
```

---

## Flow Diagram

### 📊 Complete Flow

```
┌───────────────────────────────────────────────────────────────────────┐
│                         SETTING SYSTEM FLOW                            │
└───────────────────────────────────────────────────────────────────────┘

SCENARIO 1: ADMIN UPDATE SETTING
═════════════════════════════════════════════════════════���═════════════

┌─────────┐
│  Admin  │
└────┬────┘
     │ 1. Buka /admin/settings
     ▼
┌─────────────────────┐
│ SettingController   │
│ @index()            │
├─────────────────────┤
│ Setting::orderBy()  │
│ ->get()             │
└────┬────────────────┘
     │ 2. Query all settings
     ▼
┌─────────────────────┐
│    Database         │
│  SELECT * FROM      │
│  settings           │
└────┬────────────────┘
     │ 3. Return data
     ▼
┌─────────────────────┐
│  View: index.blade  │
│  - Form with values │
│  - Input per type   │
└────┬────────────────┘
     │ 4. Admin edit & submit
     │ POST /admin/settings
     │ settings[home_kegiatan_per_page]=9
     ▼
┌─────────────────────┐
│ SettingController   │
│ @update()           │
├─────────────────────┤
│ 1. Validate input   │
│ 2. Loop settings    │
│ 3. Clear cache      │
│ 4. Redirect         │
└────┬────────────────┘
     │ 5. Setting::set('home_...', 9)
     ▼
┌─────────────────────┐
│  Model: Setting     │
│  @set()             │
├─────────────────────┤
│ updateOrCreate()    │
│ Cache::forget()     │
└────┬────────────────┘
     │ 6. UPDATE DB
     ▼
┌─────────────────────┐
│    Database         │
│  UPDATE settings    │
│  SET value = '9'    │
│  WHERE key = '...'  │
└────┬────────────────┘
     │ 7. Success
     ▼
┌─────────────────────┐
│    Cache            │
│  DELETE             │
│  setting.home_...   │
└────┬────────────────┘
     │ 8. Redirect with success
     ▼
┌─────────────────────┐
│  View: index.blade  │
│  Alert: "Berhasil!" │
└─────────────────────┘


SCENARIO 2: USER VISIT HOMEPAGE (FIRST TIME AFTER UPDATE)
═══════════════════════════════════════════════════════════

┌─────────┐
│  User   │
└────┬────┘
     │ 1. Buka /
     ▼
┌─────────────────────┐
│  PageController     │
│  @home()            │
└────┬────────────────┘
     │ 2. Setting::get('home_...', 6)
     ▼
┌─────────────────────┐
│  Model: Setting     │
│  @get()             │
├─────────────────────┤
│ Cache::remember()   │
└────┬────────────────┘
     │ 3. Cek cache
     ▼
┌─────────────────────┐
│    Cache            │
│  GET                │
│  setting.home_...   │
└────┬────────────────┘
     │ 4. MISS! (Cache kosong)
     ▼
┌─────────────────────┐
│    Database         │
│  SELECT * FROM      │
│  settings           │
│  WHERE key = '...'  │
└────┬────────────────┘
     │ 5. Return value = '9'
     ▼
┌─────────────────────┐
│    Cache            │
│  SET                │
│  setting.home_...   │
│  value = '9'        │
│  expire = 3600s     │
└────┬────────────────┘
     │ 6. Return 9
     ▼
┌─────────────────────┐
│  PageController     │
│  @home()            │
├─────────────────────┤
│ $perPage = 9        │
│ Kegiatan::latest()  │
│ ->paginate(9)       │
└────┬────────────────┘
     │ 7. Query kegiatan
     ▼
┌─────────────────────┐
│    Database         │
│  SELECT * FROM      │
│  kegiatans          │
│  ORDER BY...        │
│  LIMIT 9 OFFSET 0   │
└────┬────────────────┘
     │ 8. Return 9 records
     ▼
┌─────────────────────┐
│  View: home.blade   │
│  - Display 9 items  │
│  - Pagination       │
└─────────────────────┘


SCENARIO 3: USER VISIT HOMEPAGE (SUBSEQUENT REQUESTS)
═══════════════════════════════════════════════════════

┌─────────┐
│  User2  │
└────┬────┘
     │ 1. Buka /
     ▼
┌─────────────────────┐
│  PageController     │
│  @home()            │
└────┬────────────────┘
     │ 2. Setting::get('home_...', 6)
     ▼
┌─────────────────────┐
│  Model: Setting     │
│  @get()             │
├─────────────────────┤
│ Cache::remember()   │
└────┬────────────────┘
     │ 3. Cek cache
     ▼
┌─────────────────────┐
│    Cache            │
│  GET                │
│  setting.home_...   │
└────┬────────────────┘
     │ 4. HIT! value = '9' ← FAST!
     │ (No DB query needed)
     ▼
┌─────────────────────┐
│  PageController     │
│  @home()            │
├─────────────────────┤
│ $perPage = 9        │
│ Kegiatan::latest()  │
│ ->paginate(9)       │
└────┬────────────────┘
     │ 5. Query kegiatan
     ▼
┌─────────────────────┐
│    Database         │
│  SELECT * FROM      │
│  kegiatans          │
│  ORDER BY...        │
│  LIMIT 9 OFFSET 0   │
└────┬────────────────┘
     │ 6. Return 9 records
     ▼
┌─────────────────────┐
│  View: home.blade   │
│  - Display 9 items  │
│  - Pagination       │
└─────────────────────┘
```

---

## Cara Kerja

### 🔄 Timeline Lengkap

```
TIME 0: Initial State
────────────────────────────────────────────────────────
Database:
  settings table:
    key: 'home_kegiatan_per_page'
    value: '6'
    
Cache:
  (empty)

Homepage: Menampilkan 6 kegiatan per page


TIME 1: Admin Update Setting (10:00:00)
────────────────────────────────────────────────────────
Admin Action:
  1. Buka /admin/settings
  2. Lihat form dengan nilai current (6)
  3. Ubah ke 9
  4. Submit

System Process:
  1. SettingController@update() menerima data
  2. Validasi: ✅ Pass
  3. Loop: Setting::set('home_kegiatan_per_page', 9)
  4. Database: UPDATE value = '9'
  5. Cache: DELETE 'setting.home_kegiatan_per_page'
  6. Redirect dengan success message

Result:
  Database: value = '9' ✅
  Cache: (empty) ✅


TIME 2: First User Visit After Update (10:00:05)
────────────────────────────────────────────────────────
User Action:
  Buka homepage /

System Process:
  1. PageController@home()
  2. Setting::get('home_kegiatan_per_page', 6)
  3. Cek cache: MISS (karena baru di-delete)
  4. Query DB: value = '9'
  5. Save to cache (expire: 1 hour)
  6. Return 9
  7. Kegiatan::latest()->paginate(9)
  8. Display homepage

Performance:
  Setting query: 10ms (DB query)
  Kegiatan query: 15ms
  Total: 25ms

Result:
  Cache: value = '9', expire at 11:00:05 ✅
  User melihat: 9 kegiatan per page ✅


TIME 3: Other Users Visit (10:00:10 - 10:59:59)
────────────────────────────────────────────────────────
Many users visit homepage

System Process:
  1. PageController@home()
  2. Setting::get('home_kegiatan_per_page', 6)
  3. Cek cache: HIT! ← Value dari cache
  4. Return 9 (no DB query needed)
  5. Kegiatan::latest()->paginate(9)
  6. Display homepage

Performance:
  Setting query: 0.1ms (cache hit)
  Kegiatan query: 15ms
  Total: 15.1ms ← 40% faster!

Result:
  All users melihat: 9 kegiatan per page ✅


TIME 4: Cache Expired (11:00:06)
────────────────────────────────────────────────────────
Cache expired after 1 hour

System Process:
  1. User buka homepage
  2. Setting::get('home_kegiatan_per_page', 6)
  3. Cek cache: MISS (expired)
  4. Query DB: value = '9'
  5. Save to cache (expire: 12:00:06)
  6. Continue...

Result:
  Cache refreshed ✅
  System continue working normally ✅
```

---

## Contoh Penggunaan

### 📝 Contoh 1: Menambah Setting Baru

**Step 1: Tambah di Database**

```php
// Via migration atau seeder
use App\Models\Setting;

Setting::create([
    'key' => 'site_name',
    'value' => 'SMP Mentari',
    'type' => 'text',
    'description' => 'Nama website yang ditampilkan di header'
]);
```

**Step 2: Otomatis Muncul di Admin Panel**

Form di `/admin/settings` akan otomatis menampilkan field baru karena pakai loop:

```blade
@foreach($settings as $setting)
    {{-- Akan include setting baru --}}
@endforeach
```

**Step 3: Gunakan di Controller/View**

```php
// Di controller
$siteName = Setting::get('site_name', 'Default Name');

// Di view
<h1>{{ Setting::get('site_name', 'SMP Mentari') }}</h1>
```

---

### 📝 Contoh 2: Setting Boolean (Maintenance Mode)

**Database**:
```php
Setting::create([
    'key' => 'maintenance_mode',
    'value' => '0',  // 0 = false, 1 = true
    'type' => 'boolean',
    'description' => 'Aktifkan mode maintenance'
]);
```

**View (Otomatis jadi dropdown)**:
```blade
@if($setting->type === 'boolean')
    <select name="settings[{{ $setting->key }}]">
        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option>
        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option>
    </select>
@endif
```

**Penggunaan di Middleware**:
```php
class MaintenanceMiddleware
{
    public function handle($request, $next)
    {
        if (Setting::get('maintenance_mode', '0') === '1') {
            return response()->view('maintenance');
        }
        
        return $next($request);
    }
}
```

---

### 📝 Contoh 3: Multiple Settings

**Update banyak setting sekaligus**:

```php
// Form submit
POST /admin/settings
settings[home_kegiatan_per_page] = 9
settings[site_name] = SMP Mentari
settings[maintenance_mode] = 0
settings[items_per_page_admin] = 15

// Controller akan loop semua
foreach ($validated['settings'] as $key => $value) {
    Setting::set($key, $value);  // Update 4x
}

Setting::clearCache();  // Clear cache 1x
```

---

## Best Practices

### ✅ DO's

1. **Selalu Gunakan Default Value**
   ```php
   // ✅ Good
   $perPage = Setting::get('home_kegiatan_per_page', 6);
   
   // ❌ Bad
   $perPage = Setting::get('home_kegiatan_per_page');  // Bisa null!
   ```

2. **Type Hint Setting**
   ```php
   // ✅ Good - Cast ke integer
   $perPage = (int) Setting::get('home_kegiatan_per_page', 6);
   
   // ✅ Good - Cast ke boolean
   $isActive = Setting::get('maintenance_mode', '0') === '1';
   ```

3. **Clear Cache After Bulk Update**
   ```php
   // ✅ Good
   foreach ($settings as $key => $value) {
       Setting::set($key, $value);
   }
   Setting::clearCache();  // Clear once
   
   // ❌ Bad
   foreach ($settings as $key => $value) {
       Setting::set($key, $value);
       Cache::forget("setting.{$key}");  // Redundant!
   }
   ```

4. **Validasi Input Berdasarkan Type**
   ```php
   // ✅ Good
   $rules = [];
   foreach ($settings as $setting) {
       if ($setting->type === 'number') {
           $rules["settings.{$setting->key}"] = 'required|integer|min:1|max:50';
       } elseif ($setting->type === 'boolean') {
           $rules["settings.{$setting->key}"] = 'required|in:0,1';
       } else {
           $rules["settings.{$setting->key}"] = 'required|string|max:255';
       }
   }
   $request->validate($rules);
   ```

5. **Gunakan Konstanta untuk Key**
   ```php
   // ✅ Good
   class SettingKey {
       const HOME_PER_PAGE = 'home_kegiatan_per_page';
       const SITE_NAME = 'site_name';
   }
   
   $perPage = Setting::get(SettingKey::HOME_PER_PAGE, 6);
   // Benefit: Autocomplete, Type-safe, Easy refactor
   ```

---

### ❌ DON'Ts

1. **Jangan Query DB Langsung**
   ```php
   // ❌ Bad - Bypass cache
   $setting = DB::table('settings')->where('key', 'home_...')->first();
   
   // ✅ Good - Use cache
   $value = Setting::get('home_kegiatan_per_page', 6);
   ```

2. **Jangan Hardcode Values**
   ```php
   // ❌ Bad
   $kegiatan = Kegiatan::latest()->paginate(6);  // Hardcoded!
   
   // ✅ Good
   $perPage = Setting::get('home_kegiatan_per_page', 6);
   $kegiatan = Kegiatan::latest()->paginate($perPage);
   ```

3. **Jangan Lupa Clear Cache**
   ```php
   // ❌ Bad - Cache tidak ter-clear
   Setting::where('key', 'home_...')->update(['value' => 9]);
   // Cache masih value lama!
   
   // ✅ Good
   Setting::set('home_kegiatan_per_page', 9);
   // Cache otomatis di-clear
   ```

4. **Jangan Set Cache TTL Terlalu Pendek**
   ```php
   // ❌ Bad - Cache 60 detik, DB tetap sering di-hit
   Cache::remember("setting.{$key}", 60, function() {...});
   
   // ✅ Good - Cache 1 jam, balance antara freshness & performance
   Cache::remember("setting.{$key}", 3600, function() {...});
   ```

---

## 🎓 Kesimpulan

### Ringkasan Flow

```
1. MODEL (Setting.php)
   ├─ Menyediakan method get() untuk ambil setting dengan cache
   ├─ Menyediakan method set() untuk simpan setting
   └─ Menyediakan method clearCache() untuk hapus cache

2. CONTROLLER (SettingController.php)
   ├─ index(): Tampilkan form edit settings
   └─ update(): Simpan perubahan settings

3. VIEW (admin/settings/index.blade.php)
   ├─ Loop semua settings dari DB
   ├─ Tampilkan input sesuai type (number/boolean/text)
   └─ Submit form untuk update

4. PAGE CONTROLLER (PageController.php)
   ├─ Gunakan Setting::get() untuk ambil konfigurasi
   └─ Apply setting ke query (pagination, dll)
```

### Key Benefits

- 🚀 **Performance**: Cache mengurangi query DB hingga 100x
- ⚙️ **Flexibility**: Ubah config tanpa edit code
- 🎨 **User-Friendly**: Admin panel yang mudah digunakan
- 🔄 **Dynamic**: Perubahan langsung terlihat
- 📦 **Scalable**: Mudah tambah setting baru

### Pola Pikir

```
Database → Cache → Application → User

Database: Source of truth (value = 9)
Cache: Performance layer (TTL 1 hour)
Application: Logic layer (use Setting::get())
User: See the result (9 items per page)
```

---

**Dibuat**: 15 Oktober 2025  
**Project**: Aplikasi SMP Mentari  
**Tech Stack**: Laravel 12, Eloquent, Redis Cache
