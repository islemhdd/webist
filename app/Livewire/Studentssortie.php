<?php


namespace App\Livewire;

use App\Models\Sortie;
use App\Models\Student;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


class Studentssortie extends Component
{
    public Student $student;      //* Passed from the Blade view
    public $HasRecentlyOut;       //* Indicates if a sortie exists
    public $cons;                 //* Consignment status
    public $currentSortie;
    public bool $lock = false;

    public function mount($student, $lock)
    {
        $this->student = $student;
        $this->cons = $student->consigned;
        $this->lock = $lock;


        // Retrieve the first related sortie (assuming 'sortie' is a defined relationship)
        $this->currentSortie = $this->student->sortie()->first();
        $this->HasRecentlyOut = ($this->currentSortie !== null);
    }

    // Safely get a property from the current sortie
    public function setprop($prop)
    {
        return $this->currentSortie ? $this->currentSortie->{$prop} : null;
    }

    public function setOrUpdateSortie($choix, $from, $to)

    {



        //?the list is not locked and the student is not sonsigned
        if (!$this->lock && !$this->student->consigned) {

            // If there's no current sortie, create one regardless of the value of $choix
            if (!$this->currentSortie) {
                $sortie = Sortie::create([
                    'student_id' => $this->student->matricule,
                    'choix'      => $choix,
                    'from'       => $from,
                    'to'         => $to,
                ]);
                $this->student->choix = $choix;
                $this->student->save();
                $this->currentSortie = $sortie;
                $this->HasRecentlyOut = true;
            } else {
                // If a sortie already exists, decide whether to update or delete it
                if ($choix != $this->student->choix) {
                    // Update the sortie using updateOrCreate. We use the current sortie's properties for matching.
                    $sortie = Sortie::updateOrCreate(
                        [
                            'student_id' => $this->student->matricule,
                            'choix'      => $this->currentSortie->choix,
                            'from'       => $this->currentSortie->from,
                            'to'         => $this->currentSortie->to,
                        ],
                        [
                            'choix' => $choix,
                            'from'  => $from,
                            'to'    => $to,
                        ]
                    );
                    $this->student->choix = $choix;
                    $this->student->save();
                    $this->currentSortie = $sortie;
                    $this->HasRecentlyOut = true;
                } else {
                    try {
                        $this->HasRecentlyOut = false;
                        // Delete current sortie only if it exists
                        if ($this->currentSortie) {
                            $this->currentSortie->delete();
                            $this->student->choix = null;
                            $this->student->save();
                        }
                        // Update current sortie after deletion (could be null)
                        $this->currentSortie = Sortie::where('student_id', $this->student->matricule)
                            ->latest('created_at')
                            ->first();
                    } catch (\Exception $e) {
                        Log::error('Error in setOrUpdateSortie: ' . $e->getMessage());
                        $this->HasRecentlyOut = false;
                        // Optionally flash an error message to the session
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.studentssortie');
    }
}
