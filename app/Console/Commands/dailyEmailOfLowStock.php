<?php

namespace App\Console\Commands;

use App\Mail\dailyLowStockProducts;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Mail;

class dailyEmailOfLowStock extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily-email-of-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks low stock daily and sends them via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $suppliersLink = FacadesDB::table('inventory_transactions')
        ->where('transaction_type', '=', 'in')
        ->whereNotNull('supplier');

        $data = facadesDB::table('inventories')->whereColumn('inventories.quantity', '<=', 'inventories.minimium_quantity')
                ->join('products', 'products.product_id', '=', 'inventories.product')
                ->join('warehouses', 'warehouse_id', '=', 'inventories.warehouse')
                ->join('countries', 'country_id', '=', 'warehouses.country')
                ->joinSub($suppliersLink, 'SL', function ($join){
                    $join->on('products.product_id', '=', 'SL.product');
                })
                ->join('suppliers', 'SL.supplier', '=', 'suppliers.supplier_id')
                ->select([
                    'countries.name as cName',
                    'warehouses.location',
                    'products.name as pName',
                    'products.sku',
                    'inventories.quantity',
                    'inventories.minimium_quantity',
                    'suppliers.contact_info'
                    ])
                    ->get();
                    
        if ($data->isEmpty()) {
                $this->info('no low stock inventories at moment');
                COMMAND::SUCCESS;
            }
            
        $result = $data->groupBy('cName')->map(function ($c){
            return [
                'country' => $c->first()->cName,
                'warehouses' => $c->groupBy('location')->map(function ($w){
                    return [
                        'warehouse_location' => $w->first()->location,
                        'inventory' => $w->map(function ($i){
                            return [
                                'product' => $i->pName,
                                'sku' => $i->sku,
                                'current_quantity' => $i->quantity,
                                'minimium_quantity' => $i->minimium_quantity,
                                'supplier' => $i->contact_info
                            ];
                        })->values()
                    ];
                })->values()
            ];
        })->values();
        
        Mail::to(env('LOW_STOCK_REPORT_EMAIL'))->send(new dailyLowStockProducts($result));
    }
}
