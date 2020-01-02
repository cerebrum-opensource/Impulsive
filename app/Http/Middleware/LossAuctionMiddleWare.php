<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Models\UserLossAuction;
use Session;

class LossAuctionMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if (Auth::user() && !Auth::user()->hasRole(USER)) //If user does //not have this permission
        {
            abort('401');
            exit;
        }
        else if(Auth::guest()){
             $token = @$request->route()->parameters()['token'];
             $afterplay_route_addr = route('frontend_loss_auction',[$token]);
             Session::put('afterplay_route', $token);
            return redirect('loss_auction_error')->with('error',trans('message.not_login_user_message_error'));
        }
        else {

            if(count($request->route()->parameters()) > 0){
                $userId = Auth::id();

                $token = @$request->route()->parameters()['token'];
                $currentDate = date( "Y-m-d ");
                $data = UserLossAuction::where('link', $token)->where('status',UserLossAuction::ACTIVE)->whereDate('link_expire_time','>=', $currentDate)->firstOrFail();
                $userDataId = $data->user_id;
                if($userDataId == $userId){

                }
                else {
                    return redirect('loss_auction_error')->with('error',trans('message.loss_link_not_valid'));
                }

            }
        }
        
        return $next($request);
    }
}
