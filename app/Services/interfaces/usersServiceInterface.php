<?php

namespace App\Services\interfaces;

interface UsersServiceInterface {
    public function createUser(array $data);
    public function getUserByID($id);
}