<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 09.07.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */

namespace App\Policam\Ac\Policont;

use JsonSerializable;

final class OutgoingMessage implements JsonSerializable
{
    protected $id;
    protected $operation;
    protected $active = 1;
    protected $online = 0;
    protected $granted = 0;
    protected $events_success = 0;
    protected $open = 15;
    protected $open_control = 30;
    protected $close_control = 30;
    protected $cards = [];
    protected $device;
    protected $cmd;
    protected $value;
    protected $devices = [];
    protected $operation_types = [
        'set_active' => ['active', 'online'],
        'check_access' => ['granted'],
        'ping' => [],
        'events' => ['events_success'],
        'set_door_params' => ['open', 'open_control', 'close_control'],
        'add_cards' => ['cards'],
        'del_cards' => ['cards'],
        'command' => ['device', 'cmd', 'value'],
    ];

    public function __construct(int $id = 0)
    {
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        $response = [];

        $operation = $this->operation;
        $operation_types = $this->operation_types;

        $response['id'] = $this->id;
        $response['operation'] = $this->operation;
        foreach ($operation_types[$operation] as $param) {
            $response[$param] = $this->$param;
        }

        return $response;
    }

    public function generateId(): int
    {
        return $this->id = mt_rand(500000, 999999999);
    }

    public function setOperation(string $operation): bool
    {
        if (array_key_exists($operation, $this->operation_types)) {
            $this->operation = $operation;
            return true;
        }

        return false;
    }

    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    public function setGranted(int $granted): void
    {
        $this->granted = $granted;
    }

    public function eventCounter(bool $count = true): void
    {
        $this->events_success += $count;
    }

    public function setOpenTime(int $open): void
    {
        $this->open = $open;
    }

    public function setOpenControl(int $open_control): void
    {
        $this->open_control = $open_control;
    }

    public function setCloseControl(int $close_control): void
    {
        $this->close_control = $close_control;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function addDevice(int $device): void
    {
        $this->devices[] = $device;
    }

    public function setDevice(int $device): void
    {
        $this->device = $device;
    }
}
