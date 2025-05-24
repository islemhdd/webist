<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\Attributes\On;

class Searchbar extends Component
{
    public $searchTerm = '';
    public $searchType = 'matricule';

    protected $queryString = ['searchTerm'];

    public function updatedSearchTerm()
    {
        $query = Student::query();

        if (strlen($this->searchTerm) >= 2) {
            switch ($this->searchType) {
                case 'matricule':
                    $query->where('matricule', 'like', '%' . $this->searchTerm . '%');
                    break;
                case 'nom':
                    $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    break;
                case 'grade':
                    $query->where('grade', 'like', '%' . $this->searchTerm . '%');
                    break;
                case 'companie':
                    $query->whereHas('section', function ($q) {
                        $q->where('companie', 'like', '%' . $this->searchTerm . '%');
                    });
                    break;
                case 'section':
                    $query->whereHas('section', function ($q) {
                        $q->where('code', 'like', '%' . $this->searchTerm . '%');
                    });
                    break;
            }
        }

        $results = $query->get();
        $this->dispatch('search-results-updated', ['students' => $results]);
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->dispatch('search-results-updated', ['students' => Student::all()]);
    }

    public function render()
    {
        return view('livewire.searchbar');
    }
}
