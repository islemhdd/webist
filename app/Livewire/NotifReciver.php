<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Component;

class NotifReciver extends Component
{

    public $students;

    public function render()
    {
        $this->students = Student::all();

        return view('livewire.notif-reciver');
    }
}
