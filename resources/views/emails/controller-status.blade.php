@component('mail::message')
    Привет, {{ $user->name }}!<br>
    Контроллер изменил статус:<br>
    @php
        $devices = json_decode($controller->devices_status);
    @endphp

    @foreach($devices as $id => $device)
        Slave {{ $id }}: {{ $device->timeout }}<br>
    @endforeach
@endcomponent
