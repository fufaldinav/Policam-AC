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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Загружает в контроллер все карты
     *
     * @param Request $request
     * @param int $ctrl_id
     * @param bool $sl0
     *
     * @return string
     */
    public function reloadCards(Request $request, int $ctrl_id, bool $sl0 = false): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $ctrl = App\Controller::find($ctrl_id);

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
                $tasker->add($ctrl_id);

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
     * @param int $ctrl_id
     * @param int $open
     * @param int $open_control
     * @param int $close_control
     *
     * @return string
     */
    public function setDoorParams(Request $request, int $ctrl_id, int $open, int $open_control = 30, int $close_control = 30): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $tasker = new Tasker();

        $tasker->setDoorParams($open, $open_control, $close_control);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }

    /**
     * Очищает память контроллера
     *
     * @param Request $request
     * @param int $ctrl_id
     * @param int $device
     *
     * @return string
     */
    public function clear(Request $request, int $ctrl_id, int $device = null): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $devices = [];
        if (isset($device)) {
            $devices[] = $device;
        }

        $ctrl = App\Controller::find($ctrl_id);

        $tasker = new Tasker();

        $tasker->clearCards($ctrl, $devices);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
    }

    /**
     * Останавливает работу контроллера
     *
     * @param Request $request
     * @param int $ctrl_id
     * @param int $device
     *
     * @return string
     */
    public function stop(Request $request, int $ctrl_id, int $device = -1): string
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $ctrl = App\Controller::find($ctrl_id);

        if ($device >= $ctrl->devices) {
            return __('У контроллера меньше ведомых, чем задано');
        }

        $tasker = new Tasker();

        $tasker->stop($ctrl, $device);
        $tasker->add($ctrl_id);

        $count = $tasker->send();

        if ($count > 0) {
            return __('Заданий успешно отправлено: :count', ['count' => $count]);
        } else {
            return __('Нет отправленных заданий');
        }
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

                    $byte1 = substr($card->CardNo, -8, 2);
                    $byte2 = substr($card->CardNo, -6, 2);
                    $byte3 = substr($card->CardNo, -4, 2);
                    $byte4 = substr($card->CardNo, -2);

                    $card->CardNo = $byte4 . $byte3 . $byte2 . $byte1;

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

        $i = 1;

        foreach ($organization->divisions as $division) {
            foreach ($division->persons()->whereNotNull('referral_code_id')->get() as $person) {
                $rc = $person->referralCode;

                if ($rc->activated === 1) {
                    $recNo = $i + 70;

                    if ($sl0 && isset($rc->sl0)) {
                        $cardNo = $rc->sl0;
                    } else if (! $sl0) {
                        $cardNo = $rc->card;
                    } else {
                        continue;
                    }

                    if (preg_match('/62490(.{4})468(.)/', $rc->code)) {
                        $cardNo = '88' . substr($cardNo, -6);
                    } else {
                        $byte1 = substr($cardNo, -8, 2);
                        $byte2 = substr($cardNo, -6, 2);
                        $byte3 = substr($cardNo, -4, 2);
                        $byte4 = substr($cardNo, -2);

                        $cardNo = $byte4 . $byte3 . $byte2 . $byte1;
                    }

                    $cards .= '<record' . $i . '  nRecNo="' . $recNo . '" nCardStatus="0" nCardType="0" szCardNo="' . $cardNo . '" szUserID="9901" />';

                    $i++;
                }
            }
        }

        $xml .= '<AccessControlCard bCheck="1" recordNum="' . $i . '">';
        $xml .= $cards;
        $xml .= '</AccessControlCard><AccessControlPwd bCheck="0" /><AccessQRCode bCheck="0" /></VTO>';

        return response($xml)->header('Content-type', 'text/xml');
    }
}
