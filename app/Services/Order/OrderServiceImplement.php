<?php

namespace App\Services\Order;

use Exception;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Services\Product\ProductService;
use App\Repositories\Order\OrderRepository;
use App\Repositories\ProductOrder\ProductOrderRepository;
use App\Repositories\ProductStock\ProductStockRepository;

class OrderServiceImplement implements OrderService
{
    protected $orderRepository, $productOrderRepository, $productService, $productStockRepository;

    public function __construct(
        OrderRepository $orderRepository, ProductOrderRepository $productOrderRepository,
        ProductService $productService, ProductStockRepository $productStockRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productOrderRepository = $productOrderRepository;
        $this->productService = $productService;
        $this->productStockRepository = $productStockRepository;
    }
    
    // Method for find all order data using OrderRepository Class
    public function findAllOrderData()
    {
        try {
            $ordersData = $this->orderRepository->findAllWithFullRelation();
            return Response::baseResponseWithStatusCode(200, 'Pengambilan Data Pesanan Berhasil!', true, $ordersData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Pesanan!');
        }
    }

    // Method for find one product (where condition "pesanan_id") data using OrderRepository Class
    public function findOneOrderData($pesanan_id)
    {
        try {
            $orderData = $this->orderRepository->findOneByOneSpecificColumnWithFullRelation('pesanan_id', $pesanan_id);
            if(!is_null($orderData)) return Response::baseResponseWithStatusCode(200, 'Data Pesanan Berhasil Ditemukan!', true, $orderData);
            return Response::baseResponseWithStatusCode(404, 'Data Pesanan Tidak Ditemukan!');
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Pengambilan Data Pesanan!');
        }
    }

    // Method for create order data
    // $dto = Data Transfer Object
    public function createOrderData($dto)
    {
        try {
            $products = $dto['pesanan_produk'];
            unset($dto['pesanan_produk']);

            $validatedProductOrders = $this->validateMassProductOrder($products);
            $createdData = $this->orderRepository->create($dto);

            foreach ($validatedProductOrders as $productOrder) {
                
                $this->productOrderRepository->create([
                    'pesanan_id' => $createdData->pesanan_id,
                    'produk_id' => $productOrder['produk_id'],
                    'jumlah' => $productOrder['jumlah']
                ]);

                $currentStock = $this->productStockRepository->findOneByOneSpecificColumn('produk_id', $productOrder['produk_id'])->stok;
                $this->productStockRepository->updateByOneSpecificColumn('produk_id', $productOrder, [
                    'stok' => $currentStock - $productOrder['jumlah']
                ]);
            }

            return Response::baseResponseWithStatusCode(201, 'Data Pesanan Berhasil Dibuat!', true, $createdData);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(400, $th->getMessage());
        }
    }
    
    // Method for update one order data by "pesanan_id" column
    // $dto = Data Transfer Object
    public function updateOneOrderData($pesanan_id, $dto)
    {
        try {
            $findOrderResponse = $this->findOneOrderData($pesanan_id);
            if($findOrderResponse->success) {
                foreach ($dto as $key => $value) {
                    // Set NOT NULL column with previous value
                    if($key == 'user_id' && is_null($value)) $dto[$key] = $findProductResponse->data->$key;
                }

                $updatedData = $this->orderRepository->updateByOneSpecificColumn('pesanan_id', $pesanan_id, $dto);
                return Response::baseResponseWithStatusCode(200, 'Data Pesanan Berhasil Diperbarui!', true, $updatedData);
            }

            return Response::baseResponseWithStatusCode($findOrderResponse->code, $findOrderResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Memperbarui Data Pesanan!');
        }
    }
    
    // Method for delete one order data by "pesanan_id" column
    public function deleteOneOrderData($pesanan_id)
    {
        try {
            $findOrderResponse = $this->findOneOrderData($pesanan_id);
            if($findOrderResponse) {
                $this->orderRepository->deleteByOneSpecificColumn('pesanan_id', $pesanan_id);
                return Response::baseResponseWithStatusCode(200, 'Data Pesanan Berhasil Dihapus!');
            }

            return Response::baseResponseWithStatusCode($findOrderResponse->code, $findOrderResponse->message);
        } catch (\Throwable $th) {
            return Response::baseResponseWithStatusCode(500, 'Terjadi Kesalahan Saat Menghapus Data Pesanan!');
        }
    }

    // Method for Mass Validating Product Order Data
    public function validateMassProductOrder($products)
    {
        // Group Products Data by "produk_id" column
        $arrayProducts = collect($products)->groupBy('produk_id')->map(function($item) {
            return $item->sum('jumlah');
        })->map(function($item, $key) {
            return [
                'produk_id' => $key,
                'jumlah' => $item
            ];
        })->values();

        foreach ($arrayProducts as $productOrder) {
            $productResponse = $this->productService->findOneProductData($productOrder['produk_id']);
            if($productResponse->success){
                $currentStock = !is_null($productResponse->data->detail) ? $productResponse->data->detail->stok : 0;
                if($productOrder['jumlah'] <= $currentStock){
                    return $products;
                }else{
                    throw new Exception('Stok Produk "'. $productOrder['produk_id'] .'" Tidak Mencukupi, Sisa: '. $currentStock, 1);
                    break;
                }
            }else{
                throw new Exception($productResponse->message);
                break;
            }
        }
    }
}