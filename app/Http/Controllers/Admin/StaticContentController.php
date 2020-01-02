<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\PageSlider;
use App\Models\PageFooter;
use App\Models\FooterLink;
use App\Models\StaticPage;
use App\Models\FaqType;
use App\Models\HomePageWidget;
use App\User;
use Auth;
use App\Http\Helper\Helper;
use App\Http\Requests\Slider as SliderRequest; 
use App\Http\Requests\Footer as FooterRequest; 
use App\Http\Requests\FooterLink as FooterLinkRequest; 
use App\Http\Requests\Widget as WidgetRequest; 
use App\Http\Requests\SupportWidget as SupportWidgetRequest; 
use App\Http\Requests\SeoWidget as SeoWidgetRequest; 
use App\Http\Requests\Page as PageRequest; 



class StaticContentController extends ApiController{
   

    
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
    public function getSliders() {
        return view('admin.static_pages.slider.index')->with('active', 'pages')->with('sub_active', 'sliders');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getSliderList()
    {
        $faqs = PageSlider::query()->orderBy('id', 'DESC');
        return \ DataTables::of($faqs)->editColumn('image', 'admin.static_pages.slider.datatables.image_column')->editColumn('status', 'admin.static_pages.slider.datatables.status_column')->addColumn('actions', 'admin.static_pages.slider.datatables.action_buttons ')->addColumn('setting', 'admin.static_pages.slider.datatables.setting_buttons ')->rawColumns(['actions','status','setting','image'])->addIndexColumn()->make(true);
    }

    /**
    * Function to delete the package.
    */
    public function sliderDelete(Request $request){
      if($request->has('id')){

        $slider_id = $request->id;
          PageSlider::find($slider_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.slider_deleted_successfully')],200);
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_slider')],200);
      }
    }

     /**
    * Get Add FAQ Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */


    public function getCreateEditSlider($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $slider = PageSlider::find($id);              
      }
      else
      {
         $slider = new PageSlider;   
      }
      
      return view('admin.static_pages.slider.add', ['slider'=>$slider])->with('active', 'pages')->with('sub_active', 'sliders');
    }


    /**
    * Save/Update FAQ.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postCreateSlider(SliderRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','slider_id','image');
        if ($request->has('slider_id') && $request->get('slider_id')) {
            $slider_record = PageSlider::find($request->get('slider_id'));
        }
        else {
            $slider_record = new PageSlider();
            $data['type'] = PageSlider::HOME;
            // $data['user_id'] = AUTH::id();
            $data['status'] = PageSlider::ACTIVE;
        }
        
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   

            $filePath = public_path('/images/admin/sliders/').$slider_record->id; 
            if($request->image != null)
            {   
                $imageName1 = time().'.'.request()->image->getClientOriginalExtension();
                $uploadedDP = $request->file('image');
                $uploadedDP->move($filePath, $imageName1);
                PageSlider::where('id',$slider_record->id)->update(['image'=>$imageName1]);
            }

            $request->session()->flash('message.level','success');
            if ($request->has('slider_id') && $request->get('slider_id') !='') {
                $request->session()->flash('message.content',trans('message.slider_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.slider_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.slider_created_successfully'),'slider_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_slider'));
            return response()->json(['message'=>trans('message.error_created_slider')],200);            
        }


    }


    /**
    * Active/deactive FAQ.
    * parameter Request $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatusSlider(Request $request)
    {
    
        $response = PageSlider::where('id',$request->id)->update(['status'=>$request->status]);
          
        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.slider_status_changed_successfully'));
            return response()->json(['message'=>trans('message.slider_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_slider_status'));
            return response()->json(['message'=>trans('message.error_updated_slider_status'),'status'=>0],200);            
        }
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getFooterPage() {

        $footer = PageFooter::first();
        if(!$footer){
            $footer = new PageFooter;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.footer.add_footer', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'footer');
    }


     public function postCreateFooter(FooterRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = PageFooter::find($request->get('footer_id'));
        }
        else {
            $slider_record = new PageFooter();
            // $data['user_id'] = AUTH::id();
            $data['status'] = PageFooter::ACTIVE;
        }
        
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   

            $filePath = public_path('/images/admin/footer/').$slider_record->id; 
            if($request->logo != null)
            {   
                $imageName1 = time().'.'.request()->logo->getClientOriginalExtension();
                $uploadedDP = $request->file('logo');
                $uploadedDP->move($filePath, $imageName1);
                PageFooter::where('id',$slider_record->id)->update(['logo'=>$imageName1]);
            }

            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.footer_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.footer_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.footer_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_footer'));
            return response()->json(['message'=>trans('message.error_created_footer')],200);            
        }


    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getFooterLinkPage() {
        return view('admin.static_pages.footer.index')->with('active', 'pages')->with('sub_active', 'footer_link');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getFooterLinkList()
    {
        $faqs = FooterLink::query()->orderBy('id', 'DESC');
        return \ DataTables::of($faqs)->editColumn('status', 'admin.static_pages.footer.datatables.status_column')->addColumn('actions', 'admin.static_pages.footer.datatables.action_buttons ')->addColumn('setting', 'admin.static_pages.footer.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }

    /**
    * Get Add Footer Link Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */

    public function getFooterLinkCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $footer_link = FooterLink::find($id);              
      }
      else
      {
         $footer_link = new FooterLink;   
      }
     // $faq_type = faq_type();
      return view('admin.static_pages.footer.add', ['footer_link'=>$footer_link])->with('active', 'pages')->with('sub_active', 'footer_link');
    }



    /**
    * Save/Update Footer Link.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postFooterLinkCreate(FooterLinkRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','footer_link_id');
        if ($request->has('footer_link_id') && $request->get('footer_link_id')) {
            $footer_link_record = FooterLink::find($request->get('footer_link_id'));
        }
        else {
            $footer_link_record = new FooterLink();
            // $data['user_id'] = AUTH::id();
            $data['status'] = FooterLink::ACTIVE;
        }
        
        $footer_link_record->fill($data);       
       
        if($footer_link_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_link_id') && $request->get('footer_link_id') !='') {
                $request->session()->flash('message.content',trans('message.footer_link_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.footer_link_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.footer_link_created_successfully'),'faq_id'=>$footer_link_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_footer_link'));
            return response()->json(['message'=>trans('message.error_created_footer_link')],200);            
        }


    }


    /**
    * Active/deactive Footer Link.
    * parameter Request $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatusFooterLink(Request $request)
    {
    
        $response = FooterLink::where('id',$request->id)->update(['status'=>$request->status]);
        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.footer_link_status_changed_successfully'));
            return response()->json(['message'=>trans('message.footer_link_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_footer_link_status'));
            return response()->json(['message'=>trans('message.error_updated_footer_link_status'),'status'=>0],200);            
        }
    }



     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getAGBPage() {

        $footer = StaticPage::where('page_slug','agb')->first();
        if(!$footer){
            $footer = new StaticPage;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.pages.agb', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'agb');
    }


     public function postAGBPage(PageRequest $request)
    {
      
      $message = '';
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = StaticPage::find($request->get('footer_id'));
        }
        else {
            $slider_record = new StaticPage();
            // $data['user_id'] = AUTH::id();
            $data['page_slug'] = 'agb';
            $data['status'] = StaticPage::ACTIVE;
        }    
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.page_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.page_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.page_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_page'));
            return response()->json(['message'=>trans('message.error_created_page')],200);            
        }


    }
         /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getTermPage() {

        $footer = StaticPage::where('page_slug','terms_and_conditions')->first();
        if(!$footer){
            $footer = new StaticPage;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.pages.terms', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'terms');
    }


     public function postCreateTermPage(PageRequest $request)
    {
      
      $message = '';
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = StaticPage::find($request->get('footer_id'));
        }
        else {
            $slider_record = new StaticPage();
            // $data['user_id'] = AUTH::id();
            $data['page_slug'] = 'terms_and_conditions';
            $data['status'] = StaticPage::ACTIVE;
        }    
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.page_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.page_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.page_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_page'));
            return response()->json(['message'=>trans('message.error_created_page')],200);            
        }


    }


        /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getImprintPage() {

        $footer = StaticPage::where('page_slug','imprint')->first();
        if(!$footer){
            $footer = new StaticPage;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.pages.imprint', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'imprint');
    }


     public function postCreateImprintPage(PageRequest $request)
    {
      
      $message = '';
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = StaticPage::find($request->get('footer_id'));
        }
        else {
            $slider_record = new StaticPage();
            // $data['user_id'] = AUTH::id();
            $data['page_slug'] = 'imprint';
            $data['status'] = StaticPage::ACTIVE;
        }    
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.page_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.page_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.page_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_page'));
            return response()->json(['message'=>trans('message.error_created_page')],200);            
        }


    }

     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getAboutUsPage() {

        $footer = StaticPage::where('page_slug','ueber-uns')->first();
        if(!$footer){
            $footer = new StaticPage;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.pages.about', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'about_us');
    }


     public function postCreateAboutPage(PageRequest $request)
    {
      
      $message = '';
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = StaticPage::find($request->get('footer_id'));
        }
        else {
            $slider_record = new StaticPage();
            // $data['user_id'] = AUTH::id();
            $data['page_slug'] = 'ueber-uns';
            $data['status'] = StaticPage::ACTIVE;
        }    
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.page_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.page_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.page_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_page'));
            return response()->json(['message'=>trans('message.error_created_page')],200);            
        }


    }


      /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getHowWorkPage() {

        $footer = StaticPage::where('page_slug','how_it_works')->first();
        if(!$footer){
            $footer = new StaticPage;   
        }

        //return view('admin.static_pages.footer.index');
        return view('admin.static_pages.pages.how_it_work', ['footer'=>$footer, 'active' => 'pages'])->with('sub_active', 'how_it_works');
    }


     public function postCreateHowWorkPage(PageRequest $request)
    {
      
      $message = '';
      $data = $request->except('_token','footer_id','logo');
        if ($request->has('footer_id') && $request->get('footer_id')) {
            $slider_record = StaticPage::find($request->get('footer_id'));
        }
        else {
            $slider_record = new StaticPage();
            // $data['user_id'] = AUTH::id();
            $data['page_slug'] = 'how_it_works';
            $data['status'] = StaticPage::ACTIVE;
        }    
        $slider_record->fill($data);       
       
        if($slider_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('footer_id') && $request->get('footer_id') !='') {
                $request->session()->flash('message.content',trans('message.page_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.page_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.page_created_successfully'),'footer_id'=>$slider_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_page'));
            return response()->json(['message'=>trans('message.error_created_page')],200);            
        }
    }



    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getWidgetPage() {
        $count = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SLIDER)->count();
        return view('admin.static_pages.widget.index',['count'=>$count])->with('active', 'pages')->with('sub_active', 'widgets');
    }

    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getWidgetList()
    {
        $widgets = HomePageWidget::query()->where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SLIDER)->orderBy('id', 'DESC');
        return \ DataTables::of($widgets)->editColumn('status', 'admin.static_pages.widget.datatables.status_column')->addColumn('actions', 'admin.static_pages.widget.datatables.action_buttons ')->addColumn('setting', 'admin.static_pages.widget.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }

    /**
    * Get Add Footer Link Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */

    public function getWidgetCreateEdit($id=null)
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
      return view('admin.static_pages.widget.add', ['widget'=>$widget])->with('active', 'pages')->with('sub_active', 'widgets');
    }



    /**
    * Save/Update Footer Link.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postWidgetCreate(WidgetRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','widget_id');
        if ($request->has('widget_id') && $request->get('widget_id')) {
            $widget_record = HomePageWidget::find($request->get('widget_id'));
        }
        else {
            $widget_record = new HomePageWidget();
            // $data['user_id'] = AUTH::id();
            $data['status'] = HomePageWidget::ACTIVE;
            $data['type'] = HomePageWidget::TYPE_SLIDER;
        }
        
        $widget_record->fill($data);       
       
        if($widget_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('widget_id') && $request->get('widget_id') !='0') {
                $request->session()->flash('message.content',trans('message.widget_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.widget_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.widget_created_successfully'),'faq_id'=>$widget_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_widget'));
            return response()->json(['message'=>trans('message.error_created_widget')],200);            
        }


    }


    /**
    * Active/deactive Footer Link.
    * parameter Request $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatusWidget(Request $request)
    {
    
        $response = HomePageWidget::where('id',$request->id)->update(['status'=>$request->status]);
        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.widget_status_changed_successfully'));
            return response()->json(['message'=>trans('message.widget_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_widget_status'));
            return response()->json(['message'=>trans('message.error_updated_widget_status'),'status'=>0],200);            
        }
    }



     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getSupportWidgetPage() {
        $count = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SUPPORT)->count();
        return view('admin.static_pages.support_widget.index',['count'=>$count])->with('active', 'pages')->with('sub_active', 'support_widgets');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getSupportWidgetList()
    {
        $widgets = HomePageWidget::query()->where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SUPPORT)->orderBy('id', 'DESC');
        return \ DataTables::of($widgets)->editColumn('image', 'admin.static_pages.support_widget.datatables.image_column')->editColumn('status', 'admin.static_pages.support_widget.datatables.status_column')->addColumn('actions', 'admin.static_pages.support_widget.datatables.action_buttons ')->addColumn('setting', 'admin.static_pages.support_widget.datatables.setting_buttons ')->rawColumns(['actions','status','setting','image'])->addIndexColumn()->make(true);
    }

    /**
    * Get Add Footer Link Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */

    public function getSupportWidgetCreateEdit($id=null)
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
      return view('admin.static_pages.support_widget.add', ['widget'=>$widget])->with('active', 'pages')->with('sub_active', 'support_widgets');
    }



    /**
    * Save/Update Footer Link.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postSupportWidgetCreate(SupportWidgetRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','widget_id','logo');
        if ($request->has('widget_id') && $request->get('widget_id')) {
            $widget_record = HomePageWidget::find($request->get('widget_id'));
        }
        else {
            $widget_record = new HomePageWidget();
            // $data['user_id'] = AUTH::id();
            $data['status'] = HomePageWidget::ACTIVE;
            $data['type'] = HomePageWidget::TYPE_SUPPORT;
        }
        
        $widget_record->fill($data);       
       
        if($widget_record->save())
        {   
            $filePath = public_path('/images/admin/widget/').$widget_record->id; 
            if($request->logo != null)
            {   
                $imageName1 = time().'.'.request()->logo->getClientOriginalExtension();
                $uploadedDP = $request->file('logo');
                $uploadedDP->move($filePath, $imageName1);
                HomePageWidget::where('id',$widget_record->id)->update(['image'=>$imageName1]);
            }


            $request->session()->flash('message.level','success');
            if ($request->has('widget_id') && $request->get('widget_id') !='0') {
                $request->session()->flash('message.content',trans('message.widget_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.widget_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.widget_created_successfully'),'faq_id'=>$widget_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_widget'));
            return response()->json(['message'=>trans('message.error_created_widget')],200);            
        }


    }


     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getSeoWidgetPage() {
        
        return view('admin.static_pages.seo_widget.index')->with('active', 'pages')->with('sub_active', 'seo_widgets');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getSeoWidgetList()
    {
        $widgets = HomePageWidget::query()->where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_OTHERS)->orderBy('id', 'DESC');
        return \ DataTables::of($widgets)->editColumn('page_type', 'admin.static_pages.seo_widget.datatables.page_type_column')->editColumn('status', 'admin.static_pages.seo_widget.datatables.status_column')->addColumn('actions', 'admin.static_pages.seo_widget.datatables.action_buttons ')->addColumn('setting', 'admin.static_pages.seo_widget.datatables.setting_buttons ')->rawColumns(['actions','status','setting','image'])->addIndexColumn()->make(true);
    }

    /**
    * Get Add Footer Link Page
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\View 
    */

    public function getSeoWidgetCreateEdit($id=null)
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
      return view('admin.static_pages.seo_widget.add', ['widget'=>$widget])->with('active', 'pages')->with('sub_active', 'seo_widgets');
    }



    /**
    * Save/Update Footer Link.
    * parameter FaqRequest $request
    *
    * @return \Illuminate\Http\Response
    */


    public function postSeoWidgetCreate(SeoWidgetRequest $request)
    {
      
     
      $message = '';
    
      $data = $request->except('_token','id','logo');
        if ($request->has('id') && $request->get('id')) {
            $widget_record = HomePageWidget::find($request->get('id'));
        }
        else {
            $widget_record = new HomePageWidget();
            // $data['user_id'] = AUTH::id();
            $data['status'] = HomePageWidget::ACTIVE;
            $data['type'] = HomePageWidget::TYPE_OTHERS;
        }
        
        $widget_record->fill($data);       
       
        if($widget_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('id') && $request->get('id') !='0') {
                $request->session()->flash('message.content',trans('message.widget_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.widget_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.widget_created_successfully'),'faq_id'=>$widget_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_widget'));
            return response()->json(['message'=>trans('message.error_created_widget')],200);            
        }

 
    }


}
