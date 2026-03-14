<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
    product: Object,
});

const isEdit = !!props.product;

const form = useForm({
    name: props.product?.name ?? "",
    interest_rate: props.product?.interest_rate ?? "",
    interest_type: props.product?.interest_type ?? "flat",
    duration_weeks: props.product?.duration_weeks ?? "",
    installment_frequency: props.product?.installment_frequency ?? "weekly",
    processing_fee: props.product?.processing_fee ?? 0,
    late_fee: props.product?.late_fee ?? 0,
    min_amount: props.product?.min_amount ?? "",
    max_amount: props.product?.max_amount ?? "",
    status: props.product?.status ?? "active",
});

const submit = () => {
    if (isEdit) {
        form.put(route("loan-products.update", props.product.id));
    } else {
        form.post(route("loan-products.store"));
    }
};
</script>

<template>
    <Head :title="isEdit ? $t('Edit Loan Product') : $t('Add Loan Product')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ isEdit ? $t('Edit Loan Product') : $t('Add New Loan Product') }}
                </h2>
                <Link :href="route('loan-products.index')" class="text-sm text-gray-600 hover:underline">{{ $t('Back to List') }}</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Product Name') }}</label>
                                <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Interest Rate') }} (%)</label>
                                <input v-model="form.interest_rate" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Interest Type') }}</label>
                                <select v-model="form.interest_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="flat">Flat</option>
                                    <option value="reducing">Reducing Balance</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Duration (Weeks)') }}</label>
                                <input v-model="form.duration_weeks" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Installment Frequency') }}</label>
                                <select v-model="form.installment_frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Processing Fee') }}</label>
                                <input v-model="form.processing_fee" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Late Fee') }}</label>
                                <input v-model="form.late_fee" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Min Amount') }}</label>
                                <input v-model="form.min_amount" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Max Amount') }}</label>
                                <input v-model="form.max_amount" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Status') }}</label>
                                <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-span-2 flex justify-end">
                                <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 font-bold uppercase tracking-wider">
                                    {{ isEdit ? $t('Update Product') : $t('Create Product') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
