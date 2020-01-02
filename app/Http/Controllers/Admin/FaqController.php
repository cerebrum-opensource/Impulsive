<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Faq;
use App\Models\FaqType;
use App\User;
use Auth;
use App\Http\Helper\Helper;
use App\Http\Requests\Faq as FaqRequest; 
use App\Http\Requests\FaqType as FaqTypeRequest; 



class FaqController extends ApiController{
   

    
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
    public function getFaqs() {
        return view('admin.faqs.index')->with('active', 'faqs')->with('sub_active', 'faq_list');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getFaqList()
    {
        $faqs = Faq::query()->orderBy('id', 'DESC');
        return \ DataTables::of($faqs)->editColumn('status', 'admin.faqs.datatables.status_column')->addColumn('actions', 'admin.faqs.datatables.action_buttons ')->addColumn('setting', 'admin.faqs.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }

  

     /**
    * Get Add FAQ Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */


    public function getCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $faq = Faq::find($id);              
      }
      else
      {
         $faq = new Faq;   
      }
      //$faq_type = faq_type();
      $faq_type = FaqType::pluck('name','id')->prepend(trans('label.please_select'), '')->toArray();
      return view('admin.faqs.add', ['faq'=>$faq, 'active' => 'faqs','faq_type'=>$faq_type])->with('active', 'faqs')->with('sub_active', 'faq_list');
    }

        /**
    * Function to delete the category.
    */
    public function faqDelete(Request $request){
      if($request->has('id')){

        $faq_id = $request->id;

          Faq::find($faq_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.faq_deleted_successfully')],200);

      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_faq')],200);
      }
    }


    /**
    * Save/Update FAQ.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postCreate(FaqRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','faq_id');
        if ($request->has('faq_id') && $request->get('faq_id')) {
            $faq_record = Faq::find($request->get('faq_id'));
        }
        else {
            $faq_record = new Faq();
            $data['type'] = Faq::LOGIN;
            $data['user_id'] = AUTH::id();
            $data['status'] = Faq::ACTIVE;
        }
        
        $faq_record->fill($data);       
       
        if($faq_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('faq_id') && $request->get('faq_id') !='') {
                $request->session()->flash('message.content',trans('message.faq_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.faq_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.faq_created_successfully'),'faq_id'=>$faq_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_faq'));
            return response()->json(['message'=>trans('message.error_created_faq')],200);            
        }


    }


    /**
    * Active/deactive FAQ.
    * parameter Request $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatus(Request $request)
    {
    
        $response = Faq::where('id',$request->id)->update(['status'=>$request->status]);
          


        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.faq_status_changed_successfully'));
            return response()->json(['message'=>trans('message.faq_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_faq_status'));
            return response()->json(['message'=>trans('message.error_updated_faq_status'),'status'=>0],200);            
        }
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getFaqTypes() {
        return view('admin.faq_type.index')->with('active', 'faqs')->with('sub_active', 'faq_type_list');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getFaqTypeList()
    {
        $faqs = FaqType::query()->orderBy('id', 'DESC');
        return \ DataTables::of($faqs)->editColumn('status', 'admin.faq_type.datatables.status_column')->addColumn('actions', 'admin.faq_type.datatables.action_buttons ')->addColumn('setting', 'admin.faq_type.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }

  

     /**
    * Get Add FAQ Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */


    public function getFaqTypeCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $faq_type = FaqType::find($id);              
      }
      else
      {
         $faq_type = new FaqType;   
      }
     // $faq_type = faq_type();
      return view('admin.faq_type.add', ['active' => 'faqs','faq_type'=>$faq_type])->with('sub_active', 'faq_type_list');;
    }



    /**
    * Save/Update FAQ.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postFaqTypeCreate(FaqTypeRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','faq_type_id');
        if ($request->has('faq_type_id') && $request->get('faq_type_id')) {
            $faq_record = FaqType::find($request->get('faq_type_id'));
        }
        else {
            $faq_record = new FaqType();
            $data['user_id'] = AUTH::id();
            $data['status'] = Faq::ACTIVE;
        }
        
        $faq_record->fill($data);       
       
        if($faq_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('faq_id') && $request->get('faq_id') !='') {
                $request->session()->flash('message.content',trans('message.faq_type_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.faq_type_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.faq_type_created_successfully'),'faq_id'=>$faq_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_faq_type'));
            return response()->json(['message'=>trans('message.error_created_faq_type')],200);            
        }


    }


    /**
    * Active/deactive FAQ.
    * parameter Request $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatusFaqType(Request $request)
    {
    
        $response = FaqType::where('id',$request->id)->update(['status'=>$request->status]);
          


        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.faq_type_status_changed_successfully'));
            return response()->json(['message'=>trans('message.faq_type_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_faq_type_status'));
            return response()->json(['message'=>trans('message.error_updated_faq_type_status'),'status'=>0],200);            
        }
    }
}
