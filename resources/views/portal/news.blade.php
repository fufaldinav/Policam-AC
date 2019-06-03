@extends('portal.layout')

@section('content')
    <div class="section-main" style="margin-top: 64px;">
        <div class="container">
            @foreach($lastNews as $entry)
                <a href="#" class="news-entry-link">
                    <div class="row news-entry">
                        <div class="col-2 news-entry-date">{{ $entry->created_at }}</div>
                        <div class="col-10 news-entry-header">{{ $entry->header }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
