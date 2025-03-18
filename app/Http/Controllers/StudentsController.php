<?php

namespace App\Http\Controllers;

use App\Models\Id;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $students = Student::all();
        return view("student.index", compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = $request->session()->token();

        $token = csrf_token();
        $id = $request->input("id");
        if (Id::find($id)) {
            if (in_array($request->input("grade"), [1, 2, 3])) {
                $student = new Student(["id" => $id, "grade" => $request->input("grade"), "name" => $request->input("name")]);
                $student->save();
                $students = Student::all();
                return redirect()->route('student.index');
            }
        }
        return view("signup", ['message' => "could nt signup"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
