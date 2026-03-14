<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Member;
use App\Models\LoanProduct;
use App\Http\Resources\LoanResource;
use App\Http\Resources\MemberResource;
use App\Services\LoanService;
use App\Http\Requests\StoreLoanRequest;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('member')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('loan_number', 'like', "%{$search}%");
        }

        $loans = $query->paginate(10)->withQueryString();

        return Inertia::render('Loans/Index', [
            'loans' => LoanResource::collection($loans),
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        $members = Member::select('id', 'name', 'member_code', 'phone')->get();
        $products = LoanProduct::where('status', 'active')->get();

        return Inertia::render('Loans/Create', [
            'members' => $members,
            'products' => $products
        ]);
    }

    public function store(StoreLoanRequest $request, LoanService $loanService)
    {
        try {
            $loanService->createLoan($request->validated());
            return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating loan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Loan $loan)
    {
        $loan->loadMissing([
            'member',
            'collections' => function ($query) {
                $query->with('collector')->orderBy('collection_date', 'desc');
            }
        ]);

        return Inertia::render('Loans/Show', [
            'loan' => (new LoanResource($loan))->resolve()
        ]);
    }
}
