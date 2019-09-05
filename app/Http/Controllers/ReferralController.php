<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('ac.reg', compact('referralCode'));
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

    public function getOrganization(int $organizationId)
    {
        $organization = App\Organization::where(['id' => $organizationId])->select('id', 'name')->get();

        return response()->json($organization);
    }
}
