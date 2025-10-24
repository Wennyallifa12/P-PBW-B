<?php

use App\Models\PesanTamu;

test('model adalah instance dari Eloquent Model', function () {
    $model = new PesanTamu();
    
    expect($model)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class);
});

test('fillable attributes terdefinisi dengan benar', function () {
    $fillable = (new PesanTamu())->getFillable();

    expect($fillable)->toContain('nama');
    expect($fillable)->toContain('email');
    expect($fillable)->toContain('pesan');
    expect($fillable)->toHaveCount(3);
});

test('model memiliki atribut nama yang dapat diisi', function () {
    $model = new PesanTamu();
    
    expect($model->isFillable('nama'))->toBeTrue();
});

test('model memiliki atribut email yang dapat diisi', function () {
    $model = new PesanTamu();
    
    expect($model->isFillable('email'))->toBeTrue();
});

test('model memiliki atribut pesan yang dapat diisi', function () {
    $model = new PesanTamu();
    
    expect($model->isFillable('pesan'))->toBeTrue();
});

test('model dapat menggunakan fill method untuk set attributes', function () {
    $model = new PesanTamu();
    
    $model->fill([
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => 'Test pesan',
    ]);
    
    expect($model->nama)->toBe('John Doe');
    expect($model->email)->toBe('john@example.com');
    expect($model->pesan)->toBe('Test pesan');
});

test('model dapat set individual attributes', function () {
    $model = new PesanTamu();
    
    $model->nama = 'Jane Smith';
    $model->email = 'jane@example.com';
    $model->pesan = 'Pesan dari Jane';
    
    expect($model->nama)->toBe('Jane Smith');
    expect($model->email)->toBe('jane@example.com');
    expect($model->pesan)->toBe('Pesan dari Jane');
});

test('model dapat membuat instance dengan constructor array', function () {
    $model = new PesanTamu([
        'nama' => 'Test Name',
        'email' => 'test@example.com',
        'pesan' => 'Test message',
    ]);
    
    expect($model->nama)->toBe('Test Name');
    expect($model->email)->toBe('test@example.com');
    expect($model->pesan)->toBe('Test message');
});

test('model dapat menyimpan nama dengan panjang 255 karakter', function () {
    $model = new PesanTamu();
    $namaPanjang = str_repeat('a', 255);
    
    $model->nama = $namaPanjang;
    
    expect($model->nama)->toBe($namaPanjang);
    expect(strlen($model->nama))->toBe(255);
});

test('model dapat menyimpan email dengan format panjang', function () {
    $model = new PesanTamu();
    $emailPanjang = str_repeat('a', 243) . '@example.com'; // Total 255
    
    $model->email = $emailPanjang;
    
    expect($model->email)->toBe($emailPanjang);
    expect(strlen($model->email))->toBe(255);
});

test('model dapat menyimpan pesan dengan teks yang sangat panjang', function () {
    $model = new PesanTamu();
    $pesanPanjang = str_repeat('Lorem ipsum dolor sit amet. ', 500);
    
    $model->pesan = $pesanPanjang;
    
    expect($model->pesan)->toBe($pesanPanjang);
});

test('model dapat menyimpan karakter khusus dalam nama', function () {
    $model = new PesanTamu();
    $namaKhusus = "O'Brien-Smith Jr. & Co.";
    
    $model->nama = $namaKhusus;
    
    expect($model->nama)->toBe($namaKhusus);
});

test('model dapat menyimpan unicode characters', function () {
    $model = new PesanTamu();
    
    $model->nama = 'Müller 日本語';
    $model->pesan = 'Test pesan dengan emoji 😊';
    
    expect($model->nama)->toBe('Müller 日本語');
    expect($model->pesan)->toContain('😊');
});

test('model dapat get attributes dengan method getAttribute', function () {
    $model = new PesanTamu([
        'nama' => 'Test User',
        'email' => 'test@example.com',
    ]);
    
    expect($model->getAttribute('nama'))->toBe('Test User');
    expect($model->getAttribute('email'))->toBe('test@example.com');
});

test('model dapat set attributes dengan method setAttribute', function () {
    $model = new PesanTamu();
    
    $model->setAttribute('nama', 'New Name');
    $model->setAttribute('email', 'new@example.com');
    
    expect($model->nama)->toBe('New Name');
    expect($model->email)->toBe('new@example.com');
});

test('model dapat mengkonversi ke array', function () {
    $model = new PesanTamu([
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => 'Test pesan',
    ]);
    
    $array = $model->toArray();
    
    expect($array)->toBeArray();
    expect($array)->toHaveKey('nama');
    expect($array)->toHaveKey('email');
    expect($array)->toHaveKey('pesan');
});

test('model dapat mengkonversi ke JSON', function () {
    $model = new PesanTamu([
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => 'Test pesan',
    ]);
    
    $json = $model->toJson();
    
    expect($json)->toBeString();
    expect(json_decode($json))->toBeObject();
});

test('model menggunakan table name yang benar', function () {
    $model = new PesanTamu();
    
    expect($model->getTable())->toBe('pesan_tamus');
});

test('model memiliki primary key yang benar', function () {
    $model = new PesanTamu();
    
    expect($model->getKeyName())->toBe('id');
});
