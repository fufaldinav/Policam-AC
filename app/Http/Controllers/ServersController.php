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
use App\Policam\Ac\Z5RWEB;
use App\Policam\Ac\Policont;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string|null
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = new Z5RWEB\Request($request->getContent());
        return $handler->handle();
    }

    /**
     * @param Request $request
     *
     * @return string|null
     * @throws \Exception
     */
    public function policont(Request $request)
    {
        $handler = new Policont\Request($request->getContent());
        return $handler->handle();
    }

    public function getEvents(int $organizationId, int $count = null)
    {
        $organization = App\Organization::find($organizationId);

        $events = App\Event::whereIn('controller_id', $organization->controllers)->orderBy('time', 'asc')->limit($count)->get();

        foreach ($events as &$event) {
            $card = App\Card::find($event->card_id);
            $event->person_id = $card->person->id;
        }

        return response()->json($events);
    }
}
