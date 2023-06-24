<?php

namespace App\Providers;

use App\Services\User\UserService;
use App\Services\Order\OrderService;
use Illuminate\Support\ServiceProvider;
use App\Services\Product\ProductService;
use App\Repositories\User\UserRepository;
use App\Repositories\Order\OrderRepository;
use App\Services\User\UserServiceImplement;
use App\Services\Order\OrderServiceImplement;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductServiceImplement;
use App\Repositories\User\UserRepositoryImplement;
use App\Services\ProductOrder\ProductOrderService;
use App\Services\ProductStock\ProductStockService;
use App\Repositories\Order\OrderRepositoryImplement;
use App\Repositories\Product\ProductRepositoryImplement;
use App\Repositories\ProductOrder\ProductOrderRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Services\ProductOrder\ProductOrderServiceImplement;
use App\Services\ProductStock\ProductStockServiceImplement;
use App\Repositories\ProductOrder\ProductOrderRepositoryImplement;
use App\Repositories\ProductStock\ProductStockRepositoryImplement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services Binding
        $this->app->bind(UserService::class, UserServiceImplement::class);
        $this->app->bind(ProductService::class, ProductServiceImplement::class);
        $this->app->bind(ProductStockService::class, ProductStockServiceImplement::class);
        $this->app->bind(OrderService::class, OrderServiceImplement::class);
        $this->app->bind(ProductOrderService::class, ProductOrderServiceImplement::class);
        
        // Repositories Binding
        $this->app->bind(UserRepository::class, UserRepositoryImplement::class);
        $this->app->bind(ProductRepository::class, ProductRepositoryImplement::class);
        $this->app->bind(ProductStockRepository::class, ProductStockRepositoryImplement::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryImplement::class);
        $this->app->bind(ProductOrderRepository::class, ProductOrderRepositoryImplement::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
