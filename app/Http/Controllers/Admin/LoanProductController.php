<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/LoanProducts/Index', [
            'products' => LoanProduct::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/LoanProducts/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'interest_type' => 'required|string',
            'duration_weeks' => 'required|integer|min:1',
            'installment_frequency' => 'required|string',
            'processing_fee' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        LoanProduct::create($validated);

        return redirect()->route('loan-products.index')->with('success', 'Loan Product created successfully.');
    }

    public function edit(LoanProduct $loanProduct)
    {
        return Inertia::render('Admin/LoanProducts/Edit', [
            'product' => $loanProduct
        ]);
    }

    public function update(Request $request, LoanProduct $loanProduct)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'interest_type' => 'required|string',
            'duration_weeks' => 'required|integer|min:1',
            'installment_frequency' => 'required|string',
            'processing_fee' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        $loanProduct->update($validated);

        return redirect()->route('loan-products.index')->with('success', 'Loan Product updated successfully.');
    }

    public function destroy(LoanProduct $loanProduct)
    {
        // Add check if product is in use if needed
        $loanProduct->delete();
        return redirect()->route('loan-products.index')->with('success', 'Loan Product deleted successfully.');
    }
}
