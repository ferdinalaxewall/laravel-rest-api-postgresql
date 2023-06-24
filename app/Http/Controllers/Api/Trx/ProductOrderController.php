<?php

namespace App\Http\Controllers\Api\Trx;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Validator;
use App\Services\ProductOrder\ProductOrderService;

class ProductOrderController extends Controller
{
    protected $productOrderService, $orderService, $productService;

    public function __construct(
        ProductOrderService $productOrderService, OrderService $orderService,
        ProductService $productService
    ) {
        $this->productOrderService = $productOrderService;
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *      path="/pesanan-produk",
     *      operationId="findAllProductOrder",
     *      tags={"Product Order"},
     *      summary="Get list of product orders",
     *      description="Returns list of product orders",
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Pesanan Produk Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Pesanan Produk!",
     *     )
     * )
     *
     * Returns list of product orders
     */

    // Method for get all data of Product Order's Table using ProductOrderService class and returning as JSON Response
    public function findAll()
    {
        $productOrderResponse = $this->productOrderService->findAllProductOrderData();
        if($productOrderResponse->success) return Response::jsonResponse($productOrderResponse->code, $productOrderResponse->message, true, $productOrderResponse->data);
        return Response::jsonResponse($productOrderResponse->code, $productOrderResponse->message);
    }
    
    /**
     * @OA\Get(
     *      path="/pesanan-produk/{pesanan_produk_id}",
     *      operationId="findOneProductOrder",
     *      tags={"Product Order"},
     *      summary="Get single product order data",
     *      description="Returns single product order data",
     *      @OA\Parameter(
     *          name="pesanan_produk_id",
     *          description="Pesanan Produk id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Pesanan Produk Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Pesanan Produk!",
     *      )
     * )
     *
     * Returns single product order data
     */
    
    // Method for get one data of Product Order's Table using ProductOrderService Class and returning as JSON Response
    public function findOne($pesanan_produk_id)
    {
        $productOrderResponse = $this->productOrderService->findOneProductOrderData($pesanan_produk_id);
        if($productOrderResponse->success) return Response::jsonResponse($productOrderResponse->code, $productOrderResponse->message, true, $productOrderResponse->data);
    
        return Response::jsonResponse($productOrderResponse->code, $productOrderResponse->message);
    }

    /**
     * @OA\Post(
     *      path="/pesanan-produk",
     *      operationId="createProductOrder",
     *      tags={"Product Order"},
     *      summary="Create product order data",
     *      description="Create product order data",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"pesanan_id","produk_id","jumlah"},
     *                  @OA\Property(
     *                      property="pesanan_id",
     *                      description="ID Pesanan (Required)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="produk_id",
     *                      description="ID Produk (Required)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="jumlah",
     *                      description="Jumlah Produk (Required)",
     *                      type="integer",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Produk Berhasil Dibuat!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Membuat Data Pesanan Produk!",
     *      )
     * )
     *
     * Create product order data
     */

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pesanan_id' => 'required',
            'produk_id' => 'required',
            'jumlah' => 'required|numeric|digits_between:1,4',
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $orderResponse = $this->orderService->findOneOrderData($request->pesanan_id);
        if($orderResponse->success){
            $productResponse = $this->productService->findOneProductData($request->produk_id);

            if($productResponse->success){
                $createProductOrderResponse = $this->productOrderService->createProductOrderData($validator->valid());
                if($createProductOrderResponse->success) return Response::jsonResponse($createProductOrderResponse->code, $createProductOrderResponse->message, true, $createProductOrderResponse->data);
                return Response::jsonResponse($createProductOrderResponse->code, $createProductOrderResponse->message);
            }

            return Response::jsonResponse($productResponse->code, $productResponse->message);
        }

        return Response::jsonResponse($orderResponse->code, $orderResponse->message);
    }

    /**
     * @OA\Put(
     *      path="/pesanan-produk/{pesanan_produk_id}",
     *      operationId="updateOneProductOrder",
     *      tags={"Product Order"},
     *      summary="Update product order data",
     *      description="Update product order data",
     *      @OA\Parameter(
     *          name="pesanan_produk_id",
     *          description="Pesanan Produk id",
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
     *                      property="pesanan_id",
     *                      description="ID Pesanan (Optional)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="produk_id",
     *                      description="ID Produk (Optional)",
     *                      type="uuid",
     *                  ),
     *                  @OA\Property(
     *                      property="jumlah",
     *                      description="Jumlah Produk (Optional)",
     *                      type="integer",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Produk Berhasil Diperbarui!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Memperbarui Data Pesanan Produk!",
     *      )
     * )
     *
     * Update product order data
     */

    // Method for update one data by "produk_id" column of Product Order's Table using ProductOrderService Class and returning as JSON Response
    public function updateOne(Request $request, $pesanan_produk_id)
    {
        $validator = Validator::make($request->all(), [
            'pesanan_id' => 'nullable',
            'produk_id' => 'nullable',
            'jumlah' => 'nullable|numeric|digits_between:1,4',
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $orderResponse = $this->orderService->findOneOrderData($request->has('pesanan_id') ? $request->pesanan_id : null);
        if($orderResponse->success || !$request->has('pesanan_id')){
            $productResponse = $this->productService->findOneProductData($request->has('produk_id') ? $request->produk_id : null);

            if($productResponse->success || !$request->has('produk_id')){
                $updateProductOrderResponse = $this->productOrderService->updateOneProductOrderData($pesanan_produk_id, $validator->valid());
                if($updateProductOrderResponse->success) return Response::jsonResponse($updateProductOrderResponse->code, $updateProductOrderResponse->message, true, $updateProductOrderResponse->data);
                return Response::jsonResponse($updateProductOrderResponse->code, $updateProductOrderResponse->message);
            }

            return Response::jsonResponse($productResponse->code, $productResponse->message);
        }

        return Response::jsonResponse($orderResponse->code, $orderResponse->message);
    }

    /**
     * @OA\Delete(
     *      path="/pesanan-produk/{pesanan_produk_id}",
     *      operationId="deleteOneProductOrder",
     *      tags={"Product Order"},
     *      summary="Delete product order data",
     *      description="Delete product order data",
     *      @OA\Parameter(
     *          name="pesanan_produk_id",
     *          description="Pesanan Produk id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Pesanan Produk Berhasil Dihapus!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Pesanan Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Menghapus Data Pesanan Produk!",
     *      )
     * )
     *
     * Delete product order data
     */

    // Method for delete one data by "produk_id" column of Product Order's Table using ProductOrderService Class and returning as JSON Response
    public function deleteOne($pesanan_produk_id)
    {
        $deleteProductOrderResponse = $this->productOrderService->deleteOneProductOrderData($pesanan_produk_id);
        return Response::jsonResponse($deleteProductOrderResponse->code, $deleteProductOrderResponse->message);
    }
}
