<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\Collection;
use App\Models\Installment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Members
        $totalMembers = Member::count();

        // 2. Active Loans Count
        $activeLoansCount = Loan::where('status', 'active')->count();

        // 3. Total Loan Amount (Principal of active loans)
        $totalLoanAmount = Loan::where('status', 'active')->sum('loan_amount');

        // 4. Total Savings
        $totalSavings = SavingsAccount::sum('balance');

        // 5. Today's Collections
        $todaysCollections = Collection::whereDate('collected_at', Carbon::today())->sum('amount');

        // 6. Outstanding Loans Balance (Total Payable - Amount Paid on Active Loans)
        // Amount Paid = sum of collections tied to active loans
        $paidOnActiveLoans = Collection::whereHas('loan', function ($q) {
            $q->where('status', 'active');
        })->sum('amount');

        $totalPayableActive = Loan::where('status', 'active')->sum('total_payable');
        $outstandingLoansBalance = $totalPayableActive - $paidOnActiveLoans;

        // 7. Graph Data: Last 7 Days Collections
        $last7Days = collect(range(6, 0))->map(function ($days) {
            return Carbon::today()->subDays($days);
        });

        $chartLabels = [];
        $chartData = [];

        foreach ($last7Days as $day) {
            $chartLabels[] = $day->format('M d');
            $chartData[] = Collection::whereDate('collected_at', $day)->sum('amount');
        }

        return Inertia::render('Dashboard', [
            'metrics' => [
                'total_members' => $totalMembers,
                'active_loans_count' => $activeLoansCount,
                'total_loan_amount' => $totalLoanAmount,
                'total_savings' => $totalSavings,
                'todays_collections' => $todaysCollections,
                'outstanding_balance' => $outstandingLoansBalance,
            ],
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ]
        ]);
    }
}
