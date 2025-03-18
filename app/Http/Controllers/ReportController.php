<?php

namespace App\Http\Controllers;

use App\Notifications\Report as NotificationsReport;


use App\Models\Report;
use App\Models\Student;
use App\Providers\AppServiceProvider;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'student_id' => "exists:students,id|digits:7"
        ]);




        // Store the report in the database...
        $report = new Report([
            'student_id' => $request->student_id,
            'description' => $request->description,
            'title' => $request->title


        ]);
        $report->save();
        $DE = Student::find(2022001);
        $DE->notify(new NotificationsReport($report));
        return redirect()->route("posts.index");
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
