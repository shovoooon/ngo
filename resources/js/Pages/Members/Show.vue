<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    member: Object,
});

const deleteMember = () => {
    if (confirm('Are you strictly positive you want to completely erase this member? This action is purely destructive and irreversible.')) {
        router.delete(route('members.destroy', props.member.id));
    }
};
</script>

<template>
    <Head :title="member.name + ' - ' + $t('Profile')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <Link :href="route('members.index')" class="text-gray-500 hover:text-indigo-600 transition">
                        <svg class="h-6 w-6 border rounded-md p-1 bg-white shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-bold leading-tight text-gray-800">{{ $t('Profile') }}: {{ member.name }}</h2>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('members.edit', member.id)" class="px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-600 text-xs font-bold rounded-md hover:bg-indigo-100 transition">
                        {{ $t('Edit') }}
                    </Link>
                    <button @click="deleteMember" class="px-3 py-1 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-md hover:bg-red-100 transition">
                        {{ $t('Delete') }}
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6 space-y-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <!-- Personal Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            {{ $t('Personal Details') }}
                        </h3>
                        <span :class="[member.status === 'active' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200', 'px-3 py-1 rounded-full text-xs font-bold border']">
                            {{ $t(member.status) }}
                        </span>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $t('Member Code') }}</p>
                            <p class="mt-1 font-mono text-lg font-bold text-indigo-700">{{ member.member_code }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $t('Phone Number') }}</p>
                            <p class="mt-1 text-base font-medium text-gray-900">{{ member.phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $t('National ID') }}</p>
                            <p class="mt-1 text-base font-medium text-gray-900">{{ member.nid || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $t('Village / Region') }}</p>
                            <p class="mt-1 text-base font-medium text-gray-900">{{ member.village || 'N/A' }}</p>
                        </div>
                        <div class="lg:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $t('Detailed Address') }}</p>
                            <p class="mt-1 text-base font-medium text-gray-900">{{ member.address || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Financial Outline -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Savings Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-md font-bold text-gray-800">{{ $t('Savings Registry') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between items-end">
                                <div>
                                    <p class="text-xs text-gray-600 font-medium tracking-wide uppercase">{{ $t('Current Balance') }}</p>
                                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $page.props.settings.currency_symbol }}{{ member.savings_account ? Number(member.savings_account.balance).toFixed(2) : '0.00' }}</p>
                                </div>
                                <Link :href="route('savings.show', member.id)" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 transition shadow-sm text-sm">
                                    {{ $t('View Ledger') }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Loans Summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-md font-bold text-gray-800">{{ $t('Loan Ledgers') }}</h3>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-xs">
                                        <th class="px-4 py-2 border-b">{{ $t('Loan #') }}</th>
                                        <th class="px-4 py-2 border-b">{{ $t('Principal') }}</th>
                                        <th class="px-4 py-2 border-b">{{ $t('Status') }}</th>
                                        <th class="px-4 py-2 border-b"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="loan in member.loans" :key="loan.id" class="border-b last:border-0 hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono text-indigo-600 font-medium">{{ loan.loan_number }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $page.props.settings.currency_symbol }}{{ Number(loan.loan_amount).toFixed(2) }}</td>
                                        <td class="px-4 py-3">
                                            <span :class="[loan.status === 'active' ? 'text-amber-600 bg-amber-50 border-amber-200' : 'text-green-600 bg-green-50 border-green-200', 'px-2 py-0.5 text-xs rounded-full border']">{{ $t(loan.status) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <Link :href="route('loans.show', loan.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">{{ $t('View') }}</Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!member.loans || member.loans.length === 0">
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">{{ $t('No recorded loans found.') }}</td>
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
