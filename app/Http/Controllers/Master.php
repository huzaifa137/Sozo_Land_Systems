<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRegister;
use App\Models\buyer;
use App\Models\Estate;
use App\Models\plot;
use App\Models\house;
use Illuminate\Support\Facades\Hash;
use Alert;

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
        $plots = plot::all();

        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];

        return view('Admin.buyer',$data,compact(['estates','plots']));
    }

    public function store_buyer_details(Request $request){

        $save = new buyer;

        $save->save();
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
}
