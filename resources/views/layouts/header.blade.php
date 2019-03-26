<div id="header">
    <div id="header-container">
        <div id="header-left">
            @lang('ac/common.school'){{ $org_name }}
        </div>
        <div id="header-content">
            @include('layouts.nav')
        </div>
        <div id="header-right">
            <a class="nav" href="{{ url('/logout') }}">
                @lang('ac/common.exit')
            </a>
        </div>
    </div>
</div>