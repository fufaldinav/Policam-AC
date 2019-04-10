@extends('layout')

@section('scripts')
    window.translations = {!! Cache::get('translations') !!};
    window.AcData = [];
    divisions = window.AcData.divisions = [];
    persons = window.AcData.persons = [];
    @foreach ($divisions as $div)
        div = divisions[{{ $div->id }}] = {!! $div->toJson() !!};
        div.persons = [];
        @foreach($div->persons as $person)
            div.persons.push({{ $person->id }});
            person = persons[{{ $person->id }}];
            if (person === undefined) {
                person = {!! $person->toJson() !!};
                person.cards = {!! $person->cards->toJson() !!};
                person.photos = {!! $person->photos->toJson() !!};
                person.divisions = [];
            }
            persons[{{ $person->id }}] = person;
            person.divisions.push({{ $div->id }});
            delete person;
        @endforeach
        delete div;
    @endforeach
    delete divisions;
    delete persons;
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="ac-menu-left" class="d-none d-sm-block col-sm-3 col-xl-2 bg-white px-0 ac-menu"></div>
            <div class="col-12 col-sm-9 col-lg-6 col-xl-7">
                <div class="row mt-4">
                    <div class="container-fluid">
                        <form id="form-person" class="needs-validation" novalidate>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <div class="photo-bg">
                                        <div class="photo-del" data-title="{{ __('ac.delete') }})" hidden>
                                            <button type="button" class="close" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-file" id="photo"
                                           onchange="handleFiles(this.files)" disabled>
                                </div>
                                <div class="form-group col-6">
                                    <div class="form-group">
                                        <label for="f">{{ __('ac.f') }}</label>
                                        <input type="text" class="form-control" id="f" placeholder="{{ __('ac.f') }}"
                                               disabled required>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Фамилия" является обязательным.') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="i">{{ __('ac.i') }}</label>
                                        <input type="text" class="form-control" id="i" placeholder="{{ __('ac.i') }}"
                                               disabled required>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Имя" является обязательным.') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="o">{{ __('ac.o') }}</label>
                                        <input type="text" class="form-control" id="o" placeholder="{{ __('ac.o') }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="birthday">{{ __('ac.birthday') }}</label>
                                        <input type="date" class="form-control" id="birthday"
                                               placeholder="{{ __('ac.birthday') }}" required disabled>
                                        <div class="invalid-feedback">
                                            {{ __('Поле "Дата рождения" является обязательным.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">{{ __('ac.address') }}</label>
                                <input type="text" class="form-control" id="address"
                                       placeholder="{{ __('ac.address') }}" disabled>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="phone">{{ __('ac.phone') }}</label>
                                    <input type="text" class="form-control" id="phone"
                                           placeholder="{{ __('ac.phone') }}" disabled>
                                </div>
                                <div class="form-group col-6">
                                    <label for="id">{{ __('ac.uid') }}</label>
                                    <input type="text" class="form-control" id="id" placeholder="{{ __('ac.uid') }}" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <button id="ac-button-save" type="button" class="btn btn-primary" onclick="window.Ac.savePerson();">
                                        {{ __('ac.save') }}
                                    </button>
                                    <button id="ac-button-delete" type="button" class="btn btn-secondary" onclick="window.Ac.clearPerson();">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="ac-menu-right" class="d-none d-lg-block col-lg-3 bg-white ac-menu">
                <div class="events"></div>
            </div>
            <input id="type" name="type" type="text" hidden readonly>
        </div>
    </div>
@endsection
