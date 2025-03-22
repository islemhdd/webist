<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\Authenticatable as A;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use stdClass;

class Officer extends Model implements A

{
    use Authenticatable;
    protected $guarded = ['role'];
    public $incrementing = false;
    public $timestamps = false;
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
    public function haleTimes(): HasMany
    {
        return $this->hasMany(HaleTime::class);
    }
    public function isHale(): bool
    {    //if its the officers time
        $hals = $this->haletimes->pluck('day');
        $day = Carbon::now()->format('l');
        return in_array($day, $hals);
    }
    public function companie(): array //return the companies of the officer
    {

        // dd(1);
        $comp = DB::select("SELECT distinct companie as c From sections where officer_id=$this->id");
        $compArray = [];
        foreach ($comp as $c) {
            $compArray[] = $c->c;
        }
        return $compArray;
    }
    // public function Reports(): Collection
    // {
    //     // $reports = Report::whereIn('student_id', Student::whereIn('section_id', $this->sections()->pluck('id'))->pluck('id'))->get();
    //     // return $reports;
    // }
}
