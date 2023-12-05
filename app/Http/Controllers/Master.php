<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRegister;
use App\Models\buyer;
use App\Models\Estate;
use App\Models\plot;
use App\Models\house;
use App\Models\resale;
use App\Models\reciept;
use App\Models\agreement;
use App\Models\pdf_receipt;
use Illuminate\Support\Facades\Hash;
use Alert;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\HTML;
use Illuminate\Support\Facades\Storage;


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

        $all_sales = buyer::all();

        $currentDate = Carbon::today();
        $totalAmount = buyer::whereDate('created_at', $currentDate)->sum('amount_payed');

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $totalweekSales = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount_payed');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totalmonthSales = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
                    ->sum('amount_payed');

        $plots_fully_paid = DB::table('buyers')->where('next_installment_pay','=',"Fully payed")->count();
        $under_payment = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")->where('next_installment_pay','!=',"Resold")->count();


        // $amount_in_debts = buyer::whereDate('created_at', $currentDate)->sum('balance');
       
        $amount_in_debts = buyer::sum('balance');

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        
        return view('Admin.dashboard',$data ,compact(['all_sales','totalAmount','totalweekSales','totalmonthSales'
                                                    ,'plots_fully_paid','under_payment','amount_in_debts']));
    }

    public function admin_buyer(){

        $estates = Estate::all();

        $plots = DB::table('plots')->where('status','=',"Not taken")->get();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.buyer',$data,compact(['estates','plots']));
    }

    public function store_buyer_details(Request $request){

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');

        $post = new buyer;

        $post->firstname= $request->firstname;
        $post->lastname= $request->lastname;
        $post->gender= $request->gender;
        $post->date_of_birth= $request->date_of_birth;
        $post->NIN= $request->NIN;

        $file=$request->national_id_front;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/national_id'),$filename);
        $post->national_id_front=$filename; 
        
        $file=$request->national_id_back;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/national_id'),$filename);
        $post->national_id_back=$filename; 
        
        $post->card_number= $request->card_number;
        $post->land_poster= $request->land_poster;
        $post->method_payment= $request->payment_method;
        $post->purchase_type= $request->purchase_type;
        $post->estate= $request->estate;
        $post->location= $request->location;

        $post->width_1= $request->width_1;
        $post->width_2= $request->width_2;
        $post->height_1= $request->height_1;
        $post->height_2= $request->height_2;


        $post->plot_number= $request->plot_number;
        $post->amount_payed= $request->amount_payed;
        $post->balance= $request->balance;
        $post->reciepts= $request->receipt_img;
        $post->agreement = $request->agreement;
        $post->date_sold = $formattedDate;

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

        return response()->json([
            "status"=>TRUE,
            "message"=>"Sale has been done successfull",
        ]);

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
       $post->estate_price = $request->estate_price;
       $post->number_of_plots = $request->number_of_plots;
       $post->save();
        
    //    Alert::success('Estate added', 'Congurations on adding a new Estate');

       return back()->with('success','Congurations on adding a new Estate');
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

        $status = $request->land_status;
        $estate_name = $request->Estate;

        $no_of_plots =estate::where('estate_name',$estate_name)->value('number_of_plots');
        $count_estates = buyer::where('estate',$estate_name)->count();

        $plot_numb = $request->House_plot;

        if($plot_numb == "Plot"){
            if($count_estates >= $no_of_plots){
                return response()->json([
                    "status"=>FALSE,
                    "message"=>"The plots in this estate are fully taken",
                  ]);
            }
        }

        if($plot_numb == "Plot"){
                if($status == "Not_taken"){
                    $count_estates = plot::where('estate',$estate_name)->count();
                        if($count_estates >= $no_of_plots){

                        return response()->json([
                        "status"=>FALSE,
                        "message"=>"The plots in this estate are fully taken",
                    ]);

                }
            }
        }
        
        if($status == "Fully_taken"){

            $estate_price =estate::where('estate_name',$estate_name)->value('estate_price');
            $paid_amount = $request->amount_paid;
            if($paid_amount < $estate_price)
            {
                return response()->json([
                    "status"=>FALSE,
                    "message"=>"Amount used to purchase this plot is less",
                ]);
            }
        }

        if($status == "Fully_taken"){

        $post = new buyer;

        $post->firstname= $request->firstname;
        $post->lastname= $request->lastname;
        $post->gender= "-";
        $post->date_of_birth= "-";
        $post->NIN= "-";

        $file=$request->agreement_added;
        $filename1=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/agreements'),$filename1);
        $post->agreement=$filename1;

        $file=$request->national_id_front;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/national_id'),$filename);
        $post->national_id_front=$filename; 
        
        $file=$request->national_id_back;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/national_id'),$filename);
        $post->national_id_back=$filename; 


        $post->card_number= "-";
        $post->land_poster= "Paid";
        $post->method_payment= "paying_in_installments";
        $post->purchase_type= $request->House_plot;
        $post->estate= $request->Estate;
        $post->location= $request->location;
        $post->width_1= $request->width1;
        $post->width_2= $request->width2;
        $post->height_1= $request->height1;
        $post->height_2= $request->height2;
        $post->plot_number= $request->plot_number;
        $post->balance= $request->balance;
        $post->date_sold = $request->date_sold;
        $post->next_installment_pay = "Fully payed";
        $post->amount_payed = $request->amount_paid;

        $post->save();
        
        $plot_number = $request->plot_number;
        $user_id =buyer::where('plot_number',$plot_number)->value('id');
        
        $user_id = $user_id;
        $reciepts = "-";
        $agreement_reciept = $filename1; 
        $user_amount_paid = $request->amount_paid;
        $Date_of_payment = $request->date_sold;
         
        $save = new agreement();

        $save->user_id = $user_id;
        $save->Amount_paid = $user_amount_paid;
        $save->Date_of_payment = $Date_of_payment;
        $save->reciept=$reciepts;
        $save->agreement=$filename1;
        $save->save();

       return response()->json([
        "status"=>TRUE,
        "message"=>"Congurations on adding a new Plot",
      ]);

        }
        else if($status == "Under_payment"){

         
            $post = new buyer;

            $post->firstname= $request->firstname;
            $post->lastname= $request->lastname;
            $post->gender= "-";
            $post->date_of_birth= "-";
            $post->NIN= "-";
            $post->agreement=$request->agreement_added;; 

            $file=$request->national_id_front;
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/national_id'),$filename);
            $post->national_id_front=$filename; 
            
            $file=$request->national_id_back;
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/national_id'),$filename);
            $post->national_id_back=$filename; 
            
            $post->card_number= "-";
            $post->land_poster= "Paid";
            $post->method_payment= "paying_in_installments";
            $post->purchase_type= $request->House_plot;
            $post->estate= $request->Estate;
            $post->location= $request->location;
            $post->width_1= $request->width1;
            $post->width_2= $request->width2;
            $post->height_1= $request->height1;
            $post->height_2= $request->height2;
            $post->plot_number= $request->plot_number;
            $post->balance= $request->balance;
            $post->date_sold = $request->date_sold;
            $post->next_installment_pay = $request->next_installment_date;
            $post->amount_payed = $request->amount_paid;
            $post->reciepts = '0';
    
            $post->save();

            return response()->json([
                "status"=>TRUE,
                "message"=>"Congurations on adding a new Plot",
              ]);
        }
        else
        {

            $post = new plot;

            $post->estate = $request->Estate;
            $post->plot_number = $request->plot_number;
            $post->location = $request->location;
            $post->width_1= $request->width1;
            $post->width_2= $request->width2;
            $post->height_1= $request->height1;
            $post->height_2= $request->height2;
            $post->status = "Not taken";
            
            $post->save();

            return response()->json([
                "status"=>TRUE,
                "message"=>"Congurations on adding a new Plot",
              ]);
        }
    }

    public function send_house_data(Request $request){

        $post = new house;

        $post->estate = $request->Estate;
        $post->plot_number = $request->plot_number;
        $post->location = $request->location;
        $post->width_1= $request->width1;
        $post->width_2= $request->width2;
        $post->height_1= $request->height1;
        $post->height_2= $request->height2;
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
        $Phonenumber = $request->phone_number;
        $amount_in_words = $request->amount_in_words;

        $user_info =buyer::where('id',$user_id)->get();
        $receipt_no = rand(10000,50000);
        $post = new reciept();

        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;
        $post->Phonenumber = $Phonenumber;
        $post->amount_in_words = $request->amount_in_words;
        $post->reciept='-'; 

        $post->save();


        $pdf = PDF::loadView('invoice_pdf',compact(['Amount','Balance','Phonenumber','amount_in_words']));
        $filename = 'payment_reciepet' . time() . '.pdf';

        $pdf->save(storage_path("app/public/pdf_receipts/{$filename}"));

        $post = new pdf_receipt();

        $post->user_id=$user_id;
        $post->receipt=$filename;       

        $post->save();
        
        return redirect()->back();
    

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$Amount;

        $update_buyer_amount=buyer::where('id',$user_id)
                                    ->update(['reciepts'=>"Pending",
                                              'amount_payed'=> $all_cash,
                                              'balance'=>$Balance]);

        // Update First reciept records

        $estate_no_no=buyer::where('id',$user_id)->value('estate');
        $plot_no_no = buyer::where('id',$user_id)->value('plot_number');

        $whereConditions = ['estate' => $estate_no_no,
                            'plot_number' => $plot_no_no];
                        
        DB::table('plots')->where($whereConditions)->update(['status' => 'Underpayment',]);
            

        if($update_buyer_amount)
        {
            return redirect('pending-buyers')->with('success','Reciept has been recorded successfully');
        }
    }



    public function store_agreement(Request $request)
    {

        $user_id = $request->user_id;
        $original_amount =buyer::where('id',$user_id)->value('amount_payed');

        $estate_name =buyer::where('id',$user_id)->value('estate');
        $estate_price =estate::where('estate_name',$estate_name)->value('estate_price');

        $user_amount_paid = $request->amount_paid;
        $all_cash = $original_amount+$user_amount_paid;


            if($all_cash < $estate_price)
            {
                return back()->with('error','This amount paid is not enough to take this plot in this estate');
            }
           
       
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

         // Update First reciept records

         $estate_no_no=buyer::where('id',$user_id)->value('estate');
         $plot_no_no = buyer::where('id',$user_id)->value('plot_number');
 
         $whereConditions = ['estate' => $estate_no_no,
                             'plot_number' => $plot_no_no];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed',]);

        DB::insert('insert into reciepts (user_id,amount,balance,reciept,Date_of_payment) values (?,?,?,?,?)', [$user_id,$balance,$user_amount_paid,$user_receipt,$Date_of_payment]);
        
        
        if($save)
        {
            return redirect('pending-buyers')->with('success','Agreement has been recorded and plot been sold successfully');
        }
    }

    // SALES AND ACCOUNTING

    public function weeklyRecords(){

        $data = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        $weeklyRecords = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        $totalAmount = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount_payed');
        $plots_sold = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = buyer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('next_installment_pay','!=', "Fully payed")->where('next_installment_pay','!=',"Resold")->count();

        return view('Admin.Sales.weekly',$data, compact(['weeklyRecords','totalAmount','plots_sold','under_payment_plots']));

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

        $totalAmount = buyer::sum('amount_payed');
        $plots_sold =  DB::table('buyers')->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")->where('next_installment_pay','!=',"Resold")->count();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.customer_sales',$data,compact(['all_sales','totalAmount','plots_sold','under_payment_plots']));
    }

    public function recordsOnCurrentDate()
    { 

        $currentDate = Carbon::today();
        $records = buyer::whereDate('created_at', $currentDate)->get();
        $totalAmount = buyer::whereDate('created_at', $currentDate)->sum('amount_payed');
        $plots_sold = buyer::whereDate('created_at', $currentDate)->where('next_installment_pay', "Fully payed")->count();
        $under_payment_plots = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")->where('next_installment_pay','!=',"Resold")->count();
                                        
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Sales.today',$data, compact(['records','totalAmount','plots_sold','under_payment_plots']));
    }

        public function recordsInCurrentMonth()
        {


            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
           
             $records = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
                                                                    ->get();

            $totalAmount = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
                        ->sum('amount_payed');

            $plots_sold = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
                        ->where('next_installment_pay', "Fully payed")->count();
          
            $under_payment_plots = buyer::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)
                        ->where('next_installment_pay','!=', "Fully payed")->where('next_installment_pay','!=',"Resold")->count();
                

            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            
            return view('Admin.Sales.monthly',$data, compact(['records','totalAmount','plots_sold','under_payment_plots']));
            
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

    public function update_payment_reminder($id)
    {

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        $records = buyer::find($id);

         return view('Admin.Sales.update_payment_reminder',$data,compact(['records']));
    }

    public function store_update_payment_reminder(Request $request){
        
        $status = $request->status;
        $reminder_date = $request->reminder_date;
        $user_id = $request->user_id;
        
        if($status == 'Fully payed')
        {
            $update_buyer = buyer::where('id',$user_id)
                                    ->update([ 'next_installment_pay'=> $status,
                                                        ]);
        }
        else
        {
            $update_buyer = buyer::where('id',$user_id)
                                    ->update([ 'next_installment_pay'=> $reminder_date,
                                                        ]);
        }
        
        return redirect('payment-reminder')->with('success','Payment reminder has been updated successfully');
    }

    // Resale Module

    public function search_plot()
    {

        $records = Estate::all();
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Resale.search_plot',$data , compact(['records']));
    }

    public function search_land_db(Request $request)
    {

        $status = $request->land_plot;
        $plot_no = $request->plot_no;
        

        if($status == 'House')
        {
            $land_estate = $request->land_estate;
            $result = DB::table('buyers')
                                  ->where('purchase_type', $status,)
                                  ->where('estate', $land_estate,)
                                  ->where('plot_number',$plot_no)
                                  ->first();



            if(!$result)
            {
                return back()->with('error','No House has been found with provided information');
            }
            else
            {
                $user_id = $result->id;
                return back()->with('success','Data has been found');
            }
        }
        else
        {

            $land_estate = $request->estate;
            $result = DB::table('buyers')
                                  ->where('purchase_type', $status,)
                                  ->where('estate', $land_estate,)
                                  ->where('plot_number',$plot_no)
                                  ->first();

             if(!$result)
             {
                 return back()->with('error','No Plot has been found with provided information');
             }
             else
             {
                 
                 $id = $result->id;
                 $user_information = DB::table('buyers')->where('id','=',$id)->get();
                 $user_reciepts = DB::table('reciepts')->where('user_id','=',$id)->get();
                 $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

                 return view('Admin.Resale.resale',$data , compact(['user_information','user_reciepts','id']));

             }
        }
          
    }

        public function resale()
        {
            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
                        
            return view('Admin.Resale.resale',$data);
        }

        public function resale_amount($id)
        {

            $user_id = $id;
            $asset_info = DB::table('buyers')->where('id','=',$id)->first();
            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            return view('Admin.Resale.resale_amount',$data, compact(['asset_info','user_id']));
        }

        public function store_resale_amount(Request $request){

            $user_id = $request->user_id;
            $purchase_type = $request->purchase_type;
            $estate = $request->estate;
            $plot_no = $request->plot_no;
            $amount_resold = $request->amount_resold;
            $reciept = $request->reciept;

            $post = new resale;

            $post->user_id = $user_id;
            $post->purchase_type = $purchase_type;
            $post->estate = $estate;
            $post->plot_number = $plot_no;
            $post->amount_resold = $amount_resold;

            $file=$reciept;
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/receipts'),$filename);
            $post->reciept_resold=$filename;
            
            $save = $post->save();

            $original_amount=buyer::where('id',$user_id)->value('amount_payed');
            $all_cash = $original_amount+$amount_resold;

            $update_buyer_amount=buyer::where('id',$user_id)
                                    ->update([ 'amount_payed'=> $all_cash,
                                                'next_installment_pay'=>"Resold",
                                                        ]);

                  $whereConditions = [
                          'estate' => $estate,
                          'plot_number' => $plot_no,
                                     ];

                  DB::table('plots')
                        ->where($whereConditions)
                        ->update(['status' => 'Not taken',]);
                            
                        

            if($save)
            {
                return redirect('search-land')->with('success','Reselling has been accomplished successfully');
            }
            else
            {
                return redirect('search-land')->with('error','Reselling has been accomplished successfully');

            }
        }

        // Load data dynamically.

        public function get_secound_option(Request $request)
        {
            $info = $request->input('value');

            $whereConditions = ['estate' => $info,
                                'status' => "Not taken"];
             $data = DB::table('plots')->where($whereConditions)->get();

            return response()->json($data);
        }

        public function get_input_option(Request $request)
        {
            $info = $request->input('value');

           
            $whereConditions = ['plot_number' => $info,
                                'status' => "Not taken"];
                                
             $data = DB::table('plots')->where($whereConditions)->get();
             return response()->json($data);
        }

        // Generate Invoice and Agreement

        public function generate_invoice()
        {

            $pdf = PDF::loadView('invoice_pdf');
            return $pdf->download('techsolutionstuff.pdf');

        }

        public function show_invoice()
        {
            return view('invoice_pdf');
        }
}