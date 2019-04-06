<div id="header">
    <div id="header-container">
        <div id="header-left">
            {{ __('ac.school') }}{{ isset($org_name) ? $org_name : '' }}
        </div>
        <div id="header-content">
            @include('layouts.nav')
        </div>
        <div id="header-right">
            <a class="nav" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('auth.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
