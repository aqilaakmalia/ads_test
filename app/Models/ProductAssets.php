<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssets extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $table ='product_assets';
    protected $fillable =['product_id','image'];

    public function product_asset()
    {
        return $this->belongsTo('Products', 'product_id');
    }
}
