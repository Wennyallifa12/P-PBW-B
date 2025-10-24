@extends('layouts.admin')

@section('title', 'Tambah Kegiatan Baru')

@section('admin_content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Tambah Kegiatan Baru</h1>
        <form action="{{ route('kegiatan.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="judul" class="block text-gray-700 font-bold mb-2">Judul Kegiatan</label>
                <input type="text" id="judul" name="judul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
                <a href="{{ route('kegiatan.index') }}" class="text-gray-600">Batal</a>
            </div>
        </form>
    </div>
@endsection