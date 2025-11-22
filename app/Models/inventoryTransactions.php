<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventoryTransactions extends Model
{
    use HasFactory;
    protected $table = 'inventory_transactions';
    protected $fillable = ['product', 'warehouse', 'supplier', 'quantity', 'transaction_type', 'transaction_date', 'created_by'];
    protected $primaryKey = 'inventory_transation_id';
    public $timestamps = false;
    public function product()
    {
        return $this->belongsTo(products::class, 'product', 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(warehouses::class, 'warehouse', 'warehouse_id');
    }

    public function supplier()
    {
        return $this->belongsTo(suppliers::class, 'supplier', 'supplier_id');
    }
}
