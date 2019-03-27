@extends('layout')

@section('content')
<div id="main">
    <div id="scrollable-container">
        <table border="0" cellpadding="4" cellspacing="0">
            <thead>
            <tr>
                <th>@lang('ac/common.number')</th>
                <th>@lang('ac/common.letter')</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input id="number" type="text" size="2" maxlength="2" required=""></td>
                <td><input id="letter" type="text" size="1" maxlength="1" required=""></td>
                <td><button onclick="saveDivision({{ $org_id }})">@lang('ac/common.save')</button></td>
            </tr>
            @foreach ($divs as $div)
            @if ($div->type != 1)
                @continue
            @endif
            <tr>
                @php($name = explode(' ', $div->name))
                <td>{{ $name[0] }}</td>
                <td>{{ $name[1] ?? '' }}</td>
                <td><button onclick="deleteDivision({{ $div->id }})">@lang('ac/common.delete')</button></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
