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

namespace App\Policam\Ac;

use App, Auth;

class Polling
{
    protected static $timeout = 10;

    public function __construct()
    {
        self::$timeout = config('ac.long_poll_timeout');
    }

    /**
     * Реализует long polling
     *
     * @param int[]    $event_types Типы событий
     * @param int|null $time        Время последнего запроса
     *
     * @return mixed[] События от контроллера
     */
    public static function polling(array $event_types, int $time = null): array
    {
        $time = $time ?? time();

        $user = App\User::find(Auth::id());

        $orgs = $user->organizations;

        $ctrl_list = [];

        foreach ($orgs as $org) {
            $ctrl_list = array_merge($ctrl_list, $org->controllers()->get()->toArray());
        }

        if ($ctrl_list) {
            while (self::$timeout > 0) {
                $controllers = [];

                foreach ($ctrl_list as $ctrl) {
                    $controllers[] = $ctrl['id'];
                }

                $events = App\Event::whereIn('event', $event_types)
                    ->whereIn('controller_id', $controllers)
                    ->whereDate('created_at', '>', date('Y-m-d H:i:s', $time))
                    ->orderBy('time', 'DESC')
                    ->get()->toArray();

                if ($events) {
                    return $events;
                }

                self::$timeout--;
                sleep(1);
            }
        } else {
            return [];
        }

        return [];
    }
}
