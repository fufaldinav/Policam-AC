@extends('portal.layout')

@section('content')
    <div class="section-main" style="margin-top: 62px; background-color: #e8eef7;">
        <div class="container pt-4"><h1>{{ __('НОВОСТИ') }}</h1></div>
        <div class="container mt-4 pb-2">
            @foreach($lastNews as $entry)
                <a href="{{ route('portal.news.entry', ['id' => $entry->id]) }}" class="news-entry">
                    <div class="row shadow-sm mb-2 news-entry">
                        <div class="d-flex col-3 col-sm-2 col-lg-1 bg-primary justify-content-center align-items-center news-entry-date">
                            @php
                                $timestamp = strtotime($entry->created_at);
                                $day = date("j", $timestamp);
                                $month = date("m", $timestamp);
                            @endphp
                            <h2>{{ $day }}.{{ $month }}</h2>
                        </div>
                        <div
                            class="d-flex col-9 col-sm-10 col-lg-11 text-left bg-white align-items-center news-entry-header"><div class="text-truncate">{{ $entry->header }}</div></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
