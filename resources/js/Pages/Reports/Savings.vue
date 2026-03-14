<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    accounts: Object,
});

const exportCsv = () => {
    const query = new URLSearchParams({
        export: 'csv',
    }).toString();
    
    window.location.href = `${route('reports.savings')}?${query}`;
};
</script>

<template>
    <Head :title="$t('Savings Reporting')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Total Pooled Savings Aggregation') }}</h2>
                <button @click="exportCsv" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-sm text-sm font-bold flex items-center gap-2 transition-colors">
                    {{ $t('Export Full CSV') }}
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg">
                    
                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                        <p class="text-sm text-gray-600">
                            {{ $t('This report filters out all members explicitly with an available minimum savings balance of at least') }} <strong>{{ $page.props.settings.currency_symbol }}1.00</strong>. {{ $t('Empty or null savings accounts are visually hidden to retain clarity over exactly what capital sits in the NGO.') }}
                        </p>
                    </div>

                    <div class="p-6">
                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $t('Internal Member ID') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $t('Member Reference') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-right text-xs font-bold text-blue-600 uppercase tracking-wider">{{ $t('Total Active Storage Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="account in accounts.data" :key="account.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-bold text-gray-400">
                                            {{ account.member?.member_code || `MBR-SYS-${account.member_id}` }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900 border-l border-gray-100">
                                            {{ account.member?.name || $t('Unknown') }}
                                            <span class="block text-xs text-indigo-500 font-bold border-t border-gray-100 mt-1 pt-1">{{ account.member?.phone }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-2xl text-right font-black text-blue-600 border-l border-gray-100">
                                            {{ $page.props.settings.currency_symbol }}{{ Number(account.balance).toLocaleString() }}
                                        </td>
                                    </tr>
                                    <tr v-if="accounts.data.length === 0">
                                        <td colspan="3" class="py-12 text-center text-sm font-bold text-red-500 italic">{{ $t('No savings capital data detected within the environment.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Mapping -->
                        <div class="mt-4 flex flex-wrap items-center justify-between" v-if="accounts.links && accounts.links.length > 3">
                            <div class="w-full">
                                <nav class="relative z-0 inline-flex flex-wrap shadow-sm -space-x-px w-full" aria-label="Pagination">
                                    <Link 
                                        v-for="(link, i) in accounts.links" 
                                        :key="i"
                                        :href="link.url || '#'"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium flex-1 text-center justify-center whitespace-nowrap"
                                        :class="[
                                            link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            !link.url ? 'opacity-50 cursor-not-allowed hidden' : '',
                                            i === 0 ? 'rounded-l-md' : '',
                                            i === accounts.links.length - 1 ? 'rounded-r-md' : ''
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
