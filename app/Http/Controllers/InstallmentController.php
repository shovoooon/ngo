<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Http\Resources\InstallmentResource;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Http\Requests\StoreCollectionRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Installment::with(['loan.member'])
            ->where('status', 'pending');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('loan.member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('due_before')) {
            $query->whereDate('due_date', '<=', $request->input('due_before'));
        }

        $installments = $query->orderBy('due_date', 'asc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Installments/Index', [
            'installments' => InstallmentResource::collection($installments),
            'filters' => $request->only(['search', 'status', 'due_before'])
        ]);
    }

    public function update(StoreCollectionRequest $request, Installment $installment, PaymentService $paymentService)
    {
        try {
            DB::transaction(function () use ($request, $installment, $paymentService) {
                // Lock the installment for update to prevent double payment race conditions
                $lockedInstallment = Installment::where('id', $installment->id)->lockForUpdate()->firstOrFail();
                $paymentService->recordPayment($lockedInstallment, $request->validated());
            });

            return back()->with('success', 'Installment payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }
}
