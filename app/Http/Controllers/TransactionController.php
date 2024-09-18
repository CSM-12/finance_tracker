<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // View All Transactions
        $transactions_data = Transaction::with('category:id,name')->orderBy('created_at', 'DESC')->where('user_id', '=', Auth::id())->get();
        $categories_data = category::where('user_id', '=', Auth::id())->get();

        // return $transactions_data;
        return view('Transaction', compact('transactions_data', 'categories_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Insert Transactions
        try{
            if($request->transaction_type == 'out'){
                $transaction_amount = -($request->transaction_amount);
            }
            else{
                $transaction_amount = $request->transaction_amount;
            }

            $transaction_date = $request->transaction_date ? $request->transaction_date : date('Y-m-d H:i:s');

            Transaction::create([
                'title' => $request->transaction_title,
                'category_id' => $request->transaction_category,
                'type' => $request->transaction_type,
                'amount'=> $transaction_amount,
                'created_at'=> $transaction_date,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('transaction.index')->with('alert', 'Transaction Created Successfully!')->with('alertType', 'success');
        }
        catch(QueryException $e){
            return redirect()->route('transaction.index')->with('alert', 'Something Went Wrong While Creating Transaction!')->with('alertType', 'danger');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update transaction Details
        try {
            if($request->transaction_type == 'out'){
                $transaction_amount = -($request->transaction_amount);
            }
            else{
                $transaction_amount = $request->transaction_amount;
            }

            $transaction = Transaction::find($id)->where('user_id', '=', Auth::id());
            $transaction->update([
                'title' => $request->transaction_title,
                'amount' => $transaction_amount,
                'category_id' => $request->transaction_category,
                'type' => $request->transaction_type,
                'created_at' => $request->transaction_date
            ]);

            return redirect()->route('transaction.index')->with('alert', 'Transaction Updated Successfully!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('transaction.index')->with('alert', 'Something Went Wrong While Updating Transaction!')->with('alertType', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete Transaction
        try {
            $transaction = Transaction::find($id)->where('user_id', '=', Auth::id());
            $transaction->delete();

            return redirect()->route('transaction.index')->with('alert', 'Transaction Deleted!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('transaction.index')->with('alert', 'Something Went Wrong While Deleting Transaction!')->with('alertType', 'danger');
        }
    }
}
