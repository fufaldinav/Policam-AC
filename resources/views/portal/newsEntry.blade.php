@extends('portal.layout')

@section('content')
    <div class="section-main" style="margin-top: 62px; background-color: #e8eef7;">
        <div class="container pt-4">
            <h1>{{ __('НОВОСТИ') }}</h1>
            <p class="text-left">
                <a class="text-decoration-none" href="{{ route('portal.news') }}">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    {{ __('Назад') }}
                </a>
            </p>
        </div>
        <div class="container mt-4 pb-2">
            <div class="row shadow mb-4">
                <div class="col-sm-12 col-md-8 bg-white py-2">
                    <h4>{{ $entry->header }}</h4>
                    <p class="text-right">
                        <small>
                            {{ __('Опубликовано:') }} {{ $entry->formattedDate('d.m.Y') }}
                        </small>
                    </p>
                    <p class="text-left">
                        {!! $entry->content !!}
                    </p>
                </div>
                <div class="col-sm-12 col-md-4 py-2" style="background-color: #f5f5f5;">
                    <p class="mb-1">{{ __('Другие новости') }}</p>
                    <div class="row mx-1">
                        @foreach($lastNews as $entry)
                            <a class="text-decoration-none"
                               href="{{ route('portal.news.entry', ['id' => $entry->id]) }}">
                                <div class="col-12 my-1 bg-white shadow-sm">
                                    <p class="text-left" style="color:gray;"><small>{{ $entry->header }}</small></p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
