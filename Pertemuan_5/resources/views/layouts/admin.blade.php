{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Halaman (Opsional, tapi bagus untuk judul) --}}
        <header class="bg-white shadow mb-6 rounded-lg">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @yield('admin_title', 'Area Admin')
                </h2>
            </div>
        </header>

        <div class="flex flex-col md:flex-row gap-6">

            <aside class="md:w-1/4 flex-shrink-0">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Section Master</h3>
                    <ul class="space-y-2">
                        <li>
                            {{-- Link ke Dashboard Utama --}}
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 font-bold text-blue-700' : '' }}">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            {{-- Link ke CRUD Kegiatan --}}
                            <a href="{{ route('kegiatan.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('kegiatan.*') ? 'bg-blue-100 font-bold text-blue-700' : '' }}">
                                Kegiatan
                            </a>
                        </li>
                        <li>
                            {{-- Link ke Manajemen Buku Tamu --}}
                            <a href="{{ route('bukutamu.admin.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('bukutamu.admin.*') ? 'bg-blue-100 font-bold text-blue-700' : '' }}">
                                Buku Tamu
                            </a>
                        </li>
                    </ul>

                    <hr class="my-4">

                    <h3 class="font-bold text-lg mb-2 text-gray-800">Pengaturan</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('settings.*') ? 'bg-blue-100 font-bold text-blue-700' : '' }}">
                                Konfigurasi Website
                            </a>
                        </li>
                    </ul>

                    <hr class="my-4">

                    <h3 class="font-bold text-lg mb-2 text-gray-800">User Management</h3>
                    <ul class="space-y-2">
                        <li>
                            <span class="block px-4 py-2 text-gray-400 cursor-not-allowed"> (Nanti dikerjakan) </span>
                        </li>
                    </ul>
                </div>
            </aside>

            <main class="md:w-3/4">
                @yield('admin_content')
            </main>

        </div>
    </div>
@endsection