<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// include models
use App\Models\Transaction;
use App\Models\category;
use App\Models\Saving;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sum of transactions
        $total_balance = Transaction::where('user_id', '=', Auth::id())->sum('amount');

        // Account Balance
        $account_balance = $total_balance;

        // Latest 5 Transaction
        $latest_transactions_data = Transaction::where('user_id', '=', Auth::id())->with('category:id,name')->orderBy('created_at', 'DESC')->limit(5)->get();

        // daily sum of trsanaction
        $dailySums = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) - 1 as month, DAY(created_at) as day, SUM(amount) as total_amount')
            ->where('user_id', '=', Auth::id())
            ->whereYear('updated_at', date('Y'))
            ->groupBy('year', 'month', 'day')
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc')
            ->get();

        $maxDailySum = $dailySums->max('total_amount');
        $minDailySum = $dailySums->min('total_amount');

        // Spends Per Category
        $category_expences = Transaction::where('transactions.user_id', '=', Auth::id())
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, ABS(SUM(transactions.amount)) as total_amount')
            ->whereYear('transactions.updated_at', date('Y'))
            ->where('type' , '=', 'out')
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'desc')
            ->get(); 

        // Income Source
        $category_incomes = Transaction::where('transactions.user_id', '=', Auth::id())
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(transactions.amount) as total_amount')
            ->whereYear('transactions.updated_at', date('Y'))
            ->where('type' , '=', 'in')
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'desc')
            ->get(); 

        // Monthly Income Vs Expence
        $monthlyIEs = Transaction::where('transactions.user_id', '=', Auth::id())
            ->selectRaw('MONTH(updated_at) as month, SUM(CASE WHEN type = "in" THEN amount ELSE 0 END) as income,
        ABS(SUM(CASE WHEN type = "out" THEN amount ELSE 0 END)) as expence')
            ->whereYear('updated_at', date('Y'))
            ->groupBy('month')
            ->get();

        // Monthly Category Budget
        $budgets = Transaction::where('transactions.user_id', '=', Auth::id())
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, ABS(SUM(transactions.amount)) as total_amount, MAX(categories.budget) as budget')
            ->whereMonth('transactions.updated_at', "=", date('m'))
            ->where('type', '=', 'out')
            ->whereNotNull('categories.budget')
            ->where('categories.budget', '!=', 0)
            ->groupBy('categories.name')
            ->get()
            ->map(function ($item) {
                $percentage = ($item->total_amount / $item->budget) * 100;
                $item->percentage = $percentage > 100 ? 100 : $percentage;
                return $item;
            });

        // return compact('budgets');
        // return compact('account_balance', 'latest_transactions_data', 'dailySums', 'category_expences', 'monthlyIEs');
        return view('dashboard', compact('account_balance', 'latest_transactions_data', 'dailySums', 'minDailySum', 'maxDailySum', 'category_expences', 'category_incomes', 'monthlyIEs', 'budgets'));
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
