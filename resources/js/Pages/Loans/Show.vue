<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    loan: Object,
});

const showPaymentModal = ref(false);

const form = useForm({
    loan_id: props.loan.id,
    amount: "",
    collection_date: new Date().toISOString().split("T")[0],
    note: "",
});

const openPaymentModal = () => {
    form.clearErrors();
    form.amount =
        props.loan.installment_amount > 0 ? props.loan.installment_amount : "";
    showPaymentModal.value = true;
};

const closePaymentModal = () => {
    showPaymentModal.value = false;
    form.reset();
};

const submitPayment = () => {
    form.post(route("collections.store"), {
        preserveScroll: true,
        onSuccess: () => closePaymentModal(),
    });
};

const calculateInstallmentsPaid = () => {
    if (!props.loan.installment_amount || props.loan.installment_amount == 0)
        return 0;
    return Math.floor(props.loan.paid_amount / props.loan.installment_amount);
};
</script>

<template>
    <Head :title="$t('Loan Details')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t("Loan Collection Details") }}: {{ loan.loan_number }}
                </h2>
                <div class="flex items-center gap-4">
                    <button
                        v-if="loan.status === 'active'"
                        @click="openPaymentModal"
                        class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 transition"
                    >
                        {{ $t("Collect Payment") }}
                    </button>
                    <Link
                        :href="route('loans.index')"
                        class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-4 py-2 hover:bg-indigo-50 transition font-medium text-sm"
                    >
                        &larr; {{ $t("Return to Active Loans") }}
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Financial Overview Dashboard -->
                <div
                    class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg mb-8 border border-gray-100"
                >
                    <div
                        class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center"
                    >
                        <h3
                            class="text-sm font-bold text-gray-700 uppercase tracking-wider"
                        >
                            {{ $t("Loan Overview") }}
                        </h3>
                        <span
                            class="uppercase tracking-wider inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold shadow-sm"
                            :class="
                                loan.status === 'active'
                                    ? 'bg-green-100 text-green-800 border border-green-200'
                                    : 'bg-yellow-100 text-yellow-800 border border-yellow-200'
                            "
                        >
                            {{ $t(loan.status) }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Funded Member") }}
                                </p>

                                <Link
                                    :href="
                                        route('members.show', loan.member?.id)
                                    "
                                    class="font-bold text-lg text-indigo-600 hover:underline"
                                >
                                    {{ loan.member?.name }}
                                </Link>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Principal Value") }}
                                </p>
                                <p class="font-bold text-lg text-gray-900">
                                    {{ $page.props.settings.currency_symbol
                                    }}{{ loan.loan_amount }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Total Liability") }}
                                </p>
                                <p class="font-bold text-lg text-gray-900">
                                    {{ $page.props.settings.currency_symbol
                                    }}{{ loan.total_payable }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Disbursement Date") }}
                                </p>
                                <p class="font-semibold text-gray-800">
                                    {{
                                        new Date(
                                            loan.start_date,
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                            <div
                                class="col-span-2 md:col-span-4 border-t border-gray-200 mt-2 pt-4 grid grid-cols-2 md:grid-cols-4 gap-6"
                            >
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        {{ $t("Total Paid") }}
                                    </p>
                                    <p class="font-bold text-lg text-green-600">
                                        {{ $page.props.settings.currency_symbol
                                        }}{{ loan.paid_amount || "0.00" }}
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-500 mb-1"
                                    >
                                        {{ $t("Remaining Balance") }}
                                    </p>
                                    <p
                                        class="font-bold text-lg"
                                        :class="
                                            loan.remaining_balance > 0
                                                ? 'text-red-600'
                                                : 'text-gray-900'
                                        "
                                    >
                                        {{ $page.props.settings.currency_symbol
                                        }}{{ loan.remaining_balance || "0.00" }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Installment Details -->
                <div
                    class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg mb-8 border border-gray-100"
                >
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                        <h3
                            class="text-sm font-bold text-gray-700 uppercase tracking-wider"
                        >
                            {{ $t("Installment Information") }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Installment Amount") }}
                                </p>
                                <p class="font-bold text-lg text-gray-900">
                                    {{ $page.props.settings.currency_symbol
                                    }}{{ loan.installment_amount }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Total Installments") }}
                                </p>
                                <p class="font-bold text-lg text-gray-900">
                                    {{ loan.total_installments }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Installments Paid") }}
                                </p>
                                <p class="font-bold text-lg text-green-600">
                                    {{ calculateInstallmentsPaid() }}
                                </p>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 mb-1"
                                >
                                    {{ $t("Installments Due") }}
                                </p>
                                <p class="font-bold text-lg text-red-600">
                                    {{
                                        loan.total_installments -
                                        calculateInstallmentsPaid()
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collection History -->
                <div
                    class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg border border-gray-100"
                >
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                        <h3
                            class="text-sm font-bold text-gray-700 uppercase tracking-wider"
                        >
                            {{ $t("Collection History") }}
                        </h3>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900"
                                    >
                                        {{ $t("Date") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        {{ $t("Amount") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        {{ $t("Collected By") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        {{ $t("Note") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="collection in loan.collections"
                                    :key="collection.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            new Date(
                                                collection.collection_date,
                                            ).toLocaleDateString()
                                        }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-sm font-bold text-green-600"
                                    >
                                        +{{
                                            $page.props.settings
                                                .currency_symbol
                                        }}{{ collection.amount }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-600 font-medium"
                                    >
                                        {{
                                            collection.collector?.name ||
                                            $t("Staff")
                                        }}
                                    </td>
                                    <td
                                        class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate"
                                        :title="collection.note"
                                    >
                                        {{ collection.note || "-" }}
                                    </td>
                                </tr>
                                <tr v-if="!loan.collections?.length">
                                    <td
                                        colspan="4"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        <p class="mt-4 text-sm font-medium">
                                            {{
                                                $t(
                                                    "No collections recorded yet.",
                                                )
                                            }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collect Payment Modal -->
        <Modal
            :show="showPaymentModal"
            @close="closePaymentModal"
            maxWidth="md"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $t("Record Loan Repayment") }}
                </h2>

                <form @submit.prevent="submitPayment" class="space-y-4">
                    <div>
                        <InputLabel
                            :value="
                                $t('Settle Amount') +
                                ' (' +
                                $t('Remaining') +
                                ': ' +
                                $page.props.settings.currency_symbol +
                                loan.remaining_balance +
                                ')'
                            "
                        />
                        <div
                            class="relative mt-1 border-gray-300 rounded-md shadow-sm"
                        >
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <span class="text-gray-500 sm:text-sm">{{
                                    $page.props.settings.currency_symbol
                                }}</span>
                            </div>
                            <TextInput
                                v-model="form.amount"
                                type="number"
                                step="0.01"
                                min="0.01"
                                class="pl-7 block w-full"
                                required
                            />
                        </div>
                        <InputError
                            :message="form.errors.amount"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel :value="$t('Collection Date')" />
                        <TextInput
                            v-model="form.collection_date"
                            type="date"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            :message="form.errors.collection_date"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel :value="$t('Note (Optional)')" />
                        <textarea
                            v-model="form.note"
                            class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full text-sm"
                            rows="3"
                        ></textarea>
                        <InputError :message="form.errors.note" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closePaymentModal">
                            {{ $t("Cancel") }}
                        </SecondaryButton>
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            {{ $t("Record Payment") }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
