@extends('layout')

@section('content')
    <div class="container-fluid d-flex justify-content-center">
        <div class="row w-75 mt-2">
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/students.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Ученики') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/cp/students" class="btn btn-primary">
                            @if(Auth::user()->hasRole(3) || Auth::user()->isAdmin())
                                {{ __('Перейти') }}
                                @else
                                {{ __('Недоступно') }}
                            @endif
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/schedule.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Расписание') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/cp/schedule" class="btn btn-primary">{{ __('Перейти') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/statistics.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Статистика') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/cp/statistics" class="btn btn-primary">{{ __('Перейти') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/observing.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Наблюдение') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/observer" class="btn btn-primary">{{ __('Перейти') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/personnel.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Персонал') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/cp/persons" class="btn btn-primary">{{ __('Перейти') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="card mb-2" style="width: 18rem;">
                    <img src="/img/divisions.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Подразделения') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                        <a href="/cp/divisions" class="btn btn-primary">{{ __('Перейти') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
