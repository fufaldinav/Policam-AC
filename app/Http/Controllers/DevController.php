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
use Storage;

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

    public function parseImportFile(Request $request)
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $contents = Storage::disk('local')->get('import.csv');

        $response = '';

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line) {
            $params = explode(',', $line);

            $organization = App\Organization::where('name', $params[7])->first();
            $division = $organization->divisions->where('name', $params[8])->first();
            if (is_null($division)) {
                $division = App\Division::create(['name' => $params[8], 'type' => $params[5], 'organization_id' => $organization->id]);
            }
            $rc = App\ReferralCode::where('code', $params[6])->first();

            $person = App\Person::firstOrCreate([
                'f' => $params[0],
                'i' => $params[1],
                'o' => $params[2] ?? null,
                'gender' => $params[3] !== '' ? $params[3] : null,
                'birthday' => $params[4],
                'type' => $params[5],
                'organization_id' => $organization->id,
                'referral_code_id' => $rc->id
            ]);

            $person->divisions()->syncWithoutDetaching([$division->id]);
        }


        return response($response)->header('Content-Type', 'text/plain');
    }

    public function createExportFile(Request $request, int $organizationId)
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $response = '';

        $organization = App\Organization::find($organizationId);

        abort_if(! $organization, 403);

        foreach ($organization->divisions as $division) {
            foreach ($division->persons as $person) {
                $rc = $person->referralCode;

                if (is_null($rc)) {
                    $code = null;
                } else {
                    $code = $rc->code;
                }

                $response .= $person->f . ',' . $person->i . ',' . $person->o . ',' . $person->gender . ',' . $person->birthday . ',' . $person->type . ',' . $code . ',' . $organization->name . ',' . $division->name . PHP_EOL;
            }
        }

        Storage::disk('local')->put('export.csv', $response);
    }
}
