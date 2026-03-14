<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <Head title="Modern Microfinance Management for NGOs" />

    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-indigo-100 selection:text-indigo-700">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">M</div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">MF NGO</span>
                    </div>
                    
                    <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                        <a href="#features" class="hover:text-indigo-600 transition-colors">Features</a>
                        <a href="#how-it-works" class="hover:text-indigo-600 transition-colors">How It Works</a>
                        <a href="#benefits" class="hover:text-indigo-600 transition-colors">Benefits</a>
                    </div>

                    <div class="flex items-center gap-4">
                        <Link :href="route('login')" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">
                            Login
                        </Link>
                        <Link 
                            :href="route('login')" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200"
                        >
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-20 pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold mb-6 border border-indigo-100 uppercase tracking-wider">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Trusted by 50+ NGOs in Bangladesh
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight leading-[1.1] mb-8">
                        Modern <span class="text-indigo-600">Microfinance</span> Management for NGOs
                    </h1>
                    <p class="text-xl text-slate-600 leading-relaxed mb-10 max-w-2xl mx-auto font-medium">
                        A simplified, powerful platform designed for village NGOs to manage members, automate loan tracking, and secure savings with ease.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link 
                            :href="route('login')" 
                            class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white text-lg font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 hover:-translate-y-1 flex items-center justify-center gap-2"
                        >
                           Get Started Free
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </Link>
                        <a 
                            href="#features" 
                            class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 text-lg font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center justify-center"
                        >
                           Learn More
                        </a>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="mt-20 relative max-w-5xl mx-auto">
                    <div class="absolute inset-0 bg-indigo-600/5 blur-3xl rounded-full"></div>
                    <div class="relative bg-slate-900 rounded-2xl shadow-2xl p-2 border border-slate-800 overflow-hidden">
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-800/50 border-b border-slate-700">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                            </div>
                            <div class="flex-1 text-center">
                                <span class="bg-slate-700/50 px-3 py-1 rounded-md text-[10px] text-slate-400 font-mono">dashboard.ngo-system.com</span>
                            </div>
                        </div>
                        <div class="aspect-video bg-white flex items-center justify-center p-8 group overflow-hidden">
                            <!-- Visual placeholder for a dashboard -->
                            <div class="w-full h-full flex flex-col gap-6">
                                <div class="flex gap-4">
                                    <div class="flex-1 h-32 bg-slate-50 rounded-xl border border-slate-100 p-4">
                                        <div class="w-1/2 h-2 bg-slate-200 rounded mb-2"></div>
                                        <div class="w-3/4 h-6 bg-indigo-100 rounded"></div>
                                    </div>
                                    <div class="flex-1 h-32 bg-slate-50 rounded-xl border border-slate-100 p-4">
                                        <div class="w-1/2 h-2 bg-slate-200 rounded mb-2"></div>
                                        <div class="w-3/4 h-6 bg-green-100 rounded"></div>
                                    </div>
                                    <div class="flex-1 h-32 bg-slate-50 rounded-xl border border-slate-100 p-4">
                                        <div class="w-1/2 h-2 bg-slate-200 rounded mb-2"></div>
                                        <div class="w-3/4 h-6 bg-amber-100 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-slate-50 rounded-xl border border-slate-100 p-6">
                                    <div class="w-1/4 h-4 bg-slate-200 rounded mb-6"></div>
                                    <div class="space-y-4">
                                        <div class="flex gap-4">
                                            <div class="w-12 h-12 bg-slate-200 rounded-full"></div>
                                            <div class="flex-1 space-y-2 py-2">
                                                <div class="w-1/3 h-2 bg-slate-200 rounded"></div>
                                                <div class="w-1/4 h-2 bg-slate-100 rounded"></div>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <div class="w-12 h-12 bg-slate-200 rounded-full"></div>
                                            <div class="flex-1 space-y-2 py-2">
                                                <div class="w-1/2 h-2 bg-slate-200 rounded"></div>
                                                <div class="w-1/3 h-2 bg-slate-100 rounded"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20 max-w-3xl mx-auto">
                    <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">Core Features</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">Everything you need to run your NGO efficiently</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Member Management -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Member Management</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Keep detailed records of all your members, including village data, NID, and contact info.</p>
                    </div>

                    <!-- Loan Tracking -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Loan Tracking</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Automated interest calculation, disbursement records, and outstanding balance monitoring.</p>
                    </div>

                    <!-- Collection Recording -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Collection Recording</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Easily record weekly or monthly collections with instant digital receipt generation.</p>
                    </div>

                    <!-- Savings Accounts -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Savings Accounts</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Manage member savings, deposits, and withdrawals with a transparent transaction history.</p>
                    </div>

                    <!-- Financial Reports -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 mb-6 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Financial Reports</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Generate collection reports, loan summaries, and ledger statements in one click.</p>
                    </div>

                    <!-- Multi-language Support -->
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5a18.022 18.022 0 01-3.827-5.802M11 5v2m-7 8c1.333-3.333 4-6.667 7-6.667a7.513 7.513 0 013.573 1.053L16.5 10.5M4 11h14"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Multi-language Support</h4>
                        <p class="text-slate-600 leading-relaxed font-medium">Switch effortlessly between English and Bengali for better local accessibility.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-32 overflow-hidden bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-24 max-w-3xl mx-auto">
                    <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">The Process</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">Step-by-step NGO operation</h3>
                </div>

                <div class="relative">
                    <!-- Progress Line (Desktop) -->
                    <div class="hidden lg:block absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-24 relative">
                        <!-- Step 1 -->
                        <div class="relative text-center bg-white">
                            <div class="w-20 h-20 bg-white rounded-full border-[6px] border-slate-100 flex items-center justify-center text-3xl font-black text-indigo-600 mx-auto mb-8 shadow-xl">1</div>
                            <h5 class="text-2xl font-bold text-slate-900 mb-4">Register Members</h5>
                            <p class="text-slate-600 font-medium leading-relaxed">Add village members to the system with their KYC details and biometric-ready profiles.</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative text-center bg-white">
                            <div class="w-20 h-20 bg-white rounded-full border-[6px] border-slate-100 flex items-center justify-center text-3xl font-black text-indigo-600 mx-auto mb-8 shadow-xl">2</div>
                            <h5 class="text-2xl font-bold text-slate-900 mb-4">Disburse Loans</h5>
                            <p class="text-slate-600 font-medium leading-relaxed">Create loan products and disburse funds to members with automated schedules and snapshots.</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative text-center bg-white">
                            <div class="w-20 h-20 bg-white rounded-full border-[6px] border-slate-100 flex items-center justify-center text-3xl font-black text-indigo-600 mx-auto mb-8 shadow-xl">3</div>
                            <h5 class="text-2xl font-bold text-slate-900 mb-4">Record Collections</h5>
                            <p class="text-slate-600 font-medium leading-relaxed">Collect installments and savings in the field, with real-time updates and SMS-ready summaries.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="py-32 bg-slate-900 text-white relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px]"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="flex flex-col lg:flex-row items-center gap-20">
                    <div class="flex-1 space-y-8">
                        <div>
                            <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-[0.2em] mb-4">Why Choose Us</h2>
                            <h3 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">Designed for field work and built for growth</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex gap-4 items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center shrink-0 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h6 class="text-xl font-bold mb-1">Works on Mobile Devices</h6>
                                    <p class="text-slate-400 font-medium leading-relaxed">Field workers can use smartphones to record data directly in the village.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center shrink-0 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h6 class="text-xl font-bold mb-1">Simple Reporting</h6>
                                    <p class="text-slate-400 font-medium leading-relaxed">No complicated accounting knowledge needed. Simple screens and direct results.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center shrink-0 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h6 class="text-xl font-bold mb-1">Secure Data</h6>
                                    <p class="text-slate-400 font-medium leading-relaxed">Your data is stored in a secure cloud, with automated daily backups and SSL encryption.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 w-full max-w-xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="aspect-square bg-slate-800 rounded-2xl flex flex-col p-8 justify-between hover:bg-slate-800/80 transition-colors">
                                <div class="bg-indigo-500/20 w-12 h-12 rounded-lg flex items-center justify-center text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="font-bold text-lg leading-tight">Mobile Optimized Interface</div>
                            </div>
                            <div class="aspect-square bg-slate-800 rounded-2xl flex flex-col p-8 justify-between hover:bg-slate-800/80 transition-colors translate-y-8">
                                <div class="bg-indigo-500/20 w-12 h-12 rounded-lg flex items-center justify-center text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                </div>
                                <div class="font-bold text-lg leading-tight">Real-time Analytics</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-indigo-600 rounded-[2.5rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-indigo-200">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-indigo-800"></div>
                    
                    <div class="relative max-w-2xl mx-auto space-y-10">
                        <h2 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight">Ready to modernize your operations?</h2>
                        <p class="text-xl text-indigo-50 font-medium opacity-90 leading-relaxed">
                            Join over 50+ NGOs already improving community life with our management platform.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <Link 
                                :href="route('login')" 
                                class="w-full sm:w-auto px-10 py-5 bg-white text-indigo-600 text-lg font-black rounded-2xl hover:bg-indigo-50 transition-all shadow-xl"
                            >
                                Start Your Journey
                            </Link>
                            <Link 
                                :href="route('login')" 
                                class="text-white hover:text-indigo-100 font-bold transition-colors underline underline-offset-8 decoration-2"
                            >
                                View Demo
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <div class="space-y-6">
                        <div class="flex items-center gap-2">
                             <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">M</div>
                             <span class="text-xl font-bold tracking-tight text-slate-900">MF NGO</span>
                        </div>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            Empowering village NGOs with modern financial tools to drive community development and financial inclusion.
                        </p>
                    </div>

                    <div>
                        <h6 class="text-slate-900 font-bold mb-6">Product</h6>
                        <ul class="space-y-4 text-slate-500 font-medium">
                            <li><a href="#features" class="hover:text-indigo-600 transition-colors">Features</a></li>
                            <li><a href="#how-it-works" class="hover:text-indigo-600 transition-colors">How It Works</a></li>
                            <li><a href="#benefits" class="hover:text-indigo-600 transition-colors">Benefits</a></li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-slate-900 font-bold mb-6">Support</h6>
                        <ul class="space-y-4 text-slate-500 font-medium">
                            <li><a href="#" class="hover:text-indigo-600 transition-colors">Documentation</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-slate-900 font-bold mb-6">Contact</h6>
                        <ul class="space-y-4 text-slate-500 font-medium">
                            <li><a href="mailto:info@mf-ngo.com" class="hover:text-indigo-600 transition-colors">info@mf-ngo.com</a></li>
                            <li><a href="tel:+8801700000000" class="hover:text-indigo-600 transition-colors">+880 1700-000000</a></li>
                            <li class="flex gap-4 mt-2">
                                <a href="#" class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                                <a href="#" class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 font-medium text-sm">&copy; 2026 MF NGO. All rights reserved.</p>
                    <p class="text-slate-400 font-mono text-xs">Modern Microfinance v1.0.0</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
