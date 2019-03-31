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
use App\Policam\Ac\Tasker;
use Illuminate\Http\Request;

class PersonsController extends Controller
{
    /**
     * Страница добавления человека
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        /* Карты */
        $card_list = [];

        $person = App\Person::find(0);

        $cards = $person->cards;

        if (! $cards) {
            $card_list[] = __('ac/common.missing');
        } else {
            $card_list[] = __('ac/common.not_selected');
            foreach ($cards as $card) {
                $card_list[$card->id] = $card->wiegand;
            }
        }

        $org_name = $org->name ?? __('ac/common.missing');
        $js_list = ['add_person', 'events', 'main'];

        return view('ac.person_add', compact(
            'divs',
            'card_list',
            'org_name',
            'js_list',
        ));
    }

    /**
     * Страница редактирования людей
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();

        /* Карты */
        $card_list = [];

        $person = App\Person::find(0);

        $cards = $person->cards;

        if (! $cards) {
            $card_list[] = __('ac/common.missing');
        } else {
            $card_list[] = __('ac/common.not_selected');
            foreach ($cards as $card) {
                $card_list[$card->id] = $card->wiegand;
            }
        }

        $org_name = $org->name ?? __('ac/common.missing');
        $js_list = ['main', 'events', 'edit_persons', 'tree'];

        return view('ac.persons_edit', compact(
            'divs',
            'card_list',
            'org_name',
            'js_list',
        ));
    }

    /**
     * Сохраняет человека
     *
     * @param Request $request
     * @param int|null $person_id
     *
     * @return int
     */
    public function save(Request $request, int $person_id = null): int
    {
        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        $person_data = json_decode($request->input('person'), true);
        $card_list = json_decode($request->input('cards'), true);
        $div_list = json_decode($request->input('divs'), true);
        $photo_list = json_decode($request->input('photos'), true);

        $person = App\Person::updateOrCreate(
            ['id' => $person_id],
            $person_data
        );

        /* Карты */
        $tasker = new Tasker();

        foreach ($card_list as $card_id) {
            $card = App\Card::find($card_id);

            $person->cards()->save($card);

            $tasker->addCards([$card->wiegand]);

            foreach ($org->controllers as $ctrl) {
                $tasker->add($ctrl->id);
            }
        }

        $tasker->send();

        /* Подразделения */
        if ($div_list) {
            foreach ($div_list as $div_id) {
                $div = App\Division::find($div_id);

                $person->divisions()->syncWithoutDetaching([$div->id]);
            }
        } else {
            $div = App\Division::where('organization_id', $org->id)
                ->where('type', 0)
                ->first();

            $person->divisions()->syncWithoutDetaching([$div->id]);
        }

        /* Фотографии */
        foreach ($photo_list as $photo_id) {
            $photo = App\Photo::find($photo_id);

            $person->photos()->save($photo);
        }

        return (int)$person->id;
    }

    /**
     * Удаляет человека
     *
     * @param Request $request
     *
     * @return int
     * @throws \Exception
     */
    public function delete(Request $request): int
    {
        $person_id = $request->input('person_id');

        if (is_null($person_id)) {
            return 0;
        }

        $user = App\User::find(Auth::id());

        $org = $user->organizations()->first();

        $person = App\Person::find($person_id);

        /* Подразделения */
        foreach ($person->divisions as $div) {
            $person->divisions()->detach($div->id);
        }

        /* Фотографии */
        foreach ($person->photos as $photo) {
            $photo->delete();
        }

        /* Карты */
        $tasker = new Tasker();

        foreach ($person->cards as $card) {
            $card->person_id = 0;
            $card->save();

            $tasker->delCards([$card->wiegand]);

            foreach ($org->controllers as $ctrl) {
                $tasker->add($ctrl->id);
            }
        }

        $tasker->send();

        /* Подписки */
        foreach ($person->users as $sub) {
            $person->users()->detach($sub->id);
        }

        return (int)$person->delete();
    }

    /**
     * Возвращает человека
     *
     * @param int|null $person_id ID человека
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $person_id = null)
    {
        if (is_null($person_id)) {
            return null;
        }

        $person = App\Person::where('id', $person_id)->first([
            'id', 'f', 'i', 'o', 'type', 'birthday', 'address', 'phone'
        ]);

        return response()->json([
            'person' => $person,
            'photos' => $person->photos,
            'divs' => $person->divisions
        ]);
    }

    /**
     * Получает человека по карте
     *
     * @param int|null $card_id ID карты
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCard(int $card_id = null)
    {
        if (is_null($card_id)) {
            return null;
        }

        $card = App\Card::find($card_id);

        $person = $card->person()->first([
            'id', 'f', 'i', 'o', 'type', 'birthday', 'address', 'phone'
        ]);

        return response()->json([
            'person' => $person,
            'photos' => $person->photos,
            'divs' => $person->divisions
        ]);
    }

    /**
     * Получает людей по подразделению
     *
     * @param int|null $div_id ID подразделения
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getList(int $div_id = null)
    {
        if (is_null($div_id)) {
            return null;
        }

        $div = App\Division::find($div_id);

        $persons = $div->persons()->orderByRaw('f ASC, i ASC, o ASC')->get([
            'person_id as id', 'f', 'i', 'o', 'type', 'birthday', 'address', 'phone'
        ]);

        return response()->json($persons);
    }
}
