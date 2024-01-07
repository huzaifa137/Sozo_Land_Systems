<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AdminRegister;
use App\Models\buyer;
use App\Models\Estate;
use App\Models\plot;
use App\Models\house;
use App\Models\resale;
use App\Models\reciept;
use App\Models\agreement;
use App\Models\expenditure;
use App\Models\pdf_agreements;
use App\Models\expenditure_service;
use App\Models\pdf_receipt;
use Illuminate\Support\Facades\Hash;
use Alert;
use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\HTML;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Image;

class Master extends Controller
{
    public function login(){

        return view('Login.login');
    }

    public function reload_captcha()
    {

        return response()->json(['captcha'=>captcha_img('flat')]);
    }

    public function register($id){

        // return $id;

        $admin_category = AdminRegister::where('id',$id)->value('admin_category');

        if($admin_category == 'Admin')
        {
        return back()->with('error','Only Super Admins can Add more Admins');
        }
        else if($admin_category == null){

        return back()->with('error','Only Super Admins can Add more Admins');
        }

        return view('Login.register');
    }

    public function admin_register_data(Request $request)
    {
        
        $request->validate([
            'username'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'admin_category'=>'required',
            'password'=>'required',
            'confirm_password'=>'required',
        ]);

        $original_pass = $request->password;
        $confirm_pass = $request->confirm_password;


        if($original_pass === $confirm_pass){

        $register_admin =  new AdminRegister;

        $register_admin->username = $request->username;
        $register_admin->firstname = $request->firstname;
        $register_admin->lastname = $request->lastname;
        $register_admin->email = $request->email;
        $register_admin->password = Hash::make($request->password);
        $register_admin->admin_category = $request->admin_category;

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
            'captcha'=>'required|captcha',
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

        $plots = DB::table('plots')->where('status','!=',"Fully payed")->get();
        
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

        $file=$request->profile_pic;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('profile_pic'),$filename);
        $post->profile_pic=$filename; 
        
        $post->card_number= $request->card_number;
        $post->land_poster= $request->land_poster;
        $post->phonenumber= $request->phonenumber;
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

        $post->next_installment_pay= $request->next_installment_pay;
        
        $save = $post->save();

        return response()->json([
            "status"=>TRUE,
            "message"=>"Sale has been done successfull",
        ]);

    }


    public function edit_sales(Request $request, $id,$user_id)
    {

         $admin_category = AdminRegister::where('id',$user_id)->value('admin_category');

         if($admin_category == 'Admin')
         {
            return back()->with('error','Only Super Admins can edit records');
         }
         else if($admin_category == null){

            return back()->with('error','Only Super Admins can edit records');
         }
          
         $info =  buyer::find($id);

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Edit.buyer_edit',$data,compact(['info']));
    }

    public function edit_user_info(Request $request){


        $post = buyer::find($request->id);

        $post->firstname= $request->firstname;
        $post->lastname= $request->lastname;
        $post->gender= $request->gender;
        $post->date_of_birth= $request->date_of_birth;
        $post->NIN= $request->NIN;
        $post->phonenumber= $request->phonenumber;
        $post->card_number= $request->card_number;
        $post->purchase_type= $request->purchase_type;

        $save=$post->save();

        if($save)
        {
            return back()->with('success','Information has been updated successfully');
        }
        else
        {
            return back()->with('error','Error while trying to update data');
        }
    }

 
    // public function delete_sale($id)
    // {
    //     $data =  buyer::find($id);

    //     $data->delete();

    //     return back()->with('sucess','Data has been deleted successfully');

    // }

    public function view_specific_sale($id)
    {
        $userdata = buyer::find($id);
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.view_specific_buyer',$data,compact(['userdata']));
    }

    // ESTATES FUNCTIONS
    public function estates(){

        $estates = Estate::all();

        $count_estates = Estate::all()->count();
        $number_plots = Estate::all()->sum('number_of_plots');

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.estates',$data , compact(['estates','count_estates','number_plots']));
    }

    public function add_estate(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        return view('Admin.add_estate',$data);
    }

    public function store_estate(Request $request){

        $validate = $request->validate([
            'estate_name'=>'required',
            'location'=>'required',
            'estate_price'=>'required',
            'number_of_plots'=>'required',
            'estate_pdf'=>'required',
        ]);

       $post = new Estate;

       $post->estate_name = $request->estate_name;
       $post->location = $request->location;
       $post->estate_price = $request->estate_price;
       $post->number_of_plots = $request->number_of_plots;

       $file=$request->estate_pdf;

       $filename=date('YmdHi').'.'.$file->getClientOriginalExtension();
       $file->move('estate_pdf',$filename);
       $post->estate_pdf=$filename;


       $post->save();
        
    //    Alert::success('Estate added', 'Congurations on adding a new Estate');

       return back()->with('success','Congurations on adding a new Estate');
    }

    public function download_estate($id)
    {

        $estate_name = buyer::where('id',$id)->value('estate');

        $estate_record = estate::where('estate_name',$estate_name)->value('estate_pdf');

        return response()->download(public_path('estate_pdf/'.$estate_record));


        // return $estate_record;

    }

    public function view_estate($id)
    {

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        
        $specific_estate = Estate::find($id);

        $estate_name = $specific_estate->estate_name;

        $count_estates_fully = DB::table('buyers')
                    ->where('estate','=',$estate_name)
                    ->where('next_installment_pay','=',"Fully payed")->count();


        $count_estates_not_fully = DB::table('buyers')
                        ->where('estate','!=',$estate_name)
                        ->where('next_installment_pay','!=',"Fully payed")->count();

        return view('Admin.view_specific_estate',$data , compact(['specific_estate','count_estates_fully','count_estates_not_fully']));
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

        $post->phonenumber= $request->phonenumber;


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

        $file=$request->profile_pic;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('profile_pic'),$filename);
        $post->profile_pic=$filename; 

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
            $post->phonenumber= $request->phonenumber;

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
            
            $file=$request->profile_pic;
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('profile_pic'),$filename);
            $post->profile_pic=$filename; 

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

        $records = Estate::all();

        // $not_fully_paid = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")
        //                                      ->where('reciepts','!=',"0")->get();

        $not_fully_paid = DB::table('buyers')->where('next_installment_pay','!=',"Fully payed")
                                                                                ->get();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.pending',$data,compact(['not_fully_paid','records']));
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
        $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id','=',$id)->get();


        return view('Admin.Receipts.view_receipts',$data,compact(['user_information','user_reciepts','user_reciepts_pdf']));
    }

    public function add_agreement($id){

        $user_id = $id;
        
        $estate = DB::table('buyers')->where('id','=',$id)->value('estate');
        $plot_no = DB::table('buyers')->where('id','=',$id)->value('plot_number');

        $check_plot =  DB::table('plots')->where('estate','=',$estate)
                                         ->where('plot_number','=',$plot_no)->value('status');


        if($check_plot == 'Fully payed')
        {

            $estates = estate::all();

            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            return view('Admin.Receipts.full_paid_plot',$data,compact(['user_id','plot_no','estate','estates']));
        }
        else
        {
            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            return view('Admin.Receipts.add_agreement',$data,compact(['user_id']));

        }        
    }

    public function view_agreement($id){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        $user_information = DB::table('buyers')->where('id','=',$id)->get();
        $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
        $user_agreements = DB::table('pdf_agreements')->where('user_id','=',$id)->get();
        $user_reciepts_pdf = DB::table('reciepts')->where('user_id','=',$id)->get();
        $user_agreements_pdf = DB::table('agreements')->where('user_id','=',$id)->get();


        return view('Admin.Receipts.view_agreement',$data,compact(['user_information','user_reciepts','user_agreements','user_reciepts_pdf','user_agreements_pdf']));
    }


    public function store_new_receipt(Request $request)
    {

        $request->validate([
            'amount_paid'=>'required',
            'Date_of_payment'=>'required',
            'balance_pending'=>'required',
            'phone_number'=>'required',
            'amount_in_words'=>'required',
        ]);

        $user_email = $request->user_email;
        $user_name = $request->user_name;
        $user_id = $request->user_id;

        $user_email = $request->user_email;
        $user_name = $request->user_name;
        $Phonenumber = $request->phone_number;
        $amount_in_words = $request->amount_in_words;
        $Amount = $request->amount_paid;


        $user_info =buyer::where('id',$user_id)->first();
        $receipt_no = rand(10000,50000);

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');
        

        $user_id = $request->user_id;
        $amount_paid = $request->amount_paid;
        $Balance = $request->balance_pending;

        $post = new reciept();

        $pdf = PDF::loadView('invoice_pdf',compact(['user_email','user_name','formattedDate','receipt_no','Amount','Balance','Phonenumber','amount_in_words','user_info']));
        $filename = 'payment_reciepet' . time() . '.pdf';
        $pdf->save(storage_path("app/public/pdf_receipts/{$filename}"));

        $post->reciept=$filename; 
        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;
        $post->Phonenumber = $Phonenumber;
        $post->amount_in_words = $request->amount_in_words;
        $post->save();


        $original_amount=buyer::where('id',$user_id)->value('amount_payed');

        $all_cash = $original_amount+$amount_paid;
        
        $update_buyer_amount=buyer::where('id',$user_id)
                                    ->update([ 'amount_payed'=> $all_cash,
                                                'balance'=>$Balance
                                                        ]);

        //    return $pdf->stream($filename);               
        return $pdf->download($filename);                                                
                                 
    }

    public function add_first_reciept($id){

        $user_id = $id;
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.Receipts.add_first_receipt',$data,compact(['user_id']));
    }

    public function store_first_receipt(Request $request)
    {

        $request->validate([

            'amount_paid'=>'required',
            'Date_of_payment'=>'required',
            'balance_pending'=>'required',
            'phone_number'=>'required',
            'amount_in_words'=>'required',
        ]);

        $user_email = $request->user_email;
        $user_name = $request->user_name;


        $user_id = $request->user_id;
        $Amount = $request->amount_paid;
        $Balance = $request->balance_pending;
        $Phonenumber = $request->phone_number;
        $amount_in_words = $request->amount_in_words;

        $user_info =buyer::where('id',$user_id)->first();
        $receipt_no = rand(10000,50000);

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y/m/d');
        
        $pdf = PDF::loadView('invoice_pdf',compact(['user_email','user_name','formattedDate','receipt_no','Amount','Balance','Phonenumber','amount_in_words','user_info']));
        $filename = 'payment_reciepet' . time() . '.pdf';

        $pdf->save(storage_path("app/public/pdf_receipts/{$filename}"));

        $post = new reciept();

        $post->user_id = $request->user_id;
        $post->Amount = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $post->Balance = $request->balance_pending;
        $post->Phonenumber = $Phonenumber;
        $post->amount_in_words = $request->amount_in_words;
        $post->reciept=$filename; 

        $post->save();

        // $post = new pdf_receipt();

        // $post->user_id=$user_id;
        // $post->receipt=$filename;       

        // $post->save();    

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
                        
         $plot_status = DB::table('plots')->where($whereConditions)->value('status');
        
        if($plot_status == "Not taken")
        {
            $plot_status = DB::table('plots')->where($whereConditions)->update(['status' => 'Underpayment',]);
        }
            
        // return $pdf->stream($filename);

        return $pdf->download($filename);

        if($update_buyer_amount)
        {
            return redirect('pending-buyers')->with('success','Reciept has been recorded successfully');
        }
    }



    public function store_agreement(Request $request)
    {


        $amount_in_words = $request->amount_in_words;
        
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

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$user_amount_paid;

         // Document formulation

        $user_info =buyer::where('id',$user_id)->first();

        $profile_pic =buyer::where('id',$user_id)->value('profile_pic');

        $profile_pic  = public_path('profile_pic/'.$profile_pic);

        // dd($user_info);


        $day = $user_info->created_at->day;
        $month = $user_info->created_at->month;
        $year = $user_info->created_at->year;


        $pdf = PDF::loadView('agreement_pdf',compact(['user_amount_paid','user_info',
        'all_cash','Date_of_payment','day','month','year','amount_in_words','profile_pic']));



        $filename = 'payment_agreement' . time() . '.pdf';

        $pdf->save(storage_path("app/public/agreements/{$filename}"));


        $post = new agreement();

        $post->user_id = $request->user_id;
        $post->Amount_paid = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $user_receipt ='-'; 
        $post->reciept= '-'; 
        $post->agreement=$filename; 

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$user_amount_paid;
        
        $save = $post->save();

         // Update buyer records

        $update_buyer_agreement = buyer::where('id',$user_id)->update(['next_installment_pay'=>"Fully payed",
                                                            'reciepts'=> '-',
                                                            'agreement'=> '-',
                                                            'amount_payed'=> $all_cash,
                                                            'balance'=>$balance]);


         $estate_no_no=buyer::where('id',$user_id)->value('estate');
         $plot_no_no = buyer::where('id',$user_id)->value('plot_number');
 
         $whereConditions = ['estate' => $estate_no_no,
                             'plot_number' => $plot_no_no];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed',]);

        DB::insert('insert into reciepts (user_id,amount,balance,reciept,Date_of_payment,Phonenumber,amount_in_words) values (?,?,?,?,?,?,?)', [$user_id,$balance,$user_amount_paid,$user_receipt,$Date_of_payment,'-','-']);

        
        // return $pdf->stream($filename);

        return $pdf->download($filename);

        
        if($save)
        {
            return redirect('pending-buyers')->with('success','Agreement has been recorded and plot been sold successfully');
        }
    }   

    public function store_agreement_new_plot(Request $request){

        $amount_in_words = $request->amount_in_words;
        
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

        $estate_update = $request->Estate_plot;
        $width_update1 = $request->plot_width1;
        $width_update2 = $request->plot_width2;
        $height_update1 = $request->plot_height1;
        $height_update2 = $request->plot_height2;
        $location = $request->location_plot;
        $plot_numb = $request->plot_number;


        $info_update = buyer::where('id',$user_id)->update(['estate'=>$estate_update,
                                                            'width_1'=> $width_update1,
                                                            'width_2'=> $width_update2,
                                                            'height_1'=> $height_update1,
                                                            'height_2'=> $height_update2,
                                                            'location'=> $location,
                                                            'plot_number'=> $plot_numb,
                                                                    ]);


         // Document formulation

        $user_info =buyer::where('id',$user_id)->first();
        $profile_pic =buyer::where('id',$user_id)->value('profile_pic');
        $profile_pic  = public_path('profile_pic/'.$profile_pic);   
                                                                    
        $day = $user_info->created_at->day;
        $month = $user_info->created_at->month;
        $year = $user_info->created_at->year;


        $pdf = PDF::loadView('agreement_pdf',compact(['user_amount_paid','user_info',
        'all_cash','Date_of_payment','day','month','year','amount_in_words','profile_pic']));


        // $pdf = PDF::loadView('agreement_pdf',compact(['user_amount_paid','user_info','all_cash','Date_of_payment']));
        $filename = 'payment_agreement' . time() . '.pdf';

        $pdf->save(storage_path("app/public/agreements/{$filename}"));

        $post = new agreement();

        $post->user_id = $request->user_id;
        $post->Amount_paid = $request->amount_paid;
        $post->Date_of_payment = $request->Date_of_payment;
        $user_receipt ='-'; 
        $post->reciept= '-'; 
        $post->agreement=$filename; 

        $original_amount=buyer::where('id',$user_id)->value('amount_payed');
        $all_cash = $original_amount+$user_amount_paid;
        
        $save = $post->save();

         // Update buyer records

        $update_buyer_agreement = buyer::where('id',$user_id)->update(['next_installment_pay'=>"Fully payed",
                                                            'reciepts'=> '-',
                                                            'agreement'=> '-',
                                                            'amount_payed'=> $all_cash,
                                                            'balance'=>$balance]);


         $estate_no_no=buyer::where('id',$user_id)->value('estate');
         $plot_no_no = buyer::where('id',$user_id)->value('plot_number');
 
         $whereConditions = ['estate' => $estate_no_no,
                             'plot_number' => $plot_no_no];

        DB::table('plots')->where($whereConditions)->update(['status' => 'Fully payed',]);

        DB::insert('insert into reciepts (user_id,amount,balance,reciept,Date_of_payment,Phonenumber,amount_in_words) values (?,?,?,?,?,?,?)', [$user_id,$balance,$user_amount_paid,$user_receipt,$Date_of_payment,'-','-']);

        $profile_pic=buyer::where('id',$user_id)->value('profile_pic');

        // return $pdf->stream($filename);

        return $pdf->download($filename);

        
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
             $records_count = buyer::whereDate('next_installment_pay', $formattedDate)->count();

             $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

             return view('Admin.Sales.payment_reminders',$data, compact(['records_count','records']));
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

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $NIN = $request->NIN;
        $date_of_birth = $request->date_of_birth;

        $estate = $request->estate;
        $land_plot = $request->land_plot;
        
        if($firstname != null)
        {
            
            $result = DB::table('buyers')
                        ->where('firstname', $firstname,)
                        ->where('lastname', $lastname,)
                        ->first();

                        if(!$result)
                            {
                                return back()->with('error','No record has been found with provided information');
                            }
                            else
                            {
            
                                $id = $result->id;
                                $user_information = DB::table('buyers')->where('id','=',$id)->get();
                                $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                                $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            
                                 return view('Admin.Resale.show_info',$data , compact(['user_information','user_reciepts','id']));
            
                            }
        }
        else if($NIN != null)
        {

            $result = DB::table('buyers')
                        ->where('NIN', $NIN,)
                        ->first();

                        if(!$result)
                            {
                                return back()->with('error','No record has been found with provided information');
                            }
                            else
                            {
            
                                $id = $result->id;
                                $user_information = DB::table('buyers')->where('id','=',$id)->get();
                                $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                                $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
            
                                 return view('Admin.Resale.show_info',$data , compact(['user_information','user_reciepts','id']));
            
                            }
        }
        else if($estate != null)
        {

            $result = DB::table('buyers')
                                  ->where('estate', $estate,)
                                  ->where('plot_number', $land_plot,)
                                  ->first();


             if(!$result)
             {
                 return back()->with('error','No Plot has been found with provided information');
             }
             else
             {

                 $id = $result->id;
                 $user_information = DB::table('buyers')->where('id','=',$id)->get();
                 $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                 $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

                  return view('Admin.Resale.show_info',$data , compact(['user_information','user_reciepts','id']));

             }
        }
        else if($date_of_birth != null)
        {

            $result = DB::table('buyers')
            ->where('date_of_birth', $date_of_birth,)
            ->first();

                if(!$result)
                {
                return back()->with('error','No Plot has been found with provided information');
                }

                else
                {

                    $id = $result->id;
                    $user_information = DB::table('buyers')->where('id','=',$id)->get();
                    $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                    $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

                     return view('Admin.Resale.show_info',$data , compact(['user_information','user_reciepts','id']));

                }
        }
    }


    public function search_plot_land_db(Request $request)
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

                 $user_information = DB::table('buyers')->where('id','=',$id)->get();
                 $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                 $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

                 return view('Admin.Search.underpayment',$data , compact(['user_information','user_reciepts','id']));
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
                 
                // dd("welcome Home");


                 $id = $result->id;
                 $user_information = DB::table('buyers')->where('id','=',$id)->get();
                 $user_reciepts = DB::table('pdf_receipts')->where('user_id','=',$id)->get();
                 $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

                 return view('Admin.Search.underpayment',$data , compact(['user_information','user_reciepts','id']));

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

            $whereConditions = ['estate' => $info];                             

             $data = DB::table('plots')->where($whereConditions)
                                        ->where('status','!=','Fully payed')
                                        ->get();

            return response()->json($data);
        }

        public function get_input_option(Request $request)
        {
            $info = $request->input('selectedValue');

            $whereConditions = ['plot_number' => $info];
                               
                                
             $width_1 = DB::table('plots')->where($whereConditions)->where('status' ,'!=', "Fully payed")->value('width_1');
             $width_2 = DB::table('plots')->where($whereConditions)->where('status' ,'!=', "Fully payed")->value('width_2');
             $height_1 = DB::table('plots')->where($whereConditions)->where('status' ,'!=', "Fully payed")->value('height_1');
             $height_2 = DB::table('plots')->where($whereConditions)->where('status' ,'!=', "Fully payed")->value('height_2');
             $location = DB::table('plots')->where($whereConditions)->where('status' ,'!=', "Fully payed")->value('location');

             

            return response()->json([
                "status"=>TRUE,
                "width_1"=>$width_1,
                "width_2"=>$width_2,
                "height_1"=>$height_1,
                "height_2"=>$height_2,
                "location"=>$location,
            ]);
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

        public function attach_agreement_view($id){

            $user_id = $id;
            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

            return view('Admin.Receipts.attach_agreement_page',$data , compact(['user_id']));
        }

        public function store_agreement_new(Request $request)
        {

            $request->validate([
                'agreement'=>'required',
            ]);


        $post  = new pdf_agreements;

        $post->user_id = $request->user_id;

        $file=$request->agreement;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/agreements'),$filename);
        $post->agreement=$filename; 

        $post->save();

        return redirect('accomplished')->with('success','Agreement has been uploaded successfully');
        }


        public function attach_receipt_view($id){

            $user_id = $id;
            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

            return view('Admin.Receipts.attach_receipt_page',$data , compact(['user_id']));
        }

        public function store_attach_receipt_new(Request $request)
        {

            $request->validate([
                'receipt' => 'required',
            ]);
            
        $post  = new pdf_receipt;

        $post->user_id = $request->user_id;

        $file=$request->receipt;
        $filename=date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/receipts'),$filename);
        $post->receipt=$filename; 

        $post->save();

        return redirect('pending-buyers')->with('success','Receipt has been uploaded successfully');
        }



        public function download_agreement_receipt(Request $request,$Book_pdf)
        {

            $filePath = 'pdf_receipts/'.$Book_pdf;

            if (Storage::exists($filePath)) {
                return response()->download(storage_path($filePath));
            } else {
                abort(404, 'File not found');
            }
        }

        public function download_receipt_payment($filename)
        {


            // $filename = str_replace('.pdf', '', $filename);

            // dd($filename);
            // $pdf = reciept::where('reciept', $filename)->value('reciept');
            // return response()->download(public_path('pdf_receipts/'.$filename));

            // $filePath = storage_path('app/pdf_receipts/'. $filename);

            // $filePath = public_path('pdf_receipts/' . $filename);

            // dd($filePath);

            return response()->download(storage_path('public/pdf_receipts/'.$filePath));


            // return $pdf->download($filename);   


            return response()->download($filePath, Str::slug($filename))
                        ->withHeaders([
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'inline; filename="' . Str::slug($filename) . '"',
                        ]);

            // dd($pdf);

            // $pdf =  storage_path('public/pdf_receipts/'.$pdf);

            // return $pdf->download($filename);   
            
            // return $pdf->stream('resume.pdf');


            return response()->download(public_path('pdf_receipts/'.$pdf));


        }

        public function add_expenditure()
        {

            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

            return view('Admin.Expenditure.add_expenditure', $data);
        }

        public function store_expenditure(Request $request)
        {
            $added_amount = 0;
            $total_amount = $request->total_amount;
            $expenditure_name = $request->expenditure_name;

            $dynamicInputsAmounts = $request->input('dynamic_inputs2', []);

            foreach ($dynamicInputsAmounts as $value) {
                $added_amount+=$value;
            }

            if($added_amount > $total_amount)
            {
                return back()->with('error','Added amount is greater than total amount submitted');
            }
            else if($added_amount < $total_amount)
            {
                return back()->with('error','Added amount is less than total amount submitted');
            }
            else if($added_amount != $total_amount){

                return back()->with('error','Invalid amount and total expenses provided');

            }
            $random_numb = rand(10000,50000);
            
                $post = new expenditure;

                $post->random_numb = $random_numb;
                $post->service = $expenditure_name;
                $post->amount = $total_amount;

                $post->save();

            $dynamicInputs = $request->input('dynamic_inputs', []);

            foreach ($dynamicInputs as $value) {
                
                $post = new expenditure_service;

                $post->random_number = $random_numb;
                $post->all_services = $value;

                $post->save();
            }

            return back()->with('success','Expenditure has been added successfully');
        }

        public function today_expense()
        {
            
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        $currentDate = Carbon::today();
        $totalExpenditureAll = expenditure::whereDate('created_at', $currentDate)->get();
        $totalExpenditureServices = expenditure_service::whereDate('created_at', $currentDate)->get();

        $totalExpenditureServices = $totalExpenditureServices->groupBy('random_number');
        
        $totalAmount = expenditure::whereDate('created_at', $currentDate)->sum('amount');
        
        $totalExpenditure = expenditure::whereDate('created_at', $currentDate)->count();

        return view('Admin.Expenditure.today_expenditure',$data , compact(['totalExpenditureAll','totalAmount','totalExpenditure','totalExpenditureServices']));
        }

        // SEARCH MODULE 


        public function search_module()
        {

            $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

            $records = Estate::all();

            return view('Admin.Search.search_module',$data, compact(['records']));
        }

        public function index() 
        {

        $data = [
            'imagePath'    => public_path('img/logo.jpg'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];


        $pdf = PDF::loadView('resume', $data);
        return $pdf->stream('resume.pdf');
    }

    public function download_national_id(Request $request,$id){

        $user_id =  $id;
        
        $user_info =buyer::where('id',$user_id)->first();
        $national_id_front =buyer::where('id',$user_id)->value('national_id_front');
        $national_id_back =buyer::where('id',$user_id)->value('national_id_back');
        
        $national_id_front  = public_path('public/national_id/'.$national_id_front);
        $national_id_back  = public_path('public/national_id/'.$national_id_back);


            $national_id_front    = $national_id_front;
            $national_id_back     = $national_id_back;
        

        $pdf = PDF::loadView('national_id',compact(['national_id_front','national_id_back']));

        return $pdf->download('national_id.pdf');
        
    }
}