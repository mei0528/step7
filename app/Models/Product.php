<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public  function getList(){
    $products = DB::table('products')
    ->join('companies','products.company_id','companies.id')
    ->select('products.*','companies.company_name')
    ->get();
    //->where('products.id','=',)
    //->first();

    return $products;
   }

   public function company()
   {
       return $this->belongsTo(Company::class, 'company_id');
   }

    
}

