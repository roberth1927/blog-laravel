<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Seller;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
 use SoftDeletes;

    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $dates = ['deleted_at'];
    protected $fillable = [
    'name',
    'description',
    'quantity',
    'status',
    'image',
    'sller_id'
   ];
  
    public function estaDisponible (){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }
    
        
    public function seller(){
        return $this->belongsTo(Seller::class);
    }
   
   
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
   
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

}
