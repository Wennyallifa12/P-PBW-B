<?php

use App\Models\PesanTamu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman buku tamu dapat diakses', function () {
    $response = $this->get('/bukutamu');

    $response->assertStatus(200);
    $response->assertViewIs('bukutamu.index');
    $response->assertViewHas('daftar_pesan');
});

test('dapat menyimpan pesan tamu dengan data yang valid', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => 'Terima kasih atas pelayanannya yang sangat baik!',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertRedirect('/bukutamu');
    $response->assertSessionHas('success', 'Pesan Anda telah terkirim!');

    $this->assertDatabaseHas('pesan_tamus', $data);
});

test('validasi nama wajib diisi', function () {
    $data = [
        'nama' => '',
        'email' => 'john@example.com',
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['nama']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('validasi email wajib diisi', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => '',
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('validasi email harus format yang benar', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => 'bukan-email-valid',
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('validasi pesan wajib diisi', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => '',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['pesan']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('nama tidak boleh lebih dari 255 karakter', function () {
    $data = [
        'nama' => str_repeat('a', 256),
        'email' => 'john@example.com',
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['nama']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('email tidak boleh lebih dari 255 karakter', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => str_repeat('a', 244) . '@example.com', // 244 + 12 = 256
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseCount('pesan_tamus', 0);
});

test('dapat menampilkan daftar pesan tamu', function () {
    // Buat beberapa pesan tamu
    PesanTamu::create([
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => 'Pesan pertama',
    ]);

    PesanTamu::create([
        'nama' => 'Jane Smith',
        'email' => 'jane@example.com',
        'pesan' => 'Pesan kedua',
    ]);

    $response = $this->get('/bukutamu');

    $response->assertStatus(200);
    $response->assertSee('John Doe');
    $response->assertSee('Jane Smith');
    $response->assertSee('Pesan pertama');
    $response->assertSee('Pesan kedua');
});

test('pesan tamu ditampilkan berurutan dari yang terbaru', function () {
    // Buat pesan dengan delay kecil untuk memastikan urutan created_at berbeda
    $pesan1 = PesanTamu::create([
        'nama' => 'Pesan Lama',
        'email' => 'old@example.com',
        'pesan' => 'Ini pesan lama',
    ]);

    sleep(1);

    $pesan2 = PesanTamu::create([
        'nama' => 'Pesan Baru',
        'email' => 'new@example.com',
        'pesan' => 'Ini pesan baru',
    ]);

    $response = $this->get('/bukutamu');

    $response->assertStatus(200);
    
    // Ambil data dari view
    $daftarPesan = $response->viewData('daftar_pesan');
    
    // Pesan terbaru harus di posisi pertama
    expect($daftarPesan->first()->id)->toBe($pesan2->id);
    expect($daftarPesan->last()->id)->toBe($pesan1->id);
});

test('old input tetap ada setelah validasi gagal', function () {
    $data = [
        'nama' => 'John Doe',
        'email' => 'bukan-email-valid',
        'pesan' => 'Pesan test',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertSessionHasErrors(['email']);
    
    // Old input harus tersimpan untuk nama dan pesan
    expect(session()->getOldInput('nama'))->toBe('John Doe');
    expect(session()->getOldInput('pesan'))->toBe('Pesan test');
});

test('form buku tamu menampilkan pesan kosong jika belum ada data', function () {
    $response = $this->get('/bukutamu');

    $response->assertStatus(200);
    $response->assertSee('Belum ada pesan yang masuk');
});

test('dapat mengirim pesan dengan nama yang mengandung karakter khusus', function () {
    $data = [
        'nama' => "O'Brien-Smith Jr.",
        'email' => 'obrien@example.com',
        'pesan' => 'Test pesan dengan nama khusus',
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertRedirect('/bukutamu');
    $this->assertDatabaseHas('pesan_tamus', $data);
});

test('dapat mengirim pesan dengan teks panjang', function () {
    $pesanPanjang = str_repeat('Ini adalah pesan yang sangat panjang. ', 100);
    
    $data = [
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'pesan' => $pesanPanjang,
    ];

    $response = $this->post('/bukutamu', $data);

    $response->assertRedirect('/bukutamu');
    $this->assertDatabaseHas('pesan_tamus', [
        'nama' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});
