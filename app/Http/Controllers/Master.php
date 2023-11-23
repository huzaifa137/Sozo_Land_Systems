<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRegister;
use App\Models\buyer;
use App\Models\Estate;
use App\Models\plot;
use App\Models\house;
use App\Models\reciept;
use App\Models\agreement;
use Illuminate\Support\Facades\Hash;
use Alert;
use DB;
use Carbon\Carbon;


class Master extends Controller
{
    public function login(){

        return view('Login.login');
    }

    public function register(){

        return view('Login.register');
    }

    public function admin_register_data(Request $request)
    {

        $request->validate([
            'username'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'confirm_password'=>'required',
        ]);

        $original_pass = $request->password;
        $confirm_pass = $request->confirm_password;


        if($original_pass === $confirm_pass){

        $register_admin =  new AdminRegister;

        $register_admin->username = $request->username;
        $register_admin->email = $request->email;
        $register_admin->password = Hash::make($request->password);

        $save = $register_admin->save();
            
        if($save){

            return back()->with('success','New Admin has been added to the Sozo Land Systems');
        }


        }
        else{
            return back()->with('fail','Password is not the same as confirm password')->withInput();
        }
    }

    public function admin_check(Request $request){

        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        $AdminEmail = AdminRegister::where('email','=',$request->email)->first();

        if(!$AdminEmail){
            return back()->with('fail','We dont recognise the above email or password');
        }
        else{

            if(Hash::check($request->password,$AdminEmail->password)){
                
                $request->session()->put('LoggedAdmin',$AdminEmail->id);

                return redirect('admin-dashboard');
            }
            else     
            {
             return back()->with('fail','incorrect email or password'); 
            }
        }
    }

    public function logout()
        {
            if(session()->has('LoggedAdmin'))
            {
                session()->pull('LoggedAdmin');
                return redirect('/');
            }
        }


    public function dashboard()
    {
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        
        return view('Admin.dashboard',$data);
    }

    public function admin_buyer(){

        $estates = Estate::all();

        $plots = DB::table('plots')->where('status','=',"Not taken")->get();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.buyer',$data,compact(['estates','plots']));
    }

    public function store_buyer_details(Request $request){

        $post = new buyer;

        $post->firstname= $request->firstname;
        $post->lastname= $request->lastname;
        $post->gender= $request->gender;
        $post->date_of_birth= $request->date_of_birth;
        $post->NIN= $request->NIN;
        $file=$request->national_id;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/national_id'),$filename);
        $post->national_id=$filename;    
        $post->card_number= $request->card_number;
        $post->land_poster= $request->land_poster;
        $post->method_payment= $request->payment_method;
        $post->purchase_type= $request->purchase_type;
        $post->estate= $request->estate;
        $post->location= $request->location;
        $post->width= $request->width;
        $post->height= $request->height;
        $post->plot_number= $request->plot_number;
        $post->amount_payed= $request->amount_payed;
        $post->balance= $request->balance;
        $post->reciepts= $request->receipt_img;
        $post->agreement = $request->agreement;
        // $file=$request->receipt_img;
        // $filename=date('YmdHi').$file->getClientOriginalName();
        // $file->move(public_path('public/receipts'),$filename);
        // $post->reciepts=$filename; 

        // $file=$request->agreement;
        // $filename=date('YmdHi').$file->getClientOriginalName();
        // $file->move(public_path('public/agreements'),$filename);
        // $post->agreement=$filename;

        $post->next_installment_pay= $request->next_installment_pay;
        
        $save = $post->save();

        // DB::insert('insert into reciepts (user_id,amount,balance,reciept) values (?,?,?,?)', [$user_agree_id,$balance,$user_amount_paid,$reciepts]);

        if($save){
           
            return back()->with('success','Sale has been done successfull');
        }
    }


    public function Edit_sale($id)
    {
        return buyer::find($id);
    }
 
    public function delete_sale($id)
    {
        return buyer::find($id);
    }

    public function view_specific_sale($id)
    {
        $userdata = buyer::find($id);
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.view_specific_buyer',$data,compact(['userdata']));
    }

    // ESTATES FUNCTIONS
    public function estates(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
         return view('Admin.estates',$data);
    }

    public function add_estate(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        return view('Admin.add_estate',$data);
    }

    public function store_estate(Request $request){


        $post = new Estate;

       $post->estate_name = $request->estate_name;
       $post->location = $request->location;
       $post->number_of_plots = $request->number_of_plots;
       $post->save();
        
       Alert::success('Estate added', 'Congurations on adding a new Estate');


       return back();
    }

    public function plots(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        $estates = Estate::all();

        return view('Admin.plots',$data,compact('estates'));
    }

    public function add_house()
    {
        $estates = Estate::all();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.addhouse',$data,compact(['estates']));
    }

    public function send_plot_estate(Request $request){
        
        $data = $request->all();

        $post = new plot;

       $post->estate = $request->Estate;
       $post->plot_number = $request->plot_number;
       $post->location = $request->location;
       $post->width = $request->width;
       $post->height = $request->height;
       $post->status = $request->status;
       
       $post->save();
        
       Alert::success('Plot added', 'Congurations on adding a new Plot');

       return back();
    }

    public function send_house_data(Request $request){

        // return $request->all();

        $post = new house;

        $post->estate = $request->Estate;
        $post->plot_number = $request->plot_number;
        $post->location = $request->location;
        $post->width = $request->width;
        $post->height = $request->height;
        $post->status = $request->status;

        $post->save();
        
        Alert::success('House added', 'Congurations on adding a new house');
 
        return back();

    }


    public function update_data_form(Request $request){

        $info = $request->Estate_plot;

        $specific_estate = DB::table('plots')->select('plot_number')->where('estate','=',$info)->get();

        return response()->json([
            "status"=>TRUE,
            "message"=>"data has been creaeted",
            "data"=>$specific_estate,
        ]);
    }

    // Reciepts and Agreements

    public function pending_agreements(){
        $pending_agreements = DB::table('buyers')->where('reciepts','=',"0")
                    ->where('next_installment_pay','=',"Fully payed")->get();

         $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending_agreements',$data,compact(['pending_agreements']));

    }

    public function pending_buyers()
    {

        $not_fully_paid = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")
                                             ->where('reciepts','!=',"0")->get();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending',$data,compact(['not_fully_paid']));
    }

    public function pending_receipts()
    {
        $pending_reciepts = DB::table('buyers')->where('reciepts','=',"0")
                                               ->where('next_installment_pay','!=',"Fully payed")->get();

                                        
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending_receipts',$data,compact(['pending_reciepts']));
    }


    public function accomplished_buyers()
    {
        $fully_paid = DB::table('buyers')->where('next_installment_pay','=',"Fully payed")
                                         ->where('reciepts','!=',"0")->get();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.accomplished',$data,compact(['fully_paid']));
    }

    public function add_reciept($id){

        $user_id = $id;
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_receipt',$data,compact(['user_id']));
    }

    public function view_reciept($id){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        $user_information = DB::table('buyers')->where('id','=',$id)->get();
        $user_reciepts = DB::table('reciepts')->where('user_id','=',$id)->get();

        return view('Admin.Receipts.view_receipts',$data,compact(['user_information','user_reciepts']));
    }

    public function add_agreement($id){

        $user_id = $id;
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_agreement',$data,compact(['user_id']));
    }

    public function view_agreement($id){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        $user_information = DB::table('buyers')->where('id','=',$id)->get();
        $user_reciepts = DB::table('reciepts')->where('user_id','=',$id)->get();
        $user_agreements = DB::table('agreements')->where('user_id','=',$id)->get();

        return view('Admin.Receipts.view_agreement',$data,compact(['user_information','user_reciepts','user_agreements']));
    }

    public function store_new_receipt(Request $request)
    {

        $user_id = $request->user_id;
        $amount_paid = $request->amount_paid;
        $Balance = $request->balance_pending;

        $post = new reciept();

        $file=$request->receipt_added;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/receipts'),$filename);
        $post->reciept=$filename; 
        
        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;

        $save = $post->save();

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');

        $all_cash = $original_amount+$amount_paid;
        
        $update_buyer_amount=buyer::where('id',$user_id)
                                    ->update([ 'amount_payed'=> $all_cash,
                                                'balance'=>$Balance
                                                        ]);

        if($save)
        {
            return redirect('pending-buyers')->with('success','Reciept has been recorded successfully');
        }
    }

    public function add_first_reciept($id){

        $user_id = $id;
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_first_receipt',$data,compact(['user_id']));
    }

    public function store_first_receipt(Request $request)
    {

        $user_id = $request->user_id;
        $Amount = $request->amount_paid;
        $Balance = $request->balance_pending;

        $post = new reciept();

        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;
        
        $file=$request->receipt_added;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/receipts'),$filename);
        $post->reciept=$filename; 

        $save = $post->save();

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$Amount;

        $update_buyer_amount=buyer::where('id',$user_id)
                                    ->update(['reciepts'=>"Pending",
                                              'amount_payed'=> $all_cash,
                                              'balance'=>$Balance]);

        if($update_buyer_amount)
        {
            return redirect('pending-buyers')->with('success','Reciept has been recorded successfully');
        }
    }



    public function store_agreement(Request $request)
    {

        $user_id = $request->user_id;
        $reciepts = $request->reciept_added;
        $agreement_reciept = $request->agreement_added;
        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = $request->Date_of_payment;

        $balance = 0;

        $post = new agreement();

        $post->user_id = $request->user_id;
        $post->Amount_paid = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;

        $file=$request->reciept_added;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/receipts'),$filename);
        $user_receipt =$filename; 
        $post->reciept=$filename; 

        $file=$request->agreement_added;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/agreements'),$filename);
        $post->agreement=$filename; 

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$user_amount_paid;

        
        $save = $post->save();

        $update_buyer_agreement = buyer::where('id',$user_id)->update(['next_installment_pay'=>"Fully payed",
                                                            'reciepts'=>$reciepts,
                                                            'agreement'=>$agreement_reciept,
                                                            'amount_payed'=> $all_cash,
                                                            'balance'=>$balance]);


        DB::insert('insert into reciepts (user_id,amount,balance,reciept,Date_of_payment) values (?,?,?,?,?)', [$user_id,$balance,$user_amount_paid,$user_receipt,$Date_of_payment]);
        
        
        if($save)
        {
            return redirect('pending-buyers')->with('success','Agreement has been recorded successfully');
        }
    }

    // SALES AND ACCOUNTING

    public function weeklyRecords(){

        $data = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

                $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        $weeklyRecords = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
            
         return view('Admin.Sales.weekly',$data, compact(['weeklyRecords']));

    }

    public function different_weekly_sales_collection()
    {
        
        // $allRecords = buyer::all();

        // $recordsByWeek = $allRecords->groupBy(function ($record) {
        //     return Carbon::parse($record->created_at)->startOfWeek();
        // });

        // $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        // return view('Admin.Sales.weekly',$data, ['recordsByWeek' => $recordsByWeek]);
    }

    public function all_sales(){

        $all_sales = buyer::all();
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.customer_sales',$data,compact(['all_sales']));
    }

    public function recordsOnCurrentDate()
    { 

        $currentDate = Carbon::today();
        $records = buyer::whereDate('created_at', $currentDate)->get();
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Sales.today',$data, ['records' => $records]);
    }

        public function recordsInCurrentMonth()
        {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
           
             $records = buyer::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->get();

            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            
            return view('Admin.Sales.monthly',$data, ['records' => $records]);
            
        }

        // PAYMENT REMINDERS

        public function searchByPaymentDate(Request $request)
        {
             $currentDate = Carbon::now();

             $formattedDate = $currentDate->format('Y/m/d');
             $records = buyer::whereDate('next_installment_pay', $formattedDate)->get();
             $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

             return view('Admin.Sales.payment_reminders',$data, ['records' => $records]);
    }

}
