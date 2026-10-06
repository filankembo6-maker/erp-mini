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

const onSplashFinished = () => { showSplash.value = false; };

const cards = [
    { label: 'Produits au catalogue', key: 'products', href: 'products.index', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { label: 'Clients actifs', key: 'clients', href: 'clients.index', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
    { label: 'Devis en attente', key: 'quotes_pending', href: 'quotes.index', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { label: 'Factures impayées', key: 'invoices_unpaid', href: 'invoices.index', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
];

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
const formatDateTime = (date) => new Date(date).toLocaleString('fr-FR', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
</script>

<template>
    <Head title="Dashboard" />

    <WelcomeSplash v-if="showSplash" @finished="onSplashFinished" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto">

            <!-- En-tête -->
            <div class="mb-8 pb-6 border-b border-[#14b8a6]/25">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div class="text-[10px] font-semibold tracking-[0.2em] uppercase text-[#14b8a6]">
                            Cockpit
                        </div>
                        <h1 class="text-[28px] font-semibold text-[#0f172a] tracking-[-0.01em] mt-2">
                            Tableau de bord
                        </h1>
                        <p class="text-[13px] text-[#0f172a]/60 mt-1">
                            Synthèse de l'activité commerciale
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-[#0f172a]/60">
                        <span class="w-1.5 h-1.5 bg-[#14b8a6] rounded-full"></span>
                        <span>Données actualisées à l'instant</span>
                    </div>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <Link v-for="(card, i) in cards" :key="i"
                      :href="route(card.href)"
                      class="bg-white border border-[#14b8a6]/20 rounded-2xl p-6 hover:border-[#14b8a6] hover:shadow-lg hover:shadow-[#14b8a6]/10 transition-all group">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-11 h-11 rounded-xl bg-[#14b8a6] flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-[#14b8a6] opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                    <div class="text-[36px] font-semibold text-[#0f172a] tabular-nums leading-none tracking-[-0.02em]">
                        {{ stats[card.key] }}
                    </div>
                    <div class="text-[10px] text-[#0f172a]/60 uppercase tracking-[0.14em] font-semibold mt-3">
                        {{ card.label }}
                    </div>
                </Link>
            </div>

            <!-- Grille principale -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <div class="lg:col-span-2 space-y-5">

                    <!-- Alertes de stock -->
                    <div class="bg-white border border-[#14b8a6]/20 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-[#14b8a6]/15 bg-gradient-to-r from-[#14b8a6]/10 to-transparent">
                            <div>
                                <h2 class="text-[14px] font-semibold text-[#0f172a]">Alertes de stock</h2>
                                <p class="text-[11px] text-[#0f172a]/60 mt-0.5">Produits sous le seuil de réapprovisionnement</p>
                            </div>
                            <Link :href="route('products.index')" class="text-[10px] uppercase tracking-[0.16em] font-semibold text-[#14b8a6] hover:text-[#0f172a] transition-colors">
                                Consulter
                            </Link>
                        </div>

                        <div v-if="low_stock.length === 0" class="px-6 py-12 text-center">
                            <p class="text-[13px] text-[#0f172a]/60">Aucune alerte. Tous les stocks sont conformes.</p>
                        </div>

                        <div v-else class="divide-y divide-[#14b8a6]/10">
                            <Link v-for="product in low_stock" :key="product.id"
                                  :href="route('products.show', product.id)"
                                  class="flex items-center justify-between px-6 py-4 hover:bg-[#14b8a6]/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-1 h-10 rounded-full bg-[#14b8a6]"></div>
                                    <div>
                                        <div class="text-[13px] font-medium text-[#0f172a]">{{ product.name }}</div>
                                        <div class="text-[11px] text-[#0f172a]/60 font-mono mt-0.5">{{ product.sku }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[14px] font-semibold text-[#14b8a6] tabular-nums">{{ product.stock_quantity }}</div>
                                    <div class="text-[10px] text-[#0f172a]/60 uppercase tracking-[0.12em] mt-0.5">seuil {{ product.stock_alert }}</div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Devis récents -->
                    <div class="bg-white border border-[#14b8a6]/20 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-[#14b8a6]/15 bg-gradient-to-r from-[#14b8a6]/10 to-transparent">
                            <div>
                                <h2 class="text-[14px] font-semibold text-[#0f172a]">Devis récents</h2>
                                <p class="text-[11px] text-[#0f172a]/60 mt-0.5">Dernières propositions commerciales</p>
                            </div>
                            <Link :href="route('quotes.index')" class="text-[10px] uppercase tracking-[0.16em] font-semibold text-[#14b8a6] hover:text-[#0f172a] transition-colors">
                                Consulter
                            </Link>
                        </div>

                        <div v-if="recent_quotes.length === 0" class="px-6 py-12 text-center">
                            <p class="text-[13px] text-[#0f172a]/60">Aucun devis enregistré.</p>
                        </div>

                        <div v-else class="divide-y divide-[#14b8a6]/10">
                            <Link v-for="quote in recent_quotes" :key="quote.id"
                                  :href="route('quotes.show', quote.id)"
                                  class="flex items-center justify-between px-6 py-4 hover:bg-[#14b8a6]/5 transition-colors">
                                <div>
                                    <div class="text-[13px] font-semibold text-[#0f172a] font-mono">{{ quote.reference }}</div>
                                    <div class="text-[11px] text-[#0f172a]/60 mt-0.5">{{ quote.client?.name }} · {{ formatDate(quote.created_at) }}</div>
                                </div>
                                <div class="text-[14px] font-semibold text-[#0f172a] tabular-nums">{{ formatCFA(quote.total_amount) }}</div>
                            </Link>
                        </div>
                    </div>

                </div>

                <div class="space-y-5">

                    <!-- Bloc en attente turquoise -->
                    <div class="rounded-2xl p-6 text-white shadow-lg shadow-[#14b8a6]/30 bg-gradient-to-br from-[#14b8a6] to-[#0d9488]">
                        <div class="text-[10px] uppercase tracking-[0.2em] text-white/80 font-semibold">En attente</div>
                        <div class="text-[11px] text-white/70 mt-1">Devis non convertis</div>
                        <div class="text-[28px] font-semibold tabular-nums mt-4 tracking-[-0.01em]">
                            {{ formatCFA(stats.quotes_pending_amount) }}
                        </div>
                        <div class="text-[12px] text-white/85 mt-2">
                            {{ stats.quotes_pending }} document<span v-if="stats.quotes_pending > 1">s</span>
                        </div>
                    </div>

                    <div class="bg-white border border-[#14b8a6]/20 rounded-2xl p-6">
                        <div class="text-[10px] uppercase tracking-[0.2em] text-[#14b8a6] font-semibold">À encaisser</div>
                        <div class="text-[11px] text-[#0f172a]/60 mt-1">Factures impayées</div>
                        <div class="text-[28px] font-semibold text-[#0f172a] tabular-nums mt-4 tracking-[-0.01em]">
                            {{ formatCFA(stats.invoices_unpaid_amount) }}
                        </div>
                        <div class="text-[12px] text-[#0f172a]/60 mt-2">
                            {{ stats.invoices_unpaid }} facture<span v-if="stats.invoices_unpaid > 1">s</span>
                        </div>
                    </div>

                    <div class="bg-white border border-[#14b8a6]/20 rounded-2xl overflow-hidden">
                        <div class="px-6 py-5 border-b border-[#14b8a6]/15 bg-gradient-to-r from-[#14b8a6]/10 to-transparent">
                            <h2 class="text-[14px] font-semibold text-[#0f172a]">Activité récente</h2>
                        </div>

                        <div v-if="activity.length === 0" class="px-6 py-10 text-center">
                            <p class="text-[13px] text-[#0f172a]/60">Aucun événement.</p>
                        </div>

                        <div v-else class="divide-y divide-[#14b8a6]/10">
                            <div v-for="(item, i) in activity" :key="i" class="px-6 py-4">
                                <div class="text-[12px] text-[#0f172a] leading-relaxed">{{ item.description }}</div>
                                <div class="text-[10px] text-[#0f172a]/60 uppercase tracking-[0.12em] mt-2">
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