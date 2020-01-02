<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


     public function rules() {
        //return my custom rules
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:6', 'max:16', 'confirmed',
                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                      //  'regex:/[A-Z]/',      // must contain at least one uppercase letter
                        'regex:/[0-9]/',      // must contain at least one digit
                        /*'regex:/[@$!%*#?&]/'*/ ],
         //   'password_confirmation' => 'required',
        ];
    }    

    public function rules1() {
        //return my custom rules
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:6', 'max:16', 'confirmed',
                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                      //  'regex:/[A-Z]/',      // must contain at least one uppercase letter
                        'regex:/[0-9]/',      // must contain at least one digit
                        /*'regex:/[@$!%*#?&]/'*/ ],
           // 'password_confirmation' => 'required',
        ];
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
      if($request->password_confirmation)
        $request->validate($this->rules(), $this->validationErrorMessages());
      else
        $request->validate($this->rules1(), $this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }


    // add this function to not logged in the user after reset the password

    protected function resetPassword($user, $password)
    {   
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();
        Session::flash('message.level','success');
        Session::flash('message.content',trans('passwords.reset'));

        event(new PasswordReset($user));

       // $this->guard()->login($user);
    }
}
