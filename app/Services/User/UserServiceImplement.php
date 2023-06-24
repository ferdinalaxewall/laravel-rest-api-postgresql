<?php

namespace App\Services\User;

use App\Helpers\Response;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;

class UserServiceImplement implements UserService
{
    // Protect the Repository Variable
    protected $userRepository;

    // Service Class Constructor with Repository Parameter
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    // Method for find all user data using UserRepository Class
    public function findAllUserData()
    {
        try {
            $usersData = $this->userRepository->findAll();
            return Response::baseResponseWithStatusCode(200, 'Pengambilan Data User Berhasil!', true, $usersData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data User!');
        }
    }

    // Method for find one user (where condition user_id) data using UserRepository Class
    public function findOneUserData($user_id)
    {
        try {
            $userData = $this->userRepository->findOneByOneSpecificColumn('user_id', $user_id);
            if(!is_null($userData)) return Response::baseResponseWithStatusCode(200, 'Data User Berhasil Ditemukan!', true, $userData);
            return Response::baseResponseWithStatusCode(404, 'Data User Tidak Ditemukan!');
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data User!');
        }
    }

    // Method for create user data
    // $dto = Data Transfer Object
    public function createUserData($dto)
    {
        try {
            $createdData = $this->userRepository->create($dto);
            return Response::baseResponseWithStatusCode(201, 'Data User Berhasil Dibuat!', true, $createdData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Membuat Data User!');
        }
    }
    
    // Method for update one user data by user_id column
    // $dto = Data Transfer Object
    public function updateOneUserData($user_id, $dto)
    {
        try {
            $findUserResponse = $this->findOneUserData($user_id);
            if($findUserResponse->success) {
                foreach ($dto as $key => $value) {
                    // Set NOT NULL column with previous value
                    if($key != 'alamat' && $key != 'nomor_hp' && is_null($value)) $dto[$key] = $findUserResponse->data->$key;
                }

                $updatedData = $this->userRepository->updateByOneSpecificColumn('user_id', $user_id, $dto);
                return Response::baseResponseWithStatusCode(200, 'Data User Berhasil Diperbarui!', true, $updatedData);
            }

            return Response::baseResponseWithStatusCode($findUserResponse->code, $findUserResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Memperbarui Data User!');
        }
    }
    
    // Method for delete one user data by user_id column
    public function deleteOneUserData($user_id)
    {
        try {
            $findUserResponse = $this->findOneUserData($user_id);
            if($findUserResponse) {
                $this->userRepository->deleteByOneSpecificColumn('user_id', $user_id);
                return Response::baseResponseWithStatusCode(200, 'Data User Berhasil Dihapus!');
            }

            return Response::baseResponseWithStatusCode($findUserResponse->code, $findUserResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Menghapus Data User!');
        }
    }

}