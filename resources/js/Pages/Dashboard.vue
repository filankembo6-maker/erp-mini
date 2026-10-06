<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WelcomeSplash from '@/Components/WelcomeSplash.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { formatCFA } from '@/Utils/format';

defineProps({
    stats: { type: Object, default: () => ({}) },
    low_stock: { type: Array, default: () => [] },
    recent_quotes: { type: Array, default: () => [] },
    recent_invoices: { type: Array, default: () => [] },
    activity: { type: Array, default: () => [] },
});

const showSplash = ref(false);
onMounted(() => {
    if (!sessionStorage.getItem('splash_seen')) {
        showSplash.value = true;
        sessionStorage.setItem('splash_seen', '1');
    }
});
const onSplashFinished = () => { showSplash.value = false; };

const roles = computed(() => usePage().props.auth?.user?.roles);
const can = (allowed) =>
    !roles.value || roles.value.includes('admin') || allowed.some((r) => roles.value.includes(r));

const allCards = [
    { label: 'Chiffre d\'affaires du mois', key: 'revenue_month', money: true, href: 'invoices.index', roles: ['commercial'], color: 'bg-[#1e3a8a]', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Devis en attente', key: 'quotes_pending', href: 'quotes.index', roles: ['commercial'], color: 'bg-[#10b981]', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { label: 'Factures impayées', key: 'invoices_unpaid', href: 'invoices.index', roles: ['commercial'], color: 'bg-[#f59e0b]', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
    { label: 'Factures en retard (+30 j)', key: 'invoices_late', href: 'invoices.index', roles: ['commercial'], color: 'bg-[#dc2626]', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Produits au catalogue', key: 'products', href: 'products.index', roles: ['magasinier'], color: 'bg-[#1e3a8a]', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { label: 'Produits en alerte de stock', key: 'low_stock_count', href: 'products.index', roles: ['magasinier'], color: 'bg-[#dc2626]', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
    { label: 'Clients actifs', key: 'clients', href: 'clients.index', roles: ['commercial'], color: 'bg-[#0891b2]', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
];

const cards = computed(() => allCards.filter((c) => can(c.roles)));

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
const formatDateTime = (date) => new Date(date).toLocaleString('fr-FR', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });

const statusClass = (status) => {
    const s = (status || '').toLowerCase();
    if (s === 'payée' || s === 'payee' || s === 'paid') return 'bg-emerald-100 text-emerald-700';
    if (s === 'impayée' || s === 'impayee' || s === 'unpaid') return 'bg-amber-100 text-amber-700';
    if (s === 'annulée' || s === 'annulee' || s === 'cancelled') return 'bg-slate-200 text-slate-600';
    return 'bg-slate-100 text-slate-600';
};
</script>

<template>
    <Head title="Dashboard" />

    <WelcomeSplash v-if="showSplash" @finished="onSplashFinished" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto">

            <!-- Cartes de synthèse -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <Link v-for="card in cards" :key="card.key" :href="route(card.href)"
                      class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-slate-300 hover:shadow-lg transition-all group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm" :class="card.color">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                    <div class="font-bold text-[#1e3a8a] tabular-nums leading-none tracking-[-0.02em]"
                         :class="card.money ? 'text-[26px]' : 'text-[36px]'">
                        {{ card.money ? formatCFA(stats[card.key] ?? 0) : (stats[card.key] ?? 0) }}
                    </div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-[0.14em] font-semibold mt-3">
                        {{ card.label }}
                    </div>
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Alertes de stock -->
                    <div v-if="can(['magasinier'])" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-red-500 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[14px] font-bold text-[#1e3a8a]">Alertes de stock</h2>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Produits sous le seuil de réapprovisionnement</p>
                                </div>
                            </div>
                            <Link :href="route('products.index')" class="text-[10px] uppercase tracking-[0.16em] font-semibold text-[#0891b2] hover:text-[#1e3a8a] transition-colors">
                                Consulter
                            </Link>
                        </div>

                        <div v-if="low_stock.length === 0" class="px-6 py-12 text-center">
                            <p class="text-[13px] text-slate-500">Aucune alerte. Tous les stocks sont conformes.</p>
                        </div>

                        <div v-else class="divide-y divide-slate-100">
                            <Link v-for="product in low_stock" :key="product.id" :href="route('products.show', product.id)"
                                  class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-1 h-10 rounded-full" :class="product.stock_quantity === 0 ? 'bg-red-500' : 'bg-amber-500'"></div>
                                    <div>
                                        <div class="text-[13px] font-medium text-[#1e3a8a]">{{ product.name }}</div>
                                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">{{ product.sku }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[14px] font-bold tabular-nums" :class="product.stock_quantity === 0 ? 'text-red-600' : 'text-amber-600'">
                                        {{ product.stock_quantity === 0 ? 'Rupture' : product.stock_quantity }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-[0.12em] mt-0.5">seuil {{ product.stock_alert }}</div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Devis récents -->
                    <div v-if="can(['commercial'])" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[14px] font-bold text-[#1e3a8a]">Devis récents</h2>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Dernières propositions commerciales</p>
                                </div>
                            </div>
                            <Link :href="route('quotes.index')" class="text-[10px] uppercase tracking-[0.16em] font-semibold text-[#10b981] hover:text-[#1e3a8a] transition-colors">
                                Consulter
                            </Link>
                        </div>

                        <div v-if="recent_quotes.length === 0" class="px-6 py-12 text-center">
                            <p class="text-[13px] text-slate-500">Aucun devis enregistré.</p>
                        </div>

                        <div v-else class="divide-y divide-slate-100">
                            <Link v-for="quote in recent_quotes" :key="quote.id" :href="route('quotes.show', quote.id)"
                                  class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
                                <div>
                                    <div class="text-[13px] font-bold text-[#1e3a8a] font-mono">{{ quote.reference }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ quote.client?.name }} · {{ formatDate(quote.created_at) }}</div>
                                </div>
                                <div class="text-[14px] font-bold text-[#1e3a8a] tabular-nums">{{ formatCFA(quote.total_amount) }}</div>
                            </Link>
                        </div>
                    </div>

                    <!-- Factures récentes -->
                    <div v-if="can(['commercial'])" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[14px] font-bold text-[#1e3a8a]">Factures récentes</h2>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Dernières factures émises et leur statut</p>
                                </div>
                            </div>
                            <Link :href="route('invoices.index')" class="text-[10px] uppercase tracking-[0.16em] font-semibold text-[#d97706] hover:text-[#1e3a8a] transition-colors">
                                Consulter
                            </Link>
                        </div>

                        <div v-if="recent_invoices.length === 0" class="px-6 py-12 text-center">
                            <p class="text-[13px] text-slate-500">Aucune facture émise.</p>
                        </div>

                        <div v-else class="divide-y divide-slate-100">
                            <Link v-for="invoice in recent_invoices" :key="invoice.id" :href="route('invoices.show', invoice.id)"
                                  class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
                                <div>
                                    <div class="text-[13px] font-bold text-[#1e3a8a] font-mono">{{ invoice.reference }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ invoice.client?.name }} · {{ formatDate(invoice.created_at) }}</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full" :class="statusClass(invoice.status)">{{ invoice.status }}</span>
                                    <div class="text-[14px] font-bold text-[#1e3a8a] tabular-nums">{{ formatCFA(invoice.total_amount) }}</div>
                                </div>
                            </Link>
                        </div>
                    </div>

                </div>

                <!-- Colonne latérale -->
                <div class="space-y-5">

                    <div v-if="can(['commercial'])" class="rounded-2xl p-6 text-white shadow-lg bg-[#1e3a8a]">
                        <div class="text-[10px] uppercase tracking-[0.2em] text-white/85 font-semibold">En attente</div>
                        <div class="text-[11px] text-white/75 mt-1">Devis non convertis</div>
                        <div class="text-[28px] font-bold tabular-nums mt-4 tracking-[-0.01em] drop-shadow-sm">
                            {{ formatCFA(stats.quotes_pending_amount ?? 0) }}
                        </div>
                        <div class="text-[12px] text-white/90 mt-2">
                            {{ stats.quotes_pending ?? 0 }} document<span v-if="(stats.quotes_pending ?? 0) > 1">s</span>
                        </div>
                    </div>

                    <div v-if="can(['commercial'])" class="bg-white border border-slate-200 rounded-2xl p-6">
                        <div class="text-[10px] uppercase tracking-[0.2em] text-[#f59e0b] font-semibold">À encaisser</div>
                        <div class="text-[11px] text-slate-500 mt-1">Factures impayées</div>
                        <div class="text-[28px] font-bold text-[#1e3a8a] tabular-nums mt-4 tracking-[-0.01em]">
                            {{ formatCFA(stats.invoices_unpaid_amount ?? 0) }}
                        </div>
                        <div class="text-[12px] text-slate-500 mt-2">
                            {{ stats.invoices_unpaid ?? 0 }} facture<span v-if="(stats.invoices_unpaid ?? 0) > 1">s</span>
                            <span v-if="(stats.invoices_late ?? 0) > 0" class="text-red-600 font-semibold">
                                dont {{ stats.invoices_late }} en retard
                            </span>
                        </div>
                    </div>

                    <!-- Activité récente (admin uniquement) -->
                    <div v-if="can([])" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100">
                            <h2 class="text-[14px] font-bold text-[#1e3a8a]">Activité récente</h2>
                        </div>

                        <div v-if="activity.length === 0" class="px-6 py-10 text-center">
                            <p class="text-[13px] text-slate-500">Aucun événement.</p>
                        </div>

                        <div v-else class="divide-y divide-slate-100">
                            <div v-for="(item, i) in activity" :key="i" class="px-6 py-4">
                                <div class="text-[12px] text-[#1e3a8a] leading-relaxed">{{ item.description }}</div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-[0.12em] mt-2">
                                    {{ item.user }} · {{ formatDateTime(item.created_at) }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>