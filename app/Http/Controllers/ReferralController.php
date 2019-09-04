<?php

namespace App\Http\Controllers;

use App\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function parseLink(Request $request, int $referralCode = null)
    {
        if (isset($referralCode)) {
            $rc = ReferralCode::where(['code' => $referralCode, 'user_id' => null])->first();

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
        $referralCodes = $request->user()->referralCodes()->select('id', 'code', 'organization_id')->get();

        return response()->json($referralCodes);
    }
}
