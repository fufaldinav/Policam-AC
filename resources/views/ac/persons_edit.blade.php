@extends('layout')

@section('content')
<div id="main" class="main-grid-container">
    <div id="menu" onclick="tree_toggle(arguments[0]);">
        <ul class="tree-container">
            @php ($last_div = count($divs) - 1)
            @foreach ($divs as $k => &$div)
            <li class="tree-node tree-is-root tree-expand-closed {{ ($k === $last_div) ? 'tree-is-last' : '' }}">
                <div class="tree-expand"></div>
                <div class="tree-content tree-expand-content">
                    {{ $div->name }}
                </div>
                <ul class="tree-container">
                    @php($persons = $div->persons()->orderByRaw('f ASC, i ASC, o ASC')->get())
                    @php($last_person = count($persons) - 1)
                    @foreach ($persons as $n => $person)
                    <li id="person{{ $person->id }}" class="tree-node tree-expand-leaf {{ ($n === $last_person) ? 'tree-is-last' : '' }}">
                        <div class="tree-expand"></div>
                        <div class="tree-content">
                            @php($card_count = $person->cards->count())
                            <a class="person{{ ($card_count == 0) ? ' no-card' : '' }}" href="#{{ $person->id }}" onClick="getPersonInfo({{ $person->id }});">{{ $person->f }} {{ $person->i }}</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </div>
    <div id="info">
        <div id="info-photo" class="info-item">
            <div id="photo_bg" class="photo">
                <div id="photo_del" data-title="@lang('ac/common.delete')" hidden>
                    <img src="/img/delete.png" />
                </div>
            </div>
            <input id="photo" name="photo" type="file" hidden /><br />
        </div>
        <div id="info-f" class="info-item">
            @lang('ac/common.f')<br />
            <input maxlength="20" id="f" name="f" size="30" type="text" required readonly />
        </div>
        <div id="info-i" class="info-item">
            @lang('ac/common.i')<br />
            <input maxlength="20" id="i" name="i" size="30" type="text" required readonly />
        </div>
        <div id="info-o" class="info-item">
            @lang('ac/common.o')<br />
            <input maxlength="20" id="o" name="o" size="30" type="text" readonly />
        </div>
        <div id="info-div" class="info-item">
            <!-- CLASS TO DELETE -->
        </div>
        <div id="info-birthday" class="info-item">
            @lang('ac/common.birthday')<br />
            <input maxlength="20" id="birthday" name="birthday" size="15" type="date" required readonly />
        </div>
        <div id="info-address" class="info-item">
            @lang('ac/common.address')<br />
            <input maxlength="50" id="address" name="address" size="60" type="text" readonly />
        </div>
        <div id="info-phone" class="info-item">
            @lang('ac/common.phone')<br />
            <input maxlength="10" id="phone" name="phone" size="15" type="text" readonly />
        </div>
        <div id="info-card" class="info-item">
            @lang('ac/common.card')<br />
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
                @lang('ac/common.save')</button>
        </div>
        <div id="info-button2" class="info-item">
            <button id="delete" type="button">
                @lang('ac/common.delete')</button>
        </div>
    </div>
    <input id="id" name="id" type="text" hidden readonly />
    <input id="type" name="type" type="text" hidden readonly />
</div>
@endsection
