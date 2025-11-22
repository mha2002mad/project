<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class warehouses extends Model
{
    use HasFactory;
    protected $table = 'warehouses';
    protected $fillable = ['location', 'name', 'country'];
    protected $primaryKey = 'warehouse_id';
    public $incrementing = true;
    public $timestamps = false;
    public function country()
    {
        return $this->belongsTo(countries::class, 'country', 'country_id');
    }

    public function inventories()
    {
        return $this->hasMany(inventories::class, 'warehouse', 'warehouse_id');
    }

    public function outBoundTransactions()
    {
        return $this->hasMany(inventoryTransactions::class, 'warehouse', 'warehouse_id')->where('transaction_type', 'out');
    }
    public function inBoundTransactions()
    {
        return $this->hasMany(inventoryTransactions::class, 'warehouse', 'warehouse_id')->where('transaction_type', 'in');
    }
    
    public function transactions()
    {
        return $this->hasMany(inventoryTransactions::class, 'warehouse', 'warehouse_id');
    }
}
