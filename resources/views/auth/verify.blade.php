@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ac/email.verify') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('ac/email.fresh_link') }}
                        </div>
                    @endif

                    {{ __('ac/email.check_email') }}
                    {{ __('ac/email.did_not_receive') }}, <a href="{{ route('verification.resend') }}">{{ __('ac/email.click_here') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
