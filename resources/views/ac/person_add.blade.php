@extends('layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="d-none d-sm-block col-sm-3 col-xl-2 bg-white px-1 ac-menu">
                <div class="list-group list-group-flush">
                    @foreach ($divs as $div)
                        @php($count = $div->persons()->get()->count())
                        <button type="button"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                onclick="checkedDiv({{ $div->id }})" {{ $count == 0 ? ' disabled' : '' }}>
                            {{ $div->name }}
                            <span class="badge badge-primary badge-pill">{{ $count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-sm-9 col-lg-6 col-xl-7">
                <div class="row mt-4">
                    <div class="container-fluid">
                        <form class="needs-validation" novalidate>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <div id="photo" class="photo-bg">
                                        <div class="photo-del" data-title="{{ __('ac.delete') }})" hidden>
                                            <img src="/img/delete.png"/>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-file" id="photo"
                                           onchange="handleFiles(this.files)"/>
                                </div>
                                <div class="form-group col-6">
                                    <div class="form-group">
                                        <label for="f">{{ __('ac.f') }}</label>
                                        <input type="text" class="form-control" id="f" placeholder="{{ __('ac.f') }}"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Фамилия" является обязательным.') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="i">{{ __('ac.i') }}</label>
                                        <input type="text" class="form-control" id="i" placeholder="{{ __('ac.i') }}"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Имя" является обязательным.') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="o">{{ __('ac.o') }}</label>
                                        <input type="text" class="form-control" id="o" placeholder="{{ __('ac.o') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="birthday">{{ __('ac.birthday') }}</label>
                                        <input type="date" class="form-control" id="birthday"
                                               placeholder="{{ __('ac.birthday') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Дата рождения" является обязательным.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">{{ __('ac.address') }}</label>
                                <input type="text" class="form-control" id="address"
                                       placeholder="{{ __('ac.address') }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="phone">{{ __('ac.phone') }}</label>
                                    <input type="text" class="form-control" id="phone"
                                           placeholder="{{ __('ac.phone') }}">
                                </div>
                                <div class="form-group col-6">
                                    <label for="uid">{{ __('ac.uid') }}</label>
                                    <input type="text" class="form-control" id="uid" placeholder="{{ __('ac.uid') }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="savePersonInfo();">
                                        {{ __('ac.save') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearPersonInfo();">
                                        {{ __('ac.clear') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-none d-lg-block col-lg-3 bg-white ac-menu">
                <div class="events"></div>
            </div>
            <input id="type" name="type" type="text" hidden readonly/>
        </div>
    </div>
@endsection
