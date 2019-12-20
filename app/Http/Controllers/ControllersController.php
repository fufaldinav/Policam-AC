<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
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

namespace App\Http\Controllers;

use App;
use App\Policam\Ac\Tasker;
use Illuminate\Http\Request;

class ControllersController extends Controller
{
    private $request;
    private $cmd;
    private $controllerSn;
    private $value = -1;
    private $device = -1;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Загружает в контроллер все карты
     *
     * @param Request $request
     * @param string $ctrl_sn
     * @param bool $sl0
     *
     * @return string
     */
    public function reloadCards(Request $request, string $ctrl_sn, bool $sl0 = false): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $ctrl = App\Controller::where('sn', $ctrl_sn)->first();

        abort_if(is_null($ctrl), 404);

        $org = $ctrl->organization;

        $cards = [];

        foreach ($org->persons as $person) {
            $rc = $person->referralCode;
            if (isset($rc)) {
                if ($rc->activated === 1) {
                    if ($sl0 && isset($rc->sl0)) {
                        $card = App\Card::firstOrCreate(['wiegand' => $rc->sl0]);
                    } else if (! $sl0) {
                        $card = App\Card::firstOrCreate(['wiegand' => $rc->card]);
                    } else {
                        continue;
                    }
                    $person->cards()->save($card);
                    $cards[] = $card;
                }
            }
        }

        $tasker = new Tasker();

        for ($i = 0, $codes = [], $card_count = count($cards); $i < $card_count; $i++) {
            $codes[] = $cards[$i]->wiegand;

            /*
            | 1. Запишем задания если: а) это не первый проход
            |                          и
            |                          б) это десятый проход
            |                          или
            |                          в) это последний проход
            | 2. Очистим список кодов карт на отправку
            |
            | Таким образом сформируем задания на отправку по 5 за раз
            */
            if (($i > 0 && ($i % 5 === 0)) || ($i === ($card_count - 1))) {
                $tasker->addCards($ctrl, $codes);
                $tasker->add($ctrl->id);

                $codes = [];
            }
        }

        $count = $tasker->send();

        return __('Заданий успешно отправлено: :count', ['count' => $count]);
    }

    public function getList(Request $request)
    {
        $controllers = $request->user()->controllers;

        return response()->json($controllers);
    }

    /**
     * Устанавливает параметры открытия двери
     *
     * @param Request $request
     * @param int $ctrl_sn
     * @param int $open
     * @param int $open_control
     * @param int $close_control
     *
     * @return string
     */
    public function setDoorParams(Request $request, int $ctrl_sn, int $open, int $open_control = 30, int $close_control = 30): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $ctrl = App\Controller::where('sn', $ctrl_sn)->first();

        abort_if(is_null($ctrl), 404);

        $tasker = new Tasker();

        $tasker->setDoorParams($open, $open_control, $close_control);
        $tasker->add($ctrl->id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }

    public function stop(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'stop';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function reboot(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'reboot';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function reset(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'reset';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearEeprom(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'clear_eeprom';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearMessages(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'clear_messages';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearCards(Request $request, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'clear_cards';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearEvents(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'clear_events';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearMessagesBlacklist(Request $request, int $controllerSn): string
    {
        $this->request = $request;
        $this->cmd = 'clear_messages_bl';
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function clearCardsBlacklist(Request $request, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'clear_cards_bl';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function clearEventsBlacklist(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'clear_events_bl';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function devices(Request $request, int $value, int $controllerSn): string
    {
        $this->request = $request;
        $this->cmd = 'devices';
        $this->value = $value;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

//    public function address(Request $request, int $value, int $controllerSn, int $device): string
//    {
//        $this->request = $request;
//        $this->cmd = 'address';
//        $this->value = $value;
//        $this->controllerSn = $controllerSn;
//        $this->device = $device;
//
//        return $this->command();
//    }

    public function alarm(Request $request, int $value, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'alarm';
        $this->value = $value;
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function freeModeTimeout(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'free_mode_timeout';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function doorMode(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'door_mode';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function doorSensors(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'door_sensors';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function doorOpenTimeout(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'door_open_timeout';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function vBatMin(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'vbat_min';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function vBatDelta(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'vbat_delta';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function readers(Request $request, int $value, int $controllerSn, int $device): string
    {
        $this->request = $request;
        $this->cmd = 'readers';
        $this->value = $value;
        $this->device = $device;
        $this->controllerSn = $controllerSn;

        return $this->command();
    }

    public function format(Request $request, int $controllerSn, int $device = -1): string
    {
        $this->request = $request;
        $this->cmd = 'format';
        $this->controllerSn = $controllerSn;
        $this->device = $device;

        return $this->command();
    }

    public function generateImportString(Request $request, int $organizationId, bool $sl0 = false)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        $organization = App\Organization::find($organizationId);

        abort_if(! $organization, 403);

        $obj = new \stdClass();
        $obj->AccessControlCard = [];

        foreach ($organization->divisions as $division) {
            foreach ($division->persons()->whereNotNull('referral_code_id')->get() as $person) {
                $rc = $person->referralCode;

                if ($rc->activated === 1) {
                    $card = new \stdClass();
                    $card->CardName = substr($rc->code, -10);

                    if ($sl0 && isset($rc->sl0)) {
                        $card->CardNo = $rc->sl0;
                    } else if (! $sl0) {
                        $card->CardNo = $rc->card;
                    } else {
                        continue;
                    }

                    if ($rc->type === 2) {
                        $card->CardNo = '88' . substr($card->CardNo, -6);
                    } else {
                        $byte1 = substr($card->CardNo, -8, 2);
                        $byte2 = substr($card->CardNo, -6, 2);
                        $byte3 = substr($card->CardNo, -4, 2);
                        $byte4 = substr($card->CardNo, -2);

                        $card->CardNo = $byte4 . $byte3 . $byte2 . $byte1;
                    }

                    $card->CardStatus = 0;
                    $card->CardType = 0;
                    $card->UserID = '9901';

                    $obj->AccessControlCard[] = $card;
                }
            }
        }

        echo json_encode($obj);
    }

    public function generateImportXml(Request $request, int $organizationId, bool $sl0 = false)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        $organization = App\Organization::find($organizationId);

        abort_if(! $organization, 403);

        $xml = '<VTO><VideoConfig bCheck="0" /><AudioConfig bCheck="0" /><Indoor bCheck="0" />';
        $cards = '';

        $i = 0;

        foreach ($organization->divisions as $division) {
            foreach ($division->persons()->whereNotNull('referral_code_id')->get() as $person) {
                $rc = $person->referralCode;

                if ($rc->activated === 1) {
                    if ($sl0 && isset($rc->sl0)) {
                        $cardNo = $rc->sl0;
                    } else if (! $sl0) {
                        $cardNo = $rc->card;
                    } else {
                        continue;
                    }

                    $recNo = $i++ + 70;

                    if ($rc->type === 2) {
                        $cardNo = '88' . substr($cardNo, -6);
                    } else {
                        $byte1 = substr($cardNo, -8, 2);
                        $byte2 = substr($cardNo, -6, 2);
                        $byte3 = substr($cardNo, -4, 2);
                        $byte4 = substr($cardNo, -2);

                        $cardNo = $byte4 . $byte3 . $byte2 . $byte1;
                    }

                    $cards .= '<record' . $i . '  nRecNo="' . $recNo . '" nCardStatus="0" nCardType="0" szCardNo="' . $cardNo . '" szUserID="9901" />';
                }
            }
        }

        $xml .= '<AccessControlCard bCheck="1" recordNum="' . $i . '">';
        $xml .= $cards;
        $xml .= '</AccessControlCard><AccessControlPwd bCheck="0" /><AccessQRCode bCheck="0" /></VTO>';

        return response($xml)->header('Content-type', 'text/xml');
    }

    /**
     * Отправляет комманду на контроллер
     *
     * @return string
     */
    private function command()
    {
        abort_if(! $this->request->user()->isAdmin(), 403);

        $ctrl = App\Controller::where('sn', $this->controllerSn)->first();

        abort_if(is_null($ctrl), 404);

        if ($this->device >= $ctrl->devices()->count()) {
            return __('У контроллера меньше slave, чем задано');
        }

        $tasker = new Tasker();

        $tasker->cmd($this->cmd, $this->value, $ctrl, $this->device);
        $tasker->add($ctrl->id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }
}
