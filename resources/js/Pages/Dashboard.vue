<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WelcomeSplash from '@/Components/WelcomeSplash.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { formatCFA, formatRelative } from '@/Utils/format';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? { name: '' });

const showSplash = ref(false);

onMounted(() => {
    if (!sessionStorage.getItem('splash_shown')) {
        showSplash.value = true;
        sessionStorage.setItem('splash_shown', '1');
    }
});

const todayStr = new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const stats = computed(() => page.props.stats || {});
const lowStock = computed(() => page.props.low_stock || []);
const recentQuotes = computed(() => page.props.recent_quotes || []);
const recentInvoices = computed(() => page.props.recent_invoices || []);
const activity = computed(() => page.props.activity || []);
</script>

<template>
    <Head title="Tableau de bord" />

    <WelcomeSplash v-if="showSplash" @finished="showSplash = false" />

    <AuthenticatedLayout>
        <div class="max-w-[1440px] mx-auto space-y-8">

            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <div class="text-[11px] font-medium text-[#a1a1aa] uppercase tracking-wider">{{ todayStr }}</div>
                    <h1 class="text-[28px] font-semibold text-[#09090b] tracking-[-0.025em] mt-1.5">
                        Bonjour, {{ user.name }}
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('quotes.create')"
                          class="inline-flex items-center gap-2 h-9 px-3.5 rounded-lg text-[12px] font-medium bg-[#09090b] hover:bg-[#166534] text-white transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau devis
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-[#e4e4e7] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[11px] font-medium text-[#52525b] uppercase tracking-wider">Produits</div>
                        <div class="w-6 h-6 rounded-md bg-[#f4f4f5] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-[26px] font-semibold text-[#09090b] tabular-nums leading-none">{{ stats.products ?? 0 }}</div>
                    <Link :href="route('products.index')" class="inline-flex items-center gap-1 text-[11px] text-[#166534] font-medium mt-4 hover:underline">
                        Voir le catalogue
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>

                <div class="bg-white rounded-xl border border-[#e4e4e7] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[11px] font-medium text-[#52525b] uppercase tracking-wider">Clients</div>
                        <div class="w-6 h-6 rounded-md bg-[#f4f4f5] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-[26px] font-semibold text-[#09090b] tabular-nums leading-none">{{ stats.clients ?? 0 }}</div>
                    <Link :href="route('clients.index')" class="inline-flex items-center gap-1 text-[11px] text-[#166534] font-medium mt-4 hover:underline">
                        Voir le répertoire
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>

                <div class="bg-white rounded-xl border border-[#e4e4e7] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[11px] font-medium text-[#52525b] uppercase tracking-wider">Devis en attente</div>
                        <div class="w-6 h-6 rounded-md bg-[#f4f4f5] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-[26px] font-semibold text-[#09090b] tabular-nums leading-none">{{ stats.quotes_pending ?? 0 }}</div>
                    <div class="text-[11px] text-[#a1a1aa] mt-2">Montant : {{ formatCFA(stats.quotes_pending_amount ?? 0) }}</div>
                    <Link :href="route('quotes.index')" class="inline-flex items-center gap-1 text-[11px] text-[#166534] font-medium mt-2 hover:underline">
                        Suivre les devis
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>

                <div class="bg-white rounded-xl border border-[#e4e4e7] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[11px] font-medium text-[#52525b] uppercase tracking-wider">À encaisser</div>
                        <div class="w-6 h-6 rounded-md bg-[#fef3c7] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#a16207]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-[26px] font-semibold text-[#a16207] tabular-nums leading-none">{{ stats.invoices_unpaid ?? 0 }}</div>
                    <div class="text-[11px] text-[#a1a1aa] mt-2">Total : {{ formatCFA(stats.invoices_unpaid_amount ?? 0) }}</div>
                    <Link :href="route('invoices.index')" class="inline-flex items-center gap-1 text-[11px] text-[#166534] font-medium mt-2 hover:underline">
                        Encaisser
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-xl border border-[#e4e4e7]">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#f4f4f5]">
                            <div>
                                <h2 class="text-[14px] font-semibold text-[#09090b]">Alertes de stock</h2>
                                <p class="text-[11px] text-[#a1a1aa] mt-0.5">Produits dont le stock est sous le seuil</p>
                            </div>
                            <Link :href="route('products.index')" class="text-[11px] text-[#166534] font-medium hover:underline">Catalogue</Link>
                        </div>

                        <div v-if="lowStock.length === 0" class="px-5 py-12 text-center">
                            <p class="text-[12px] text-[#a1a1aa]">Tous les stocks sont au-dessus du seuil</p>
                        </div>

                        <div v-else class="divide-y divide-[#f4f4f5]">
                            <div v-for="product in lowStock" :key="product.id" class="flex items-center gap-4 px-5 py-3">
                                <div class="w-8 h-8 rounded-md bg-[#f4f4f5] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[12px] font-medium text-[#09090b] truncate">{{ product.name }}</div>
                                    <div class="text-[10px] text-[#a1a1aa] font-mono mt-0.5">{{ product.sku }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-[12px] font-semibold tabular-nums"
                                         :class="product.stock_quantity === 0 ? 'text-red-700' : 'text-[#a16207]'">
                                        {{ product.stock_quantity }} unité{{ product.stock_quantity > 1 ? 's' : '' }}
                                    </div>
                                    <div class="text-[10px] text-[#a1a1aa]">seuil : {{ product.stock_alert }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-[#e4e4e7]">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#f4f4f5]">
                            <div>
                                <h2 class="text-[14px] font-semibold text-[#09090b]">Devis récents</h2>
                                <p class="text-[11px] text-[#a1a1aa] mt-0.5">Dernières propositions</p>
                            </div>
                            <Link :href="route('quotes.index')" class="text-[11px] text-[#166534] font-medium hover:underline">Tout voir</Link>
                        </div>

                        <div v-if="recentQuotes.length === 0" class="px-5 py-12 text-center">
                            <p class="text-[12px] text-[#a1a1aa]">Aucun devis récent</p>
                        </div>

                        <table v-else class="w-full">
                            <tbody>
                                <tr v-for="quote in recentQuotes" :key="quote.id" class="border-b border-[#f4f4f5] last:border-0 hover:bg-[#fafaf9]">
                                    <td class="px-5 py-3 text-[11px] font-mono text-[#52525b] w-32">{{ quote.reference }}</td>
                                    <td class="px-5 py-3 text-[12px] text-[#09090b]">{{ quote.client?.name }}</td>
                                    <td class="px-5 py-3 text-[11px] text-[#a1a1aa] text-right">{{ formatRelative(quote.created_at) }}</td>
                                    <td class="px-5 py-3 text-[12px] font-medium text-[#09090b] text-right tabular-nums w-32">{{ formatCFA(quote.total_amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white rounded-xl border border-[#e4e4e7]">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#f4f4f5]">
                            <h2 class="text-[14px] font-semibold text-[#09090b]">Activité récente</h2>
                        </div>
                        <div v-if="activity.length === 0" class="px-5 py-12 text-center">
                            <p class="text-[12px] text-[#a1a1aa]">Aucune activité pour le moment</p>
                        </div>
                        <div v-else class="divide-y divide-[#f4f4f5]">
                            <div v-for="(item, i) in activity" :key="i" class="flex items-start gap-3 px-5 py-3">
                                <div class="w-1.5 h-1.5 rounded-full mt-1.5 shrink-0"
                                     :class="{
                                         'bg-emerald-500': item.type === 'create',
                                         'bg-sky-500': item.type === 'update',
                                         'bg-red-500': item.type === 'delete',
                                         'bg-[#a1a1aa]': item.type === 'view',
                                     }"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[12px] text-[#09090b]">{{ item.description }}</div>
                                    <div class="text-[10px] text-[#a1a1aa] mt-0.5">{{ item.user }} · {{ formatRelative(item.created_at) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    <div class="bg-white rounded-xl border border-[#e4e4e7] p-5">
                        <h2 class="text-[14px] font-semibold text-[#09090b] mb-4">Raccourcis</h2>
                        <div class="space-y-0.5">
                            <Link :href="route('clients.create')" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#f4f4f5] transition-colors">
                                <div class="w-7 h-7 rounded-md bg-[#f4f4f5] flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <span class="text-[12px] text-[#09090b] font-medium">Ajouter un client</span>
                            </Link>
                            <Link :href="route('products.create')" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#f4f4f5] transition-colors">
                                <div class="w-7 h-7 rounded-md bg-[#f4f4f5] flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <span class="text-[12px] text-[#09090b] font-medium">Ajouter un produit</span>
                            </Link>
                            <Link :href="route('stock-movements.create')" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#f4f4f5] transition-colors">
                                <div class="w-7 h-7 rounded-md bg-[#f4f4f5] flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </div>
                                <span class="text-[12px] text-[#09090b] font-medium">Entrée / sortie stock</span>
                            </Link>
                            <Link :href="route('invoices.index')" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#f4f4f5] transition-colors">
                                <div class="w-7 h-7 rounded-md bg-[#f4f4f5] flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-[12px] text-[#09090b] font-medium">Encaisser des factures</span>
                            </Link>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-[#e4e4e7]">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-[#f4f4f5]">
                            <h2 class="text-[14px] font-semibold text-[#09090b]">Factures récentes</h2>
                        </div>

                        <div v-if="recentInvoices.length === 0" class="px-5 py-8 text-center">
                            <p class="text-[12px] text-[#a1a1aa]">Aucune facture</p>
                        </div>

                        <div v-else class="divide-y divide-[#f4f4f5]">
                            <Link
                                v-for="invoice in recentInvoices"
                                :key="invoice.id"
                                :href="route('invoices.show', invoice.id)"
                                class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-[#fafaf9] transition-colors"
                            >
                                <div class="min-w-0">
                                    <div class="text-[11px] font-mono text-[#52525b] truncate">{{ invoice.reference }}</div>
                                    <div class="text-[11px] text-[#09090b] mt-0.5 truncate">{{ invoice.client?.name }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-[11px] font-medium text-[#09090b] tabular-nums">{{ formatCFA(invoice.total_amount) }}</div>
                                    <div class="text-[10px] mt-0.5 font-medium"
                                         :class="{
                                             'text-emerald-700': invoice.status === 'payee',
                                             'text-[#a16207]': invoice.status === 'impayee',
                                             'text-[#a1a1aa]': invoice.status === 'annulee',
                                         }">
                                        {{ invoice.status === 'payee' ? 'Payée' : invoice.status === 'impayee' ? 'Impayée' : 'Annulée' }}
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>