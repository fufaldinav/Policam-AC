@extends('layout')

@section('content')
<div id="main"  class="main-grid-container">
    <div id="menu">
        @foreach ($divs as $div)
        <div id="div{{ $div->id }}" class="menu-item" onclick="setDiv{{ $div->id }}">
            {{ $div->name }}
        </div>
        @endforeach
    </div>
    <div id="info">
        <div id="info-photo" class="info-item">
            <div id="photo_bg" class="photo">
                <div id="photo_del" data-title="@lang('ac/common.delete')" hidden>
                    <img src="/img/delete.png" />
                </div>
            </div>
            <input id="photo" name="photo" type="file" onchange="handleFiles(this.files)" /><br />
        </div>
        <div id="info-f" class="info-item">
            @lang('ac/common.f')<br />
            <input maxlength="20" id="f" name="f" size="30" type="text" onchange="checkData(this);" required />
        </div>
        <div id="info-i" class="info-item">
            @lang('ac/common.i')<br />
            <input maxlength="20" id="i" name="i" size="30" type="text" onchange="checkData(this);" required />
        </div>
        <div id="info-o" class="info-item">
            @lang('ac/common.o')<br />
            <input maxlength="20" id="o" name="o" size="30" type="text" />
        </div>
        <div id="info-div" class="info-item">
            <!-- CLASS TO DELETE -->
        </div>
        <div id="info-birthday" class="info-item">
            @lang('ac/common.birthday')<br />
            <input maxlength="20" id="birthday" name="birthday" size="15" type="date" onchange="checkData(this);" required />
        </div>
        <div id="info-address" class="info-item">
            @lang('ac/common.address')<br />
            <input maxlength="50" id="address" name="address" size="60" type="text" />
        </div>
        <div id="info-phone" class="info-item">
            @lang('ac/common.phone')<br />
            <input maxlength="10" id="phone" name="phone" size="15" type="text" />
        </div>
        <div id="info-card" class="info-item">
            @lang('ac/common.card')<br />
            <select id="cards" name="cards">
                @foreach($card_list as $id => $wiegand)
                    <option value="{{ $id }}">{{ $wiegand }}</option>
                @endforeach
            </select>
        </div>
        <div id="info-button1" class="info-item">
            <button type="button" onclick="savePersonInfo();">
                @lang('ac/common.save')</button>
        </div>
        <div id="info-button2" class="info-item">
            <button type="button" onclick="clearPersonInfo();">
                @lang('ac/common.clear')</button>
        </div>
    </div>
</div>
@endsection
