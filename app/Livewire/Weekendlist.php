<?php

namespace App\Livewire;

use App\Events\SortieLocked;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class Weekendlist extends Component
{
    public $choice = 'all';
    public int $bat;
    public bool $lock;
    public string $role;
    public string $searchTerm = '';
    public string $searchType = 'matricule';

    public function mount($bat, $lock)
    {
        $this->bat = $bat;
        $this->lock = $lock;
        $this->role = auth()->user()->role->name;
    }

    public function updated($property)
    {
        // Update when search term or type changes
        if (in_array($property, ['searchTerm', 'searchType'])) {
            $this->filterStudents();
        }
    }

    public function filterStudents()
    {
        // Always include the bat filter
        $this->choice = 'all'; // Reset choice filter when searching
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchType = 'matricule';
        $this->filterStudents();
    }

    public function selector($choice)
    {
        $this->choice = $choice;
        $this->searchTerm = ''; // Clear search when changing choice
    }

    public function lockFire()
    {
        $this->lock = !$this->lock;
        DB::update("UPDATE list_lock SET status = ? WHERE id = ?", [$this->lock, $this->bat]);
        broadcast(new SortieLocked($this->bat))->toOthers();
    }

    public function render()
    {
        $officer = auth()->user()->isOfficer();
        $query = Student::where('grade', $this->bat)
            ->with('section');


        if ($officer->role->name == 'Chef de compagnie') {

            $userSections = $officer->sections()->pluck('id');
            $query->whereHas('section', function ($q) use ($userSections) {
                $q->whereIn('id', $userSections);
            });
        }
        if ($officer->role->name == 'Chef de batallaint') {


            $query->where("grade", $officer->bat);
        }


        // Apply search filter if term exists
        if ($this->searchTerm) {
            $term = '%' . $this->searchTerm . '%';


            switch ($this->searchType) {
                case 'matricule':
                    $query->where('matricule', 'like', $term);
                    break;
                case 'nom':
                    $query->where('nom', 'like', $term);
                    break;
                case 'grade':
                    $query->where('grade', 'like', $term);
                    break;
                case 'companie':
                    $query->whereHas('section', function ($q) use ($term) {
                        $q->where('companie', 'like', $term);
                    });
                    break;
                case 'section':
                    $query->whereHas('section', function ($q) use ($term) {
                        $q->where('id', 'like', $term);
                    });
                    break;
            }
        }

        // Apply choice filter
        if ($this->choice === 'pasMarquer') {
            $query->whereNull('choix');
        } elseif (in_array($this->choice, ['ven', 'sam', '48h', '36h'])) {
            $query->where('choix', $this->choice);
        }

        $students = $query->get();

        return view('livewire.weekendlist', [
            'students' => $students
        ]);
    }
}
