<?php 

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function saveDiposit($data)
    {
        $transactionId = $data->id;

        Transaction::updateOrCreate([
            'id' => $transactionId
        ], [
            'user_id'          => auth()->id(),
            'amount'           => $data->amount,
            'date'             => date('Y-m-d'),
            'transaction_type' => 'deposit',
        ]);
    }



    public function saveWithdrawal($data)
    {
        $transactionId = $data->id;
        $currentBalance = getCurrentBalance();

        $withdrawalFee = $this->calculateWithdrawalFee($data);

        $totalDeductedAmount = $withdrawalFee + $data->amount;


        if($totalDeductedAmount > $currentBalance) {
            return redirect()->back()->withError('You do not have sufficient balance with charge to make this transaction');
        }

        Transaction::updateOrCreate([
            'id' => $transactionId
        ], [
            'user_id'          => auth()->id(),
            'amount'           => $data->amount,
            'fee'              => $withdrawalFee,
            'date'             => date('Y-m-d'),
            'transaction_type' => 'withdrawal',
        ]);
    }


    private function calculateWithdrawalFee($data)
    {
        $month = date('Y-m');

        // Fetch the total withdrawal amount for the user
        // $totalWithdrawalAmount = Transaction::authorized()->withdrawal()->sum('amount');
        $withdrawalAmount = $feeableAmount = $data->amount;


        // Define withdrawal rates based on user account type
        $withdrawalRate = isIndividualAccount() ? 0.015 : 0.025;

        $totalWithdrawalThisMonth = Transaction::authorized()->withdrawal()->where('date', 'like', $month . '%')->sum('amount');

        // Apply free withdrawal conditions // Friday withdrawals are free
        $freeWithdrawalLimit = 1000;
        if(isIndividualAccount()) {
                
            if (date('l') == 'Friday' || $withdrawalAmount <= $freeWithdrawalLimit) {
                $withdrawalRate = 0;
            } else {

                $monthlyFreeWithdrawalLimit = 5000;
                $totalWithdrawalAmount = $withdrawalAmount;
                $totalWithdrawalAmount -= 1000;
                $totalWithdrawalAmount += $totalWithdrawalThisMonth;


                if($totalWithdrawalAmount < $monthlyFreeWithdrawalLimit) {
                    $withdrawalRate = 0;
                } else {
                    $feeableAmount = $withdrawalAmount - 1000;
                    if($totalWithdrawalThisMonth < $monthlyFreeWithdrawalLimit) {
                        $feeableAmount -= $totalWithdrawalThisMonth;
                    }
                }
            }

        } else {
            // Decrease the withdrawal fee to 0.015% for Business accounts after a total withdrawal of 50K
            if (isBusinessAccount() && $totalWithdrawalThisMonth > 50000) {
                $withdrawalRate = 0.015;
            }
        }

        $feeAmount = $feeableAmount * $withdrawalRate;

        return $feeAmount;
    }


    public function updateUserBalance()
    {
        $totalDipositAmount    = Transaction::authorized()->deposit()->sum('amount');
        $totalWithdrawalAmount = Transaction::authorized()
                                ->withdrawal()
                                ->sum(DB::raw('amount + fee'));

        User::where('id', auth()->id())->update(['balance' => ($totalDipositAmount - $totalWithdrawalAmount)]);
    }
}