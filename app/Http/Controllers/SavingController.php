<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all Entries
        $savings_data = Saving::with('category:id,name')->orderBy('created_at', 'DESC')->get();
        $categories_data = category::all();

        // return $transactions_data;
        return view('Saving', compact('savings_data', 'categories_data'));
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
        // Insert Saving Goal Entry
        try{
            Saving::create([
                'title' => $request->saving_title,
                'description' => $request->saving_description,
                'amount'=> $request->saving_amount,
                'due_date' => $request->saving_due_date,
                'category_id' => $request->saving_category,
            ]);

            return redirect()->route('savings.index')->with('alert', 'Goal Created Successfully!')->with('alertType', 'success');
        }
        catch(QueryException $e){
            return redirect()->route('savings.index')->with('alert', 'Something Went Wrong While Creating Saving Goal!')->with('alertType', 'danger');
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
        // Update Saving Goal Data
        try {
            $saving = Saving::find($id);
            $saving->update([
                'title' => $request->saving_title,
                'description' => $request->saving_description,
                'amount' => $request->saving_amount,
                'due_date' => $request->saving_due_date,
                'category_id' => $request->saving_category,
            ]);

            return redirect()->route('savings.index')->with('alert', 'Saving Goal Updated Successfully!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('savings.index')->with('alert', 'Something Went Wrong While Updating Saving Goal!')->with('alertType', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete Saving Goal
        try {
            $transaction = Saving::find($id);
            $transaction->delete();

            return redirect()->route('savings.index')->with('alert', 'Saving Goal Deleted!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('savings.index')->with('alert', 'Something Went Wrong While Deleting Saving Goal!')->with('alertType', 'danger');
        }
    }
}
