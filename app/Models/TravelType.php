<?php

namespace App\Models;
use App\Traits\OfLoggedUser;
use Illuminate\Database\Eloquent\Model;

class TravelType extends Model
{
    use OfLoggedUser;
    protected $table = 'attendance_types';
    public $timestamps = false;
}
