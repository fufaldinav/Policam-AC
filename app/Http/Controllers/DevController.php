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
use phpDocumentor\Reflection\Types\Object_;
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

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line) {
            $params = explode(',', $line);

            $organization = App\Organization::where('name', $params[7])->first();
            $division = $organization->divisions->where('name', $params[8])->first();
            if (is_null($division)) {
                $division = App\Division::create(['name' => $params[8], 'type' => $params[5], 'organization_id' => $organization->id]);
            }
            $rc = App\ReferralCode::where('code', $params[6])->first();

            if (isset($rc)) {
                $rcId = $rc->id;
            } else {
                $rcId = null;
                echo $params[6] . PHP_EOL;
            }

            $person = App\Person::firstOrCreate([
                'f' => $params[0],
                'i' => $params[1],
                'o' => $params[2] ?? null,
                'gender' => $params[3] !== '' ? $params[3] : null,
                'birthday' => $params[4],
                'type' => $params[5],
                'organization_id' => $organization->id,
                'referral_code_id' => $rcId
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

    public function parseImportSl0File(Request $request)
    {
        abort_if(! $request->user()->isAdmin(), 403);

        $contents = Storage::disk('local')->get('import_sl0.csv');

        $response = '';

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line) {
            $params = explode(',', $line);

            $code = $params[0];
            $sl0 = $params[1];
            $sl3 = $params[2];

            while(strlen($sl3) < 12) {
                $sl3 = '0' . $sl3;
            }

            while(strlen($sl0) < 12) {
                $sl0 = '0' . $sl0;
            }

            $rc = App\ReferralCode::firstOrCreate(['code' => $code, 'card' => $sl3]);
            if (is_null($rc)) {
                $response .= $code . PHP_EOL;
                continue;
            }

            $rc->sl0 = $sl0;
            $rc->save();
        }

        return response($response)->header('Content-Type', 'text/plain');
    }

    public function generateImportString(Request $request, int $organizationId, bool $sl0 = false)
    {
        if ($request->user()->id !== 179 && $request->user()->id !== 83 && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $organization = App\Organization::find($organizationId);

        abort_if(! $organization, 403);

        $obj = new \stdClass();
        $obj->AccessControlCard = [];

        foreach ($organization->divisions as $division) {
            foreach ($division->persons()->whereNotNull('referral_code_id')->get() as $person) {
                $rc = $person->referralCode;

                if ($rc->activated === 1) {
                    $card = new \stdClass();
                    $card->CardName = substr($rc->code, -10);

                    if ($sl0 && isset($rc->sl0)) {
                        $card->CardNo = $rc->sl0;
                    } else if (! $sl0) {
                        $card->CardNo = $rc->card;
                    } else {
                        continue;
                    }

                    $byte1 = substr($card->CardNo, -8, 2);
                    $byte2 = substr($card->CardNo, -6, 2);
                    $byte3 = substr($card->CardNo, -4, 2);
                    $byte4 = substr($card->CardNo, -2);

                    $card->CardNo = $byte4 . $byte3 . $byte2 . $byte1;

                    $card->CardStatus = 0;
                    $card->CardType = 0;
                    $card->UserID = '9901';

                    $obj->AccessControlCard[] = $card;
                }
            }
        }

        echo json_encode($obj);
    }
}
