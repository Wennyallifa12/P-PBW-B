@extends('layouts.app')

@section('title', 'Selamat Datang di SMP Mentari')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Kegiatan Terbaru di SMP Mentari</h1>
        <p class="text-lg text-gray-600 mt-2">Selamat datang di website resmi kami. Berikut adalah beberapa kegiatan yang telah kami laksanakan.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse ($kegiatan_terbaru as $kegiatan)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $kegiatan->judul }}</h3>
                <p class="text-gray-700">{{ $kegiatan->deskripsi }}</p>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative inline-block" role="alert">
                    <p class="font-bold">Belum Ada Kegiatan</p>
                    <p class="text-sm">Belum ada kegiatan yang dipublikasikan. Silakan cek kembali nanti.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    @if($kegiatan_terbaru->hasPages())
        <div class="mt-6">
            {{ $kegiatan_terbaru->links() }}
        </div>
    @endif
@endsection