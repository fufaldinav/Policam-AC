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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class UtilsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Сохраняет ошибки от клиентов
     *
     * @param Request $request
     * @param string|null $err Текст ошибки или NULL - получить POST-запрос
     *
     * @return string
     */
    public function saveErrors(Request $request, string $err = null): string
    {
        $err = $err ?? $request->input('error') ?? 'Неизвестная ошибка или ошибка не указана';

        Log::error($err);

        return $err;
    }

    /**
     * Обрабатывает проблемы с картами
     *
     * @param Request $request
     *
     * @return string
     */
    public function cardProblem(Request $request): string
    {
        $problem_type = $request->input('type');
        $person_id = $request->input('person_id');

        if (is_null($problem_type) || is_null($person_id)) {
            return null;
        }

        $response = __('Событие зарегистрировано');

        $person = App\Person::find($person_id);

        $cards = $person->cards;

        if (! $cards) {
            return null;
        }

        $description = "{$person->id} {$person->f} {$person->i} ";

        if ($problem_type == 1) {
            $description .= 'forgot card';
        } elseif ($problem_type == 2 || $problem_type == 3) {
            $tasker = new Tasker();

            $cards_to_delete = [];

            foreach ($cards as &$card) {
                $cards_to_delete[] = $card->wiegand;

                $card->person_id = 0;
                $card->save();
            }
            unset($card);

            foreach ($person->controllers as $ctrl) {
                $tasker->delCards($ctrl->type, $cards_to_delete);
                $tasker->add($ctrl->id);
            }

            $tasker->send();

            $response .= __(' и карта удалена');

            $description .= 'lost/broke card';
        } else {
            return null;
        }

        App\Userevent::create([
            'user_id' => $request->user()->id,
            'type' => $problem_type,
            'description' => $description,
            'time' => Carbon::now()->toDateTimeString()
        ]);

        return $response;
    }
}
