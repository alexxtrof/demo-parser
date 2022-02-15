@extends('layouts.app')

@section('content')
    <div class="mb-3">
        <a href="{{ route('news.index') }}">Вернуться к списку</a>
    </div>

    @if($item)
        <h1 class="h2 mb-3">{{ $item->title }}</h1>
        <div class="mb-3">
            <span class="text-primary">{{ $item->category->title }}</span>
            <span class="mx-1">|</span>
            <span class="text-secondary">{{ $item->published_at }}</span>
        </div>

        @if($item->image)
            <div class="mb-3">
                <img src="{{ $item->image }}" alt="{{ $item->title }}">
            </div>
        @endif

        <div>{!! $item->text !!}</div>
    @else
        <div>Элемент не найден</div>
    @endif

@endsection
