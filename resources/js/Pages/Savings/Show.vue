<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    member: Object
});

const mData = computed(() => props.member?.data || props.member || {});
const accounts = computed(() => mData.value.savings_accounts?.data || mData.value.savings_accounts || []);
const transactionsList = computed(() => mData.value.transactions?.data || mData.value.transactions || []);

const currentBalance = computed(() => {
    if (accounts.value.length === 0) {
        return 0;
    }
    return accounts.value[0].balance;
});
</script>

<template>
    <Head :title="$t('Savings Ledger')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('Savings Ledger') }}: <span class="text-indigo-600">{{ mData.name }}</span>
                </h2>
                <div class="flex items-center gap-4">
                    <a :href="route('savings.download-statement', mData.id)" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $t('Download Statement') }}
                    </a>
                    <Link :href="route('savings.index')" class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-4 py-2 hover:bg-indigo-50 transition font-medium text-sm">
                        &larr; {{ $t('Return to Savings Accounts') }}
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Financial Overview -->
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg mb-8 border border-gray-100 flex flex-col md:flex-row items-center justify-between p-6">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900">{{ mData.name }}</h3>
                        <p class="text-sm font-medium text-gray-500 mt-1">ID: {{ mData.member_code || mData.id }} • Phone: {{ mData.phone }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-right">
                        <p class="text-4xl font-black text-green-600">{{ $page.props.settings.currency_symbol }}{{ currentBalance }}</p>
                    </div>
                </div>

                <!-- Transaction Ledger Table -->
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $t('Transaction History') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto shadow-sm ring-1 ring-gray-200 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Date & Time') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Type') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Amount') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Balance') }}</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Note / Reference') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="transaction in transactionsList" :key="transaction.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {{ new Date(transaction.created_at).toLocaleString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm capitalize">
                                            <span 
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold shadow-sm border"
                                                :class="transaction.type === 'deposit' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-red-50 text-red-700 border-red-200'"
                                            >
                                                {{ $t(transaction.type) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-black" :class="transaction.type === 'deposit' ? 'text-blue-600' : 'text-red-600'">
                                            {{ transaction.type === 'deposit' ? '+' : '-' }} {{ $page.props.settings.currency_symbol }}{{ transaction.amount }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                                            {{ $page.props.settings.currency_symbol }}{{ transaction.balance_after }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-600">
                                            {{ transaction.note || '--' }}
                                        </td>
                                    </tr>
                                    <tr v-if="transactionsList.length === 0">
                                        <td colspan="5" class="py-12 text-center text-gray-500 font-medium text-sm">
                                            {{ $t('No tracking history found. The ledger is empty.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
