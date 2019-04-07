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

class PersonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index(Request $request, int $organization_id = null) {
        if ($organization_id) {
            $org = App\Organization::find($organization_id);
        } else {
            $org = $request->user()->organizations()->first();
        }

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get()
            ->load(['persons' => function ($query) {
                $query->orderByRaw('f ASC, i ASC, o ASC')->get()->load('cards');
            }]);

        /* Карты */
        $card_list = [];

        $cards = App\Card::where('person_id', 0)->get();

        if (!$cards) {
            $card_list[] = __('ac.missing');
        } else {
            $card_list[] = __('ac.not_selected');
            foreach ($cards as $card) {
                $card_list[$card->id] = $card->wiegand;
            }
        }

        $org_name = $org->name ?? __('ac.missing');

        return view('ac.persons', compact(
            'divs', 'card_list', 'org_name'
        ));
    }
    /**
     * Страница добавления человека
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request)
    {
        $org = $request->user()->organizations()->first();

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

        $cards = App\Card::where('person_id', 0)->get();

        if (!$cards) {
            $card_list[] = __('ac.missing');
        } else {
            $card_list[] = __('ac.not_selected');
            foreach ($cards as $card) {
                $card_list[$card->id] = $card->wiegand;
            }
        }

        $org_name = $org->name ?? __('ac.missing');
        $js_list = ['add_person', 'main'];

        return view('ac.person_add', compact('divs', 'card_list', 'org_name', 'js_list'));
    }

    /**
     * Страница редактирования людей
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $org = $request->user()->organizations()->first();

        if (! $org) {
            return view('ac.error', ['error' => 'Огранизации отсутствуют']);
        }

        /* Подразделения */
        $divs = $org
            ->divisions()
            ->orderByRaw('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get()
            ->load(['persons' => function ($query) {
                $query->orderByRaw('f ASC, i ASC, o ASC')->get()->load('cards');
            }]);

        /* Карты */
        $card_list = [];

        $cards = App\Card::where('person_id', 0)->get();

        if (!$cards) {
            $card_list[] = __('ac.missing');
        } else {
            $card_list[] = __('ac.not_selected');
            foreach ($cards as $card) {
                $card_list[$card->id] = $card->wiegand;
            }
        }

        $org_name = $org->name ?? __('ac.missing');
        $css_list = ['edit_persons'];
        $js_list = ['main', 'edit_persons', 'tree'];

        return view('ac.persons_edit', compact(
            'divs', 'card_list',
            'org_name', 'css_list', 'js_list'
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
        $org = $request->user()->organizations()->first();

        abort_if(! $org, 403);

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

            if (!$card) {
                continue;
            }

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

                if (!$div) {
                    continue;
                }

                $person->divisions()->syncWithoutDetaching([$div->id]);
            }
        } else {
            $div = App\Division::where('organization_id', $org->id)
                ->where('type', 0)
                ->first();

            if ($div) {
                $person->divisions()->syncWithoutDetaching([$div->id]);
            }
        }

        /* Фотографии */
        foreach ($photo_list as $photo_id) {
            $photo = App\Photo::find($photo_id);

            if (!$photo) {
                continue;
            }

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

        $org = $request->user()->organizations()->first();

        $person = $request->user()->persons()->where('persons.id', $person_id)->first();

        abort_if(!$person, 403);

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
     * @param Request $request
     * @param int     $person_id ID человека
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, int $person_id)
    {
        $person = $request->user()->persons()->where('persons.id', $person_id)->first();

        abort_if(! $person, 403);

        return response()->json([
            'person' => $person,
            'photos' => $person->photos,
            'divs' => $person->divisions
        ]);
    }

    /**
     * Получает человека по карте
     *
     * @param Request $request
     * @param int     $card_id ID карты
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCard(Request $request, int $card_id)
    {
        $card = $request->user()->cards()->where('cards.id', $card_id)->first();

        abort_if(! $card, 403);

        $person = $card->person;

        return response()->json([
            'person' => $person,
            'photos' => $person->photos,
            'divs' => $person->divisions
        ]);
    }

    /**
     * Получает людей по подразделению
     *
     * @param Request $request
     * @param int     $div_id ID подразделения
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getListByDivision(Request $request, int $div_id)
    {
        $div = $request->user()->divisions()->where('divisions.id', $div_id)->first();

        abort_if(! $div, 403);

        $persons = $div->persons()->orderByRaw('f ASC, i ASC, o ASC')->get([
            'person_id as id', 'f', 'i', 'o', 'type', 'birthday', 'address', 'phone',
        ]);

        return response()->json($persons);
    }
}
