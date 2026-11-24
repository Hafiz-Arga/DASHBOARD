@extends('layouts.app')

@section('title', $title . ' | Superstore EIS')

@section('content')

    <div class="page-header">
        <div>
            <span class="page-eyebrow">{{ $eyebrow }}</span>
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>
    </div>

    <div class="whatif-card">
        <h3>Segera hadir</h3>
        <p>Halaman ini masih dalam pengembangan.</p>
    </div>

@endsection
