<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function checkLogin(Request $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));

        $member = DB::table('member')->where('member_username', $username)->first();

        if ($member) {
            if (password_verify($password, $member->member_password) && ($member->permission == "Account" || $member->permission == "Admin")) {
                $request->session()->put('member_id', $member->member_id);
                $request->session()->put('member_name', $member->member_name);
                $request->session()->put('permission', $member->permission);

                Log::info('User to login | '.$member->member_name);
                return redirect()->route('invoice');
            } else {
                return redirect()->route('login')->with('error', 'Invalid credentials');
            }
        } else {
            return redirect()->route('login')->with('error', 'Invalid credentials');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
