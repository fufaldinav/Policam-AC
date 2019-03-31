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

use App, Auth;
use App\Policam\Ac\Notificator;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Панель управления
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $org_name = __('ac/common.missing');
        $css_list = ['ac'];
        $js_list = ['push'];

        return view('ac/cp', compact(
            'org_name', 'css_list', 'js_list'
        ));
    }

    /**
     * Получает токен от пользователя
     *
     * @param Request $request
     * @return void
     */
    public function token(Request $request): void
    {
        $user = App\User::find(Auth::id());

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
     * @param string|null $hash Хэш уведомления
     *
     * @return string|null
     * @throws \Exception
     */
    public function notification(string $hash = null): ?string
    {
        if (!isset($hash)) {
            return null;
        }

        $notification = App\Notification::where(['hash' => $hash])->first();

        if (! $notification) {
            return 'Уведомление устарело или не существует'; //TODO перевод
        }

        $photos = Notificator::getPhotos($notification);

        $notification->delete();

        return $notification->created_at;
    }
}
