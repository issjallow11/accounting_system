<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_type',
        'date',
        'receipt_no',
        'customer_name',
        'payment',
        'payment_date'
    ];
}
