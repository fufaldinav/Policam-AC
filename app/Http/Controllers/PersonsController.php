<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth, Lang;

class PersonsController extends Controller
{
    /*
     * Страница добавления человека
     */
    public function add()
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $data = [];

        /*
        | Подразделения
        */
        $divs = $org
            ->divisions()
            ->orderBy('type')
            ->orderByRaw('CAST(name AS UNSIGNED) ASC')
            ->orderBy('name')
            ->get();

        $data['divs'] = $divs;

        /*
        | Карты
        */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards"';

        $person = App\Person::find(0);

        $cards = $person->cards;

        if (! $cards) {
            $data['cards'][] = __('ac/common.missing');
        } else {
            $data['cards'][] = __('ac/common.not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $data['org_name'] = $org->name ?? __('ac/common.missing');
        $data['css_list'] = ['ac'];
        $data['js_list'] = ['add_person', 'events', 'main'];

        return view('ac.person_add', $data);
    }

    /*
     * Страница редактирования людей
     */
    public function edit()
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $data = [];

        /*
        | Подразделения
        */
        $divs = $org
            ->divisions()
            ->orderBy('type')
            ->orderByRaw('CAST(name AS UNSIGNED) ASC')
            ->orderBy('name')
            ->get();

        $data['divs'] = $divs;

        /*
        | Карты
        */
        $data['cards'] = [];
        $data['cards_attr'] = 'id="cards" disabled';

        $person = App\Person::find(0);

        $cards = $person->cards;

        if (! $cards) {
            $data['cards'][] = __('ac/common.missing');
        } else {
            $data['cards'][] = __('ac/common.not_selected');
            foreach ($cards as $card) {
                $data['cards'][$card->id] = $card->wiegand;
            }
        }

        $data['org_name'] = $org->name ?? __('ac/common.missing');
        $data['css_list'] = ['ac', 'edit_persons'];
        $data['js_list'] = ['main', 'events', 'edit_persons', 'tree'];

        return view('ac.persons_edit', $data);
    }

    /*
     * Сохранение человека
     */
    public function save(Request $request, int $person_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $person_data = json_decode($request->input('person'));
        $card_list = json_decode($request->input('cards'));
        $div_list = json_decode($request->input('divs'));
        $photo_list = json_decode($request->input('photos'));

        $person = App\Person::updateOrCreate(
            ['id' => $person_id],
            $person_data
        );

        /*
        | Карты
        */
        foreach ($card_list as $card_id) {
            $card = App\Card::find($card_id);

            $person->cards()->save($card);

//            $this->task->addCards([$card->wiegand]);
//
//            foreach ($org->controllers as $ctrl) {
//                $this->task->add($ctrl->id);
//            }
        }

//        $this->task->send();

        /*
        | Подразделения
        */
        if ($div_list) {
            foreach ($div_list as $div_id) {
                $div = App\Division::find($div_id);

                $person->divisions()->attach($div->id);
            }
        } else {
            $div = App\Division::where('org_id', $org->id)
                ->where('type', 0)
                ->first();

            $person->divisions()->attach($div->id);
        }

        /*
        | Фотографии
        */
        foreach ($photo_list as $photo_id) {
            $photo = App\Photo::find($photo_id);

            $person->photos()->save($photo);
        }

        return $person->id;
    }

    public function delete(int $person_id = null)
    {
        $user = Auth::user();

        if (! isset($user)) {
            return redirect('login');
        }

        $user = App\User::find($user->id);

        $org = $user->organizations()->first();

        $person = App\Person::find($person_id);

        /*
        | Подразделения
        */
        foreach ($person->divisions as $div) {
            $person->divisions()->detach($div->id);
        }

        /*
        | Фотографии
        */
        foreach ($person->photos as $photo) {
            $photo->delete();
        }

        /*
        | Карты
        */
        foreach ($person->cards as $card) {
            $card->person_id = 0;
            $card->save();

//            $this->task->delCards([$card->wiegand]);
//
//            foreach ($org->controllers as $ctrl) {
//                $this->task->add($ctrl->id);
//            }
        }

//        $this->task->send();

        /*
        | Подписки
        */
        foreach ($person->users as $sub) {
            $person->users()->detach($sub->id);
        }

        return $person->delete();
    }
}
