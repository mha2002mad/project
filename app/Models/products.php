<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['name', 'sku', 'description', 'status', 'price'];
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    public function inventories()
    {
        return $this->hasMany(inventories::class, 'product', 'product_id');
    }
}
