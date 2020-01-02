<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Query;
use App\User;
use App\Mail\SimpleTextEmail;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;



class QueryController extends ApiController{
    

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
    public function getContactUs() {
        return view('admin.queries.index')->with('active', 'contact_us')->with('sub_active', 'list');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getcontactUsList()
    {
        $queries = Query::query()->orderBy('id', 'DESC');
        return \ DataTables::of($queries)->editColumn('status', 'admin.queries.datatables.status_column')->addColumn('actions', 'admin.queries.datatables.action_buttons ')->addColumn('setting', 'admin.queries.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }

  
    /**
    * Contact Detail
    * parameter $id
    *
    * @return View
    */
    
    public function getcontactUsDetail($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $detail = Query::findOrfail($id);
        return view('admin.queries.view',compact('detail'))->with('active', 'contact_us')->with('sub_active', 'list');
    }




    public function replyMail(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(),[
                'reply_message' => ['required']
            ]);
            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()],422);
            }
            $recieve_mail = $request->input('email');
            $sender_mail = 'noreply@winimi.de';
            $mail_text = $request->input('reply_message');
            $query_id = $request->input('id');
            $subject = "Reply for your query/message.";
            Mail::to($recieve_mail)->send(new SimpleTextEmail($sender_mail,$mail_text,$subject));
            Query::where('id',$query_id)->update(['status'=>Query::RESPONSE,'reply_time'=>Carbon::now()]);
            return response()->json(['message'=> "Mail sent"],200);
        }else{
            return response()->json(['message'=> "Mail not sent"],500);
        }
        
    }
}
