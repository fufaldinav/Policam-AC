@extends('layout')

@section('content')
    <div class="container-fluid justify-content-center">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-4 col-xl-3 my-2">
                <div class="d-flex justify-content-center">
                    <img src="/img/reg/propusk.png" alt="Пропуск" class="img-responsive w-100 shadow-sm">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="row">
                    <div class="col-12 col-md-6 d-flex justify-content-center">
                        <div class="card mb-2 ac-reg-card">
                            <img src="/img/reg/new.jpg" class="card-img-top ac-reg-card-img" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Я тут новенький</h5>
                                <p class="card-text">Привет! Если Вы впервые на нашем портале, необходимо пройти
                                    процедуру регистрации, выполнив несколько простых шагов. Строго необходим
                                    действующий электронный адрес - почта.</p>
                                <a href="/register/{{ $referralCode }}" class="btn btn-primary">Зарегистрироваться</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-center">
                        <div class="card ac-reg-card">
                            <img src="/img/reg/old.jpg" class="card-img-top ac-reg-card-img" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Я уже зарегистрирован</h5>
                                <p class="card-text">Вы уже ранее регистрировались на нашем портале и пользовались
                                    кабинетом? Добро пожаловать!</p>
                                <a href="/login/{{ $referralCode }}" class="btn btn-primary">Войти в кабинет</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
