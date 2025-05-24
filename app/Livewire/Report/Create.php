<?php

namespace App\Livewire\Report;

use App\Models\Officer;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $mat = null;
    public $corps = "";
    public $title = "";
    public $id = null;
    public $isMedical = false;
    public $destination = null;
    public $successMessage = false;
    public $officer_id;




    /**
     * tbh , les verifications leur places est dant le init()
     *
     *
     */
    public function mount($officer_id)
    {
        $this->officer_id = $officer_id;
    }
    public function save()
    {


        $this->validate([
            'mat' => 'exists:students,matricule'
        ]);
        // return redirect()->back()->with

        $r = new Report();
        $r->init($this->mat, auth()->user()->id, auth()->user()->role->name, $this->title, $this->corps, $this->isMedical, $this->destination);


        if (!$r->existing()) {
            $r->save();
            $sender = Officer::find($this->officer_id);
            TODO:
            $sender->officerNotify($r);

            $this->id = $r->id;

            return redirect()->route('report.show', [

                'report_id' => $this->id,
                'id' => $this->officer_id,
            ]);
        }
    }





    public function render()
    {
        return view('livewire.report.create');
    }
}
