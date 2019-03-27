<div id="header">
    <div id="header-container">
        <div id="header-left">
            @lang('ac/common.school'){{ $org_name }}
        </div>
        <div id="header-content">
            @include('layouts.nav')
        </div>
        <div id="header-right">
            <a class="nav" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                @lang('ac/common.exit')
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
