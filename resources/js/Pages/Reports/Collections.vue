<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    collections: Object,
    filters: Object,
});

const startDate = ref(props.filters.start_date || '');
const endDate = ref(props.filters.end_date || '');

const submitFilter = () => {
    router.get(
        route('reports.collections'),
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
    
    window.location.href = `${route('reports.collections')}?${query}`;
};
</script>

<template>
    <Head title="Collections Report" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daily Collections Ledger</h2>
                <button @click="exportCsv" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-sm text-sm font-bold flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Export CSV
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg">
                    
                    <!-- Filtration Bar -->
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-end gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">From Date</label>
                            <input type="date" v-model="startDate" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">To Date</label>
                            <input type="date" v-model="endDate" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="flex gap-2">
                            <button @click="submitFilter" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-md shadow-sm text-sm font-semibold transition-colors">
                                Filter Entries
                            </button>
                            <button @click="clearFilters" v-if="startDate || endDate" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-md shadow-sm text-sm font-semibold transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-600 uppercase">Timestamp</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">Collected Amount</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">Target Loan</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">Member Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase">Collected By (Staff)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="collection in collections.data" :key="collection.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {{ new Date(collection.collected_at).toLocaleString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-black text-green-600">{{ $page.props.settings.currency_symbol }}{{ Number(collection.amount).toLocaleString() }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-indigo-600">
                                            {{ collection.loan?.loan_number || 'Unknown' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                            {{ collection.member?.name || 'Unknown' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ collection.collected_by || 'System' }}
                                        </td>
                                    </tr>
                                    <tr v-if="collections.data.length === 0">
                                        <td colspan="5" class="py-6 text-center text-sm text-gray-500 italic">No collection records found for this period.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Mapping -->
                        <div class="mt-4 flex flex-wrap items-center justify-between" v-if="collections.links.length > 3">
                            <div class="w-full">
                                <nav class="relative z-0 inline-flex flex-wrap shadow-sm -space-x-px w-full" aria-label="Pagination">
                                    <Link 
                                        v-for="(link, i) in collections.links" 
                                        :key="i"
                                        :href="link.url || '#'"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium flex-1 text-center justify-center whitespace-nowrap"
                                        :class="[
                                            link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            !link.url ? 'opacity-50 cursor-not-allowed hidden' : '',
                                            i === 0 ? 'rounded-l-md' : '',
                                            i === collections.links.length - 1 ? 'rounded-r-md' : ''
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
