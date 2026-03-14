<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    members: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

// Transaction Modal State
const showTransactionModal = ref(false);
const transactionType = ref('deposit'); // 'deposit' or 'withdraw'
const selectedMember = ref(null);

const transactionForm = useForm({
    member_id: '',
    type: 'deposit',
    amount: '',
    note: '',
});

watch(search, (value) => {
    router.get(
        route('savings.index'),
        { search: value },
        { preserveState: true, replace: true }
    );
});

const openTransactionModal = (member, type) => {
    selectedMember.value = member;
    transactionType.value = type;
    
    transactionForm.member_id = member.id;
    transactionForm.type = type;
    transactionForm.amount = '';
    transactionForm.note = '';
    
    showTransactionModal.value = true;
};

const closeTransactionModal = () => {
    showTransactionModal.value = false;
    selectedMember.value = null;
    transactionForm.reset();
    transactionForm.clearErrors();
};

const submitTransaction = () => {
    transactionForm.post(route('savings.store'), {
        onSuccess: () => {
            closeTransactionModal();
        },
    });
};

const getMemberBalance = (member) => {
    if (!member.savings_accounts || member.savings_accounts.length === 0) {
        return 0;
    }
    return member.savings_accounts[0].balance;
};
</script>

<template>
    <Head :title="$t('Savings Registry')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Savings Management') }}</h2>
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
                                    :placeholder="$t('Search member by name or code...')"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                />
                            </div>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">{{ $t('Member ID') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Name') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Contact') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-extrabold text-gray-900 uppercase">{{ $t('Current Balance') }}</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 text-center">
                                            <span class="sr-only">Actions</span>
                                            {{ $t('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="member in members.data" :key="member.id">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-400">{{ member.member_code || `M-${member.id}` }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">{{ member.name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ member.phone }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-lg font-black" :class="getMemberBalance(member) > 0 ? 'text-green-600' : 'text-gray-400'">
                                            {{ $page.props.settings.currency_symbol }}{{ getMemberBalance(member) }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium space-x-2 flex justify-end">
                                            <button @click="openTransactionModal(member, 'deposit')" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded shadow-sm text-xs font-bold transition-colors">
                                                + {{ $t('Deposit') }}
                                            </button>
                                            <button @click="openTransactionModal(member, 'withdraw')" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded shadow-sm text-xs font-bold transition-colors">
                                                - {{ $t('Withdraw') }}
                                            </button>
                                            <Link :href="route('savings.show', member.id)" class="text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 px-3 py-1.5 rounded shadow-sm text-xs font-bold transition-colors">
                                                {{ $t('View Ledger') }}
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="members.data.length === 0">
                                        <td colspan="5" class="py-6 text-center text-sm text-gray-500">{{ $t('No members found matching your search.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between" v-if="members.links.length > 3">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ members.from }}</span> to <span class="font-medium">{{ members.to }}</span> of <span class="font-medium">{{ members.total }}</span> members
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <Link 
                                            v-for="(link, i) in members.links" 
                                            :key="i"
                                            :href="link.url"
                                            v-html="link.label"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            :class="[
                                                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                !link.url ? 'opacity-50 cursor-not-allowed hidden' : '',
                                                i === 0 ? 'rounded-l-md' : '',
                                                i === members.links.length - 1 ? 'rounded-r-md' : ''
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

        <!-- Savings Transaction Modal Overlay -->
        <div v-if="showTransactionModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="closeTransactionModal" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden overflow-x-auto shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <form @submit.prevent="submitTransaction">
                        <div>
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-xl leading-6 font-black text-gray-900 mb-2 capitalize" id="modal-title">
                                    {{ $t(transactionType) }} {{ $t('Savings') }}
                                </h3>
                                <div class="mt-2 bg-gray-50 p-4 rounded border border-gray-200 mb-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ selectedMember.name }}</p>
                                        <p class="text-xs text-gray-500">{{ selectedMember.phone }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">{{ $t('Available Balance') }}</p>
                                        <p class="text-lg font-black text-indigo-600">{{ $page.props.settings.currency_symbol }}{{ getMemberBalance(selectedMember) }}</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700 capitalize">{{ $t('Amount to') }} {{ $t(transactionType) }} ({{ $page.props.settings.currency_symbol }})</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        min="1"
                                        v-model="transactionForm.amount" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-semibold text-lg" 
                                        required
                                        :max="transactionType === 'withdraw' ? getMemberBalance(selectedMember) : ''"
                                    >
                                    <p v-if="transactionType === 'withdraw'" class="mt-1 text-xs text-red-500 font-medium">{{ $t('Cannot withdraw more than the Available Balance.') }}</p>
                                    <p v-if="transactionForm.errors.amount" class="mt-2 text-sm text-red-600">{{ transactionForm.errors.amount }}</p>
                                    <p v-if="transactionForm.errors.member_id" class="mt-2 text-sm text-red-600">{{ transactionForm.errors.member_id }}</p>
                                    <p v-if="transactionForm.errors.type" class="mt-2 text-sm text-red-600">{{ transactionForm.errors.type }}</p>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('Optional Note / Reference') }}</label>
                                    <input 
                                        type="text" 
                                        v-model="transactionForm.note" 
                                        :placeholder="$t('E.g. Monthly contribution')"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" 
                                    >
                                    <p v-if="transactionForm.errors.note" class="mt-2 text-sm text-red-600">{{ transactionForm.errors.note }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="transactionForm.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors" :class="transactionType === 'deposit' ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'">
                                {{ $t('Confirm') }} {{ $t(transactionType) }}
                            </button>
                            <button type="button" @click="closeTransactionModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ $t('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
