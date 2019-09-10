@extends('portal.layout')

@section('content')
    <div class="section-main" style="margin-top: 62px; background-color: #f5f5f5;">
        <div class="container pt-4">
            <h1>{{ __('КОНТАКТЫ') }}</h1>
        </div>
        <div class="container pt-4">
            <ul class="nav nav-tabs" id="contacts" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="zar-tab" data-toggle="tab" href="#zar" role="tab" aria-controls="zar" aria-selected="true">{{ __('Заречный') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="bel-tab" data-toggle="tab" href="#bel" role="tab" aria-controls="bel" aria-selected="false">{{ __('Белоярский') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="ekb-tab" data-toggle="tab" href="#ekb" role="tab" aria-controls="ekb" aria-selected="false">{{ __('Екатеринбург') }}</a>
                </li>
            </ul>
            <div class="tab-content p-2 border border-top-0" id="contactsContent" style="background-color: #f8fafc;">
                <div class="tab-pane fade show active" id="zar" role="tabpanel" aria-labelledby="zar-tab">
                    <div class="row justify-content-around text-left">
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Телефон:</dt>
                                <dd class="col-8">+7 (343) 777 888 7</dd>
                                <dt class="col-4">E-mail:</dt>
                                <dd class="col-8">ixbit@mail.ru</dd>
                                <dt class="col-4">Отдел продаж:</dt>
                                <dd class="col-8">+7 (343) 777 888 7</dd>
                                <dt class="col-4">Отдел техподдержки:</dt>
                                <dd class="col-8">+7 (343) 777 888 7</dd>
                            </dl>
                        </div>
                        <div class="d-sm-none d-md-block col-md-1"></div>
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Адрес:</dt>
                                <dd class="col-8">ул. Мира, 40</dd>
                                <dt class="col-4">Почтовый адрес:</dt>
                                <dd class="col-8">624250, Свердловская обл., г. Заречный, ул. Алещенкова, 15</dd>
                                <dt class="col-4">GPS:</dt>
                                <dd class="col-8">56.807822, 61.325873</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="bel" role="tabpanel" aria-labelledby="bel-tab">
                    <div class="row justify-content-around text-left">
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Телефон:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">E-mail:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Отдел продаж:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Отдел техподдержки:</dt>
                                <dd class="col-8"></dd>
                            </dl>
                        </div>
                        <div class="d-sm-none d-md-block col-md-1"></div>
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Адрес:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Почтовый адрес:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">GPS:</dt>
                                <dd class="col-8"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ekb" role="tabpanel" aria-labelledby="ekb-tab">
                    <div class="row justify-content-around text-left">
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Телефон:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">E-mail:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Отдел продаж:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Отдел техподдержки:</dt>
                                <dd class="col-8"></dd>
                            </dl>
                        </div>
                        <div class="d-sm-none d-md-block col-md-1"></div>
                        <div class="col-12 col-md-5">
                            <dl class="row">
                                <dt class="col-4">Адрес:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">Почтовый адрес:</dt>
                                <dd class="col-8"></dd>
                                <dt class="col-4">GPS:</dt>
                                <dd class="col-8"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid my-4 p-0 shadow">
            <div id="map" style="height: 500px"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?apikey=613c49e8-0bec-4f5d-a7a5-545cdab5ef0d&lang=ru_RU"
            type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);

        function init() {
            var myMap = new ymaps.Map("map", {
                center: [56.807822, 61.325873],
                zoom: 12
            });
            var myPlacemark = new ymaps.Placemark([56.807822, 61.325873]);
            myMap.geoObjects.add(myPlacemark);
        }
    </script>
@endsection
