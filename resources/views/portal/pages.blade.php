@extends('portal.layout')

@section('content')
    <div class="section-main pb-4" style="margin-top: 62px; background-color: #e8eef7;">
        <div class="container pt-4"><h1>{{ $entry->header }}</h1></div>
        <div class="container my-4 py-2 bg-white shadow">
            {!! $entry->content !!}
        </div>
    </div>
@endsection
