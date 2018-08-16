<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;
use App\Repositories\UserRepository;

interface AbsenceRepositoryInterface extends BaseRepositoryInterface
{
    public function leaves(UserRepository $user);
}