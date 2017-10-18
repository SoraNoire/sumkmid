<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Helpers\SSOHelper;
use Redirect;
use Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    function __construct()
    {

    }

    public function index()
    {
        if ( ! SSOHelper::Auth())
        {
            return Redirect( route('auth.login') );
        }
        return view('auth::auth.index');
    }

    public function login()
    {
        // dd( SSOHelper::Auth() );
        if ( SSOHelper::Auth())
        {
            return Redirect( route('auth') );
        }
        return view('auth::auth.login');
    }
    public function register()
    {
        if ( SSOHelper::Auth())
        {
            return Redirect( route('auth') );
        }
        return view('auth::auth.register');
    }
    public function logout(Request $request)
    {
        SSOHelper::logout();
        $request->session()->pull('clientid', '');
        $request->session()->pull('clientsecret', '');
        return Redirect( route('auth.login') );
    }
    public function lostPassword()
    {
        return view('auth::auth.passwords.email');
    }
    public function resetPassword(Request $request, $token, $email)
    {   

        $request->merge(['token' => $token, 'password'=>'null', 'email'=>$email]);
        
        $forgot = json_decode(SSOHelper::updatePassword($request));
        if ($forgot->status =='success') {
            return view('auth::auth.passwords.reset',['email'=>$email, 'token'=>$token]);
        }
        session(['swal' => $forgot]);
        return Redirect( route('auth.password.request') )->send();
    }

    public function activation($token,$email)
    {
        $activation = json_decode(SSOHelper::activationUser($token,$email));
        session(['swal' => $activation]);
        if ($activation->status =='success') {
            return view('auth::auth.login');
        }

        return Redirect( route('auth.login') )->send();
    }


    public function postLogin(Request $request)
    {
        
        $response = json_decode( SSOHelper::login($request) );
        if ($response->status == 'success')
        {
            # sukses login nih,
            # simpan di session aja
            $request->session()->put('clientid', $response->message->clientid);
            $request->session()->put('clientsecret', $response->message->clientsecret);
            
            if ( in_array( SSOHelper::Auth()->role , ['superadmin','admin'] ) )    
                return Redirect::to( route('auth') );
            
            return Redirect::to( '/');
        }
        $msg = (!is_array($response->message)) ? $response->message : '';
        session(['swal' => ($response)]);
        return Redirect::to( route('auth.login') );
    }
    public function postRegister(Request $request)
    {
        Validator::make((array)$request->input(), [
            'name' => 'required|max:191',
            'username' => 'required|min:6|max:191',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ])->validate();
        $request->merge(['role' => 'visitor']);
        $register = json_decode(SSOHelper::register($request));
        if ($register->status =='success') {
            $register->message .= ' Check your email to activate account';
        }
        session(['swal' => $register]);
        if ($register->status =='success') {
            return view('auth::auth.login');
        }
        return Redirect( url()->previous() )->send();
    }
    public function postLostPassword(Request $request)
    {
        $forgot = SSOHelper::forgotPassword($request);
        session(['swal' => json_decode($forgot)]);

        return Redirect( route('auth.password.request') )->send();
    }
    public function postResetPassword(Request $request)
    {
        Validator::make((array)$request->input(), [
            'password' => 'required|min:6|confirmed'
        ])->validate();

        $request->merge(['action' => 'update']);
        
        $forgot = json_decode(SSOHelper::updatePassword($request));
        session(['swal' => $forgot]);
        if ($forgot->status =='success') {
            return view('auth::auth.login');
        }

        return Redirect( url()->previous() )->send();
    }
}
