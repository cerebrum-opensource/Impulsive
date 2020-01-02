<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use App\Mail\PasswordCreate;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use App\Models\FriendInvitation;


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
//    protected $redirectTo = '/login';
    protected $redirectTo = '/register-welcome';

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
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {


        $test        = $data;
        $data['dob'] = $data['b-day'] . '.' . $data['b-month'] . '.' . $data['b-year'];

        //  Mail::to('testdata@yopmail.com')->send(new PasswordCreate('sajhaksaksksjajskj'));
        $validator = Validator::make($data, [
            'username'     => [
                'required',
                'string',
                'max:255',
                'unique:users',
            ],
            'dob'          => [
                'required',
                'date_format:d.m.Y',
                'before:' . date("d.m.Y", strtotime("-10 year +1 days")),
                'after:' . date("d.m.Y", strtotime("-100 year")),
            ],
            // 'last_name' => ['required', 'string', 'max:255'],
            'accept'       => [
                'required',
            ],
            'invite_token' => [
                'nullable',
                'exists:friend_invitations,token,friend_id,NULL',
            ],
            'email'        => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password'     => [
                'required',
                'string',
                'min:6',
                'max:16',
                'confirmed',
                'regex:/[a-z]/',
                // must contain at least one lowercase letter
                //  'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',
                // must contain at least one digit
                /*'regex:/[@$!%*#?&]/'*/
            ],
            'country'      => [
                'required',
                'regex:(Deutschland|Österreich)',
            ],

            'g-recaptcha-response' => 'recaptcha',

        ]);
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['dob'] = $data['b-day'] . '.' . $data['b-month'] . '.' . $data['b-year'];
        return User::create([
                                'username'    => $data['username'],
                                'status'      => '0',
                                'is_complete' => 0,
                                'email'       => $data['email'],
                                'dob'         => change_date_format_new($data['dob']),
                                'password'    => Hash::make($data['password']),
                                'country'     => $data['country'],
                            ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        if ($request->has('invite_token') && $request->get('invite_token') != '') {
            $friend = FriendInvitation::where('token', $request->get('invite_token'))->update(['friend_id' => $user->id]);
        }

        event(new Registered($user));
        $role_r = Role::where('name', '=', USER)->firstOrFail();
        $user->assignRole($role_r);
        // $this->guard()->login($user); // <- it's actually this line login the fresh user
        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', trans('auth.registration_successfully'));
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
