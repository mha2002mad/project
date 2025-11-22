<?php

namespace App\Repositories\Interfaces;

interface usersInterface {
    public function createUser(array $data);
    public function getUserByID($id);
}