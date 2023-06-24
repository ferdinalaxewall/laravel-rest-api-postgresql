<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="findAllUser",
     *      tags={"User"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data User Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data User!",
     *     )
     * )
     *
     * Returns list of users
     */

    // Method for get all data of User's Table using UserService class and returning as JSON Response
    public function findAll()
    {
        $userResponse = $this->userService->findAllUserData();
        if($userResponse->success) return Response::jsonResponse($userResponse->code, $userResponse->message, true, $userResponse->data);
        return Response::jsonResponse($userResponse->code, $userResponse->message);
    }

    /**
     * @OA\Get(
     *      path="/user/{user_id}",
     *      operationId="findOneUser",
     *      tags={"User"},
     *      summary="Get single user data",
     *      description="Returns single user data",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data User Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data User Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data User!",
     *      )
     * )
     *
     * Returns single user data
     */
    
    // Method for get one data of User's Table using UserService Class and returning as JSON Response
    public function findOne($user_id)
    {
        $userResponse = $this->userService->findOneUserData($user_id);
        if($userResponse->success) return Response::jsonResponse($userResponse->code, $userResponse->message, true, $userResponse->data);
    
        return Response::jsonResponse($userResponse->code, $userResponse->message);
    }

    /**
     * @OA\Post(
     *      path="/user",
     *      operationId="createUser",
     *      tags={"User"},
     *      summary="Create user data",
     *      description="Create user data",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"nama_depan","nama_belakang","jk","tgl_lahir"},
     *                  @OA\Property(
     *                      property="nama_depan",
     *                      description="Nama Depan User (Required)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="nama_belakang",
     *                      description="Nama Belakang User (Required)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="alamat",
     *                      description="Alamat User (Nullable)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="nomor_hp",
     *                      description="Nomor Handphone User (Nullable)",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="jk",
     *                      description="Jenis Kelamin User (Required)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_lahir",
     *                      description="Tanggal Lahir User (Required)",
     *                      type="date",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data User Berhasil Dibuat!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Membuat Data User!",
     *      )
     * )
     *
     * Create user data
     */

    // Method for create user data
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'required|string|max:30',
            'nama_belakang' => 'required|string|max:30',
            'alamat' => 'nullable|string|max:200',
            'nomor_hp' => 'nullable|numeric|digits_between:1,15',
            'jk' => 'required|string|max:1',
            'tgl_lahir' => 'required|date'
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $createUserResponse = $this->userService->createUserData($request->all());
        if($createUserResponse->success) return Response::jsonResponse($createUserResponse->code, $createUserResponse->message, true, $createUserResponse->data);
        return Response::jsonResponse($createUserResponse->code, $createUserResponse->message);
    }

    /**
     * @OA\Put(
     *      path="/user/{user_id}",
     *      operationId="updateOneUser",
     *      tags={"User"},
     *      summary="Update user data",
     *      description="Update user data",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="nama_depan",
     *                      description="Nama Depan User (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="nama_belakang",
     *                      description="Nama Belakang User (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="alamat",
     *                      description="Alamat User (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="nomor_hp",
     *                      description="Nomor Handphone User (Optional)",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="jk",
     *                      description="Jenis Kelamin User (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_lahir",
     *                      description="Tanggal Lahir User (Optional)",
     *                      type="date",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data User Berhasil Diperbarui!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data User Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Memperbarui Data User!",
     *      )
     * )
     *
     * Update user data
     */

    // Method for update one data by "user_id" column of User's Table using UserService Class and returning as JSON Response
    public function updateOne(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'nama_depan' => 'nullable|string|max:30',
            'nama_belakang' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:200',
            'nomor_hp' => 'nullable|numeric|digits_between:1,15',
            'jk' => 'nullable|string|max:1',
            'tgl_lahir' => 'nullable|date'
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $updateUserResponse = $this->userService->updateOneUserData($user_id, $request->all());
        if($updateUserResponse->success) return Response::jsonResponse($updateUserResponse->code, $updateUserResponse->message, true, $updateUserResponse->data);
        return Response::jsonResponse($updateUserResponse->code, $updateUserResponse->message);
    }

    /**
     * @OA\Delete(
     *      path="/user/{user_id}",
     *      operationId="deleteOneUser",
     *      tags={"User"},
     *      summary="Delete user data",
     *      description="Delete user data",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data User Berhasil Dihapus!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data User Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Menghapus Data User!",
     *      )
     * )
     *
     * Delete user data
     */

    // Method for delete one data by "user_id" column of User's Table using UserService Class and returning as JSON Response
    public function deleteOne($user_id)
    {
        $deleteUserResponse = $this->userService->deleteOneUserData($user_id);
        return Response::jsonResponse($deleteUserResponse->code, $deleteUserResponse->message);
    }
}
