@extends('masterLayout')

{{-- Page Title --}}
@section('page-title')
    Transactions
@endsection

{{-- Page Style --}}
@section('page-style')
    <style>

    </style>
@endsection

{{-- Page Content --}}
@section('page-content')

    {{-- Alert Section --}}
    @if (session('alert'))
        <div class="alert alert-{{ session('alertType') }} alert-dismissible fade show" role="alert">
            {{ session('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="w-100 d-flex justify-content-center align-items-center flex-column">

        <div class="p-2 my-1 max-700 bg-light rounded shadow">
            <h1>Transactions</h1>
            <hr>

            {{-- Transaction Form --}}
            <h3>Add Transaction</h3>

            <form action="{{ route('transaction.store') }}" method="POST">
                @csrf

                <input type="text" name="transaction_title" class="form-control mb-2" placeholder="Transaction Title">
                <input type="number" name="transaction_amount" class="form-control mb-2" placeholder="Transaction Amount">

                <select class="form-select mb-2" name="transaction_category">
                    @foreach ($categories_data as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select class="form-select mb-2" name="transaction_type">
                    <option value="out" selected>Spends</option>
                    <option value="in">Income</option>
                </select>

                <input type="datetime-local" name="transaction_date" class="form-control mb-2">

                <input type="submit" class="btn btn-primary">
            </form>
        </div>


        {{-- All Transactions --}}
        <div class="p-2 my-1 max-700 bg-light rounded shadow">

            {{-- DataTables class --}}
            @if ($transactions_data->count() > 0)
                <?php $dataTable_class = 'dataTable' ?>
            @else
                <?php $dataTable_class = '' ?>
            @endif

            {{-- Transactions Table --}}
            <table class="w-100 table table-bordered rounded {{$dataTable_class}}">
                <thead>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Actions</th>
                    <th>Date</th>
                </thead>

                <tbody>
                    @if ($transactions_data->count() > 0)
                        @foreach ($transactions_data as $transaction)
                            {{-- Show Transaction Color --}}
                            @if ($transaction->type == 'out')
                                @php
                                    $tableClass = 'table-danger';
                                @endphp
                            @else
                                @php
                                    $tableClass = 'table-success';
                                @endphp
                            @endif
                            <tr class="{{$tableClass}}">
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->title }}</td>
                                <td>
                                    Rs.{{ $transaction->amount }}
                                </td>
                                <td>{{ $transaction->category->name }}</td>

                                {{-- Delete & Upadte --}}
                                <td>

                                     {{-- Delete Button --}}
                                     <form action="{{ route('transaction.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <input type="submit" class="btn btn-danger mb-1" value="Delete">
                                    </form>

                                    {{-- Update Button --}}
                                    <button class="btn btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#updateTransactionModal{{ $transaction->id }}">Update</button>

                                </td>

                                <td>{{ $transaction->created_at }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <center>No Transactions Yet!</center>
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>



    {{-- Update Transaction Modal --}}
    <!-- Modal -->

    @isset($transactions_data)
        @foreach ($transactions_data as $transaction)
            <div class="modal fade" id="updateTransactionModal{{ $transaction->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Transaction</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            {{-- Update Transaction Form --}}
                            <form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="text" name="transaction_title" class="form-control mb-1 rounded" value="{{ $transaction->title }}">
                                <input type="number" name="transaction_amount" class="form-control mb-1 rounded" value="{{ $transaction->amount }}">

                                <select name="transaction_category" class="form-select mb-2">
                                    @foreach ($categories_data as $category)

                                        {{-- Select Category --}}
                                        @if ($transaction->category == $category->name)
                                            {{$attr = 'selected'}}
                                        @else
                                            {{$attr = ''}}
                                        @endif

                                        <option value="{{$category->id}}" {{$attr}}>{{$category->name}}</option>
                                    @endforeach
                                </select>

                                {{-- Transaction Type Select --}}
                                <select name="transaction_type" class="form-select mb-1">
                                    @if ($transaction->type == 'out')
                                        {{$out = 'selected', $in = ''}}
                                    @else{
                                        {{$in = 'selected', $out = ''}}
                                    }
                                    @endif
                                    <option {{$out}} value="out">Spend</option>
                                    <option {{$in}} value="in">Income</option>
                                </select>

                                {{-- Transaction Date --}}
                                <input type="datetime-local" name="transaction_date" class="form-control mb-2" value="{{$transaction->created_at}}">
                                <br>

                                <input type="submit" class="btn btn-success">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endisset


@endsection
