<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class check_low_stock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check_low_stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check low stock products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('checking low stock inventories');

        $suppliersLink = DB::table('inventory_transactions')
        ->where('transaction_type', '=', 'in')
        ->whereNotNull('supplier');

        $data = DB::table('inventories')->whereColumn('inventories.quantity', '<=', 'inventories.minimium_quantity')
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
                        return COMMAND::SUCCESS;
        }

        $result = $data->groupBy('cName')->map(function ($c){
            return [
                'country' => $c->first()->cName,
                'warehouses' => $c->groupBy('location')->map(function ($w){
                    return [
                        'warehouse_location' => $w->first()->location,
                        'inventory' => $w->map(callback: function ($i){
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
        
        foreach ($result as $countryData){
            echo "coutry: " . $countryData['country'];
            echo "\n";
            foreach ($countryData['warehouses'] as $warehouseData){
                echo "warehouse location: " .  $warehouseData['warehouse_location'];
                echo "\n";
                foreach ($warehouseData['inventory'] as $item){
                echo "product      " . $item['product'] . "  " . $item['sku'] . "  " . $item['current_quantity'] . "  " . $item['minimium_quantity'] . "  " . $item['supplier'] . "\n";
                }
            }
            echo "\n\n\n";
        }
        
        return COMMAND::SUCCESS;
    }
}
