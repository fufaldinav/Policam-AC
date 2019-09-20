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
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index(Request $request)
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $persons = App\Person::whereIn('type', [1, 2])->get();

        foreach ($persons as $person) {
            $divs = $person->divisions;
            foreach ($divs as $div) {
                if (isset($div)) {
                    $org = $div->organization;
                    if ($org->type === 1) {
                        $org->persons()->save($person);
                    }
                }
            }
        }

        $response = print_r($persons, true);

        return response($response)->header('Content-Type', 'text/plain');
    }
}
