<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Http\Requests\WithdrawalRequest;

class WithdrawalController extends Controller
{

    /**
     * Show the transaction deposit list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transaction::authorized()->withdrawal()->paginate(100);
        
        return view('transactions.withdrawal.index', compact('transactions'));
    }




    /**
     * Show the transaction deposit.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('transactions.withdrawal.create');
    }




    public function store(WithdrawalRequest $request)
    {
        try {

            $currentBalance = getCurrentBalance();

            if($request->amount > $currentBalance) {
                return redirect()->back()->withError('You do not have sufficient balance to make this transaction');
            }

            DB::transaction(function() use($request) {
                $transactionService = new TransactionService;
                $transactionService->saveWithdrawal($request);
                $transactionService->updateUserBalance();
            });
                
            return redirect()->route('withdrawal.create')->with('success', number_format($request->amount)  . ' Amount successfully withdrawn.');

        } catch (Exception $e) {
            return redirect()->route('withdrawal.create')->with('error', $e->getMessage());
        }
    }
}
