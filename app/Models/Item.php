<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'total_quantity',
        'item_description',
        'unit_price',
    ];

    // protected $with  = [
    //     'invoice_item',
    // ];

    // public function InvoiceItem(): HasMany
    // {
    //     return  $this->hasMany(InvoiceItem::class,'item_id','item_id');
    // }

    

}
