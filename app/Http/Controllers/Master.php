<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRegister;
use App\Models\buyer;
use Illuminate\Support\Facades\Hash;

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

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.buyer',$data);
    }

    public function store_buyer_details(Request $request){

        $save = new buyer;

        $save->firstname = $request->firstname;
        $save->lastname = $request->lastname;
        $save->gender = $request->gender;
        $save->date_of_birth = $request->date_of_birth;
        $save->NIN = $request->NIN;
        $save->card_number = $request->card_number;
        $save->national_id = $request->national_id;
        $save->signature = $request->signature;
        $save->Estate = $request->Estate;
        $save->plot_number = $request->plot_number;
        $save->land_poster = $request->land_poster;
        $save->payment_method = $request->payment_method;

        $save->save();

        if($save){

            return  redirect()->back()->with('success','New plot has been sold successfully');
        }
        else{
            redirect()->back()->with('fail','Data has not been stored in the database');
        }
    }

    public function customer_sale(){

        $All_data = buyer::all();
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.customer_sales',$data,compact(['All_data']));
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

    public function estates(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
         return view('Admin.estates',$data);
    }

    public function add_estate(){

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        return view('Admin.add_estate',$data);
    }

    public function plots(){

        return view('Admin.plots');
    }
}
