<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'customer_id',
        'issue_date',
        'due_date',
        'total_amount',
    ];

    public function items(): HasMany
    {
        return  $this->hasMany(InvoiceItem::class,'invoice_id','id');
    }

    public function user(): BelongsTo
    { 
        return $this->belongsTo(User::class,'customer_id','id');
    }
}
