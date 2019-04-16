@extends('layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <ac-menu-left :current-division="currentDivision" @ac-division-changed="setCurrentDivision"></ac-menu-left>
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
