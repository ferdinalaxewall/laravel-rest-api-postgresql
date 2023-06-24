<?php

namespace App\Services\User;

interface UserService
{
    public function findAllUserData();
    public function findOneUserData($user_id);
    public function createUserData($dto);
    public function updateOneUserData($user_id, $dto);
    public function deleteOneUserData($user_id);
}