<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TransactionService;
use App\Http\Requests\DepositRequest;

class DepositController extends Controller
{

    /**
     * Show the transaction deposit list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transaction::authorized()->deposit()->paginate(100);
        
        return view('transactions.deposit.index', compact('transactions'));
    }




    /**
     * Show the transaction deposit.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('transactions.deposit.create');
    }




    public function store(DepositRequest $request)
    {
        try {

            DB::transaction(function() use($request) {
                $transactionService = new TransactionService;
                $transactionService->saveDiposit($request);
                $transactionService->updateUserBalance();
            });
                
            return redirect()->route('deposit.create')->with('success', number_format($request->amount)  . ' Amount successfully diposited.');

        } catch (Exception $e) {
            return redirect()->route('deposit.create')->with('error', $e->getMessage());
        }
    }
}
