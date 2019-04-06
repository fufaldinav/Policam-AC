@extends('layout')

@section('content')
<div id="main" class="main-grid-container">
    <div id="menu">
        @foreach($divs as $div)
            <script>divisions[`{{ $div->id }}`] = new Division({!! $div->toJson() !!});</script>
            <div id="div-{{ $div->id }}" class="divisions menu-item" onclick="showPersons({{ $div->id }})">
                {{ $div->name }}
            </div>
            <div id="persons-div-{{ $div->id }}" class="persons" hidden>
                <div id="menu-button-back" class="menu-item" onclick="showDivisions({{ $div->id }});">Назад</div>
                @foreach($div->persons as $person)
                    <script>persons[`{{ $person->id }}`] = new Person({!! $person->toJson() !!});</script>
                    <div id="person-{{ $person->id }}" class="menu-item" onclick="getPersonInfo({{ $person->id }});">{{ $person->f }} {{ $person->i }}</div>
                    <div id="cards-person-{{ $person->id }}" class="cards" hidden>
                        @foreach($person->cards as $card)
                            <div id="card-{{ $card->id }}">{{ $card->wiegand }} <button type="button" onclick="detachCard({{ $card->id }});">Отвязать</button><br /></div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    <div id="info">
        <div id="info-photo" class="info-item">
            <div id="photo_bg" class="photo">
                <div id="photo_del" data-title="@lang('ac.delete')" hidden>
                    <img src="/img/delete.png" />
                </div>
            </div>
            <input id="photo" name="photo" type="file" hidden /><br />
        </div>
        <div id="info-f" class="info-item">
            @lang('ac.f')<br />
            <input maxlength="20" id="f" name="f" size="30" type="text" required readonly />
        </div>
        <div id="info-i" class="info-item">
            @lang('ac.i')<br />
            <input maxlength="20" id="i" name="i" size="30" type="text" required readonly />
        </div>
        <div id="info-o" class="info-item">
            @lang('ac.o')<br />
            <input maxlength="20" id="o" name="o" size="30" type="text" readonly />
        </div>
        <div id="info-div" class="info-item">
            <!-- CLASS TO DELETE -->
        </div>
        <div id="info-birthday" class="info-item">
            @lang('ac.birthday')<br />
            <input maxlength="20" id="birthday" name="birthday" size="15" type="date" required readonly />
        </div>
        <div id="info-address" class="info-item">
            @lang('ac.address')<br />
            <input maxlength="50" id="address" name="address" size="60" type="text" readonly />
        </div>
        <div id="info-phone" class="info-item">
            @lang('ac.phone')<br />
            <input maxlength="10" id="phone" name="phone" size="15" type="text" readonly />
        </div>
        <div id="info-card" class="info-item">
            @lang('ac.card')<br />
            <div id="person_cards"></div>
            <div id="unknown_cards">
                <select id="cards" name="cards">
                    @foreach($card_list as $id => $wiegand)
                        <option value="{{ $id }}">{{ $wiegand }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="info-button1" class="info-item">
            <button id="save" type="button">
                @lang('ac.save')</button>
        </div>
        <div id="info-button2" class="info-item">
            <button id="delete" type="button">
                @lang('ac.delete')</button>
        </div>
    </div>
    <input id="id" name="id" type="text" hidden readonly />
    <input id="type" name="type" type="text" hidden readonly />
</div>
@endsection
