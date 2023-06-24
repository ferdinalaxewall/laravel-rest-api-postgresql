<?php

namespace App\Services\ProductOrder;

use Exception;
use App\Helpers\Response;
use App\Services\Product\ProductService;
use App\Repositories\ProductOrder\ProductOrderRepository;
use App\Repositories\ProductStock\ProductStockRepository;

class ProductOrderServiceImplement implements ProductOrderService
{
    protected $productOrderRepository, $productService, $productStockRepository;

    public function __construct(
        ProductOrderRepository $productOrderRepository, ProductService $productService,
        ProductStockRepository $productStockRepository
    ) {
        $this->productOrderRepository = $productOrderRepository;
        $this->productStockRepository = $productStockRepository;
        $this->productService = $productService;
    }
    
    // Method for find all product order data using ProductOrderRepository Class
    public function findAllProductOrderData()
    {
        try {
            $productOrdersData = $this->productOrderRepository->findAllNotDeleted();
            return Response::baseResponseWithStatusCode(200, 'Pengambilan Data Pesanan Produk Berhasil!', true, $productOrdersData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Pesanan Produk!');
        }
    }

    // Method for find one product (where condition "pesanan_produk_id") data using ProductOrderRepository Class
    public function findOneProductOrderData($pesanan_produk_id)
    {
        try {
            $productOrderData = $this->productOrderRepository->findOneNotDeletedByOneSpecificColumn('pesanan_produk_id', $pesanan_produk_id);
            if(!is_null($productOrderData)) return Response::baseResponseWithStatusCode(200, 'Data Pesanan Produk Berhasil Ditemukan!', true, $productOrderData);
            return Response::baseResponseWithStatusCode(404, 'Data Pesanan Produk Tidak Ditemukan!');
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Pesanan Produk!');
        }
    }

    // Method for create product order data
    // $dto = Data Transfer Object
    public function createProductOrderData($dto)
    {
        try {
            $dto['tgl_dihapus'] = null;
            $isValidProductOrder = $this->validateSingleProductOrder($dto['produk_id'], $dto['jumlah']);
            $createdData = $this->productOrderRepository->create($dto);

            // Update Product Stock Data
            $currentStock = $this->productStockRepository->findOneByOneSpecificColumn('produk_id', $dto['produk_id'])->stok;
            $this->productStockRepository->updateByOneSpecificColumn('produk_id', $dto['produk_id'], [
                'stok' => $currentStock - $dto['jumlah']
            ]);
            
            return Response::baseResponseWithStatusCode(201, 'Data Pesanan Produk Berhasil Dibuat!', true, $createdData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Membuat Data Pesanan Produk!');
        }
    }
    
    // Method for update one product order data by "pesanan_produk_id" column
    // $dto = Data Transfer Object
    public function updateOneProductOrderData($pesanan_produk_id, $dto)
    {
        try {
            $findProductOrderResponse = $this->findOneProductOrderData($pesanan_produk_id);
            if($findProductOrderResponse->success) {
                foreach ($dto as $key => $value) {
                    // Set NOT NULL column with previous value
                    if(($key == 'pesanan_id' || $key == 'produk_id' || $key == 'jumlah') && is_null($value)) $dto[$key] = $findProductOrderResponse->data->$key;
                    if($key == 'tgl_dihapus') $dto[$key] = null;
                }

                if(!array_key_exists('produk_id', $dto)) $dto['produk_id'] = $findProductOrderResponse->data->produk_id;

                $isValidProductOrder = $this->validateSingleProductOrder($dto['produk_id'], $dto['jumlah']);
                $updatedData = $this->productOrderRepository->updateByOneSpecificColumn('pesanan_produk_id', $pesanan_produk_id, $dto);
                
                // Update Product Stock Data
                $currentStock = $this->productStockRepository->findOneByOneSpecificColumn('produk_id', $dto['produk_id'])->stok;
                $currentStock += $findProductOrderResponse->data->jumlah;

                $this->productStockRepository->updateByOneSpecificColumn('produk_id', $dto['produk_id'], [
                    'stok' => $currentStock - $dto['jumlah']
                ]);

                return Response::baseResponseWithStatusCode(200, 'Data Pesanan Produk Berhasil Diperbarui!', true, $updatedData);
            }

            return Response::baseResponseWithStatusCode($findProductOrderResponse->code, $findProductOrderResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Memperbarui Data Pesanan Produk!');
        }
    }
    
    // Method for delete one product order data by "pesanan_produk_id" column
    public function deleteOneProductOrderData($pesanan_produk_id)
    {
        try {
            $findProductOrderResponse = $this->findOneProductOrderData($pesanan_produk_id);
            if($findProductOrderResponse) {
                $this->productOrderRepository->softDeleteProductOrder($pesanan_produk_id);
                return Response::baseResponseWithStatusCode(200, 'Data Pesanan Produk Berhasil Dihapus!');
            }

            return Response::baseResponseWithStatusCode($findProductOrderResponse->code, $findProductOrderResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Menghapus Data Pesanan Produk!');
        }
    }
    
    public function validateSingleProductOrder($produk_id, $jumlah)
    {
        $productResponse = $this->productService->findOneProductData($produk_id);
        if($productResponse->success){
            $currentStock = !is_null($productResponse->data->detail) ? $productResponse->data->detail->stok : 0;
            if($jumlah <= $currentStock){
                return true;
            }else{
                throw new Exception('Stok Produk "'. $produk_id .'" Tidak Mencukupi, Sisa: '. $currentStock, 1);
            }
        }else{
            throw new Exception($productResponse->message);
        }
    }
}