<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';
import { Line } from 'vue-chartjs';
import { computed } from 'vue';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
    metrics: Object,
    chart: Object,
});

const page = usePage();
const currencySymbol = page.props.settings?.currency_symbol || '$';
const currencyCode = page.props.settings?.currency_code || 'USD';

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        label += new Intl.NumberFormat('en-US', { style: 'currency', currency: currencyCode }).format(context.parsed.y);
                    }
                    return label;
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: function(value, index, values) {
                    return currencySymbol + value;
                }
            }
        }
    }
};

const chartData = computed(() => ({
    labels: props.chart.labels,
    datasets: [
        {
            label: `${page.props.translations?.Collections || 'Collections'} (${currencySymbol})`,
            backgroundColor: 'rgba(99, 102, 241, 0.2)', // Indigo-500 equivalent semi-transparent
            borderColor: 'rgba(79, 70, 229, 1)', // Indigo-600
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(79, 70, 229, 1)',
            pointHoverBackgroundColor: 'rgba(79, 70, 229, 1)',
            pointHoverBorderColor: '#ffffff',
            fill: true,
            tension: 0.4,
            data: props.chart.data,
        }
    ]
}));

</script>

<template>
    <Head :title="$t('Admin Dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('NGO Overview Dashboard') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- KPI Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Today's Collections -->
                    <div class="bg-indigo-600 overflow-hidden shadow-sm sm:rounded-lg text-white">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-indigo-200">{{ $t("Today's Collections") }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-4xl font-black">{{ $page.props.settings.currency_symbol }}{{ metrics.todays_collections.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Loan Capital -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $t('Active Loan Principal') }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-gray-900">{{ $page.props.settings.currency_symbol }}{{ metrics.total_loan_amount.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding Loan Balances -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $t('Outstanding Expected Return') }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-red-600">{{ $page.props.settings.currency_symbol }}{{ metrics.outstanding_balance.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Savings Volume -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $t('Total Pooled Savings') }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-green-600">{{ $page.props.settings.currency_symbol }}{{ metrics.total_savings.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Registered Members -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $t('Registered Accounts') }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-gray-900">{{ metrics.total_members.toLocaleString() }}</span>
                                <span class="text-sm text-gray-500 font-medium">{{ $t('Members') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Loans Count -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $t('Active Borrowers') }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-gray-900">{{ metrics.active_loans_count.toLocaleString() }}</span>
                                <span class="text-sm text-gray-500 font-medium">{{ $t('Loans Running') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Charting Row -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-lg">{{ $t('7-Day Operations & Collections Trend') }}</h3>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest bg-gray-100 px-2 py-1 rounded">{{ $t('Past Week') }}</span>
                    </div>
                    <div class="p-6 h-80">
                        <Line :data="chartData" :options="chartOptions" />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
