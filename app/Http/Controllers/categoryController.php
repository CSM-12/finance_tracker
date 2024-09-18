<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories_data = category::where('user_id', '=', Auth::id())->get();

        return view('categories', compact('categories_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            category::create([
                'name' => $request->category_name,
                'budget' => $request->category_budget,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('category.index')->with('alert', 'Category Created Successfully!')->with('alertType', 'success');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('category.index')->with('alert', 'Something Went Wrong While Creating Category!')->with('alertType', 'danger');
            }
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
    public function update(Request $request, int $id)
    {
        try {
            $cat = category::find($id);
            $cat->update([
                'name' => $request->category_name,
                'budget' => $request->category_budget,
            ]);

            return redirect()->route('category.index')->with('alert', 'Category Updated Successfully!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('category.index')->with('alert', 'Something Went Wrong While Updating Category!')->with('alertType', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $cat = category::find($id);
            $cat->delete();

            return redirect()->route('category.index')->with('alert', 'Category Deleted Successfully!')->with('alertType', 'success');
        } catch (QueryException $e) {
            return redirect()->route('category.index')->with('alert', 'Something Went Wrong While Deleting Category!')->with('alertType', 'danger');
        }
    }
}
