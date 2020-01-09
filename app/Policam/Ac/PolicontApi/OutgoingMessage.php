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

namespace App\Policam\Ac\PolicontApi;

use JsonSerializable;

final class OutgoingMessage implements JsonSerializable
{
    protected $id;
    protected $operation;
    protected $active = 1;
    protected $online = 0;
    protected $granted = 0;
    protected $event_success = 0;
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
        'set_online' => ['online'],
        'check_access' => ['granted'],
        'ping' => [],
        'event' => ['event_success'],
        'set_door_params' => ['open', 'open_control', 'close_control'],
        'add_cards' => ['cards'],
        'del_cards' => ['cards'],
        'command' => ['device', 'cmd', 'value'],
    ];
    protected $cmd_types = [
      'stop' => 0,
      'reboot' => 1,
      'reset' => 2,
      'clear_eeprom' => 3,
      'clear_messages' => 4,
      'clear_cards' => 4,
      'clear_events' => 5,
      'clear_messages_bl' => 6,
      'clear_cards_bl' => 6,
      'clear_events_bl' => 7,
      'devices' => 8,
      'address' => 8,
      'alarm' => 9,
      'free_mode_timeout' => 10,
      'door_mode' => 11,
      'door_sensors' => 12,
      'door_open_timeout' => 13,
      'vbat_min' => 14,
      'vbat_delta' => 15,
      'readers' => 16,
      'format' => 17,
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

    public function setOnline(int $online): void
    {
        $this->online = $online;
    }

    public function setGranted(int $granted): void
    {
        $this->granted = $granted;
    }

    public function eventSuccess(int $id = 0): void
    {
        $this->event_success = $id;
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

    public function setCmd(string $cmd): bool
    {
        if (array_key_exists($cmd, $this->cmd_types)) {
            $this->cmd = $this->cmd_types[$cmd];
            return true;
        }

        return false;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function setDevice(int $device): void
    {
        $this->device = $device;
    }
}
