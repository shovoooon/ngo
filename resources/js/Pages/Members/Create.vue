<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    phone: '',
    nid: '',
    village: '',
    address: '',
});

const submit = () => {
    form.post(route('members.store'));
};
</script>

<template>
    <Head :title="$t('Add Member')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4 py-2">
                <Link :href="route('members.index')" class="text-gray-500 hover:text-indigo-600 transition">
                    <svg class="h-6 w-6 border rounded-md p-1 bg-white shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-bold leading-tight text-gray-800">{{ $t('Add New Member') }}</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 sm:p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" :value="$t('Full Name') + ' *'" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <InputLabel for="phone" :value="$t('Phone Number') + ' *'" />
                                <TextInput
                                    id="phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                    :placeholder="$t('e.g') + ' 01700000000'"
                                />
                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>

                            <!-- NID -->
                            <div>
                                <InputLabel for="nid" :value="$t('National ID') + ' (' + $t('Optional') + ')'" />
                                <TextInput
                                    id="nid"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.nid"
                                />
                                <InputError class="mt-2" :message="form.errors.nid" />
                            </div>

                            <!-- Village -->
                            <div>
                                <InputLabel for="village" :value="$t('Village/Area')" />
                                <TextInput
                                    id="village"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.village"
                                />
                                <InputError class="mt-2" :message="form.errors.village" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <InputLabel for="address" :value="$t('Detailed Address')" />
                            <textarea
                                id="address"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3"
                                v-model="form.address"
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <div class="flex items-center justify-end border-t pt-5 mt-6 border-gray-100">
                            <Link :href="route('members.index')" class="text-sm text-gray-600 hover:text-gray-900 font-medium mr-4">{{ $t('Cancel') }}</Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ $t('Register Member') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
