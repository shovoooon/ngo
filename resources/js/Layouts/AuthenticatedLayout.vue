<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";

const showingMobileMenu = ref(false);
const showReportsMenu = ref(false);

const toggleMobileMenu = () => {
    showingMobileMenu.value = !showingMobileMenu.value;
};

const closeMobileMenu = () => {
    showingMobileMenu.value = false;
};

import { watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const flashMessage = ref(null);
const flashType = ref("success");
const page = usePage();

const showFlash = (message, type = "success") => {
    flashMessage.value = message;
    flashType.value = type;
    setTimeout(() => {
        flashMessage.value = null;
    }, 5000);
};

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) showFlash(flash.success, "success");
        if (flash?.error) showFlash(flash.error, "error");
    },
    { deep: true },
);

onMounted(() => {
    if (page.props.flash?.success)
        showFlash(page.props.flash.success, "success");
    if (page.props.flash?.error) showFlash(page.props.flash.error, "error");
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Mobile Sidebar Backdrop -->
        <div
            v-if="showingMobileMenu"
            @click="closeMobileMenu"
            class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden transition-opacity"
        ></div>

        <!-- Sidebar Navigation -->
        <nav
            :class="[
                showingMobileMenu ? 'translate-x-0' : '-translate-x-full',
                'fixed inset-y-0 left-0 z-50 w-64 bg-indigo-900 text-white transition duration-300 ease-in-out lg:static lg:translate-x-0 lg:flex lg:flex-col shadow-xl',
            ]"
        >
            <!-- Logo Area -->
            <div
                class="flex h-16 shrink-0 items-center justify-between px-4 bg-indigo-950"
            >
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-2"
                >
                    <ApplicationLogo
                        class="block h-8 w-auto fill-current text-white"
                    />
                    <span class="font-bold text-lg tracking-wider">{{ $page.props.settings.company_name || 'MF NGO' }}</span>
                </Link>
                <button
                    @click="closeMobileMenu"
                    class="lg:hidden p-2 text-indigo-300 hover:text-white"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Scrollable Nav Links -->
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <Link
                    :href="route('dashboard')"
                    :class="[
                        route().current('dashboard')
                            ? 'bg-indigo-800 text-white'
                            : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                    ]"
                >
                    <svg
                        class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                        />
                    </svg>
                    {{ $t("Dashboard") }}
                </Link>

                <Link
                    :href="route('members.index')"
                    :class="[
                        route().current('members.*')
                            ? 'bg-indigo-800 text-white'
                            : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                    ]"
                >
                    <svg
                        class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                        />
                    </svg>
                    {{ $t("Members") }}
                </Link>

                <Link
                    :href="route('loans.index')"
                    :class="[
                        route().current('loans.*')
                            ? 'bg-indigo-800 text-white'
                            : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                    ]"
                >
                    <svg
                        class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    {{ $t("Loans") }}
                </Link>

                <Link
                    :href="route('collections.index')"
                    :class="[
                        route().current('collections.*')
                            ? 'bg-indigo-800 text-white'
                            : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                    ]"
                >
                    <svg
                        class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                        />
                    </svg>
                    {{ $t("Collections") }}
                </Link>

                <Link
                    :href="route('savings.index')"
                    :class="[
                        route().current('savings.*')
                            ? 'bg-indigo-800 text-white'
                            : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                    ]"
                >
                    <svg
                        class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                        />
                    </svg>
                    {{ $t("Savings") }}
                </Link>

                <!-- Reports Section Menu -->
                <div class="pt-4 pb-2">
                    <button
                        @click="showReportsMenu = !showReportsMenu"
                        class="w-full flex items-center justify-between text-indigo-100 hover:bg-indigo-800 hover:text-white group px-2 py-2 text-sm font-medium rounded-md transition-colors"
                    >
                        <div class="flex items-center">
                            <svg
                                class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
                                />
                            </svg>
                            {{ $t("Reports") }}
                        </div>
                        <svg
                            :class="[
                                showReportsMenu ? 'rotate-180' : '',
                                'h-4 w-4 transform transition-transform duration-200',
                            ]"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                    <div
                        v-show="showReportsMenu"
                        class="mt-1 space-y-1 pl-10 pr-2"
                    >
                        <Link
                            :href="route('reports.loans')"
                            :class="[
                                route().current('reports.loans')
                                    ? 'bg-indigo-800 text-white'
                                    : 'text-indigo-200 hover:bg-indigo-800 hover:text-white',
                                'group flex items-center px-2 py-1.5 text-sm font-medium rounded-md transition-colors',
                            ]"
                        >
                            {{ $t("Loan Report") }}
                        </Link>
                        <Link
                            :href="route('reports.collections')"
                            :class="[
                                route().current('reports.collections')
                                    ? 'bg-indigo-800 text-white'
                                    : 'text-indigo-200 hover:bg-indigo-800 hover:text-white',
                                'group flex items-center px-2 py-1.5 text-sm font-medium rounded-md transition-colors',
                            ]"
                        >
                            {{ $t("Collections Tracker") }}
                        </Link>
                        <Link
                            :href="route('reports.savings')"
                            :class="[
                                route().current('reports.savings')
                                    ? 'bg-indigo-800 text-white'
                                    : 'text-indigo-200 hover:bg-indigo-800 hover:text-white',
                                'group flex items-center px-2 py-1.5 text-sm font-medium rounded-md transition-colors',
                            ]"
                        >
                            {{ $t("Savings Registry") }}
                        </Link>
                    </div>
                </div>

                <div
                    v-if="$page.props.auth.user.user_type === 'admin'"
                    class="pt-4"
                >
                    <Link
                        :href="route('users.index')"
                        :class="[
                            route().current('users.*')
                                ? 'bg-indigo-800 text-white'
                                : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors',
                        ]"
                    >
                        <svg
                            class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                            />
                        </svg>
                        {{ $t("Staff Management") }}
                    </Link>

                    <Link
                        :href="route('settings.index')"
                        :class="[
                            route().current('settings.*')
                                ? 'bg-indigo-800 text-white'
                                : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors mt-1',
                        ]"
                    >
                        <svg
                            class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"
                            />
                        </svg>
                        {{ $t("System Settings") }}
                    </Link>

                    <div
                        class="mt-4 px-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider"
                    >
                        {{ $t("Product Management") }}
                    </div>

                    <Link
                        :href="route('loan-products.index')"
                        :class="[
                            route().current('loan-products.*')
                                ? 'bg-indigo-800 text-white'
                                : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors mt-1',
                        ]"
                    >
                        <svg
                            class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        {{ $t("Loan Products") }}
                    </Link>

                    <Link
                        :href="route('savings-products.index')"
                        :class="[
                            route().current('savings-products.*')
                                ? 'bg-indigo-800 text-white'
                                : 'text-indigo-100 hover:bg-indigo-800 hover:text-white',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors mt-1',
                        ]"
                    >
                        <svg
                            class="mr-3 flex-shrink-0 h-5 w-5 text-indigo-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                            />
                        </svg>
                        {{ $t("Savings Products") }}
                    </Link>
                </div>
            </div>

            <!-- Profile Overview (Bottom of Sidebar) -->
            <div class="border-t border-indigo-800 p-4">
                <div class="flex items-center">
                    <div
                        class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white uppercase shadow-sm"
                    >
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="ml-3">
                        <p
                            class="text-sm font-bold text-white truncate max-w-[130px]"
                        >
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p
                            class="text-xs font-medium text-indigo-300 capitalize"
                        >
                            {{ $t($page.props.auth.user.user_type) }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <Link
                        :href="route('profile.edit')"
                        class="w-full flex-1 flex justify-center py-1.5 px-2 bg-indigo-800 hover:bg-indigo-700 rounded text-xs font-semibold uppercase tracking-wider transition"
                    >
                        {{ $t("Profile") }}
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="w-full flex-1 flex justify-center py-1.5 px-2 bg-indigo-800 hover:bg-rose-600 rounded text-xs font-semibold uppercase tracking-wider transition"
                    >
                        {{ $t("Log Out") }}
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div
            class="flex flex-1 flex-col overflow-hidden w-full h-full min-h-screen"
        >
            <!-- Mobile Top Header -->
            <header
                class="bg-white shadow-sm border-b border-gray-100 flex items-center justify-between lg:hidden px-4 h-16 shrink-0 z-30"
            >
                <div class="flex items-center gap-2">
                    <ApplicationLogo
                        class="block h-6 w-auto fill-current text-indigo-600"
                    />
                    <span class="font-bold text-gray-800 text-lg">{{ $page.props.settings.company_name || 'MF NGO' }}</span>
                </div>
                <button
                    @click="toggleMobileMenu"
                    class="text-gray-500 hover:text-indigo-600 p-2 rounded-md hover:bg-gray-100 transition-colors"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </header>

            <!-- Global Flash Messages -->
            <Transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="flashMessage"
                    class="fixed top-4 right-4 z-[100] max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg
                                    v-if="flashType === 'success'"
                                    class="h-6 w-6 text-green-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-6 w-6 text-red-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">
                                    {{
                                        flashType === "success"
                                            ? $t("Success")
                                            : $t("Error")
                                    }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ flashMessage }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    @click="flashMessage = null"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <span class="sr-only">{{
                                        $t("Close")
                                    }}</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Page Title Slot -->
            <div
                v-if="$slots.header"
                class="bg-white shadow-sm border-b border-gray-100 px-4 py-4 sm:px-6 lg:px-8 z-10 hidden sm:block"
            >
                <slot name="header" />
            </div>

            <!-- Scrollable Content Viewport -->
            <main class="flex-1 overflow-y-auto w-full bg-gray-50 pb-12">
                <div
                    v-if="$slots.header"
                    class="bg-white border-b border-gray-100 px-4 py-4 sm:hidden"
                >
                    <slot name="header" />
                </div>
                <div class="w-full">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
