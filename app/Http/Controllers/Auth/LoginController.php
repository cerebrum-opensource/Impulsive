<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Users;
use App\User;
use Auth;
use URL;
use Illuminate\Support\Facades\Route;
use Validator;
use Session;

class LoginController extends BaseController{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 20;
   /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/dashboard/dashboard';
    protected function redirectTo(){
        $user = Auth::user();

         \App\User::where('id', Auth::user()->id)->increment('login_count', 1);
       /* $data = Users::find($user->id);

        if($user->user_type == '0' && $user->status == '1' ){
            $url = 'admin/dashboard';
        }else{
            $url =  'dashboard';
        }
        return $url;*/
         //$afterplay_link_status = Session::get('afterplay_status');
          $afterplay_link_route = Session::get('afterplay_route');
         //die();
        if($user->hasRole(SUPER_ADMIN) || $user->hasRole(ADMIN))
          {
            return route('admin_dashboard');
          }
          elseif($user->hasRole(USER))
          {
            if(!empty($afterplay_link_route))
              {
                 return 'loss_auction/'.$afterplay_link_route;
              }
            if($user->is_complete == 1){

              return 'dashboard';
            }
            return 'edit_profile';
           // return route('user_profile');

          }
          else
          {
            abort('404');
          }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except(['logout','adminLogout']);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showLoginForm(Request $request){
        // Session1 is using for email and Session2 is using for password

        $email = '';
        $password = '';
        $data = array('email'=>$email,'password'=>$password);

        return view('auth.register',$data);
    }


     protected function credentials(\Illuminate\Http\Request $request)
    {
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'status' => '1'];
    }


    protected function hasTooManyLoginAttempts(\Illuminate\Http\Request $request)
        {

            if($this->limiter()->attempts($this->throttleKey($request)) >= $this->maxAttempts){

                $user = \App\User::where(['email'=>$request->email])->first();
                //deactivate user if user is not an Admin
                if($user && $user->hasRole(USER))
                {
                  $user->status = '0';
                  $user->save();
                }
            }

    }


    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];
        $user = \App\User::where($this->username(), $request->{$this->username()})->first();
        if ($user && \Hash::check($request->password, $user->password) && $user->status == '2') {
            $errors = [$this->username() => trans('auth.notactive_email')];
        }
        if ($user && \Hash::check($request->password, $user->password) && $user->status == '3') {
            $errors = [$this->username() => trans('auth.accountDeleted')];
        }
        elseif($user && \Hash::check($request->password, $user->password) && $user->status == '0') {
            $errors = [$this->username() => trans('auth.notactive')];
        }
        elseif($user && Auth::check() && ((($user->hasRole(SUPER_ADMIN) || $user->hasRole(ADMIN)) && Route::getFacadeRoot()->current()->getName() != 'admin-login') || (($user->hasRole(USER)) && Route::getFacadeRoot()->current()->uri() != 'login'))){
          $errors = [$this->username() => trans('auth.login_not_found')];
          Auth::logout();
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember','is_frontend'))
            ->withErrors($errors);
    }


    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
    }



    /**
     * Handle login Request
     * @return void
     */
     public function login(\Illuminate\Http\Request $request)
    {
        session()->remove('url.intended');

        $this->validateLogin($request);

        $username = $request->email;

        // allow the user to login from email as well password
        if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
    //user sent their email
            $field = 'email';
        } else {
           //they sent their username instead
            $field = 'username';
        }

        if(Auth::attempt([$field => $request->email, 'password' => $request->password, 'status' => '1' ])) {


            $user = $request->user();
          //check if user login from their respective page or not
          if(($user->hasRole(ADMIN) || $user->hasRole(SUPER_ADMIN)  )&& Route::getFacadeRoot()->current()->getName() != 'admin-login')
          {
            return $this->sendFailedLoginResponse($request);
          }
          elseif($user->hasRole(USER) && Route::getFacadeRoot()->current()->uri() != 'login')
          {
            return $this->sendFailedLoginResponse($request);
          }

          return $this->sendLoginResponse($request);
        }
        else
        {
          $this->incrementLoginAttempts($request);

          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              return $this->sendLockoutResponse($request);
          }

          return $this->sendFailedLoginResponse($request);
        }
    }

    // logout method for frontend user

    public function logout(Request $request){
        $user = Auth::user();
        if ($user != "") {
            Users::where('id',$user->id)->update(['is_logged_in'=>'0']);
        }
        $request->session()->invalidate();
        $this->guard()->logout();
        return redirect('/');

     //   $this->performLogout($request);

        //redirect to user login page
     //   return redirect(\URL::previous());
    }


    // logout method for admin

    public function adminLogout(Request $request) {

      $this->guard()->logout();

      //redirect to admin login page
      return redirect()->route('adminlogin');
    }


    // inherit the parent authenticated method to login from ajax and send response
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($request->ajax()){

            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
                'intended' => $this->redirectPath(),
            ]);

        }
    }
}

