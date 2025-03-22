<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patien extends Student
{
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'patients';
}
