<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\Setting as SettingRequest; 
use App\User;
use Auth;



class SettingController extends ApiController{
    

     public function __construct(){
        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != User::ACTIVE)
            {
                Auth::logout();
                return redirect(route('admin-login'));
            }
            return $next($request);
        });
    }
    
     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getSettingPage() {

        $setting = Setting::first();
        if(!$setting){
            $setting = new Setting;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.settings.setting_form', ['setting'=>$setting, 'active' => 'settings'])->with('sub_active', 'manage_setting');
    }


     public function postUpdateSetting(SettingRequest $request)
    {
    
        $message = '';
        $data = $request->except('_token','setting_id');
        if ($request->has('setting_id') && $request->get('setting_id')) {
            $setting = Setting::find($request->get('setting_id'));
        }
        else {
            $setting = new Setting();
        }
        
        $data['per_bid_count_descrease'] = $data['per_bid_count_raise'];
        $setting->fill($data);       
       
        if($setting->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('setting_id') && $request->get('setting_id') !='') {
                $request->session()->flash('message.content',trans('message.setting_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.setting_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.setting_created_successfully'),'setting_id'=>$setting->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_setting'));
            return response()->json(['message'=>trans('message.error_created_setting')],200);            
        }
    }
}
