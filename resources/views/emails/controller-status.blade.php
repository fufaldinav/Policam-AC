@component('mail::message')
    Привет, {{ $user->name }}!<br>
    Контроллер {{$controller->sn}} в {{ $controller->organization->name }} изменил статус:<br>
    @foreach($devices as $id => $device)
        Slave {{ $id }}
        @if($device->timeout < 3)
            в сети<br>
        @else
            не в сети, {{ $device->timeout }}{{ " " }}
            @if($device->timeout == 1 || (($device->timeout % 100) / 10 > 2 && ($device->timeout % 100) % 10 == 1))
                таймаут
            @elseif(($device->timeout >= 2 && $device->timeout <= 4) || (($device->timeout % 100) / 10 > 2 && ($device->timeout % 100) % 10 >= 2 && ($device->timeout % 100) % 10 <= 4))
                таймаута
            @else
                таймаутов
            @endif
            <br>
        @endif
    @endforeach
@endcomponent
