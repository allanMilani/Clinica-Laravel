<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $dates = ['start', 'end'];

    public function patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }
    public function phisician()
    {
        return $this->belongsTo('App\Models\Phisician');
    }
}
