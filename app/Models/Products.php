<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    // definiendo la relacion entre modelos
    public function inventoryTransactions(){
        return $this->hasMany(Inventory_transactions::class);
    }
    public function categories(){
        return $this->belongsTo(Category::class, 'id');
    }
}
