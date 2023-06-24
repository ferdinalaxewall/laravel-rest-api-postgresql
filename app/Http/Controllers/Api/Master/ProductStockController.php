<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Validator;
use App\Services\ProductStock\ProductStockService;

class ProductStockController extends Controller
{
    protected $productStockService, $productService;

    public function __construct(ProductStockService $productStockService, ProductService $productService)
    {
        $this->productStockService = $productStockService;
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *      path="/produk-stock",
     *      operationId="findAllProductStock",
     *      tags={"Product Stock"},
     *      summary="Get list of product stocks",
     *      description="Returns list of product stocks",
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Produk Stok Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Produk Stok!",
     *     )
     * )
     *
     * Returns list of product stocks
     */

    // Method for get all data of ProductStock's Table using ProductStockService class and returning as JSON Response
    public function findAll()
    {
        $productStockResponse = $this->productStockService->findAllProductStockData();
        if($productStockResponse->success) return Response::jsonResponse($productStockResponse->code, $productStockResponse->message, true, $productStockResponse->data);
        return Response::jsonResponse($productStockResponse->code, $productStockResponse->message);
    }

    /**
     * @OA\Get(
     *      path="/produk-stock/{produk_id}",
     *      operationId="findOneProductStock",
     *      tags={"Product Stock"},
     *      summary="Get single product stock data",
     *      description="Returns single product stock data",
     *      @OA\Parameter(
     *          name="produk_id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Produk Stok Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Produk Stok Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Produk Stok!",
     *      )
     * )
     *
     * Returns single product stock data
     */
    
    // Method for get one data of ProductStock's Table using ProductStockService Class and returning as JSON Response
    public function findOne($produk_id)
    {
        $productStockResponse = $this->productStockService->findOneProductStockData($produk_id);
        if($productStockResponse->success) return Response::jsonResponse($productStockResponse->code, $productStockResponse->message, true, $productStockResponse->data);
    
        return Response::jsonResponse($productStockResponse->code, $productStockResponse->message);
    }

    /**
     * @OA\Put(
     *      path="/produk-stock/{produk_id}",
     *      operationId="updateOneProductStock",
     *      tags={"Product Stock"},
     *      summary="Update product stock data",
     *      description="Update product stock data",
     *      @OA\Parameter(
     *          name="produk_id",
     *          description="Product id",
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
     *                  required={"stok"},
     *                  @OA\Property(
     *                      property="stok",
     *                      description="Stok Produk (Required)",
     *                      type="integer",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Produk Stok Berhasil Diperbarui!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Produk Stok Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Memperbarui Data Produk Stok!",
     *      )
     * )
     *
     * Update product stock data
     */

    // Method for update one data by "produk_id" column of ProductStock's Table using ProductStockService Class and returning as JSON Response
    public function updateOne(Request $request, $produk_id)
    {
        $validator = Validator::make($request->all(), [
            'stok' => 'required|numeric|min:0|digits_between:1,4'
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $updateProductStockResponse = $this->productStockService->updateOneProductStockData($produk_id, $validator->valid());
        if($updateProductStockResponse->success) return Response::jsonResponse($updateProductStockResponse->code, $updateProductStockResponse->message, true, $updateProductStockResponse->data);
        return Response::jsonResponse($updateProductStockResponse->code, $updateProductStockResponse->message);
    }
}
