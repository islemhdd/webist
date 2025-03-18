<?php

namespace App\Http\Controllers;

use App\Models\Post;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware("isadmin")->only("admin");
    // }

    // public function home(Request $var)
    // {

    //     return view('main');
    // }
    public function explore()
    { {
            // $student = Auth::guard("student")->user();
            $posts = Post::all(); // Paginate for efficiency

            return view('explore', compact('posts'));
        }
    }
    public function live()
    {
        return view('live');
    }
    public function login()
    {
        return view('login');
    }
    public function signup()
    {
        return view('signup');
    }
    public function homePage()
    {
        return view('homePage');
    }
    // test( $student){
    //     if ()
    // }
    public function store($id)
    {
        return $id;
    }
}
