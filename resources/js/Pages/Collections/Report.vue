<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    collections: Object,
    filters: Object,
    dropdowns: Object
});

const date_from = ref(props.filters.date_from || '');
const date_to = ref(props.filters.date_to || '');
const member_id = ref(props.filters.member_id || '');
const loan_id = ref(props.filters.loan_id || '');
const collector_id = ref(props.filters.collector_id || '');

const applyFilters = () => {
    router.get(route('reports.collections'), {
        date_from: date_from.value,
        date_to: date_to.value,
        member_id: member_id.value,
        loan_id: loan_id.value,
        collector_id: collector_id.value
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    date_from.value = '';
    date_to.value = '';
    member_id.value = '';
    loan_id.value = '';
    collector_id.value = '';
    applyFilters();
};

const exportCsv = () => {
    // Build query params for export
    const params = new URLSearchParams();
    if (date_from.value) params.append('date_from', date_from.value);
    if (date_to.value) params.append('date_to', date_to.value);
    if (member_id.value) params.append('member_id', member_id.value);
    if (loan_id.value) params.append('loan_id', loan_id.value);
    if (collector_id.value) params.append('collector_id', collector_id.value);
    params.append('export', 'csv');
    
    window.location.href = `${route('reports.collections')}?${params.toString()}`;
};
</script>

<template>
    <Head :title="$t('Loan Collections Report')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Loan Collections Report') }}</h2>
                <button @click="exportCsv" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $t('Export to CSV') }}
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Filters Section -->
                <div class="bg-white p-6 shadow-sm border border-gray-100 sm:rounded-lg mb-8">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">{{ $t('Report Filters') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        
                        <!-- Date From -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $t('Date From') }}</label>
                            <input type="date" v-model="date_from" @change="applyFilters" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        
                        <!-- Date To -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $t('Date To') }}</label>
                            <input type="date" v-model="date_to" @change="applyFilters" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        
                        <!-- Member Dropdown -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $t('Filter by Member') }}</label>
                            <select v-model="member_id" @change="applyFilters" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ $t('All Members') }}</option>
                                <option v-for="member in dropdowns.members" :key="member.id" :value="member.id">
                                    {{ member.name }} ({{ member.member_code }})
                                </option>
                            </select>
                        </div>
                        
                        <!-- Loan Dropdown -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $t('Filter by Loan') }}</label>
                            <select v-model="loan_id" @change="applyFilters" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ $t('All Loans') }}</option>
                                <option v-for="loan in dropdowns.loans" :key="loan.id" :value="loan.id">
                                    {{ loan.loan_number }}
                                </option>
                            </select>
                        </div>

                        <!-- Collector Dropdown -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $t('Field Officer') }}</label>
                            <select v-model="collector_id" @change="applyFilters" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ $t('All Officers') }}</option>
                                <option v-for="collector in dropdowns.collectors" :key="collector.id" :value="collector.id">
                                    {{ collector.name }}
                                </option>
                            </select>
                        </div>

                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button @click="resetFilters" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-md hover:bg-gray-200 transition text-sm">
                            {{ $t('Clear Filters') }} &times;
                        </button>
                    </div>
                </div>

                <!-- Collections Table -->
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Member') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Loan Number') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Collected By') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Note') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="collection in collections.data" :key="collection.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-l-4 border-transparent hover:border-indigo-500">
                                        {{ collection.collection_date }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-indigo-600">{{ collection.member?.name || $t('Unknown') }}</div>
                                        <div class="text-xs text-gray-500">{{ collection.member?.member_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        <Link :href="route('loans.show', collection.loan_id)" class="hover:underline text-indigo-600">
                                            {{ collection.loan?.loan_number || 'N/A' }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $page.props.settings.currency_symbol }}{{ collection.amount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ collection.collector?.name || $t('Unknown') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" :title="collection.note">
                                        {{ collection.note || '-' }}
                                    </td>
                                </tr>
                                <tr v-if="collections.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="mt-4 text-sm font-medium">{{ $t('No collections found matching the given filters.') }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="collections.links && collections.links.length > 3" class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link v-if="collections.prev_page_url" :href="collections.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">{{ $t('Previous') }}</Link>
                            <Link v-if="collections.next_page_url" :href="collections.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">{{ $t('Next') }}</Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ collections.from || 0 }}</span> to <span class="font-medium">{{ collections.to || 0 }}</span> of <span class="font-medium">{{ collections.total }}</span> entries
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <template v-for="(link, key) in collections.links" :key="key">
                                        <div v-if="link.url === null" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default" v-html="link.label"></div>
                                        <Link v-else :href="link.url" :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', 'relative inline-flex items-center px-4 py-2 border text-sm font-medium']" v-html="link.label" />
                                    </template>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
