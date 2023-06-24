<?php

namespace App\Services\ProductStock;

use App\Helpers\Response;
use App\Repositories\ProductStock\ProductStockRepository;

class ProductStockServiceImplement implements ProductStockService
{
    protected $productStockRepository;

    public function __construct(ProductStockRepository $productStockRepository)
    {
        $this->productStockRepository = $productStockRepository;
    }
    
    // Method for find all product stock data using ProductStockRepository Class
    public function findAllProductStockData()
    {
        try {
            $productStocksData = $this->productStockRepository->findAll();
            return Response::baseResponseWithStatusCode(200, 'Pengambilan Data Produk Stok Berhasil!', true, $productStocksData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Produk Stok!');
        }
    }

    // Method for find one product (where condition "produk_id") data using ProductStockRepository Class
    public function findOneProductStockData($produk_id)
    {
        try {
            $productData = $this->productStockRepository->findOneByOneSpecificColumn('produk_id', $produk_id);
            if(!is_null($productData)) return Response::baseResponseWithStatusCode(200, 'Data Produk Stok Berhasil Ditemukan!', true, $productData);
            return Response::baseResponseWithStatusCode(404, 'Data Produk Stok Tidak Ditemukan!');
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Produk Stok!');
        }
    }

    // Method for create product stock data
    // $dto = Data Transfer Object
    public function createProductStockData($dto)
    {
        try {
            $createdData = $this->productStockRepository->create($dto);
            return Response::baseResponseWithStatusCode(201, 'Data Produk Stok Berhasil Dibuat!', true, $createdData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Membuat Data Produk Stok!');
        }
    }
    
    // Method for update one product stock data by "produk_id" column
    // $dto = Data Transfer Object
    public function updateOneProductStockData($produk_id, $dto)
    {
        try {
            $findProductStockResponse = $this->findOneProductStockData($produk_id);
            if($findProductStockResponse->success) {
                $updatedData = $this->productStockRepository->updateByOneSpecificColumn('produk_id', $produk_id, $dto);
                return Response::baseResponseWithStatusCode(200, 'Data Produk Stok Berhasil Diperbarui!', true, $updatedData);
            }

            return Response::baseResponseWithStatusCode($findProductStockResponse->code, $findProductStockResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Memperbarui Data Produk Stok!');
        }
    }
    
    // Method for delete one product stock data by "produk_id" column
    public function deleteOneProductStockData($produk_id)
    {
        try {
            $findProductStockResponse = $this->findOneProductStockData($produk_id);
            if($findProductStockResponse) {
                $this->productStockRepository->deleteByOneSpecificColumn('produk_id', $produk_id);
                return Response::baseResponseWithStatusCode(200, 'Data Produk Stok Berhasil Dihapus!');
            }

            return Response::baseResponseWithStatusCode($findProductStockResponse->code, $findProductStockResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Menghapus Data Produk Stok!');
        }
    }
}