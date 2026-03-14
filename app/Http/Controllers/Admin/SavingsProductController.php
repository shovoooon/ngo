<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SavingsProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/SavingsProducts/Index', [
            'products' => SavingsProduct::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/SavingsProducts/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'interest_calculation' => 'required|string',
            'minimum_balance' => 'required|numeric|min:0',
            'withdraw_limit' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        SavingsProduct::create($validated);

        return redirect()->route('savings-products.index')->with('success', 'Savings Product created successfully.');
    }

    public function edit(SavingsProduct $savingsProduct)
    {
        return Inertia::render('Admin/SavingsProducts/Edit', [
            'product' => $savingsProduct
        ]);
    }

    public function update(Request $request, SavingsProduct $savingsProduct)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'interest_calculation' => 'required|string',
            'minimum_balance' => 'required|numeric|min:0',
            'withdraw_limit' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $savingsProduct->update($validated);

        return redirect()->route('savings-products.index')->with('success', 'Savings Product updated successfully.');
    }

    public function destroy(SavingsProduct $savingsProduct)
    {
        $savingsProduct->delete();
        return redirect()->route('savings-products.index')->with('success', 'Savings Product deleted successfully.');
    }
}
