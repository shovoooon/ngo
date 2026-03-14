<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Savings Statement - {{ $member->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .statement-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
            color: #666;
        }
        .value {
            color: #111;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .transaction-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
        }
        .transaction-table td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .amount-cell {
            text-align: right;
        }
        .type-deposit {
            color: #059669;
            font-weight: bold;
        }
        .type-withdraw {
            color: #dc2626;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
        .summary-box {
            float: right;
            width: 250px;
            background-color: #f9fafb;
            padding: 15px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .summary-label {
            font-size: 12px;
            color: #6b7280;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ setting('company_name', 'NGO Management System') }}</div>
        <div class="statement-title">Savings Account Statement</div>
        <div style="font-size: 12px; color: #666;">
            {{ setting('company_address', '') }}<br>
            Phone: {{ setting('company_phone', '') }} | Email: {{ setting('company_email', '') }}
        </div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Member Name:</td>
                <td class="value">{{ $member->name }}</td>
                <td class="label">Date Generated:</td>
                <td class="value">{{ now()->format('d M, Y h:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Member Code:</td>
                <td class="value">{{ $member->member_code }}</td>
                <td class="label">Period:</td>
                <td class="value">All Time</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td class="value">{{ $member->phone }}</td>
                <td class="label">Currency:</td>
                <td class="value">{{ setting('currency_code', 'BDT') }} ({{ setting('currency_symbol', '৳') }})</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Current Balance:</span><br>
            <span class="summary-value" style="font-size: 20px; color: #4f46e5;">
                {{ setting('currency_symbol', '৳') }}{{ number_format($member->savingsAccounts->sum('balance'), 2) }}
            </span>
        </div>
    </div>

    <div style="clear: both;"></div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="amount-cell">Type</th>
                <th class="amount-cell">Amount</th>
                <th class="amount-cell">Running Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                    <td>{{ $transaction->note ?: 'No description provided' }}</td>
                    <td class="amount-cell">
                        <span class="type-{{ $transaction->type }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="amount-cell">
                        {{ $transaction->type == 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="amount-cell" style="font-weight: bold;">
                        {{ number_format($transaction->balance_after, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This is a computer-generated statement and does not require a signature.<br>
        &copy; {{ date('Y') }} {{ setting('company_name', 'NGO Management System') }}. All rights reserved.
    </div>
</body>
</html>
