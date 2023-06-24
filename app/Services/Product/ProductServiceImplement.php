<?php

namespace App\Services\Product;

use App\Helpers\Response;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductStock\ProductStockRepository;

class ProductServiceImplement implements ProductService
{
    protected $productRepository, $productStockRepository;

    public function __construct(ProductRepository $productRepository, ProductStockRepository $productStockRepository)
    {
        $this->productRepository = $productRepository;
        $this->productStockRepository = $productStockRepository;
    }
    
    // Method for find all product data using ProductRepository Class
    public function findAllProductData()
    {
        try {
            $productsData = $this->productRepository->findAllNotDeleted();
            return Response::baseResponseWithStatusCode(200, 'Pengambilan Data Produk Berhasil!', true, $productsData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Produk!');
        }
    }

    // Method for find one product (where condition "produk_id") data using ProductRepository Class
    public function findOneProductData($produk_id)
    {
        try {
            $productData = $this->productRepository->findOneNotDeletedByOneSpecificColumn('produk_id', $produk_id);
            if(!is_null($productData)) return Response::baseResponseWithStatusCode(200, 'Data Produk Berhasil Ditemukan!', true, $productData);
            return Response::baseResponseWithStatusCode(404, 'Data Produk Tidak Ditemukan!');
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Produk!');
        }
    }

    // Method for create product data
    // $dto = Data Transfer Object
    public function createProductData($dto)
    {
        try {
            $dto['tgl_dihapus'] = null;
            $createdData = $this->productRepository->create($dto);
            $this->productStockRepository->create([
                'produk_id' => $createdData->produk_id,
                'stok' => $dto['stok']
            ]);
            return Response::baseResponseWithStatusCode(201, 'Data Produk Berhasil Dibuat!', true, $createdData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Membuat Data Produk!');
        }
    }
    
    // Method for update one product data by "produk_id" column
    // $dto = Data Transfer Object
    public function updateOneProductData($produk_id, $dto)
    {
        try {
            $findProductResponse = $this->findOneProductData($produk_id);
            if($findProductResponse->success) {
                foreach ($dto as $key => $value) {
                    // Set NOT NULL column with previous value
                    if(($key == 'nama' || $key == 'brand' || $key == 'harga') && is_null($value)) $dto[$key] = $findProductResponse->data->$key;
                    if($key == 'tgl_dihapus') $dto[$key] = null;
                }

                $updatedData = $this->productRepository->updateByOneSpecificColumn('produk_id', $produk_id, $dto);
                return Response::baseResponseWithStatusCode(200, 'Data Produk Berhasil Diperbarui!', true, $updatedData);
            }

            return Response::baseResponseWithStatusCode($findProductResponse->code, $findProductResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Memperbarui Data Produk!');
        }
    }
    
    // Method for delete one product data by "produk_id" column
    public function deleteOneProductData($produk_id)
    {
        try {
            $findProductResponse = $this->findOneProductData($produk_id);
            if($findProductResponse->success) {
                $this->productRepository->softDeleteProduct($produk_id);
                return Response::baseResponseWithStatusCode(200, 'Data Produk Berhasil Dihapus!');
            }

            return Response::baseResponseWithStatusCode($findProductResponse->code, $findProductResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Menghapus Data Produk!');
        }
    }
}