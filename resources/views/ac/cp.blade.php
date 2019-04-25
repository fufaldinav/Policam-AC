@extends('layout')

@section('content')
    <div class="container-fluid d-flex justify-content-center">
        <div class="row mt-2">
            <div class="col"></div>
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="row">
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 4, 5]) ? 'true' : 'false' }}"
                            :url="'/cp/students'"
                        >
                            <template slot="img"><img src="/img/students.jpg" class="card-img-top" alt="..."></template>
                            <template slot="title">{{ __('Ученики') }}</template>
                            <template slot="text">Добавление детей, запись в учреждения, подписка на события</template>
                        </ac-cp-function-card>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 2, 3, 4, 5]) ? 'true' : 'false' }}"
                            :url="'/cp/schedule'"
                        >
                            <template slot="img"><img src="/img/schedule.jpg" class="card-img-top" alt="..."></template>
                            <template slot="title">{{ __('Расписание') }}</template>
                            <template slot="text">Просмотр и управление расписанием</template>
                        </ac-cp-function-card>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 2, 3, 4, 5]) ? 'true' : 'false' }}"
                            :url="'/cp/statistics'"
                        >
                            <template slot="img"><img src="/img/statistics.jpg" class="card-img-top" alt="...">
                            </template>
                            <template slot="title">{{ __('Статистика') }}</template>
                            <template slot="text">Просмотр статистики по входам и выходам</template>
                        </ac-cp-function-card>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 2, 3, 6]) ? 'true' : 'false' }}"
                            :url="'/observer'"
                        >
                            <template slot="img"><img src="/img/observing.jpg" class="card-img-top" alt="...">
                            </template>
                            <template slot="title">{{ __('Наблюдение') }}</template>
                            <template slot="text">Просмотр проходящих</template>
                        </ac-cp-function-card>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 2, 3]) ? 'true' : 'false' }}"
                            :url="'/cp/persons'"
                        >
                            <template slot="img"><img src="/img/personnel.jpg" class="card-img-top" alt="...">
                            </template>
                            <template slot="title">{{ __('Персонал') }}</template>
                            <template slot="text">Управление списком учеников и персонала</template>
                        </ac-cp-function-card>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <ac-cp-function-card
                            :active="{{ Auth::user()->hasRole([1, 2, 3]) ? 'true' : 'false' }}"
                            :url="'/cp/classes'"
                        >
                            <template slot="img"><img src="/img/divisions.jpg" class="card-img-top" alt="...">
                            </template>
                            <template slot="title">{{ __('Подразделения') }}</template>
                            <template slot="text">Управление классами и подразделениями</template>
                        </ac-cp-function-card>
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
    </div>
@endsection
