<?php

namespace App\Models;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB as FacadesDB;

class Student extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'matricule';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'section_id',
        'grade'
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the ID.
     *
     * @var string
     */
    protected $keyType = 'string';











    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function sorties(): HasMany
    {
        return $this->hasMany(Sortie::class, 'student_id', 'matricule');
    }
    public function sortie(): Sortie
    {
        //! retuning the most recent sortie

        $sortie = $this->sorties->sortByDesc('created_at')->first();
        if (!$sortie) {
            return new Sortie([
                "student_id" => $this->matricule,
                'from' => null,
                'to' => null,
                'comment' => 'No sortie for the week',
            ]);
        }

        $from = $sortie->from;
        $to = $sortie->to;


        $sortie = $this->sorties()->whereBetween('from', [$from, $to])->whereBetween('to', [$from, $to])->first();
        //  because we can have an other sortie dakhl hadak intervale

        return $sortie;
    }
    /* this filters a student collection(used in the search)
    * @param Collection $collection
    tdi une collection w trdlk wahda askghar base on search
    TODO create the searsh mehtode to fetch a student base on a @var search given by the user (hadi ana ktbtha machi chatgpt )
    */
    public static function search(Collection $students, $text)
    {
        return $students->filter(function ($student) use ($text) {
            return
                stripos($student->matricule, $text) !== false ||
                stripos($student->nom, $text) !== false ||
                stripos($student->section_id, $text) !== false ||
                stripos($student->created_at, $text) !== false ||
                stripos($student->updated_at, $text) !== false ||
                stripos($student->choixes, $text) !== false;
        });
    }
    public function companie()
    {
        $s = FacadesDB::select("SELECT companie FROM sections WHERE sections.id=? ", [$this->section_id]);
        return isset($s[0]->companie) ? (int) $s[0]->companie : null;
    }


    // public function consigne(): HasMany
    // {
    //     return $this->hasMany(Consigne::class, "student_id", "matricule");
    // }
    public function sanctions(): HasMany
    {
        return $this->hasMany(Sanction::class, "matricule", "matricule");
    }
}
