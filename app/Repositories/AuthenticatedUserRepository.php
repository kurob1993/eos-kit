<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Container as App;

class AuthenticatedUserRepository extends UserRepository implements UserRepositoryInterface
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = Auth::user();
    }
}