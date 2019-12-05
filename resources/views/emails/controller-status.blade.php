@component('mail::message')
    Привет, {{ $user->name }}!<br>
    Контроллер {{$controller->sn}} в {{ $controller->organization->name }} изменил статус:<br>
    @foreach($devices as $id => $device)
        Slave {{ $id }}
        @if($device->timeout == 0)
            в сети<br>
        @else
            не в сети<br>
        @endif
    @endforeach
@endcomponent
