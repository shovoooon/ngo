<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanCollection;
use App\Models\Loan;
use App\Http\Requests\StoreLoanCollectionRequest;
use Inertia\Inertia;

class LoanCollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = LoanCollection::with(['loan.member', 'collector'])->latest('collection_date');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('loan.member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('loan', function ($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%");
            });
        }

        $collections = $query->paginate(15)->withQueryString();

        return Inertia::render('Collections/Index', [
            'collections' => $collections,
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        return Inertia::render('Collections/Create');
    }

    public function store(StoreLoanCollectionRequest $request)
    {
        $validated = $request->validated();
        $loan = Loan::findOrFail($validated['loan_id']);

        $validated['member_id'] = $loan->member_id;
        $validated['collected_by'] = auth()->id();

        LoanCollection::create($validated);

        // Check if fully paid
        $totalCollected = $loan->collections()->sum('amount');
        if ($totalCollected >= $loan->total_payable) {
            $loan->update(['status' => 'completed']);
        }

        return back()->with('success', 'Collection recorded successfully.');
    }

    public function destroy(LoanCollection $collection)
    {
        $loan = $collection->loan;
        $collection->delete();

        // If it was completed, recalculate
        if ($loan->status === 'completed') {
            $totalCollected = $loan->collections()->sum('amount');
            if ($totalCollected < $loan->total_payable) {
                $loan->update(['status' => 'active']);
            }
        }

        return back()->with('success', 'Collection record removed.');
    }
}
