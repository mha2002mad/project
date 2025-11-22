<?php

namespace App\Providers;

use App\Repositories\Interfaces\countriesInterface;
use App\Repositories\Interfaces\InventoryInterface;
use App\Repositories\Interfaces\inventoryTransactionsInterface;
use App\Repositories\Interfaces\productsInterface;
use App\Repositories\Interfaces\suppliersInterface;
use App\Repositories\Interfaces\usersInterface;
use App\Repositories\Interfaces\warehousesInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\interfaces\CountryServicesInterface;
use App\Services\interfaces\inventoryMovementsServiceInterface;
use App\Services\interfaces\inventoryServicesInterface;
use App\Services\interfaces\inventoryTransactionsServiceInterface;
use App\Services\interfaces\InventoryViewInterface;
use App\Services\interfaces\productsServiceInterface;
use App\Services\interfaces\suppliersServiceInterface;
use App\Services\interfaces\UsersServiceInterface;
use App\Services\interfaces\WarehousesServiceInterface;
use App\Services\InventoryViewService;
use App\Services\UsersService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $repositories = [
            usersInterface::class => UserRepository::class,
            countriesInterface::class => \App\Repositories\CountryRepository::class,
            warehousesInterface::class => \App\Repositories\warehouseRepository::class,
            productsInterface::class => \App\Repositories\productsRepository::class,
            suppliersInterface::class => \App\Repositories\suppliersRepository::class,
            InventoryInterface::class => \App\Repositories\inventoryRepository::class,
            inventoryTransactionsInterface::class => \App\Repositories\inventoryTransactionsRepository::class,
        ];

        $Services = [
            UsersServiceInterface::class => UsersService::class,
            CountryServicesInterface::class => \App\Services\CountriesServices::class,
            inventoryServicesInterface::class => \App\Services\inventoryServices::class,
            productsServiceInterface::class => \App\Services\ProductsService::class,
            inventoryTransactionsServiceInterface::class => \App\Services\inventoryTransactionsServices::class,
            suppliersServiceInterface::class => \App\Services\suppliersService::class,
            WarehousesServiceInterface::class => \App\Services\warehousesService::class,
            inventoryMovementsServiceInterface::class => \App\Services\inventoryMovementService::class,
            InventoryViewInterface::class => InventoryViewService::class
        ];

        foreach ($repositories as $interface => $class) {
            $this->app->bind($interface, $class);
        }
        foreach ($Services as $interface => $class) {
            $this->app->bind($interface, $class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
