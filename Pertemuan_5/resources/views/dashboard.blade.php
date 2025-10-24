{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('admin_title', 'Dashboard')

@section('admin_content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
        <p>Ini adalah pusat kendali untuk website SMP Mentari. Silakan pilih menu di sebelah kiri untuk mulai mengelola konten.</p>

        {{-- Anda bisa memindahkan statistik ke sini jika mau --}}

    </div>
@endsection