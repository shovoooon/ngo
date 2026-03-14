<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    loans: Object,
    filters: Object,
});

const startDate = ref(props.filters.start_date || '');
const endDate = ref(props.filters.end_date || '');

const submitFilter = () => {
    router.get(
        route('reports.loans'),
        { start_date: startDate.value, end_date: endDate.value },
        { preserveState: true, replace: true }
    );
};

const clearFilters = () => {
    startDate.value = '';
    endDate.value = '';
    submitFilter();
};

const exportCsv = () => {
    const query = new URLSearchParams({
        export: 'csv',
        start_date: startDate.value,
        end_date: endDate.value
    }).toString();
    
    window.location.href = `${route('reports.loans')}?${query}`;
};
</script>

<template>
    <Head :title="$t('Loans Report')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Current Loans Extractor') }}</h2>
                <button @click="exportCsv" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-sm text-sm font-bold flex items-center gap-2 transition-colors">
                    {{ $t('Export CSV') }}
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg">
                    
                    <!-- Filtration Bar -->
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">{{ $t('Start Disbursal Date') }}</label>
                            <input type="date" v-model="startDate" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">{{ $t('End Disbursal Date') }}</label>
                            <input type="date" v-model="endDate" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="flex gap-2">
                            <button @click="submitFilter" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 transition shadow-sm text-sm">
                                {{ $t('Filter') }}
                            </button>
                            <button @click="clearFilters" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-md hover:bg-gray-300 transition shadow-sm text-sm">
                                {{ $t('Clear') }}
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Loan ID') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Borrower') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Principal Amount') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Total Expected Return') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Status') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">{{ $t('Start Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="loan in loans.data" :key="loan.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-indigo-600">{{ loan.loan_number }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900 border-l border-gray-100">
                                            {{ loan.member?.name || $t('Unknown') }}
                                            <span class="block text-xs text-gray-500 font-normal">{{ loan.member?.phone }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">{{ $page.props.settings.currency_symbol }}{{ Number(loan.loan_amount).toLocaleString() }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-red-600">{{ $page.props.settings.currency_symbol }}{{ Number(loan.total_payable).toLocaleString() }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span 
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                :class="loan.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ $t(loan.status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ loan.start_date }}</td>
                                    </tr>
                                    <tr v-if="loans.data.length === 0">
                                        <td colspan="6" class="py-6 text-center text-sm text-gray-500 italic">{{ $t('No loan records match your filtration criteria.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Mapping -->
                        <div class="mt-4 flex flex-wrap items-center justify-between" v-if="loans.links && loans.links.length > 3">
                            <div class="w-full">
                                <nav class="relative z-0 inline-flex flex-wrap shadow-sm -space-x-px w-full" aria-label="Pagination">
                                    <Link 
                                        v-for="(link, i) in loans.links" 
                                        :key="i"
                                        :href="link.url || '#'"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium flex-1 text-center justify-center whitespace-nowrap"
                                        :class="[
                                            link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            !link.url ? 'opacity-50 cursor-not-allowed hidden' : '',
                                            i === 0 ? 'rounded-l-md' : '',
                                            i === loans.links.length - 1 ? 'rounded-r-md' : ''
                                        ]"
                                    />
                                </nav>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
