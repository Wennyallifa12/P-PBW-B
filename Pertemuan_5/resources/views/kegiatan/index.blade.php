@extends('layouts.admin')

@section('title', 'Kelola Kegiatan')

@section('admin_content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">Daftar Kegiatan</h1>
            <a href="{{ route('kegiatan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Kegiatan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left">Judul</th>
                        <th class="py-3 px-6 text-left">Deskripsi</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($kegiatan as $item)
                        <tr class="border-b">
                            <td class="py-3 px-6">{{ $item->judul }}</td>
                            <td class="py-3 px-6">{{ Str::limit($item->deskripsi, 50) }}</td>
                            <td class="py-3 px-6 flex gap-2">
                                <a href="{{ route('kegiatan.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Tidak ada data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection