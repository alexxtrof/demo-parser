@extends('layouts.app')

@section('content')

    @foreach($items as $item)
        <div>
            <div>{{ $item->published_at }}</div>
            <a href="{{ route('news.show', $item->id) }}">{{ $item->title }}</a>
        </div>
        <hr>
    @endforeach

    {{ $items->links() }}

@endsection
