<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suppliers extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = ['name', 'contact_info', 'address'];
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    public function inventoryTransactions()
    {
        return $this->hasMany(inventoryTransactions::class, 'supplier', 'supplier_id');
    }
    
}
