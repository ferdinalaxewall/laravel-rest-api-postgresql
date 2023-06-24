<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderService, $userService;

    public function __construct(OrderService $orderService, UserService $userService)
    {
        $this->orderService = $orderService;
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/pesanan",
     *      operationId="findAllOrder",
     *      tags={"Order"},
     *      summary="Get list of orders",
     *      description="Returns list of orders",
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Pesanan Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Pesanan!",
     *     )
     * )
     *
     * Returns list of orders
     */

    // Method for get all data of Order's Table using OrderService class and returning as JSON Response
    public function findAll()
    {
        $orderResponse = $this->orderService->findAllOrderData();
        if($orderResponse->success) return Response::jsonResponse($orderResponse->code, $orderResponse->message, true, OrderResource::collection($orderResponse->data));
        return Response::jsonResponse($orderResponse->code, $orderResponse->message);
    }
    
    /**
     * @OA\Get(
     *      path="/pesanan/{pesanan_id}",
     *      operationId="findOneOrder",
     *      tags={"Order"},
     *      summary="Get single order data",
     *      description="Returns single order data",
     *      @OA\Parameter(
     *          name="pesanan_id",
     *          description="Pesanan id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Pesanan Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Pesanan!",
     *      )
     * )
     *
     * Returns single order data
     */
    
    // Method for get one data of Order's Table using OrderService Class and returning as JSON Response
    public function findOne($pesanan_id)
    {
        $orderResponse = $this->orderService->findOneOrderData($pesanan_id);
        if($orderResponse->success) return Response::jsonResponse($orderResponse->code, $orderResponse->message, true, new OrderResource($orderResponse->data));
    
        return Response::jsonResponse($orderResponse->code, $orderResponse->message);
    }

    /**
     * @OA\Post(
     *      path="/pesanan",
     *      operationId="createOrder",
     *      tags={"Order"},
     *      summary="Create order data",
     *      description="Create order data",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"user_id","pesanan_produk"},
     *                  @OA\Property(
     *                      property="user_id",
     *                      description="ID User (Required)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="kode_voucher",
     *                      description="Kode Voucher Pesanan (Nullable)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_pesanan",
     *                      description="Tanggal Pesanan (Nullable)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_pembayaran_lunas",
     *                      description="Tenggal Pembayaran Lunas (Nullable)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_dibatalkan",
     *                      description="Tanggal Pesanan Dibatalkan (Nullable)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="no_pesanan",
     *                      description="Nomor Pesanan (Nullable)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="pesanan_produk",
     *                      description="Array Pesanan Produk (Required)",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Schema(
     *                              required={"produk_id","jumlah"},
     *                              @OA\Property(
     *                                  property="produk_id",
     *                                  description="ID Produk (Required)",
     *                                  type="uuid",
     *                              ),
     *                              @OA\Property(
     *                                  property="jumlah",
     *                                  description="Jumlah Produk (Required)",
     *                                  type="integer",
     *                              )
     *                          )
     *                      )
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Berhasil Dibuat!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Membuat Data Pesanan!",
     *      )
     * )
     *
     * Create order data
     */

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'tgl_pesanan' => 'nullable|date',
            'kode_voucher' => 'nullable|string|max:20',
            'tgl_pembayaran_lunas' => 'nullable|date',
            'tgl_dibatalkan' => 'nullable|date',
            'no_pesanan' => 'nullable|string|max:10',
            'pesanan_produk' => 'required|array|min:1',
            'pesanan_produk.*.produk_id' => 'required',
            'pesanan_produk.*.jumlah' => 'required|numeric|digits_between:1,4',
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $userResponse = $this->userService->findOneUserData($request->user_id);
        if($userResponse->success){
            $createOrderResponse = $this->orderService->createOrderData($validator->valid());
            if($createOrderResponse->success) return Response::jsonResponse($createOrderResponse->code, $createOrderResponse->message, true, $createOrderResponse->data);
            return Response::jsonResponse($createOrderResponse->code, $createOrderResponse->message);
        }

        return Response::jsonResponse($userResponse->code, $userResponse->message);
    }

    /**
     * @OA\Put(
     *      path="/pesanan/{pesanan_id}",
     *      operationId="updateOneOrder",
     *      tags={"Order"},
     *      summary="Update order data",
     *      description="Update order data",
     *      @OA\Parameter(
     *          name="pesanan_id",
     *          description="Pesanan id",
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
     *                      property="user_id",
     *                      description="ID User (Optional)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="kode_voucher",
     *                      description="Kode Voucher Pesanan (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_pesanan",
     *                      description="Tanggal Pesanan (Optional)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_pembayaran_lunas",
     *                      description="Tenggal Pembayaran Lunas (Optional)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_dibatalkan",
     *                      description="Tanggal Pesanan Dibatalkan (Optional)",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="no_pesanan",
     *                      description="Nomor Pesanan (Optional)",
     *                      type="string",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Berhasil Diperbarui!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Memperbarui Data Pesanan!",
     *      )
     * )
     *
     * Update order data
     */

    // Method for update one data by "pesanan_id" column of Order's Table using OrderService Class and returning as JSON Response
    public function updateOne(Request $request, $pesanan_id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable',
            'tgl_pesanan' => 'nullable|date',
            'kode_voucher' => 'nullable|string|max:20',
            'tgl_pembayaran_lunas' => 'nullable|date',
            'tgl_dibatalkan' => 'nullable|date',
            'no_pesanan' => 'nullable|string|max:10'
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $userResponse = $this->userService->findOneUserData($request->has('user_id') ? $request->user_id : null);
        if($userResponse->success || !$request->has('user_id')){
            $updateOrderResponse = $this->orderService->updateOneOrderData($pesanan_id, $validator->valid());
            if($updateOrderResponse->success) return Response::jsonResponse($updateOrderResponse->code, $updateOrderResponse->message, true, $updateOrderResponse->data);
            return Response::jsonResponse($updateOrderResponse->code, $updateOrderResponse->message);
        }

        return Response::jsonResponse($userResponse->code, $userResponse->message);
    }

    /**
     * @OA\Delete(
     *      path="/pesanan/{pesanan_id}",
     *      operationId="deleteOneOrder",
     *      tags={"Order"},
     *      summary="Delete order data",
     *      description="Delete order data",
     *      @OA\Parameter(
     *          name="pesanan_id",
     *          description="Pesanan id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Berhasil Dihapus!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Menghapus Data Pesanan!",
     *      )
     * )
     *
     * Delete order data
     */

    // Method for delete one data by "pesanan_id" column of Order's Table using OrderService Class and returning as JSON Response
    public function deleteOne($pesanan_id)
    {
        $deleteOrderResponse = $this->orderService->deleteOneOrderData($pesanan_id);
        return Response::jsonResponse($deleteOrderResponse->code, $deleteOrderResponse->message);
    }
}
