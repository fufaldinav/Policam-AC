<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\ReferralCode;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @param string|null $referralCode
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(string $referralCode = null)
    {
        if (isset($referralCode)) {
            $rc = ReferralCode::where(['code' => $referralCode, 'user_id' => null])->first();

            if ($rc !== null) {
                $referralCode = $rc->code;
            } else {
                $referralCode = null;
            }
        }

        return view('auth.register', compact('referralCode'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['referral_code'])) {
            $rc = ReferralCode::where(['code' => $data['referral_code'], 'user_id' => null])->first();
            if ($rc !== null) {
                $rc->user_id = $user->id;
                $rc->save();
            }
        }

//        if (isset($data['role'])) {
//            $role = Role::find($data['role']);
//            $user->roles()->save($role);
//        }

        return $user;
    }
}
