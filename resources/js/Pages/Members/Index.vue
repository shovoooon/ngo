<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    members: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

watch(search, debounce(function (value) {
    router.get(route('members.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));
</script>

<template>
    <Head :title="$t('Members Registry')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2">
                <h2 class="text-xl font-bold leading-tight text-gray-800">{{ $t('Members Registry') }}</h2>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
                    <input
                        v-model="search"
                        type="search"
                        :placeholder="$t('Search name, phone, code...')"
                        class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm"
                    />
                    <Link
                        :href="route('members.create')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                    >
                        {{ $t('Add New Member') }}
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                </div>
                
                <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $t('M-Code') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $t('Candidate Name') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $t('Phone / NID') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $t('Status') }}</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">{{ $t('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="member in members.data" :key="member.id" class="hover:bg-indigo-50/50 transition duration-150">
                                    <td class="px-6 py-4 font-mono text-xs font-bold text-indigo-600">
                                        {{ member.member_code }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ member.name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5" v-if="member.village">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ member.village }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800">{{ member.phone }}</div>
                                        <div class="text-xs text-gray-500">NID: {{ member.nid || 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="[member.status === 'active' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200', 'px-2.5 py-1 text-xs font-semibold rounded-full border']">
                                            {{ $t(member.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <Link :href="route('members.show', member.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold bg-indigo-50 px-2 py-1 rounded">{{ $t('View') }}</Link>
                                        <Link :href="route('members.edit', member.id)" class="text-amber-600 hover:text-amber-900 font-semibold bg-amber-50 px-2 py-1 rounded">{{ $t('Edit') }}</Link>
                                    </td>
                                </tr>
                                <tr v-if="members.data.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                        {{ $t('No members found.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-between items-center" v-if="members.links && members.links.length > 3">
                    <div class="text-sm text-gray-600">
                        Showing {{ (members.meta?.from) || 0 }} to {{ (members.meta?.to) || 0 }} of {{ (members.meta?.total) || 0 }} entries
                    </div>
                    <div class="flex gap-1">
                        <Link
                            v-for="(link, k) in members.meta?.links"
                            :key="k"
                            :href="link.url || ''"
                            class="px-3 py-1 text-sm border rounded-md"
                            :class="[
                                link.active ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-white text-gray-700 hover:bg-gray-50',
                                !link.url ? 'opacity-50 cursor-not-allowed' : ''
                            ]"
                            v-html="link.label"
                            preserve-scroll
                            :disabled="!link.url"
                        />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
