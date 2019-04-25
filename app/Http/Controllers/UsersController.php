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
use App\Policam\Ac\Snapshot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Панель управления
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $roles = $request->user()->roles;

        return view('ac/cp', compact('roles'));
    }

    /**
     * Получает токен от пользователя
     *
     * @param Request $request
     * @return void
     */
    public function token(Request $request): void
    {
        $user = $request->user();

        $token_key = $request->input('token');

        if ($token_key === 'false') {
            // $this->token->getBy('token', $token_key);
            // $this->token->delete(); //TODO удалять просроченный ключ
        } else {
            App\Token::firstOrCreate([
                'token' => $token_key,
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * Возвращает уведомление для пользователя
     *
     * @param string $hash Хэш уведомления
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Exception
     */
    public function notification(string $hash)
    {
        $notification = App\Notification::where(['hash' => $hash])->first();

        if (! $notification) {
            return __('Уведомление устарело или не существует');
        } elseif (Carbon::parse($notification->created_at) < Carbon::now()->subHour()) {
            $notification->delete();
            return __('Уведомление устарело или не существует');
        }

        $photos = Snapshot::getByNotification($notification);
        $css_list = ['notification'];
        $js_list = ['notification'];

        return view('ac.notification', compact('photos', 'css_list', 'js_list'));
    }

    /**
     * Возвращает организации пользователя
     *
     * @param Request $request
     *
     * @return array
     */
    public function getOrganizations(Request $request)
    {
        return $request->user()->organizations->load('controllers');
    }
}
