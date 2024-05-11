@extends('layouts.app')

@section('css')
    <style>
        .float-right {
            float: right;
        }

        .text-right {
            text-align: right;
        }
    </style>
@endsection 


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Withdrawal') }}
                        <a href="{{ route('withdrawal.create') }}" class="btn btn-sm btn-primary float-right">
                            {{ __('Add Withdrawal') }}
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
                                        
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col" class="text-center">Date</th>
                                                <th scope="col" class="text-center">Amount</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($transactions as $transaction)
                                                <tr>
                                                    <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                                    <td class="text-center">{{ fdate($transaction->date, 'd, F, Y') }}</td>
                                                    <td class="text-right">{{ $transaction->amount }}</td>
                                                </tr>
                                            @empty 
                                                <tr>
                                                    <td colspan="3" class="text-center">
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
