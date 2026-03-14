<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Collection;
use App\Models\SavingsAccount;
use App\Http\Resources\LoanResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\SavingsAccountResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function loans(Request $request)
    {
        $query = Loan::with('member')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportLoansCsv($query->get());
        }

        return Inertia::render('Reports/Loans', [
            'loans' => LoanResource::collection($query->paginate(20)->withQueryString()),
            'filters' => $request->only('start_date', 'end_date')
        ]);
    }

    public function collections(Request $request)
    {
        $query = Collection::with(['member', 'loan'])->latest('collected_at');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('collected_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCollectionsCsv($query->get());
        }

        return Inertia::render('Reports/Collections', [
            'collections' => CollectionResource::collection($query->paginate(20)->withQueryString()),
            'filters' => $request->only('start_date', 'end_date')
        ]);
    }

    public function savings(Request $request)
    {
        $query = SavingsAccount::with('member');

        $members = $query->get()->filter(function ($account) {
            return $account->balance > 0;
        });

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportSavingsCsv($members);
        }

        return Inertia::render('Reports/Savings', [
            'accounts' => SavingsAccountResource::collection($query->paginate(20)->withQueryString())
        ]);
    }

    private function exportLoansCsv($loans)
    {
        $response = new StreamedResponse(function () use ($loans) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Loan Number', 'Member Name', 'Member Phone', 'Amount', 'Target Interest %', 'Total Payable', 'Status', 'Start Date']);

            foreach ($loans as $loan) {
                fputcsv($handle, [
                    $loan->loan_number,
                    $loan->member->name ?? 'N/A',
                    $loan->member->phone ?? 'N/A',
                    $loan->loan_amount,
                    $loan->interest_rate,
                    $loan->total_payable,
                    $loan->status,
                    $loan->start_date
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="loans_report_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    private function exportCollectionsCsv($collections)
    {
        $response = new StreamedResponse(function () use ($collections) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Member Name', 'Loan Number', 'Amount Collected', 'Collected By', 'Date']);

            foreach ($collections as $collection) {
                fputcsv($handle, [
                    $collection->member->name ?? 'N/A',
                    $collection->loan->loan_number ?? 'N/A',
                    $collection->amount,
                    $collection->collected_by,
                    $collection->collected_at
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="collections_report_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    private function exportSavingsCsv($accounts)
    {
        $response = new StreamedResponse(function () use ($accounts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Member ID', 'Member Name', 'Phone', 'Total Savings Balance']);

            foreach ($accounts as $account) {
                fputcsv($handle, [
                    $account->member->member_code ?? $account->member_id,
                    $account->member->name ?? 'N/A',
                    $account->member->phone ?? 'N/A',
                    $account->balance
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="savings_report_' . date('Y-m-d') . '.csv"');

        return $response;
    }
}
