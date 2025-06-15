<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Report extends Model

{

    use SoftDeletes;
    protected $guarded = [];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'matricule');
    }
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Officer::class, 'officer_id', 'id');
    }
    public function init($student_id, $officer_id, $role, $title,  $corps, $isMedical = 0, $destination = null)
    {
        $this->student_id = $student_id;
        $this->officer_id = $officer_id;
        $this->status = $role;
        $this->title = $title;
        $this->corps = $corps;
        $this->is_medical = $isMedical;

        $this->destination = $destination;
        return 1;
    }
    public function updateStatus(string $status)
    {
        if (in_array($status, ['Chef de compagnie', 'Chef de brigade', 'Chef de batallaint

        ', 'Chef division', 'Medecin', 'Directeur général']))
            $this->status = $status;
        $this->save();
    }
    /**
     * by islem , verify if the report is alrady existing
     * TODO must be consolted
     *
     * @param Report $r
     * @return int,boolean
     */
    public function identicale(Report $r)
    {
        return ($this->student_id == $r->student_id && $this->officer_id == $r->officer_id && $this->title == $r->title && $this->corps == $r->corps && $this->is_medical == $r->is_medical && $this->destination == $r->destination);
    }
    public function existing()
    {
        $query = Report::where('student_id', $this->student_id)
            ->where('officer_id', $this->officer_id)
            ->where('title', $this->title)
            ->where('corps', $this->corps)
            ->where('is_medical', $this->is_medical);

        if ($this->destination) {
            $query->where('destination', $this->destination);
        }

        return $query->exists();
    }
}
