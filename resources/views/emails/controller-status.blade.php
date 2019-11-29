@component('mail::message')
    Привет, {{ $user->name }}!<br>
    Контроллер {{$controller->sn}} изменил статус:<br>
    @foreach($devices as $id => $device)
        Slave {{ $id }}
        @if($device->timeout == 0)
            появился в сети
        @else
            не в сети, {{ $device->timeout }} таймаутов<br>
        @endif
    @endforeach
@endcomponent
