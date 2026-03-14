<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, watch } from 'vue';

// For searching members dynamically (in a full app, this would be an API call)
// Here we will use a simple text input that expects EXACT Loan Number or we could just
// use standard inputs and validate backend. Given the swift mobile requirement,
// let's do a Loan Number search format.
const form = useForm({
    loan_id: '', // Will be resolved by the backend if they enter loan_number
    loan_number: '',
    amount: '',
    collection_date: new Date().toISOString().split('T')[0],
    note: ''
});

const submit = () => {
    // Note: since the standard rule is 'loan_id => required|exists:loans,id'
    // We actually need the backend to accept 'loan_number' or we build an API endpoint.
    // For simplicity, we will just submit and let the controller handle a custom flow OR
    // we can build a quick look-up. Let's just submit 'loan_id' if we had a full
    // component, but standard form inputs:
    
    // Instead of raw loan_id, we will pass loan_number to a specialized endpoint, or just require exact input.
    form.post(route('collections.store'), {
        preserveScroll: true,
        onSuccess: () => {
             form.reset('amount', 'note', 'loan_number', 'loan_id');
        },
    });
};
</script>

<template>
    <Head title="Quick Collection" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fast Payment Collection</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Notice: In a real PWA this would be a typeahead or barcode scanner. -->
                            <!-- We will update the backend to let "loan_number" map to "loan_id" silently -->
                            <div>
                                <InputLabel for="loan_id" value="Loan Number" />
                                <!-- Workaround: We ask for Loan ID directly since the backend rules demand it, 
                                     but to make it truly fast, we will show a text input that binds to loan_id -->
                                <TextInput
                                    id="loan_id"
                                    type="number"
                                    class="mt-1 block w-full text-lg py-3"
                                    v-model="form.loan_id"
                                    required
                                    placeholder="Enter Loan ID (e.g. 1)"
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.loan_id" />
                            </div>

                            <div>
                                <InputLabel for="amount" :value="'Collection Amount (' + $page.props.settings.currency_symbol + ')'" />
                                <TextInput
                                    id="amount"
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    class="mt-1 block w-full text-2xl py-4 font-bold text-green-700"
                                    v-model="form.amount"
                                    required
                                    placeholder="0.00"
                                />
                                <InputError class="mt-2" :message="form.errors.amount" />
                            </div>

                            <div>
                                <InputLabel for="collection_date" value="Date" />
                                <TextInput
                                    id="collection_date"
                                    type="date"
                                    class="mt-1 block w-full text-lg py-3"
                                    v-model="form.collection_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.collection_date" />
                            </div>

                            <div>
                                <InputLabel for="note" value="Note (Optional)" />
                                <textarea
                                    id="note"
                                    v-model="form.note"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full text-lg py-3"
                                    rows="2"
                                    placeholder="Enter any details..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.note" />
                            </div>

                            <div class="pt-4">
                                <PrimaryButton class="w-full justify-center py-4 text-lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Record Payment
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
