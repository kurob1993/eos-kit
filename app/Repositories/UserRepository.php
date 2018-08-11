<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    function model()
    {
        return 'App\User';
    }

    public function getEmployeeOrFail()
    {
        return $this
            ->model
            ->employee()
            ->firstOrFail();
    }

    public function getPersonnelNo()
    {
        return $this->model->firstOrFail()->personnel_no;
    }
}