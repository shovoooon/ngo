<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(
        route('users.index'),
        { search: value },
        { preserveState: true, replace: true }
    );
});
</script>

<template>
    <Head :title="$t('Staff Management')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Staff Management') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden overflow-x-auto shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Action Bar -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="w-1/3">
                                <input
                                    v-model="search"
                                    type="text"
                                    :placeholder="$t('Search by name or phone...')"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                />
                            </div>
                            <Link
                                :href="route('users.create')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ $t('Add New Staff') }}
                            </Link>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ $t('Name') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Phone Number') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Role') }}</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Status') }}</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">{{ $t('Edit') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ user.name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ user.phone }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">{{ $t(user.user_type) }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <span 
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            >
                                                {{ $t(user.status) }}
                                            </span>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end gap-3">
                                                <Link 
                                                    :href="route('users.edit', user.id)" 
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    {{ $t('Edit') }}
                                                </Link>
                                                <Link 
                                                    method="delete"
                                                    :href="route('users.destroy', user.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                    :onBefore="() => confirm($t('Are you sure you want to delete this staff member?'))"
                                                >
                                                    {{ $t('Delete') }}
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="users.data.length === 0">
                                        <td colspan="5" class="py-4 text-center text-sm text-gray-500">{{ $t('No staff members found.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 flex items-center justify-between" v-if="users.links.length > 3">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        {{ $t('Showing') }} <span class="font-medium">{{ users.from }}</span> {{ $t('to') }} <span class="font-medium">{{ users.to }}</span> {{ $t('of') }} <span class="font-medium">{{ users.total }}</span> {{ $t('results') }}
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <Link 
                                            v-for="(link, i) in users.links" 
                                            :key="i"
                                            :href="link.url"
                                            v-html="link.label"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            :class="[
                                                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                !link.url ? 'opacity-50 cursor-not-allowed hidden' : '',
                                                i === 0 ? 'rounded-l-md' : '',
                                                i === users.links.length - 1 ? 'rounded-r-md' : ''
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
