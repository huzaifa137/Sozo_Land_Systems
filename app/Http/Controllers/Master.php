<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRegister;
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

    public function dashboard()
    {
        $data=['LoggedAdminInfo'=>AdminRegister::where('id','=',session('LoggedAdmin'))->first()];
        
        return view('Admin.dashboard',$data);
    }


}
