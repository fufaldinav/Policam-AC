@extends('layout')

@section('content')
    <table class="table-striped table-hover">
        <thead>
        <tr>
            <th>{{ __('Номер') }}</th>
            <th>{{ __('Литера') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input id="number" type="text" size="2" maxlength="2" required=""></td>
            <td><input id="letter" type="text" size="1" maxlength="1" required=""></td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="saveDivision({{ $org_id }})">{{ __('Сохранить') }}</button>
            </td>
        </tr>
        @foreach ($divs as $div)
            @if ($div->type != 1)
                @continue
            @endif
            <tr>
                @php($name = explode(' ', $div->name))
                <td>{{ $name[0] }}</td>
                <td>{{ isset($name[1]) ? $name[1] : '' }}</td>
                <td>
                    <button class="btn btn-secondary btn-sm" onclick="deleteDivision({{ $div->id }})">{{ __('Удалить') }}</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
