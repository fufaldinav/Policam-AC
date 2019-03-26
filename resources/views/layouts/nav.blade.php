<a class="nav" href="{{ url('/') }}">@lang('ac/common.observ')</a>
@if (true)
    <a class="nav" href="{{ route('persons/add') }}">@lang('ac/common.adding')</a>
    <a class="nav" href="{{ route('persons/edit') }}">@lang('ac/common.editing')</a>
    <a class="nav" href="{{ route('divisions/classes') }}">@lang('ac/common.classes')</a>
@endif