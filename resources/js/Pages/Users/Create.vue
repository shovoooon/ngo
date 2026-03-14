<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    phone: '',
    password: '',
    user_type: 'officer',
    status: 'active',
});

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head :title="$t('Add Staff')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Add New Staff Member') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <form @submit.prevent="submit" class="max-w-2xl">
                            <!-- Name -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ $t('Full Name') }}</label>
                                <input id="name" type="text" v-model="form.name" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <!-- Phone -->
                            <div class="mb-6">
                                <label for="phone" class="block text-sm font-medium text-gray-700">{{ $t('Phone Number (Login ID)') }}</label>
                                <input id="phone" type="text" v-model="form.phone" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required :placeholder="$t('e.g') + ' 01700000000'">
                                <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">{{ form.errors.phone }}</p>
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="password" class="block text-sm font-medium text-gray-700">{{ $t('Password') }}</label>
                                <input id="password" type="password" v-model="form.password" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
                            </div>

                            <!-- Role -->
                            <div class="mb-6">
                                <label for="user_type" class="block text-sm font-medium text-gray-700">{{ $t('System Role') }}</label>
                                <select id="user_type" v-model="form.user_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="admin">{{ $t('Administrator') }}</option>
                                    <option value="manager">{{ $t('Branch Manager') }}</option>
                                    <option value="officer">{{ $t('Field Officer') }}</option>
                                </select>
                                <p v-if="form.errors.user_type" class="mt-2 text-sm text-red-600">{{ form.errors.user_type }}</p>
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ $t('Account Status') }}</label>
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="active">{{ $t('active') }}</option>
                                    <option value="inactive">{{ $t('inactive') }}</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end">
                                <Link :href="route('users.index')" class="text-sm text-gray-600 underline hover:text-gray-900 mr-4">{{ $t('Cancel') }}</Link>
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $t('Create Staff Member') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
