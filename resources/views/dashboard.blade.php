@extends('layouts.app')

@section('css')
    <style>
        .float-right {
            float: right;
        }

        .text-right {
            text-align: right;
        }

        .ml-1 {
            margin-left: 1rem;
        }
    </style>
@endsection 


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Dashboard') }}
                        
                        <a href="{{ route('deposit.index') }}" class="float-right ml-1">
                            {{ __('Deposit List') }}
                        </a>

                        <a href="{{ route('withdrawal.index') }}" class="float-right ml-1">
                            {{ __('Withdrawal List') }}
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        

                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <h4 class="text-success">
                                            {{ __('Current Balance: ') }} {{ getCurrentBalance() }}
                                        </h4>
                                        <h3>{{ __('Transactions') }}</h3>

                                        
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col" class="text-center">Date</th>
                                                <th scope="col" class="text-center">Transction Type</th>
                                                <th scope="col" class="text-center">Amount</th>
                                                <th scope="col" class="text-center">Fee</th>
                                                <th scope="col" class="text-center">Balance</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $balance = 0 @endphp

                                            @forelse ($transactions as $transaction)
                                                @php 
                                                    $balance = getTransctionBalance($balance, $transaction);
                                                @endphp 
                                                <tr>
                                                    <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                                    <td class="text-center">{{ fdate($transaction->date, 'd, F, Y') }}</td>
                                                    <td class="text-center">{{ $transaction->transaction_type }}</td>
                                                    <td class="text-right">{{ $transaction->amount }}</td>
                                                    <td class="text-right">{{ $transaction->fee }}</td>
                                                    <td class="text-right">{{ $balance }}</td>
                                                </tr>
                                            @empty 
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <h5>
                                                            <strong class="text-danger">{{ __('No records found') }}</strong>
                                                        </h5>
                                                    </td>
                                                </tr>
                                            @endforelse 
                                        </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
