<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
    ];
    public function SubAccount(){
        return $this->hasMany(SubAccount::class);
    }
    public function Transactions(){
        return $this->hasMany(Credit::class);
    }
}
