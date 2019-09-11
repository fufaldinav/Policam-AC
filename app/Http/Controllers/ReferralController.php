<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function parseLink(Request $request, int $referralCode = null)
    {
        if (isset($referralCode)) {
            $rc = App\ReferralCode::where(['code' => $referralCode, 'user_id' => null])->first();

            if ($rc !== null) {
                $referralCode = $rc->code;
            } else {
                $referralCode = null;
            }
        }

        if ($request->user()) {
            if ($request->user()->email_verified_at !== null) {
                if (isset($rc)) {
                    $rc->user_id = $request->user()->id;
                    $rc->save();
                }

                return redirect('cp');
            } else {
                return view('auth.verify');
            }
        }

        return view('postreg.reg', compact('referralCode'));
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

    public function getCodes(Request $request)
    {
        $referralCodes = $request->user()->referralCodes()->select('id', 'code', 'organization_id', 'activated')->get();

        return response()->json($referralCodes);
    }

    public function getDivisions(int $organizationId, int $type = 1)
    {
        $divisions = App\Division::where(['organization_id' => $organizationId, 'type' => $type])->select('id', 'name', 'organization_id')->get();

        return response()->json($divisions);
    }

    public function getOrganizations(int $organizationId = null)
    {
        if (isset($organizationId)) {
            $organizations = App\Organization::where(['id' => $organizationId, 'type' => 1])->select('id', 'name')->get();
        } else {
            $organizations = App\Organization::where(['type' => 1])->select('id', 'name')->get();
        }

        return response()->json($organizations);
    }

    public function getReferral(string $code)
    {
        $referral = App\ReferralCode::where(['code' => $code])->select('id', 'code', 'organization_id', 'activated')->first();

        if (! $referral) {
            return 0;
        }

        return response()->json($referral);
    }
}
