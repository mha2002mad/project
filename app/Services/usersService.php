<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\interfaces\UsersServiceInterface;
use Exception;

class UsersService implements UsersServiceInterface {
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        return $this->userRepository->createUser($data);
    }

    public function getUserByID($id)
    {
        try {
            return $this->userRepository->getUserByID($id);
        } catch (\Throwable $th) {
            throw new Exception('user not found');
        }
    }
}