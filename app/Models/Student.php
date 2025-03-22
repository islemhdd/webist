<?php
//! message l rohi :les commentaire ali f had model hani katbhm b ydi machi b chatgpt bah nchfa wach rani ndir

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // Properly extend User
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class Student extends Authenticatable
{

    use HasFactory, SoftDeletes, Notifiable;
    // public $timestamps = false;

    protected $guarded =  [];

    protected $primaryKey = 'id';
    protected $table = "students";
    // public $incrementing = false; must be true
    // public function posts(): HasMany
    // {
    //     return $this->hasMany(Post::class);
    // }
    public function section():BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function sorties(): HasMany
    {
        return $this->hasMany(Sortie::class);
    }
    public function sortie() :Sortie
    {
        //! retuning the most recent sortie

      $sortie= $this->sorties->sortByDesc('created_at')->first();
      if (!$sortie) {
        return new Sortie([
            "student-id"=>$this->id,
            'from' => null,
            'to' => null,
            'comment' => 'No sortie for the week',
        ]);
      }
      $from=$sortie->from;
      $to=$sortie->to;

      $sortie = $this->sorties()->whereBetween('from', [$from, $to])->whereBetween('to', [$from, $to])->first();
     return $sortie;





    }
}
