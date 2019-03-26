@extends('layout')

@section('content')
<div id="main" class="main-grid-container">
    <div id="menu">
        @foreach ($divs as $div)
        <div id="div{{ $div->id }}" class="menu-item" onclick="{{ $div->id }}">{{ $div->name }}</div>
        @endforeach
    </div>
    <div id="info">
        <div id="info-photo" class="info-item">
            <div id="photo_bg" class="photo"></div>
        </div>
        <div id="info-f" class="info-item">
            @lang('ac/common.f')<br />
            <input id="f" name="f" size="30" type="text" readonly />
        </div>
        <div id="info-i" class="info-item">
            @lang('ac/common.i')<br />
            <input id="i" name="i" size="30" type="text" readonly />
        </div>
        <div id="info-o" class="info-item">
            @lang('ac/common.o')<br />
            <input id="o" name="o" size="30" type="text" readonly />
        </div>
        <div id="info-div" class="info-item">
            <div id="divs"></div>
        </div>
        <div id="info-birthday" class="info-item">
            @lang('ac/common.birthday')<br />
            <input id="birthday" name="birthday" size="15" type="date" readonly />
        </div>
        <div id="info-address" class="info-item">
            @lang('ac/common.address')<br />
            <input id="address" name="address" size="60" type="text" readonly />
        </div>
        <div id="info-phone" class="info-item">
            @lang('ac/common.phone')<br />
            <input id="phone" name="phone" size="15" type="text" readonly />
        </div>
        <div id="info-uid" class="info-item">
            @lang('ac/common.uid')<br />
            <input id="id" name="id" size="15" type="text" readonly />
        </div>
    </div>
    <input id="type" name="type" type="text" hidden readonly />
</div>
@endsection
