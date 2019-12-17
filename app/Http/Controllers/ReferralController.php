<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function parseLink(Request $request, int $referralCode = null)
    {
        if (isset($referralCode)) {
            $rc = App\ReferralCode::where(['code' => $referralCode, 'user_id' => null, 'activated' => 0])->first();

            if (isset($rc)) {
                if ($request->user()) {
                    if (isset($request->user()->email_verified_at)) {
                        $rc->user_id = $request->user()->id;
                        $rc->save();

                        return redirect('cp');
                    } else {
                        return view('auth.verify');
                    }
                } else {
                    return view('postreg.reg', compact('referralCode'));
                }
            } else {
                return view('postreg.codeAlreadyActivated', compact('referralCode'));
            }
        } else {
            return view('postreg.enterCode');
        }
    }

    /**
     * Пост регистрация
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postreg(Request $request)
    {
        if ($request->user()->hasRole()) {
            return redirect('/cp');
        }

        return view('postreg/index');
    }

    public function getUserInfo(Request $request)
    {
        $user = App\User::where('id', $request->user()->id)->select('id', 'name', 'last_name')->first();

        return response()->json($user);
    }

    public function getCodes(Request $request)
    {
        $referralCodes = $request->user()->referralCodes()->select('id', 'code', 'organization_id', 'activated')->get();

        return response()->json($referralCodes);
    }

    public function getCodesByOrganization(Request $request, int $organizationId)
    {
        if ($organizationId > 0) {
            $organization = $request->user()->organizations->where('id', $organizationId)->first();
        } else {
            $organization = $request->user()->organizations->first();
        }

        $referralCodes = $organization->referralCodes()->select('id', 'code', 'organization_id', 'activated')->get();

        return response()->json($referralCodes);
    }

    public function getDivisions(int $organizationId, int $type = 1)
    {
        $divisions = App\Division::where(['organization_id' => $organizationId, 'type' => $type])->select('id', 'name', 'organization_id')->get();

        return response()->json($divisions);
    }

    public function getOrganizations(int $type, int $organizationId = null)
    {
        if (isset($organizationId)) {
            $organizations = App\Organization::where('id', $organizationId)->select('id', 'name', 'address', 'type')->get();
        } else if ($type > 0) {
            $organizations = App\Organization::where('type', $type)->select('id', 'name', 'address', 'type')->get();
        } else {
            $organizations = App\Organization::where('type', '<>', 0)->select('id', 'name', 'address', 'type')->get();
        }

        return response()->json($organizations);
    }

    public function activateReferral(string $code)
    {
        $referral = App\ReferralCode::where(['code' => $code])->first();

        if (is_null($referral)) {
            return 0;
        }

        return response()->json($referral);
    }

    public function getReferral(string $code)
    {
        $referral = App\ReferralCode::where(['code' => $code])->select('id', 'code', 'organization_id', 'activated')->first();

        if (is_null($referral)) {
            return 0;
        }

        return response()->json($referral);
    }

    public function parseData(Request $request)
    {
        $user = $request->user();
        $myRoles = $request->input('myRoles');
        $students = $request->input('students');
        $userData = $request->input('user');

        foreach ($myRoles as $roleId) {
            $role = App\Role::find($roleId);

            $user->roles()->save($role);

            if ($roleId === 9) {
                $person = App\Person::create([
                    'f' => $userData['f'],
                    'i' => $userData['i'],
                    'o' => $userData['o'],
                    'gender' => $userData['gender'],
                    'type' => 2,
                    'birthday' => $userData['birthday']
                ]);

                $rc = App\ReferralCode::find($userData['code']);

                $rc->persons()->save($person);

                $card = App\Card::firstOrCreate([
                    'wiegand' => $rc->card
                ]);

                $person->cards()->save($card);

                $organization = App\Organization::find($userData['organization']);

                if ($organization->type === 1) {
                    $emptyDivision = $organization->divisions()->where('type', 2)->first();
                    if (is_null($emptyDivision)) {
                        $emptyDivision = $organization->divisions()->where('type', 0)->first();
                    }
                } else {
                    $emptyDivision = $organization->divisions()->where('type', 0)->first();
                }

                $person->divisions()->save($emptyDivision);
            }
        }

        foreach ($students as $student) {
            $person = App\Person::create([
                'f' => $student['f'],
                'i' => $student['i'],
                'o' => $student['o'],
                'gender' => $student['gender'],
                'type' => 1,
                'birthday' => $student['birthday']
            ]);

            $rc = App\ReferralCode::find($student['code']);

            $rc->persons()->save($person);

            $card = App\Card::firstOrCreate([
                'wiegand' => $rc->card
            ]);

            $person->cards()->save($card);

            $division = App\Division::find($student['division']);

            $person->divisions()->save($division);

            foreach ($student['additionalOrganizations'] as $org) {
                $organization = App\Organization::find($org['id']);

                $emptyDivision = $organization->divisions()->where('type', 0)->first();

                $person->divisions()->save($emptyDivision);
            }
        }

        return response()->json();
    }
}
