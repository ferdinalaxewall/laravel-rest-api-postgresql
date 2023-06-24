<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *      path="/produk",
     *      operationId="findAllProduct",
     *      tags={"Product"},
     *      summary="Get list of products",
     *      description="Returns list of products",
     *      @OA\Response(
     *          response=200,
     *          description="Pengambilan Data Produk Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Produk!",
     *     )
     * )
     *
     * Returns list of products
     */

    // Method for get all data of Product's Table using ProductService class and returning as JSON Response
    public function findAll()
    {
        $productResponse = $this->productService->findAllProductData();
        if($productResponse->success) return Response::jsonResponse($productResponse->code, $productResponse->message, true, ProductResource::collection($productResponse->data));
        return Response::jsonResponse($productResponse->code, $productResponse->message);
    }

    /**
     * @OA\Get(
     *      path="/produk/{produk_id}",
     *      operationId="findOneProduct",
     *      tags={"Product"},
     *      summary="Get single product data",
     *      description="Returns single product data",
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
     *          description="Pengambilan Data Produk Berhasil!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Pengambilan Data Produk!",
     *      )
     * )
     *
     * Returns single product data
     */
    
    // Method for get one data of Product's Table using ProductService Class and returning as JSON Response
    public function findOne($produk_id)
    {
        $productResponse = $this->productService->findOneProductData($produk_id);
        if($productResponse->success) return Response::jsonResponse($productResponse->code, $productResponse->message, true, new ProductResource($productResponse->data));
    
        return Response::jsonResponse($productResponse->code, $productResponse->message);
    }

    /**
     * @OA\Post(
     *      path="/produk",
     *      operationId="createProduct",
     *      tags={"Product"},
     *      summary="Create product data",
     *      description="Create product data",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"nama","brand","harga","stok"},
     *                  @OA\Property(
     *                      property="nama",
     *                      description="Nama Produk (Required)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="brand",
     *                      description="Brand Produk (Required)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="harga",
     *                      description="Harga Produk (Required)",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug Produk (Nullable)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="stok",
     *                      description="Stok Produk (Required)",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_release",
     *                      description="Tanggal Release Produk (Nullable)",
     *                      type="date",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Produk Berhasil Dibuat!",
     *       ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Membuat Data Produk!",
     *      )
     * )
     *
     * Create product data
     */

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'brand' => 'required|string|max:40',
            'harga' => 'required|numeric|digits_between:1,4',
            'slug' => 'nullable|string|max:100',
            'stok' => 'required|numeric|digits_between:1,4',
            'tgl_release' => 'nullable|date',
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $createProductResponse = $this->productService->createProductData($validator->valid());
        if($createProductResponse->success) return Response::jsonResponse($createProductResponse->code, $createProductResponse->message, true, $createProductResponse->data);
        return Response::jsonResponse($createProductResponse->code, $createProductResponse->message);
    }

    /**
     * @OA\Put(
     *      path="/produk/{produk_id}",
     *      operationId="updateOneProduct",
     *      tags={"Product"},
     *      summary="Update product data",
     *      description="Update product data",
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
     *                  @OA\Property(
     *                      property="nama",
     *                      description="Nama Produk (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="brand",
     *                      description="Brand Produk (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="harga",
     *                      description="Harga Produk (Optional)",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug Produk (Optional)",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="tgl_release",
     *                      description="Tanggal Release Produk (Optional)",
     *                      type="date",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Produk Berhasil Diperbarui!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Memperbarui Data Produk!",
     *      )
     * )
     *
     * Update product data
     */

    // Method for update one data by "produk_id" column of Product's Table using ProductService Class and returning as JSON Response
    public function updateOne(Request $request, $produk_id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:40',
            'harga' => 'nullable|numeric|digits_between:1,4',
            'slug' => 'nullable|string|max:100',
            'tgl_release' => 'nullable|date',
        ]);

        if($validator->fails()) return Response::jsonResponse(400, 'Bad Request!', false, $validator->errors());

        $updateProductResponse = $this->productService->updateOneProductData($produk_id, $validator->valid());
        if($updateProductResponse->success) return Response::jsonResponse($updateProductResponse->code, $updateProductResponse->message, true, $updateProductResponse->data);
        return Response::jsonResponse($updateProductResponse->code, $updateProductResponse->message);
    }

    /**
     * @OA\Delete(
     *      path="/produk/{produk_id}",
     *      operationId="deleteOneProduct",
     *      tags={"Product"},
     *      summary="Delete product data",
     *      description="Delete product data",
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
     *          description="Data Produk Berhasil Dihapus!",
     *       ),
     *       @OA\Response(
     *          response=404, 
     *          description="Data Produk Tidak Ditemukan!",
     *      ),
     *       @OA\Response(
     *          response=500, 
     *          description="Terjadi Kesalahan Saat Menghapus Data Produk!",
     *      )
     * )
     *
     * Delete product data
     */

    // Method for delete one data by "produk_id" column of Product's Table using ProductService Class and returning as JSON Response
    public function deleteOne($produk_id)
    {
        $deleteProductResponse = $this->productService->deleteOneProductData($produk_id);
        return Response::jsonResponse($deleteProductResponse->code, $deleteProductResponse->message);
    }
}
