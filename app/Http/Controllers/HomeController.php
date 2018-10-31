<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Notifications\NewProduct;
use App\Models\User;
use App\Notifications\InvoicePaid;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $users = User::all();
        $users = User::whereIn('id', [1, 4])->get();
        \Notification::send($users, new InvoicePaid());
//        $user = Auth::user();
//        $user->notify(new NewProduct($user));
        return view('home');
    }
}
