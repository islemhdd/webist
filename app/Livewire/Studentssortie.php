<?php

namespace App\Livewire;

use App\Models\Sortie;
use App\Models\Student;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Studentssortie extends Component
{
    public Student $student;
    public $HasRecentlyOut;
    public $cons;
    public $currentSortie;
    public bool $lock = false;

    public function mount($student, $lock)
    {
        $this->student = $student;
        $this->cons = $student->consigned;
        $this->lock = $lock;

        // Get the most recent sortie for this student
        $this->currentSortie = $this->student->sortie();
        $this->HasRecentlyOut = ($this->currentSortie !== null);
    }

    /**
     * Safely get a property from the current sortie
     */
    public function setprop($prop)
    {
        return $this->currentSortie ? $this->currentSortie->{$prop} : null;
    }

    /**
     * Create, update, or delete a sortie based on the choice
     */
    public function setOrUpdateSortie($choix, $from, $to)
    {
        // Cannot modify if list is locked or student is consigned
        if ($this->lock || $this->student->consigned) {
            return;
        }

        try {
            if (!$this->currentSortie) {
                // Create new sortie
                $this->currentSortie = Sortie::create([
                    'matricule' => $this->student->matricule,
                    'choix' => $choix,
                    'from' => $from,
                    'to' => $to,
                ]);
                $this->student->choix = $choix;
                $this->student->save();
                $this->HasRecentlyOut = true;
            } elseif ($choix !== $this->student->choix) {
                // Update existing sortie with new choice
                $this->currentSortie->update([
                    'choix' => $choix,
                    'from' => $from,
                    'to' => $to,
                ]);
                $this->student->choix = $choix;
                $this->student->save();
                $this->HasRecentlyOut = true;
            } else {
                // Same choice clicked again - toggle off (delete)
                $this->currentSortie->delete();
                $this->student->choix = null;
                $this->student->save();
                $this->currentSortie = null;
                $this->HasRecentlyOut = false;
            }
        } catch (\Exception $e) {
            Log::error('Error in setOrUpdateSortie: ' . $e->getMessage());
            $this->HasRecentlyOut = false;
        }
    }

    public function render()
    {
        return view('livewire.studentssortie');
    }
}
