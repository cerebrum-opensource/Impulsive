<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\HomePageWidget;
use App\User;
use Auth;
use App\Http\Requests\NewsLetter as NewsLetterRequest; 


class NewsLetterController extends ApiController{
   

    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != User::ACTIVE)
            {
                Auth::logout();
                return redirect(route('admin-login'));
            }
            return $next($request);
        });
        $this->timezone = user_time_zone();
        
    }
     

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getNewsLetterPage() {

        $news_letter = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_NEWS_LETTER)->first();
        if(!$news_letter){
            $news_letter = new HomePageWidget;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.news_letter.add_news_letter', ['news_letter'=>$news_letter, 'active' => 'news_letter','sub_active' =>'newsletter_list']);
    }

    /**
    * Get Add Footer Link Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */

    public function getNewsLetterCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $widget = HomePageWidget::find($id);              
      }
      else
      {
         $widget = new HomePageWidget;   
      }
     // $faq_type = faq_type();
      return view('admin.static_pages.news_letter.add', ['active' => 'faqs','widget'=>$widget]);
    }



    /**
    * Save/Update Footer Link.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postNewsLetterCreate(NewsLetterRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','widget_id','logo');
        if ($request->has('widget_id') && $request->get('widget_id')) {
            $widget_record = HomePageWidget::find($request->get('widget_id'));
        }
        else {
            $widget_record = new HomePageWidget();
            $data['user_id'] = AUTH::id();
            $data['status'] = HomePageWidget::ACTIVE;
            $data['type'] = HomePageWidget::TYPE_NEWS_LETTER;
        }
        
        $widget_record->fill($data);       
       
        if($widget_record->save())
        {   
          
            $request->session()->flash('message.level','success');
            if ($request->has('widget_id') && $request->get('widget_id') !='0') {
                $request->session()->flash('message.content',trans('message.news_letter_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.news_letter_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.news_letter_created_successfully'),'faq_id'=>$widget_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_news_letter'));
            return response()->json(['message'=>trans('message.error_created_news_letter')],200);            
        }


    }


}
