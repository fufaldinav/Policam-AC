@extends('layout')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('email.verify') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('email.fresh_link') }}
                        </div>
                    @endif

                    {{ __('email.check_email') }}
                    {{ __('email.did_not_receive') }}, <a href="{{ route('verification.resend') }}">{{ __('email.click_here') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
