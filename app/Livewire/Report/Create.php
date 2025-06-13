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
    public $errorMessage = '';
    public $matriculeError = '';
    public $studentName = '';
    public $sectionWarning = '';
    public $battalionWarning = ''; // New property for battalion warning messages

    /**
     * tbh , les verifications leur places est dant le init()
     *
     *
     */
    public function mount($officer_id)
    {
        $this->officer_id = $officer_id;
    }

    public function updatedMat()
    {
        $this->matriculeError = '';
        $this->studentName = '';
        $this->sectionWarning = '';

        if (strlen($this->mat) !== 7) {
            $this->matriculeError = 'Le matricule doit contenir 7 chiffres';
            return;
        }

        try {
            $student = Student::where('matricule', $this->mat)->first();

            if (!$student) {
                $this->matriculeError = 'Étudiant non trouvé';
                return;
            }

            $this->studentName = $student->nom . ' ' . $student->prenom;

            // Check section/battalion access
            $this->checkStudentAccess($student);
        } catch (\Exception $e) {
            dd($e);
            $this->matriculeError = 'Une erreur est survenue lors de la vérification';
        }
    }

    protected function checkStudentAccess($student)
    {
        $officer = Officer::find($this->officer_id);
        if (!$officer) return;

        if ($officer->role->name === 'Chef de compagnie') {
            // Check if student's section matches officer's section
            if ($student->section->officer_id != $officer->id) {

                $this->sectionWarning = "Attention : Cet étudiant n'est pas dans votre section. Vous pouvez continuer mais assurez-vous que c'est approprié.";
            }
        } elseif ($officer->role->name  === 'Chef de batallaint') {
            // Check if student's battalion matches officer's battalion
            if ($student->grade != $officer->bat) {
                $this->sectionWarning = "Attention : Cet étudiant n'est pas dans votre bataillon. Vous pouvez continuer mais assurez-vous que c'est approprié.";
            }
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'mat' => 'required|exists:students,matricule',
                'title' => 'required|min:3',
                'corps' => 'required|min:10'
            ]);

            $r = new Report();

            if (!$r->init($this->mat, auth()->user()->id, auth()->user()->role->name, $this->title, $this->corps, $this->isMedical, $this->destination)) {
                $this->errorMessage = 'Erreur lors de l\'initialisation du rapport.';
                session()->flash('error', $this->errorMessage);
                return;
            }

            if ($r->existing()) {
                $this->errorMessage = 'Un rapport similaire existe déjà.';
                session()->flash('error', $this->errorMessage);
                return;
            }

            $r->save();
            $sender = Officer::find($this->officer_id);
            $sender->officerNotify($r);

            session()->flash('message', 'Rapport créé avec succès!');

            $this->id = $r->id;
            return redirect()->route('report.show', [
                'report_id' => $this->id,
                'id' => $this->officer_id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = 'Erreur de validation: Veuillez vérifier tous les champs requis.';
            session()->flash('error', $this->errorMessage);
            throw $e;
        } catch (\Exception $e) {
            $this->errorMessage = 'Une erreur est survenue lors de la création du rapport.';
            session()->flash('error', $this->errorMessage);
            return;
        }
    }





    public function render()
    {
        return view('livewire.report.create');
    }
}
