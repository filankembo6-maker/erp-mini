<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WelcomeSplash from '@/Components/WelcomeSplash.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const showSplash = ref(true);

const stats = [
    { label: 'Produits', value: '—', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', color: 'text-blue-700 bg-blue-50' },
    { label: 'Clients', value: '—', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', color: 'text-cyan-700 bg-cyan-50' },
    { label: 'Devis', value: '—', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', color: 'text-emerald-700 bg-emerald-50' },
    { label: 'Factures', value: '—', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', color: 'text-slate-700 bg-slate-100' },
];
</script>

<template>
    <Head title="Dashboard" />

    <WelcomeSplash v-if="showSplash" @finished="showSplash = false" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Tableau de bord</h1>
                    <p class="text-sm text-slate-500 mt-1">Vue d'ensemble de votre activité</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    <span class="text-sm text-slate-600">Système actif</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div v-for="(stat, i) in stats" :key="i"
                     class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" :class="stat.color">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">{{ stat.value }}</div>
                    <div class="text-xs text-slate-500 mt-1 font-semibold uppercase tracking-wide">{{ stat.label }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 min-h-[340px]">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-slate-800">Aperçu de l'activité</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">À venir</span>
                    </div>
                    <div class="flex items-center justify-center h-60 text-slate-400">
                        <p class="text-sm">Les graphiques arrivent bientôt</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 min-h-[340px]">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-slate-800">Activités</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">À venir</span>
                    </div>
                    <div class="flex items-center justify-center h-60 text-slate-400">
                        <p class="text-sm">Historique à venir</p>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>