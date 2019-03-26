<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth, Lang;

class PersonsController extends Controller
{
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
}
