<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    loans: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(
        route('loans.index'),
        { search: value },
        { preserveState: true, replace: true }
    );
});
</script>

<template>
    <Head :title="$t('Loans')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Loan Management') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <!-- Action Bar -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="w-1/3">
                                <input
                                    v-model="search"
                                    type="text"
                                    :placeholder="$t('Search by member or loan #...')"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                />
                            </div>
                            <Link
                                :href="route('loans.create')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ $t('Disburse New Loan') }}
                            </Link>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">{{ $t('Loan #') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Member') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Amount') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Total Payable') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Date Issued') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Status') }}</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4">
                                            <span class="sr-only">{{ $t('View') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="loan in loans.data" :key="loan.id">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ loan.loan_number }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ loan.member.name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $page.props.settings.currency_symbol }}{{ loan.loan_amount }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $page.props.settings.currency_symbol }}{{ loan.total_payable }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ new Date(loan.start_date).toLocaleDateString() }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">
                                            <span 
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                :class="loan.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                            >
                                                {{ $t(loan.status) }}
                                            </span>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                                            <Link :href="route('loans.show', loan.id)" class="text-indigo-600 hover:text-indigo-900">{{ $t('View Schedule') }}</Link>
                                        </td>
                                    </tr>
                                    <tr v-if="loans.data.length === 0">
                                        <td colspan="7" class="py-4 text-center text-sm text-gray-500">{{ $t('No active loans found in the system.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between" v-if="loans.links.length > 3">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        {{ $t('Showing') }} <span class="font-medium">{{ loans.from }}</span> {{ $t('to') }} <span class="font-medium">{{ loans.to }}</span> {{ $t('of') }} <span class="font-medium">{{ loans.total }}</span> {{ $t('results') }}
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <Link 
                                            v-for="(link, i) in loans.links" 
                                            :key="i"
                                            :href="link.url"
                                            v-html="link.label"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
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
        </div>
    </AuthenticatedLayout>
</template>
