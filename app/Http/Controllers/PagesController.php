<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Mail;

use App\Models\{UserEmail, Package, UserPlan, UserTransaction, FriendInvitation, AuctionQueue, UserWishlist, AuctionWinner, UserBonusBid, UserBuyList};
use App\Models\{ Faq, FaqType, Auction, Query };

use App\Mail\ProductOrderPlaced;
use App\Mail\QueryCreated;
use App\Services\StaticContent;
use App\Mail\SimpleTextEmail;

use App\Http\Requests\User\ContactUs as ContactUsRequest; 

class PagesController extends Controller
{   

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->timezone = user_time_zone();
        $this->static_content = new StaticContent();
        
    }


    /**
     * function to show the static page
     * @param  Path/Blade name
     * 
     * @return \Illuminate\Http\Response :: json
    */

	public function __invoke($path)
    {	  
        $queueAuctions = [];
        $liveAuctions = [];
        $timezone = $this->timezone;

        $data = $this->static_content->getPage($path);
        if($path == 'about_us'){
            $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
        }
        if($path == 'terms_and_conditions'){
            $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        }
	      try{
        	return view('static_pages.'.str_replace('/', '.', $path),['queueAuctions'=>$queueAuctions,'liveAuctions'=>$liveAuctions,'timezone'=>$timezone,'data'=>$data]);
        } catch (\InvalidArgumentException $e) {
        	abort(404);
        }
        
    }


    public function generatePDF()
    {	

    	$invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail(53);

    /*	$data = $invoice->getProductInvoice();
    	//$data = $invoice->getPackageInvoice();
			if($data['shipping_address']){
            $shi = json_decode($data['shipping_address'], true);

            }*/
    	
    	
    	//print_r($data);

    	//die();
    	/* For Product */
    	$pdf = PDF::loadView('pdf.productPdf', ['']);
        $text ='You have placed a order of the product. please check your invoice.';
        $subject ='Invoice for Product Order';
  		Mail::to('parveshdata@yopmail.com')->send(new ProductOrderPlaced($pdf->output(),$text,$subject));

    	//print_r($data);




    	//PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        /* 	for package
        $pdf = PDF::loadView('pdf.packagePdf', $data);
        $text ='You have placed a order of the product. please check your invoice.';
        $subject ='Invoice for Product Order';
  		Mail::to('parveshdata@yopmail.com')->send(new ProductOrderPlaced($pdf->output(),$text,$subject));
		*/
    }

    /**
     * function to Show the FAQ Page
     * 
     * @return View
    */

    public function getFaqsPage(){

        $faqTypes = FaqType::with('faqs')->where('status',FaqType::ACTIVE)->get();
       // $loginFaqs = Faq::where('type',Faq::LOGIN)->get();        
       // $bidsFaqs = Faq::where('type',Faq::OTHER)->get();        

        return view('static_pages.faq', ['faqTypes'=>$faqTypes]);
    }

    /**
     * function to Add the contact us query
     * @param  ContactUsRequest  $request
     * 
     * @return \Illuminate\Http\Response :: Redirect
    */


    public function addToQuery(ContactUsRequest $request){
        $data = $request->except('_token');
        $data['status'] = Query::RECIEVE;
        $query = new Query(); 

        $query->fill($data);       
   
        if($query->save())
        {   
            $text = trans('message.query_email_message');
            $subject = trans('message.query_email_subject');

            $textSender = trans('message.sender_query_email_message');
            $subjectSender = trans('message.sender_query_email_subject');
            Mail::to($data['email'])->send(new QueryCreated($data,$text,$subject));
            //Mail::to($data['email'])->send(new SimpleTextEmail($textSender,$subjectSender));
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.query_generated_successfully'));
            return response()->json(['message'=>trans('message.query_generated_successfully'),'query_id'=>$query->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_query'));
            return response()->json(['message'=>trans('message.error_created_query')],200);            
        }
    }


    public function getTermPages(){
        $path = 'terms_and_conditions';
        $queueAuctions = [];
        $liveAuctions = [];
        $timezone = $this->timezone;
        $data = $this->static_content->getPage($path);   
        return view('pages.'.str_replace('/', '.', $path),['queueAuctions'=>$queueAuctions,'liveAuctions'=>$liveAuctions,'timezone'=>$timezone,'data'=>$data]);
        
    }
    public function getAGBPage(){
        $path = 'agb';
        $queueAuctions = [];
        $liveAuctions = [];
        $timezone = $this->timezone;
        $data = $this->static_content->getPage($path);   
        return view('pages.'.str_replace('/', '.', $path),['queueAuctions'=>$queueAuctions,'liveAuctions'=>$liveAuctions,'timezone'=>$timezone,'data'=>$data]);
        
    }


    public function getImprintPage(){

        $path = 'imprint';
        $queueAuctions = [];
        $liveAuctions = [];
        $timezone = $this->timezone;
        $data = $this->static_content->getPage($path);   
        return view('pages.'.str_replace('/', '.', $path),['queueAuctions'=>$queueAuctions,'liveAuctions'=>$liveAuctions,'timezone'=>$timezone,'data'=>$data]);
    }
    public function getContestPage(){

        // $path = 'imprint';
        // $queueAuctions = [];
        // $liveAuctions = [];
        // $timezone = $this->timezone;
        // $data = $this->static_content->getPage($path);   
        return view('pages.contest');
    }

}
