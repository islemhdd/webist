<?php

namespace App\Http\Controllers;

use App\Models\Officer;
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
    public function index($id)
    {
        $officer = Officer::find($id);

        $reports = $officer->reports()->orderBy('created_at', 'desc')->get();



        return view('report.index', compact('reports', 'officer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $officer = Officer::find($id);
        return view('report.create', ["officer" => $officer]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // // public function store(Request $request)


    /**
     * Display the specified resource.
     */
    public function show($id, $report_id)
    {
        $officer = Officer::find($id);

        $report = Report::find($report_id);

        if ($officer)
            return view('report.show', ["report" => $report, "officer" => $officer]);
        return view('report.show', ["report" => $report]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Clear notification and redirect to report
     */
    public function unsetReportNotificationAndRedirect(Officer $id, Report $report_id)
    {
        $officer = $id;
        $report = $report_id;
        $officer->unreadNotifications()->where('data->report_id', $report->id)->delete();


        return redirect()->route('report.show', ['id' => $officer->id, 'report_id' => $report->id]);
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */

    public function avis(Officer $id, Report $report, Request $request)
    {


        $officer = $id;
        // TODO : check if the officer is the owner of the report
        $avis = $request->input('avis');

        // if()

        $avisRole = 'avis' . str_replace(' ', '_',  $officer->role->name);

        $report->update([
            $avisRole => $avis,

        ]);
        $report->save();


        $officer->officerNotify($report);



        return redirect()->back();
    }
    public function received(Officer $id)
    {
        $officer = $id;
        $reports = Report::where('destination', $officer->id)->where('status', '!=', 'DONE')->get();
        return view('report.received', compact('reports', 'officer'));
    }
    public function refuse(Officer $id, Report $report, Request $request)
    {
        $officer = $id;
        $officer->refuse($report, $request->input('motif'));

        return redirect()->back();
    }
}
