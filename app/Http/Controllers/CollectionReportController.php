<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanCollection;
use App\Models\Member;
use App\Models\User;
use App\Models\Loan;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CollectionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LoanCollection::with(['loan', 'member', 'collector'])->latest('collection_date');

        if ($request->filled('date_from')) {
            $query->whereDate('collection_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('collection_date', '<=', $request->date_to);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        if ($request->filled('collector_id')) {
            $query->where('collected_by', $request->collector_id);
        }

        // Handle CSV Export
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($query);
        }

        $collections = $query->paginate(15)->withQueryString();

        // Load dropdown data for frontend filters
        $members = Member::select('id', 'name', 'member_code')->orderBy('name')->get();
        $collectors = User::select('id', 'name')->orderBy('name')->get();
        // optionally load loans if requested, but usually member filters are enough. Let's send loans too.
        $loans = Loan::select('id', 'loan_number')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Collections/Report', [
            'collections' => $collections,
            'filters' => $request->only(['date_from', 'date_to', 'member_id', 'loan_id', 'collector_id']),
            'dropdowns' => [
                'members' => $members,
                'collectors' => $collectors,
                'loans' => $loans
            ]
        ]);
    }

    private function exportCsv($query)
    {
        $fileName = 'collections_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Date', 'Member Name', 'Member Code', 'Loan Number', 'Amount Collected', 'Collected By', 'Note']);

            // Process deep chunks safely
            $query->chunk(500, function ($collections) use ($file) {
                foreach ($collections as $collection) {
                    fputcsv($file, [
                        $collection->id,
                        $collection->collection_date,
                        $collection->member?->name,
                        $collection->member?->member_code,
                        $collection->loan?->loan_number,
                        $collection->amount,
                        $collection->collector?->name,
                        $collection->note
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
