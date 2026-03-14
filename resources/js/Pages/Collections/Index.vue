<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    collections: Object,
    filters: Object
});

const search = ref(props.filters.search || '');

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

watch(search, debounce(function (value) {
    router.get(route('collections.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const deleteCollection = (id) => {
    if (confirm('Are you sure you want to delete this collection? This may reopen the loan if it was previously completed.')) {
        router.delete(route('collections.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="$t('Collections')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Recent Collections Ledger') }}</h2>
                <Link :href="route('collections.create')" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    {{ $t('Quick Collect') }}
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Search and Filters -->
                <div class="mb-6 bg-white p-4 shadow-sm border border-gray-100 sm:rounded-lg">
                    <div class="max-w-md w-full relative xl:max-w-xl">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="search"
                            type="text"
                            :placeholder="$t('Search by member name, phone or loan number...')"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition duration-150 ease-in-out"
                        />
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
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="collection in collections.data" :key="collection.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-l-4 border-transparent hover:border-indigo-500">
                                        {{ collection.collection_date }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-indigo-600">{{ collection.loan?.member?.name || $t('Unknown') }}</div>
                                        <div class="text-xs text-gray-500">{{ collection.loan?.member?.phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        <Link :href="route('loans.show', collection.loan_id)" class="hover:underline text-indigo-600 font-bold block">
                                            {{ collection.loan?.loan_number || 'N/A' }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                        +{{ $page.props.settings.currency_symbol }}{{ collection.amount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="deleteCollection(collection.id)" class="text-red-400 hover:text-red-600 transition p-2">
                                            {{ $t('Delete') }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="collections.data.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="mt-4 text-sm font-medium cursor-default">{{ $t('No active collections found in the ledger.') }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="collections.links && collections.links.length > 3" class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link v-if="collections.prev_page_url" :href="collections.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm">{{ $t('Previous') }}</Link>
                            <Link v-if="collections.next_page_url" :href="collections.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm">{{ $t('Next') }}</Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-bold">{{ collections.from || 0 }}</span> to <span class="font-bold">{{ collections.to || 0 }}</span> of <span class="font-bold">{{ collections.total }}</span> entries
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <template v-for="(link, key) in collections.links" :key="key">
                                        <div v-if="link.url === null" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default" v-html="link.label"></div>
                                        <Link v-else :href="link.url" :class="[link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 font-bold' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 font-medium', 'relative inline-flex items-center px-4 py-2 border text-sm transition']" v-html="link.label" />
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
