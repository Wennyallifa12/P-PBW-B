@extends('layouts.app')

@section('title', 'Buku Tamu')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-6">Buku Tamu</h1>
        <p class="text-center text-gray-600 mb-8">Silakan tinggalkan pesan dan kesan Anda di bawah ini.</p>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white p-8 rounded-lg shadow-md mb-8">
            <form action="/bukutamu" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-bold mb-2">Nama</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                        class="shadow appearance-none border @error('nama') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('nama')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="shadow appearance-none border @error('email') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('email')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label for="pesan" class="block text-gray-700 font-bold mb-2">Pesan</label>
                    <textarea id="pesan" name="pesan" rows="4"
                        class="shadow appearance-none border @error('pesan') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('pesan') }}</textarea>
                    @error('pesan')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>

        <hr class="my-8">

        <h2 class="text-2xl font-bold text-center mb-6">Pesan dari Pengunjung</h2>
        <div class="space-y-4">
            @forelse ($daftar_pesan as $pesan)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-lg">{{ $pesan->nama }}</h3>
                        <span class="text-xs text-gray-500">{{ $pesan->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 mt-2">{{ $pesan->pesan }}</p>
                </div>
            @empty
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <p>Belum ada pesan yang masuk. Jadilah yang pertama!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
