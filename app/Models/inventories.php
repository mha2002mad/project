<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventories extends Model
{
    use HasFactory;
    protected $table = 'inventories';
    protected $fillable = ['product', 'warehouse', 'quantity', 'minimium_quantity'];
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;
    public function product()
    {
        return $this->belongsTo(products::class, 'product', 'product_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(warehouses::class, 'warehouse', 'warehouse_id');
    }
}
