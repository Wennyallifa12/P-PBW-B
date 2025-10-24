{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.admin')

@section('admin_title', 'Pengaturan Website')

@section('admin_content')
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6 text-gray-800">Konfigurasi Website</h3>
            
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    @foreach($settings as $setting)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0">
                            <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            
                            @if($setting->description)
                                <p class="text-sm text-gray-500 mb-3">{{ $setting->description }}</p>
                            @endif

                            @if($setting->type === 'number')
                                <input 
                                    type="number" 
                                    id="setting_{{ $setting->key }}"
                                    name="settings[{{ $setting->key }}]" 
                                    value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                    min="1"
                                    max="50"
                                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('settings.' . $setting->key) border-red-500 @enderror"
                                    required
                                >
                            @elseif($setting->type === 'boolean')
                                <select 
                                    id="setting_{{ $setting->key }}"
                                    name="settings[{{ $setting->key }}]"
                                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            @else
                                <input 
                                    type="text" 
                                    id="setting_{{ $setting->key }}"
                                    name="settings[{{ $setting->key }}]" 
                                    value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('settings.' . $setting->key) border-red-500 @enderror"
                                    required
                                >
                            @endif

                            @error('settings.' . $setting->key)
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <p><strong>Catatan:</strong> Perubahan akan langsung diterapkan setelah disimpan.</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Home Kegiatan Per Page:</strong> Mengatur jumlah kegiatan yang ditampilkan per halaman di homepage. Disarankan antara 3-12 untuk performa terbaik.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
