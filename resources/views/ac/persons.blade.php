@extends('layout')

@section('scripts')
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
            <ac-menu-left :current-division="currentDivision" :divisions="divisions" :persons="persons"
                          @ac-division-changed="setCurrentDivision"></ac-menu-left>
            <div class="col-12 col-sm-9 col-lg-6 col-xl-7">
                <div class="row mt-4">
                    <div class="container-fluid">
                        <ac-form-person></ac-form-person>
                    </div>
                </div>
            </div>
            <ac-menu-right></ac-menu-right>
            <input id="type" name="type" type="text" hidden readonly>
        </div>
    </div>
@endsection
