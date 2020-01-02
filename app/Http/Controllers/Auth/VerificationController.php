<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

use App\User;
use App\Models\UserEmail;
use App\Models\FriendInvitation;
use App\Models\UserBid;
use App\Models\UserPlan;
use App\Models\Setting;
use App\Mail\FriendInviteAccept;
use Auth;
use Mail; 
use Session; 

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {   
        if(Auth::guest()) {
            $user = User::find($request->route('id'));

            if ($user->markEmailAsVerified() && $user->status == '0'){
                $user->status = '1';
                $user->save();
                $request->session()->flash('message.level','success');
                $request->session()->flash('message.content',trans('auth.registration_successfully'));
                event(new Verified($user));
                Auth::login($user);

                /* Add the free bid to user account after registration */

                $setting = Setting::first();
                // is there is setting record used that else value from constant file is used
                if($setting){
                    $freeBidAmount = $setting->free_bid_amount;
                }
                else {
                    $freeBidAmount = FREE_BID_AMOUNT;
                }

                $userplan['user_id'] = $user->id;
                $userplan['bid_count'] = $freeBidAmount;
                $userplan['status'] = UserPlan::ACTIVE;
                $userplan['type'] = UserPlan::FREE_BID;
                UserPlan::create($userplan);

                $userBid['user_id'] = $user->id;
                $userBid['bid_count'] = $freeBidAmount;
                $userBid['free_bid'] = $freeBidAmount;
                UserBid::create($userBid);

                /* End for free bid code */

                /* If the user is register from the invite link */
                $friend = FriendInvitation::where('friend_id',$request->route('id'))->where('status','0')->count();
                if($friend){
                    $data = FriendInvitation::where('friend_id',$request->route('id'))->first();
                    $data->update(['status'=>'1']);

                    // is there is setting record used that else value from constant file is used
                    if($setting){
                        $inviteBidAmount = $setting->invite_bid_amount;
                    }
                    else {
                        $inviteBidAmount = INVITE_BID_AMOUNT;
                    }

                    UserBid::where('user_id',$data->user_id)->increment('bid_count', $inviteBidAmount);
                    UserBid::where('user_id',$data->user_id)->increment('free_bid', $inviteBidAmount);
                    $friendplan['user_id'] = $data->user_id;
                    $friendplan['bid_count'] = $inviteBidAmount;
                    $friendplan['status'] = UserPlan::ACTIVE;
                    $friendplan['type'] = UserPlan::INVITE_BID;
                    $friendplan['invite_user_id'] = $user->id;
                    $friendData  = User::find($data->user_id);
                    UserPlan::create($friendplan);
                    $invite_bids = Setting::firstOrFail();
                    //Mail::to($userdetail->email)->send(new SimpleTextEmail($text,$subject));
                    Mail::to($friendData->email)->send(new FriendInviteAccept($user,$friendData,$invite_bids));
                }
                /* End for invite link code */

                return redirect('edit_profile')->with('verified', true);
            }
            else if($user->markEmailAsVerified() && $user->status != '0'){
                $request->session()->flash('message.level','success');
                $request->session()->flash('message.content',trans('auth.user_verified_already'));

                if($user  && $user->is_complete == '0'){
                    Auth::login($user);
                    return redirect('edit_profile')->with('verified', true);
                }

            }
            else{
                $request->session()->flash('message.level','danger');
                $request->session()->flash('message.content',trans('auth.email_not_valid'));
            }
            return redirect($this->redirectPath())->with('verified', true);
        }
        else {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('auth.already_logged_in'));
            return redirect($this->redirectPath())->with('verified', true);
        }
        
       /* $friend = FriendInvitation::where('friend_id',$request->route('id'))->where('status','0')->count();
        if($friend){
            $data = FriendInvitation::where('friend_id',$request->route('id'))->first();
            $data->update(['status'=>'1']);
            UserBid::where('user_id',$data->user_id)->increment('bid_count', 10);
        }*/

       // $request->session()->flash('message.level','success');
       // $request->session()->flash('message.content','User verify successfully.');
        
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content','This email is already verified !');
//            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();
        $request->session()->flash('message.level','success');
        $request->session()->flash('message.content','The notification has been resubmitted !.');
        return redirect($this->redirectPath())->with('verified', true);
//        return back()->with('resent', true);
    }


    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendLink(Request $request)
    {
       
   
        $user = User::find(encrypt_decrypt('decrypt', $request->get('id')));
        $data = $user->sendEmailVerificationNotification();
        //$request->session()->flash('message.level','success');
        Session::flash('message.level','success');
        Session::flash('message.content',trans('message.email_resend_successfully'));
        //$request->session()->flash('message.content',trans('message.email_resend_successfully'));
        return response()->json(['message'=> trans('message.email_resend_successfully')],200);    
      //  return redirect($this->redirectPath())->with('verified', true);
//        return back()->with('resent', true);
    }


     public function updateEmail(Request $request)
    {   
        $id = encrypt_decrypt('decrypt', $request->route('id'));
        $user = User::with('primaryEmail')->findOrFail($id);
        if(count($user->primaryEmail)){
            User::where('id',$id)->update(['email'=>$user->primaryEmail[0]->email]);
            UserEmail::where('user_id',$id)->delete();
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.new_email_verification_successfully'));
            Auth::login($user);
        }
        else {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('auth.email_not_valid'));
        }
       
        return redirect($this->redirectPath())->with('verified', true);
    }


    
}
