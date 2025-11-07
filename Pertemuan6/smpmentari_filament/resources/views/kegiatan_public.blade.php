@extends('layouts.app')

@section('title', 'Kegiatan | SMP Mentari')

@section('content')
<h1 class="text-2xl font-bold mb-4">Kegiatan Terbaru</h1>

<div class="grid md:grid-cols-3 gap-4">
    @foreach($items as $it)
    <article class="bg-white rounded-xl shadow p-4">
        @if($it->foto)
            <img src="{{ asset('storage/'.$it->foto) }}" alt="{{ $it->judul }}" class="rounded mb-3"/>
        @endif

        <h2 class="font-semibold">{{ $it->judul }}</h2>
        <p class="text-sm text-gray-600">
            {{ \Carbon\Carbon::parse($it->tanggal)->format('d M Y') }}
        </p>
        <p class="mt-2 text-gray-700">
            {{ \Illuminate\Support\Str::limit($it->ringkasan, 100) }}
        </p>
    </article>
    @endforeach
</div>

<div class="mt-6">{{ $items->links() }}</div>
@endsection
