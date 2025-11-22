<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
    use HasFactory;
    protected $primaryKey = 'country_id';
    protected $table = 'countries';
    protected $fillable = ['name', 'code'];
    public $incrementing = true;
    public $timestamps = false;


    public function warehouses()
    {
        return $this->hasMany(warehouses::class, 'country', 'country_id');
    }
}
