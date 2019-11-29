@component('mail::message')
    Привет, {{ $user->name }}!<br>
    Контроллер {{$controller->sn}} изменил статус:<br>
    @php
        $devices = json_decode($controller->devices_status);
    @endphp

    @foreach($devices as $id => $device)
        <p>Slave {{ $id }}
        @if($device->timeout == 0)
            появился в сети
        @else
            не в сети, {{ $device->timeout }} таймаутов
        @endif
        </p>
    @endforeach
@endcomponent
