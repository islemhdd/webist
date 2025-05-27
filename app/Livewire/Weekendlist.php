<?php

namespace App\Livewire;

use App\Events\SortieLocked;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;


class Weekendlist extends Component
{
    //TODO: when we specify the time of "sortie" of the students to display

    public $choice;
    public int  $bat;

    //*the battalient of the officer
    public  $students;

    //*the status of the list

    public  $lock;
    public $role;


    public function mount($students, $bat, $lock)
    {
        $this->lock = DB::select("select status from list_lock where id = $bat")[0]->status;
        $this->students = $students;
        $this->bat = $bat;
    }

    /**
     * Undocumented function
     *
     * @param String  $choice[['ven', 'sam', '48h', '36h']]
     *
     *
     * renders the students with the specified choice
     *
     *
     *
     * @return void
     */
    public function selector($choice)
    {
        $query = Student::where('grade', $this->bat);

        if (in_array($choice, ['ven', 'sam', '48h', '36h'])) {
            $this->students = $query->where('choix', $choice)->get();
            // dd($this->students);
        } else {
            $this->students = $query->get();
        }
    }

    public function lockFire()
    {
        $this->lock = true;
        broadcast(new SortieLocked($this->bat));
    }

    public function render()
    {
        return view('livewire.weekendlist');
    }
}
