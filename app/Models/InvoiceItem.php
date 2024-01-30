<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvoiceItem extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'invoice_id',
        'item_description',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $with  = [
        'invoice',
    ];

    public function invoice(): BelongsTo
    {
        return  $this->belongsTo(Invoice::class,'invoice_id','id');
    }

    
}
