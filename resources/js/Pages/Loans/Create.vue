<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    members: Array,
    products: Array,
});

const form = useForm({
    member_id: "",
    loan_product_id: "",
    loan_amount: "",
    interest_rate: "",
    start_date: new Date().toISOString().slice(0, 10),
});

import { watch } from "vue";
watch(
    () => form.loan_product_id,
    (productId) => {
        const product = props.products.find((p) => p.id === productId);
        if (product) {
            form.interest_rate = product.interest_rate;
            // Optionally auto-set amount if min/max exists
            if (product.min_amount && !form.loan_amount)
                form.loan_amount = product.min_amount;
        }
    },
);

const submit = () => {
    console.log("Submitting form:", form);
    form.post(route("loans.store"), {
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
        onSuccess: () => {
            console.log("Loan created successfully");
        },
    });
};

console.log("Members data for loan creation:", props.members);
</script>

<template>
    <Head :title="$t('Disburse Loan')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t("Disburse New Loan") }}
                </h2>
                <Link
                    :href="route('loans.index')"
                    class="text-gray-600 hover:text-gray-900 underline text-sm"
                >
                    {{ $t("Cancel") }}
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="mb-6">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    {{ $t("Loan Product") }}
                                </label>
                                <select
                                    v-model="form.loan_product_id"
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>
                                        {{ $t("Select a product...") }}
                                    </option>
                                    <option
                                        v-for="product in products"
                                        :key="product.id"
                                        :value="product.id"
                                    >
                                        {{ product.name }} ({{
                                            product.interest_rate
                                        }}% - {{ product.duration_weeks }}W)
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.loan_product_id"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ form.errors.loan_product_id }}
                                </p>
                            </div>
                            <div class="mb-6">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    {{ $t("Select Active Member") }}
                                </label>
                                <select
                                    v-model="form.member_id"
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>
                                        {{
                                            $t("Choose a registered member...")
                                        }}
                                    </option>
                                    <option
                                        v-for="member in members"
                                        :key="member.id"
                                        :value="member.id"
                                    >
                                        {{ member.name }} - {{ member.phone }}
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.member_id"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ form.errors.member_id }}
                                </p>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"
                            >
                                <!-- Base financials -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        {{ $t("Principal Amount") }} ({{
                                            $page.props.settings
                                                .currency_symbol
                                        }})
                                    </label>
                                    <input
                                        type="number"
                                        step="10"
                                        min="1000"
                                        v-model="form.loan_amount"
                                        placeholder="5000"
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.loan_amount"
                                        class="mt-2 text-sm text-red-600"
                                    >
                                        {{ form.errors.loan_amount }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        {{ $t("Flat Interest Rate (%)") }}
                                    </label>
                                    <input
                                        type="number"
                                        step="0.5"
                                        min="0"
                                        v-model="form.interest_rate"
                                        placeholder="10.5"
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.interest_rate"
                                        class="mt-2 text-sm text-red-600"
                                    >
                                        {{ form.errors.interest_rate }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"
                            >
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        {{ $t("Disbursement / Start Date") }}
                                    </label>
                                    <input
                                        type="date"
                                        v-model="form.start_date"
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required
                                    />
                                    <p
                                        v-if="form.errors.start_date"
                                        class="mt-2 text-sm text-red-600"
                                    >
                                        {{ form.errors.start_date }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {{ $t("Disburse Loan") }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
