<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function scopeAuthorized($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopeDeposit($query)
    {
        return $query->where('transaction_type', 'deposit');
    }

    public function scopeWithdrawal($query)
    {
        return $query->where('transaction_type', 'withdrawal');
    }
}
