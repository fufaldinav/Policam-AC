<?php
/**
 * Name:   Policam AC
 *
 * Created: 09.07.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 */

namespace App\Policam\Ac\PolicontApi;

use JsonSerializable;

final class Card implements JsonSerializable
{
    protected $card;
    protected $type = 2;
    protected $tz = 255;
    protected $active = false;
    protected $devices = [];

    public function __construct(string $code)
    {
        $this->card = $code;
    }

    public function jsonSerialize(): array
    {
        $response = ['card' => $this->card, 'devices' => $this->devices];

        if ($this->active) {
            $response['type'] = $this->type;
            $response['tz'] = $this->tz;
        }

        return $response;
    }

    public function setActive(): void
    {
        $this->active = true;
    }

    public function addDevice(int $device): void
    {
        $this->devices[] = $device;
    }
}
