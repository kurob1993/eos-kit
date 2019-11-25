<?php


namespace App\Models;
use App\Traits\OfLoggedUser;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    use OfLoggedUser;
    protected $table = 'list_cities';
    public $timestamps = false;
}
