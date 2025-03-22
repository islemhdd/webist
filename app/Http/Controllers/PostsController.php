<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Student;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PostsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $student = Auth::guard('student')->user();
        // dd($student);
        $student = Auth::guard('student')->user();

        if ($student->posts != null) {
            $posts = $student->posts()->orderBy('created_at', 'desc')->get();
        }


        return view("profile", ["posts" => $posts, "student" => $student]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        return view('posts.create');
    }

    public function store(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Validate input
        $val = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'file', 'image', 'max:2048'] // Max file size 2MB
        ]);
        $destination = "img";
        $file = $request->file("image");
        $file->move($destination, $file->getClientOriginalName());

        // Create Post
        $post = new Post([
            "description" => $request->input("description"),
            "title" => $request->input("title"),
            "student_id" => $student->id
        ]);
        $post->save();

        // Store Image
        $path = $destination . "/" . $file->getClientOriginalName();
        $image = new Image([
            "url" => $path,
            "post_id" => $post->id
        ]);
        $image->save();

        // Fetch posts for the profile
        $posts = $student->posts()->orderBy('created_at', 'desc')->get();

        return redirect()->route('posts.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $profile)
    {
        $student = Auth::guard("student")->user();

        return view("posts.show", compact("student", "profile"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $profile)
    {
        // dd(1);

        $valid = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['file', 'image']
        ]);

        if ($request->hasFile('image')) {
            $profile->image()->delete();
            $destination = "img";
            $file = $request->file("image");
            $file->move($destination, $file->getClientOriginalName());
            $path = $destination . "/" . $file->getClientOriginalName();
            $image = new Image([
                "url" => $path,
                "post_id" => $profile->id
            ]);
            $image->save();
        }
        // $image->save();
        $profile->title = $request->input("title");
        $profile->description = $request->input("description");

        $profile->save();
        // dd(1);
        return redirect()->route("posts.index");
    }



    public function destroy(Post $profile)
    {
        Post::destroy($profile->id);
        return redirect()->route("posts.index");
    }
}
