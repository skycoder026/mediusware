<?php 


/*
|--------------------------------------------------------------------------
| GET ACCOUNT TYPE LIST
|--------------------------------------------------------------------------
*/

use Carbon\Carbon;
use App\Models\User;

function getAccountTypes()
{
    return ['Individual', 'Business'];
}



/*
|--------------------------------------------------------------------------
| GET CURRENT BALANCE
|--------------------------------------------------------------------------
*/
function getCurrentBalance()
{
    $user = User::where('id', auth()->id())->first();

    return $user->balance ?? 0;
}

function isIndividualAccount()
{
    return auth()->user()->account_type == 'Individual';
}

function isBusinessAccount()
{
    return auth()->user()->account_type == 'Business';
}

/*
|--------------------------------------------------------------------------
| GET TRANSACTION BALANCE
|--------------------------------------------------------------------------
*/
function getTransctionBalance($balance, $transaction)
{
    if($transaction->transaction_type == 'deposit') {
        $balance += $transaction->amount;
    } else {
        $balance += ($transaction->amount + $transaction->fee);
    }

    return $balance ?? 0;
}



/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/
function fdate($date, $format = 'Y-m-d')
{
    if(strtotime($date)) {
        return Carbon::parse($date)->format($format);
    }

    return $date;
}