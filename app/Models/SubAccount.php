<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
        'type'
    ];

    public function Account(){
        return $this->hasOne(Account::class);
    }
}
