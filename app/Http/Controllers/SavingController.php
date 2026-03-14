<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\SavingsAccount;
use App\Http\Resources\MemberResource;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use App\Services\SavingService;
use App\Http\Requests\StoreSavingTransactionRequest;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('savingsAccounts');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('member_code', 'like', "%{$search}%");
        }

        $members = $query->paginate(15)->withQueryString();

        return Inertia::render('Savings/Index', [
            'members' => MemberResource::collection($members),
            'filters' => $request->only('search')
        ]);
    }

    public function store(StoreSavingTransactionRequest $request, SavingService $savingService)
    {
        try {
            $member = Member::findOrFail($request->member_id);
            $savingService->processTransaction(
                $member,
                $request->type,
                (float) $request->amount,
                $request->note
            );

            return back()->with('success', ucfirst($request->type) . ' recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Transaction Failed: ' . $e->getMessage());
        }
    }

    public function show(Member $saving) // the route parameter will be 'saving' acting as member id
    {
        $saving->load([
            'savingsAccounts',
            'transactions' => function ($query) {
                $query->latest();
            }
        ]);

        return Inertia::render('Savings/Show', [
            'member' => new MemberResource($saving)
        ]);
    }

    public function downloadStatement(Member $member)
    {
        $member->load(['savingsAccounts']);
        $transactions = $member->transactions()->oldest()->get();

        $pdf = Pdf::loadView('pdf.savings_statement', [
            'member' => $member,
            'transactions' => $transactions
        ]);

        return $pdf->download("Statement_{$member->member_code}.pdf");
    }
}
