<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use DB;
use Auth;
use Hash;
use Carbon\Carbon;
use Validator;
use Mail; 
use Session;
use View;


class PasswordController extends Controller {


	public function sendPasswordResetToken(Request $request)
	{
		$validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }

		$user = User::where ('email', $request->email)->first();
	    if ( !$user ) {
	    	return response()->json(['errors'=>['email'=>"Email doesn't exists."]],422);
	    }elseif($user->status == 0){
	    	return response()->json(['errors'=>['email'=>"Your account has been blocked by some reason. Please contact administrator."]],422);
	    }elseif($user->status == 2){
	    	return response()->json(['errors'=>['email'=>"Email doesn't exists."]],422);
	    }

	    //create a new token to be sent to the user. 
	    $otp = rand(111111,999999);
	    DB::table('password_resets')->where('email', $request->email)->delete();
	    DB::table('password_resets')->insert([
	        'email' => $request->email,
	        'token' => $otp,
	        'created_at' => Carbon::now()
	    ]);


	    /* send mail here 
		 *
		 *
		 *
		*/
		$user->sendPasswordResetNotification($otp);
	    // Mail::to($request->email)->send(new PasswordCreate($reset_token));

	    $request->flashOnly(['email']);
	    return response()->json(['message'=>'OTP sent successfully.'],200);
	}

	public function resendPasswordResetToken(Request $request)
	{
		$validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }

		$user = User::where ('email', $request->email)->first();
	    if ( !$user ) {
	    	return response()->json(['errors'=>['email'=>"Email doesn't exists."]],422);
	    }

	    //create a new token to be sent to the user. 
	    $otp = rand(111111,999999);
	    DB::table('password_resets')->where('email', $request->email)->delete();
	    DB::table('password_resets')->insert([
	        'email' => $request->email,
	        'token' => $otp,
	        'created_at' => Carbon::now()
	    ]);


	    /* send mail here 
		 *
		 *
		 *
		*/
	    $user->sendPasswordResetNotification($otp);

	    $request->flashOnly(['email']);
	    return response()->json(['message'=>'OTP sent successfully.'],200);
	}

	public function resetPassword(Request $request)
	{
		$validator = Validator::make($request->all(),[
           // 'password' => 'required|min:6|confirmed',
            'password' => 'bail|required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password_confirmation' => 'required|min:6'
        ]);

        if($validator->fails()){
        	$request->session()->reflash();
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $validator = Validator::make($request->all(),[
           'password' => 'confirmed'
        ]);

        if($validator->fails()){
        	$request->session()->reflash();
            return response()->json(['errors'=>$validator->errors()],422);
        }
        
	    $tokenData = DB::table('password_resets')->where('token', $request->old('otp'))->where('email', $request->old('email'))->first();
	    
	    if($tokenData){
	    	$user = User::where('email', $request->old('email'))->where('status',1)->first();
		     if ( !$user ) {
		     	$request->session()->reflash();
		     	return response()->json(['errors'=>['password'=> 'Oops!! Something went wrong.']],422);	
		     }

		     $validator = Validator::make($request->all(),[
	            'password' => 'old_password:user,'.$user->id
	        ]);

	        if($validator->fails()){
	        	$request->session()->reflash();
	            return response()->json(['errors'=>$validator->errors()],422);
	        }

		    $user->password = Hash::make($request->password);
	    	$user->update(); 

	    	//also make an entry in last password table
	    	LastPassword::create(['password' =>Hash::make($request->password),'type_id'=>$user->id,'type'=>'user']);

	     	//do we log the user directly or let them login and try their password for the first time ? if yes 
	     //	Auth::login($user);

	    	// If the user shouldn't reuse the token later, delete the token 
	    	DB::table('password_resets')->where('email', $user->email)->delete();

	    	$request->session()->flash('status','You have successfully changed your account password ! ');
            return  response()->json(['message'=>'Password changed successfully.'],200);

	    }else{
	    	$request->session()->reflash();
	    	return response()->json(['errors'=>['password'=> 'Oops!! Something went wrong.']],422);	
	    }	  
	}

	public function verifyOTP(Request $request)
	{
	 	$validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'otp' => 'required|min:4|numeric'
        ]);

        if($validator->fails()){
        	$request->session()->reflash();
            return response()->json(['errors'=>$validator->errors()],422);
        }

        if($request->email != $request->old('email')){
        	$request->session()->reflash();
        	return response()->json(['errors'=>['email'=> 'Something went wrong!!']],422);	
        }

	    $tokenData = DB::table('password_resets')->where('token', $request->otp)->where('email', $request->email)->first();

	    if($tokenData){

	    	$created = new Carbon($tokenData->created_at);
			$now = Carbon::now();
			$difference = $now->diffInSeconds($created);
			
			if($difference < 1800){
				$user = User::where('email', $request->email)->where('status',1)->first();
			     if ( !$user ) {
			     	return response()->json(['errors'=>['email'=> 'Something went wrong!!']],422);	
			     }

			    $html=View::make('auth.passwords.reset_password_fields')->render();
			    $request->flash();
	            return  response()->json(['html'=>$html],200);
            }else{
		    	$request->session()->reflash();
		    	return response()->json(['errors'=>['otp'=> 'Invalid expired.']],422);	
		    }
		}else{
	    	$request->session()->reflash();
	    	return response()->json(['errors'=>['otp'=> 'Invalid OTP.']],422);	
	    }	    
	 }


}