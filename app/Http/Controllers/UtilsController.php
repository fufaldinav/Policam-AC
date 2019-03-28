<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;

class UtilsController extends Controller
{
    /**
     * Возвращает текущее время
     *
     * @return int
     */
    public function getTime(): int
    {
        return time();
    }

    /**
     * Возвращает последние события и реализует long polling
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents(Request $request)
    {
        $events = $request->input('events');
        $time = $request->input('time');

        return response()->json([
            //'msgs' => $this->messenger->polling($events, $time),
            'msgs' => [],
            'time' => time()
        ]);
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

//        $this->logger->add('err', $err);
//        $this->logger->write();

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

        $response = __('ac/common.registered');

        $person = App\Person::find($person_id);

        $cards = $person->cards;

        if (! $cards) {
            return null;
        }

        $description = "{$person->id} {$person->f} {$person->i} ";

        if ($problem_type == 1) {
            $description .= 'forgot card';
        } elseif ($problem_type == 2 || $problem_type == 3) {
            $ctrls = [];
            $cards_to_delete = [];

            foreach ($cards as &$card) {
                $cards_to_delete[] = $card->wiegand;

                $card->person_id = 0;
                $card->save();
            }
            unset($card);

            foreach ($person->divisions as $div) {
                $org = $div->organization;
                $ctrls = array_merge($ctrls, $org->controllers()->get()->toArray());
            }

            foreach ($ctrls as $ctrl) {
//                $this->task->add($ctrl->id);
            }

//            $this->task->send();

            $response .= ' ' . __('ac/common.and') . ' ' . __('ac/common.card_deleted');

            $description .= 'lost/broke card';
        } else {
            return null;
        }

        $event = App\Userevent::create([
            'user_id' => Auth::id(),
            'type' => $problem_type,
            'description' => $description,
            'time' => time()
        ]);

        return $response;
    }
}
