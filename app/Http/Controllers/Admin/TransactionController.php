<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exports\OrdersExport;
use App\Exports\TransactionsExport;
use App\Models\UserBuyList;
use App\Models\UserTransaction;
use App\User;
use Auth;
use View;
use Excel;
use Zipper; 
use File; 


class TransactionController extends BaseController{



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
     * Render view for listing of the order.
     * Parameter : Request
     * @param Request $request
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function orderList(Request $request){
        // return Excel::download(new InvoicesExport, 'invoices.xlsx');

        $active = 'orders';
        $sub_active = 'order_list';
        $typeList  = order_type(); 
        $order = UserBuyList::with('user','auction_winner');
        if($request->ajax()) {
            $order = $order->newQuery();
            if ($request->has('status') && $request->input('status') !='') {
                $order->where('status', $request->input('status'));
            }

            if ($request->has('type') && $request->input('type') !='') {
                $order->where('type', $request->input('type'));
            }

            if ($request->has('product_title') && $request->input('product_title') !='') {
                $order->whereHas('product', function($query) use($request){
                    $query->where('product_title', 'like', '%'.$request->input('product_title') . '%')
                    ->orWhere('product_brand', 'like', '%'.$request->input('product_title').'%')
                    ->orWhere('products_short_title', 'like', '%'.$request->input('product_title').'%')
                    ->orWhere('product_short_desc', 'like', '%'.$request->input('product_title').'%');
                });
            }
            if ($request->has('customer_name') && $request->input('customer_name') !='') {
                $customer_name = $request->input('customer_name');
                
                 $order = $order->whereHas('user', function ($q) use ($customer_name) {
                              $q->where('first_name', 'like', '%'.$customer_name.'%')
                              ->orWhere('last_name', 'like', '%'.$customer_name.'%');
                        });
            }
            if ($request->has('email') && $request->input('email') !='') {
                $email = $request->input('email');
                $order = $order->whereHas('user', function ($q) use ($email) {
                            $q->where('email', 'like', '%'.$email.'%');
                });
            }
            $orders = $order->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
            $request->flashOnly(['status','filter_string','type']);
            return view('admin.orders.table', ['orders' => $orders])->render();  
        }
        else {
            $filter_status = "";
            $filter_string = "";
            $type = "";
            $order = $order->newQuery();
            if ($request->old('status') !='') {
                $filter_status = $request->old('status');
                $order->where('status', $request->old('status'));
            }
            if ($request->old('type') && $request->old('type') !='') {
                    $type = $request->old('type');
                    $order->where('type', $request->old('type'));
            }

            if ($request->old('product_title') && $request->old('product_title') !='') {
                    $filter_string = $request->old('product_title');
                    $order->whereHas('product', function($query) use($request){
                        $query->where('product_title', 'like', '%'.$request->old('product_title') . '%')
                        ->orWhere('product_brand', 'like', '%'.$request->old('product_title').'%')
                        ->orWhere('products_short_title', 'like', '%'.$request->old('product_title').'%')
                        ->orWhere('product_short_desc', 'like', '%'.$request->old('product_title').'%');
                    });
            }
            $request->flashOnly(['status','filter_string','type']);
            $orders = $order->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
            return view('admin.orders.orderlist',compact('active','filter_status','filter_string','typeList','sub_active'))->with('orders', $orders);
        } 

    }
 
    /**
    * Order Detail Page
    * parameter $id
    *
    * @return View
    */
     
    public function getOrderDetail($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $detail = UserBuyList::findOrfail($id);
       // $detail->sendProductInvoice();
        return view('admin.orders.view',compact('detail'))->with('active', 'orders')->with('sub_active', 'order_list');;
    }
    

    /**
     * Export the order.
     * Parameter : Request
     * @param Request $request
     * @return Exported file to download
     * @throws \Throwable
     */

    public function ordereExport(Request $request){
        // echo "<pre>";
        // print_r($request->all());
        // die();
        $order = UserBuyList::with('user','auction_winner');
        $order = $order->newQuery();
        if ($request->has('status') && $request->input('status') !='') {
            $order->where('status', $request->input('status'));
        }

        if ($request->has('type') && $request->input('type') !='') {
            $order->where('type', $request->input('type'));
        }
        if ($request->has('product_title') && $request->input('product_title') !='') {
            $order->whereHas('product', function($query) use($request){
                 $query->where('product_title', 'like', '%'.$request->input('product_title') . '%')
                 ->orWhere('product_brand', 'like', '%'.$request->input('product_title').'%')
                 ->orWhere('products_short_title', 'like', '%'.$request->input('product_title').'%')
                 ->orWhere('product_short_desc', 'like', '%'.$request->input('product_title').'%');
            });
        }
        if($request->has('order_id') && !empty($request->get('order_id'))){
            $order->whereIn('id', $request->get('order_id'));
        }
        $orders = $order->orderBy('id', 'desc')->get();

        $fileName =  str_random(28).'.xlsx';

        $data = Excel::download(new OrdersExport($orders), $fileName);
        return $data;

    }

      /**
     * Render view for listing of the transaction.
     * Parameter : Request
     * @param Request $request
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function transactionList(Request $request){

       
        $active = 'transactions';
        $sub_active = 'transaction_list';
        $typeList  = payment_method_type(); 
        $order = UserTransaction::with(['user']);
        if ($request->ajax()) {
            $order = $order->newQuery();
            if ($request->has('status') && $request->input('status') !='') {
                $order->where('status', $request->input('status'));
            }

            if ($request->has('payment_method') && $request->input('payment_method') !='') {
                $order->where('payment_method', $request->input('payment_method'));
            }

            if ($request->has('product_title') && $request->input('product_title') !='') {
                $order->where(function($q) use($request){ 
                        $q->orWhere('transaction_id', 'like', '%'.$request->input('product_title') . '%')
                        ->orWhere('payer_id', 'like', '%'.$request->input('product_title').'%');
                });


            }
            if ($request->has('customer_name') && $request->input('customer_name') !='') {
               /* $order->where(function($q) use($request){ 
                        $q->orWhere('transaction_id', 'like', '%'.$request->input('product_title') . '%')
                        ->orWhere('payer_id', 'like', '%'.$request->input('product_title').'%');
                });*/
                 $customer_name = $request->input('customer_name');

                 $order = $order->whereHas('user', function ($q) use ($customer_name) {
                            $q->where('first_name', 'like', '%'.$customer_name.'%')
                              ->orWhere('last_name', 'like', '%'.$customer_name.'%');
                        });
                 
            }
            if ($request->has('email') && $request->input('email') !='') {
                 $email = $request->input('email');
                $order = $order->whereHas('user', function ($q) use ($email) {
                            $q->where('email', 'like', '%'.$email.'%');
                });
            }
            $orders =$order->where('status',UserTransaction::COMPLETE)->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
            $request->flashOnly(['status','filter_string','payment_method']);
            return view('admin.transactions.table', ['orders' => $orders])->render();  
        }
        else {
            $filter_status = "";
            $filter_string = "";
            $payment_method = "";
            $order = $order->newQuery();
            if ($request->old('status') !='') {
                $filter_status = $request->old('status');
                $order->where('status', $request->old('status'));
            }
            if ($request->old('payment_method') && $request->old('payment_method') !='') {
                    $payment_method = $request->old('payment_method');
                    $order->where('payment_method', $request->old('payment_method'));
            }

            if ($request->old('product_title') && $request->old('product_title') !='') {
                    $filter_string = $request->old('product_title');
                    $order->where(function($q) use($request){ 
                        $q->orWhere('transaction_id', 'like', '%'.$request->old('product_title') . '%')
                        ->orWhere('payer_id', 'like', '%'.$request->old('product_title').'%');
                    });
            }
            $request->flashOnly(['status','filter_string','payment_method']);
            $orders = $order->where('status',UserTransaction::COMPLETE)->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
            return view('admin.transactions.transactionlist',compact('active','filter_status','filter_string','typeList','sub_active'))->with('orders', $orders);
        } 

    }

    /**
    * Transaction Detail Page
    * parameter $id
    *
    * @return View
    */
     
    public function getTransactionDetail($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $detail = UserTransaction::findOrfail($id);
        return view('admin.transactions.view',compact('detail'))->with('active', 'transactions')->with('sub_active', 'transaction_list');
    }

     /**
     * Export the Transaction.
     * Parameter : Request
     * @param Request $request
     * @return Exported file to download
     * @throws \Throwable
     */

    public function transactionExport(Request $request){

        $order = new UserTransaction();
        $order = $order->newQuery();
        if ($request->has('status') && $request->input('status') !='') {
                $order->where('status', $request->input('status'));
            }

        if ($request->has('payment_method') && $request->input('payment_method') !='') {
            $order->where('payment_method', $request->input('payment_method'));
        }

        if ($request->has('product_title') && $request->input('product_title') !='') {
            $order->where(function($q) use($request){ 
                    $q->orWhere('transaction_id', 'like', '%'.$request->input('product_title') . '%')
                    ->orWhere('payer_id', 'like', '%'.$request->input('product_title').'%');
            });
        }
        if($request->has('order_id') && !empty($request->get('order_id'))){
            $order->whereIn('id', $request->get('order_id'));
        }
        $orders = $order->where('status',UserTransaction::COMPLETE)->orderBy('id', 'desc')->get();

        $fileName =  str_random(28).'.xlsx';

        $data = Excel::download(new TransactionsExport($orders), $fileName);
        return $data;

    }

    /**
     * Export the Transactions Invoice.
     * Parameter : Request
     * @param Request $request
     * @return Export invoice for download
     * @throws \Throwable
     */
      public function transactionInvoiceExport(Request $request){
        $data = [];
        $order = new UserTransaction();
        $order = $order->newQuery();
        if ($request->has('status') && $request->input('status') !='') {
                $order->where('status', $request->input('status'));
            }

        if ($request->has('payment_method') && $request->input('payment_method') !='') {
            $order->where('payment_method', $request->input('payment_method'));
        }

        if ($request->has('product_title') && $request->input('product_title') !='') {
            $order->where(function($q) use($request){ 
                    $q->orWhere('transaction_id', 'like', '%'.$request->input('product_title') . '%')
                    ->orWhere('payer_id', 'like', '%'.$request->input('product_title').'%');
            });
        }
        if($request->has('order_id') && !empty($request->get('order_id'))){
            $order->whereIn('id', $request->get('order_id'));
        }
        $orders = $order->where('status',UserTransaction::COMPLETE)->orderBy('id', 'desc')->get();
        $string = str_random(30);
        foreach ($orders as $key => $order) {

            $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($order->id);
            if($invoice->user_buy_list_id != null){
                $invoice->downloadProductInvoice(0,$string);

               
            }

            if($invoice->package_id != null){
                $invoice->downloadPackageInvoice(0,$string);
            }
        }

        File::deleteDirectory(public_path('invoice_zip'));

        $folderName = 'app/pdf'.$string.'/*.pdf';
        $files = glob(storage_path($folderName));

        $zipFolder = 'invoice_zip/'.$string.'.zip';
        Zipper::make($zipFolder)->add($files)->close();
       // sleep(50);

        $folderNames = 'app/pdf'.$string.'';
        File::deleteDirectory(storage_path($folderNames));

        $downloadFile = response()->download(public_path($zipFolder));

        //File::delete(public_path($zipFolder));
        return $downloadFile;
       // return response()->json(['data'=>'sasa'],200);

    }

    /**
     * Export the Transactions Invoice for a particular id.
     * Parameter : id
     * @param Request $id
     * @return Export invoice for download
     * @throws \Throwable
     */

     public function transactionDetailInvoiceExport($id){

        $id = encrypt_decrypt('decrypt',$id);
        $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($id);
        if($invoice->user_buy_list_id){
            return $invoice->downloadProductInvoice(1);
        }
        if($invoice->package_id){
            return $invoice->downloadPackageInvoice(1);
        }
    }
}
