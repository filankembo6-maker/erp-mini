<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WelcomeSplash from '@/Components/WelcomeSplash.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { formatCFA } from '@/Utils/format';

const props = defineProps({
    stats: Object,
    low_stock: Array,
    recent_quotes: Array,
    recent_invoices: Array,
    activity: Array,
});

const showSplash = ref(false);

onMounted(() => {
    if (!sessionStorage.getItem('splash_seen')) {
        showSplash.value = true;
        sessionStorage.setItem('splash_seen', '1');
    }
});

const onSplashFinished = () => {
    showSplash.value = false;
};

const cards = [
    { label: 'Produits', key: 'products', href: 'products.index', hint: 'au catalogue', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', color: 'text-blue-700 bg-blue-50' },
    { label: 'Clients', key: 'clients', href: 'clients.index', hint: 'enregistrés', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', color: 'text-cyan-700 bg-cyan-50' },
    { label: 'Devis en attente', key: 'quotes_pending', href: 'quotes.index', hint: 'brouillon ou envoyé', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', color: 'text-emerald-700 bg-emerald-50' },
    { label: 'Factures impayées', key: 'invoices_unpaid', href: 'invoices.index', hint: 'à encaisser', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', color: 'text-amber-700 bg-amber-50' },
];

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
const formatDateTime = (date) => new Date(date).toLocaleString('fr-FR', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
</script>

<template>
    <Head title="Dashboard" />

    <WelcomeSplash v-if="showSplash" @finished="onSplashFinished" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto space-y-6">

            <!-- En-tête -->
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-[26px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Tableau de bord</h1>
                    <p class="text-[13px] text-[#737373] mt-1">Vue d'ensemble de votre activité</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#e7e5e4]">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    <span class="text-[12px] text-[#737373] font-medium">Système actif</span>
                </div>
            </div>

            <!-- Cartes stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link v-for="(card, i) in cards" :key="i"
                      :href="route(card.href)"
                      class="bg-white rounded-2xl border border-[#e7e5e4] p-5 hover:border-[#0a0a0a] transition-colors group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" :class="card.color">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                        </svg>
                    </div>
                    <div class="text-[28px] font-semibold text-[#0a0a0a] tabular-nums leading-none">{{ stats[card.key] }}</div>
                    <div class="text-[11px] text-[#a3a3a3] mt-2">{{ card.hint }}</div>
                    <div class="text-[12px] font-medium text-[#737373] mt-0.5 group-hover:text-[#0a0a0a] transition-colors">{{ card.label }}</div>
                </Link>
            </div>

            <!-- Grille principale -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- Colonne gauche -->
                <div class="lg:col-span-2 space-y-4">

                    <!-- Produits en alerte -->
                    <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#f5f5f4]">
                            <h2 class="text-[14px] font-semibold text-[#0a0a0a]">Produits en alerte de stock</h2>
                            <Link :href="route('products.index')" class="text-[12px] text-[#737373] hover:text-[#0a0a0a]">Tout voir</Link>
                        </div>

                        <div v-if="low_stock.length === 0" class="px-6 py-8 text-center text-[13px] text-[#a3a3a3]">
                            Tous les stocks sont au-dessus du seuil d'alerte.
                        </div>

                        <div v-else class="divide-y divide-[#f5f5f4]">
                            <Link v-for="product in low_stock" :key="product.id"
                                  :href="route('products.show', product.id)"
                                  class="flex items-center justify-between px-6 py-3.5 hover:bg-[#fafaf9]">
                                <div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ product.name }}</div>
                                    <div class="text-[11px] text-[#a3a3a3] font-mono mt-0.5">{{ product.sku }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[13px] font-semibold tabular-nums"
                                         :class="product.stock_quantity === 0 ? 'text-red-600' : 'text-amber-600'">
                                        {{ product.stock_quantity }} en stock
                                    </div>
                                    <div class="text-[11px] text-[#a3a3a3] mt-0.5">seuil {{ product.stock_alert }}</div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Devis récents -->
                    <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-[#f5f5f4]">
                            <h2 class="text-[14px] font-semibold text-[#0a0a0a]">Devis récents</h2>
                            <Link :href="route('quotes.index')" class="text-[12px] text-[#737373] hover:text-[#0a0a0a]">Tout voir</Link>
                        </div>

                        <div v-if="recent_quotes.length === 0" class="px-6 py-8 text-center text-[13px] text-[#a3a3a3]">
                            Aucun devis.
                        </div>

                        <div v-else class="divide-y divide-[#f5f5f4]">
                            <Link v-for="quote in recent_quotes" :key="quote.id"
                                  :href="route('quotes.show', quote.id)"
                                  class="flex items-center justify-between px-6 py-3.5 hover:bg-[#fafaf9]">
                                <div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a] font-mono">{{ quote.reference }}</div>
                                    <div class="text-[11px] text-[#737373] mt-0.5">{{ quote.client?.name }} · {{ formatDate(quote.created_at) }}</div>
                                </div>
                                <div class="text-[13px] font-semibold text-[#0a0a0a] tabular-nums">{{ formatCFA(quote.total_amount) }}</div>
                            </Link>
                        </div>
                    </div>

                </div>

                <!-- Colonne droite -->
                <div class="space-y-4">

                    <!-- Montants clés -->
                    <div class="bg-white rounded-2xl border border-[#e7e5e4] p-6">
                        <div class="text-[11px] text-[#a3a3a3] uppercase tracking-wider font-semibold">Devis en attente</div>
                        <div class="text-[24px] font-semibold text-[#0a0a0a] tabular-nums mt-2">{{ formatCFA(stats.quotes_pending_amount) }}</div>
                        <div class="text-[12px] text-[#737373] mt-1">{{ stats.quotes_pending }} devis</div>
                    </div>

                    <div class="bg-white rounded-2xl border border-[#e7e5e4] p-6">
                        <div class="text-[11px] text-[#a3a3a3] uppercase tracking-wider font-semibold">Factures impayées</div>
                        <div class="text-[24px] font-semibold text-amber-600 tabular-nums mt-2">{{ formatCFA(stats.invoices_unpaid_amount) }}</div>
                        <div class="text-[12px] text-[#737373] mt-1">{{ stats.invoices_unpaid }} facture<span v-if="stats.invoices_unpaid > 1">s</span></div>
                    </div>

                    <!-- Activité récente -->
                    <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                        <div class="px-6 py-4 border-b border-[#f5f5f4]">
                            <h2 class="text-[14px] font-semibold text-[#0a0a0a]">Activité récente</h2>
                        </div>

                        <div v-if="activity.length === 0" class="px-6 py-8 text-center text-[13px] text-[#a3a3a3]">
                            Aucune activité pour le moment.
                        </div>

                        <div v-else class="divide-y divide-[#f5f5f4]">
                            <div v-for="(item, i) in activity" :key="i" class="px-6 py-3">
                                <div class="text-[12px] text-[#0a0a0a]">{{ item.description }}</div>
                                <div class="text-[11px] text-[#a3a3a3] mt-0.5">{{ item.user }} · {{ formatDateTime(item.created_at) }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>