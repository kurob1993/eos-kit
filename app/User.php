<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\AbsenceQuota;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Book;
use App\BorrowLog;
use App\Exceptions\BookException;

class User extends Authenticatable
{
  use LaratrustUserTrait;
  use Notifiable;

  protected $fillable = [ 'name', 'email', 'password', ];

  protected $hidden = [ 'password', 'remember_token', ];

  protected $casts = [ 'is_verified' => 'boolean', ];

  public function absences()
  {
    // one-to-many relationship dengan Absence
    return $this->hasMany('App\Models\Absence', 'personnel_no', 'personnel_no');
  }

  public function absenceQuotas()
  {
    // one-to-many relationship dengan AbsenceQuota
    return $this->hasMany('App\Models\AbsenceQuota', 'personnel_no', 'personnel_no');
  }

  public function employee()
  {
    // one-to-one relationship dengan Employee
    return $this->hasOne('App\Models\Employee', 'personnel_no', 'personnel_no');
  }

  public function structDisp()
  {
    // one-to-many relationship dengan StructDisp
    return $this->hasMany('App\Models\StructDisp', 'empnik', 'personnel_no');
  }

  public function activeAbsenceQuota()
  {
    // mencari absence quota pada hari ini untuk user
    AbsenceQuota::activeAbsenceQuota($this->personnel_no);
  }
}
