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

  public function absenceApprovals()
  {
    // one-to-many relationship dengan AbsenceApproval
    return $this->hasMany('App\Models\AbsenceApproval', 'regno', 'personnel_no');
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
    return AbsenceQuota::activeAbsenceQuota($this->personnel_no);
  }

  public function families()
  {
    // one-to-many relationship dengan it0021
    return $this->hasMany('App\Models\Family', 'PERNR', 'personnel_no');
  }

  public function getPersonnelNoWithNameAttribute()
  {
    // menggabungkan personnel_no dan nama
    return $this->personnel_no . ' - ' . $this->name;
  }
}
