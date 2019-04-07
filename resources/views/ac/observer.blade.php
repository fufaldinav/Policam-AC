@extends('layout')

@section('content')
    <div class="row">
        <div class="col-3">
            @foreach ($divs as $div)
                <div id="div{{ $div->id }}" class="menu-item"
                     onclick="getPersons({{ $div->id }})">{{ $div->name }}</div>
            @endforeach
        </div>
        <div class="col-9">
            <div class="row">
                <form>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <div id="photo" class="photo-bg"></div>
                        </div>
                        <div class="form-group col-6">
                            <div class="form-group">
                                <label for="f">{{ __('ac.f') }}</label>
                                <input type="text" class="form-control" id="f" placeholder="{{ __('ac.f') }}">
                            </div>
                            <div class="form-group">
                                <label for="i">{{ __('ac.i') }}</label>
                                <input type="text" class="form-control" id="i" placeholder="{{ __('ac.i') }}">
                            </div>
                            <div class="form-group">
                                <label for="o">{{ __('ac.o') }}</label>
                                <input type="text" class="form-control" id="o" placeholder="{{ __('ac.o') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">

                        </div>
                        <div class="form-group col-6">
                            <label for="birthday">{{ __('ac.birthday') }}</label>
                            <input type="date" class="form-control" id="birthday" value="2001-01-01"
                                   placeholder="{{ __('ac.birthday') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('ac.address') }}</label>
                        <input type="text" class="form-control" id="address" placeholder="{{ __('ac.address') }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="phone">{{ __('ac.phone') }}</label>
                            <input type="text" class="form-control" id="phone" placeholder="{{ __('ac.phone') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="uid">{{ __('ac.uid') }}</label>
                            <input type="text" class="form-control" id="uid" placeholder="{{ __('ac.uid') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">

                        </div>
                        <div class="form-group col-6">

                        </div>
                    </div>
                </form>
            </div>
        </div>
        <input id="type" name="type" type="text" hidden readonly/>
    </div>
@endsection
