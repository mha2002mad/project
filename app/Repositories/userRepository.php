<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\usersInterface;
use Exception;

class UserRepository implements usersInterface {
    protected User $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function getUserByID($id)
    {
        try {
            return $this->user->findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('user not found');
        }
    }

    public function createUser(array $data)
    {
        if ($this->user->where('email', '=', $data['email'])->exists()) {
            throw new Exception('user by this email already exists');
        }

        return $this->user->create($data);
    }
}