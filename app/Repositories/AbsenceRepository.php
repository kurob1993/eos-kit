<?php

namespace App\Repositories;

use App\Interfaces\AbsenceRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;

class AbsenceRepository extends BaseRepository implements AbsenceRepositoryInterface
{

    function model()
    {
        return 'App\Models\Absence';
    }

    public function leaves(UserRepository $user)
    {
        return $this
            ->model
            ->where('personnel_no', $user->getModel()->personnel_no)
            ->with(['absenceType', 'stage']);   
    }

}