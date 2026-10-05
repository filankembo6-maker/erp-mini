<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { formatCFA, formatDate } from '@/Utils/format';

const props = defineProps({ quotes: Array });
const page = usePage();
const filter = ref('all');
const search = ref('');

const stats = computed(() => {
    const pending = props.quotes.filter(q => q.status === 'envoye').length;
    const total = props.quotes.reduce((sum, q) => sum + parseFloat(q.total_amount), 0);
    return { total: props.quotes.length, pending, amount: total };
});

const filtered = computed(() => {
    let list = props.quotes;
    if (filter.value !== 'all') list = list.filter(q => q.status === filter.value);
    if (search.value) {
        const q = search.value.toLowerCase();
        list = list.filter(quote =>
            quote.reference.toLowerCase().includes(q) ||
            quote.client?.name?.toLowerCase().includes(q)
        );
    }
    return list;
});

const statusMap = {
    brouillon: { label: 'Brouillon', cls: 'bg-neutral-100 text-neutral-600', dot: 'bg-neutral-400' },
    envoye: { label: 'Envoyé', cls: 'bg-sky-50 text-sky-700', dot: 'bg-sky-500' },
    accepte: { label: 'Accepté', cls: 'bg-emerald-50 text-emerald-700', dot: 'bg-emerald-500' },
    refuse: { label: 'Refusé', cls: 'bg-red-50 text-red-700', dot: 'bg-red-500' },
};

const deleteQuote = (id, ref) => {
    if (confirm(`Supprimer le devis ${ref} ?`)) {
        router.delete(route('quotes.destroy', id));
    }
};
</script>

<template>
    <Head title="Devis" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto space-y-6">

            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-[26px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Devis</h1>
                    <p class="text-[13px] text-[#737373] mt-1">Propositions commerciales</p>
                </div>
                <Link :href="route('quotes.create')"
                      class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-[13px] font-semibold bg-[#0a0a0a] hover:bg-[#166534] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau devis
                </Link>
            </div>

            <div v-if="page.props.flash?.success" class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-800">
                {{ page.props.flash.success }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Total devis</div>
                    <div class="text-[28px] font-semibold text-[#0a0a0a] tabular-nums leading-none mt-3">{{ stats.total }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">En attente</div>
                    <div class="text-[28px] font-semibold text-sky-700 tabular-nums leading-none mt-3">{{ stats.pending }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Montant total</div>
                    <div class="text-[20px] font-semibold text-[#0a0a0a] tabular-nums leading-tight mt-3">{{ formatCFA(stats.amount) }}</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                <div class="flex flex-wrap items-center gap-3 p-4 border-b border-[#f5f5f4]">
                    <div class="relative flex-1 min-w-[200px]">
                        <input v-model="search" type="search" placeholder="Rechercher par référence ou client..."
                               class="w-full h-10 px-4 rounded-lg bg-[#fafaf9] border border-[#e7e5e4] text-[13px] focus:outline-none focus:border-[#166534] focus:bg-white" />
                    </div>
                    <select v-model="filter" class="h-10 px-3 rounded-lg bg-[#fafaf9] border border-[#e7e5e4] text-[12px] focus:outline-none focus:border-[#166534]">
                        <option value="all">Tous les statuts</option>
                        <option value="brouillon">Brouillon</option>
                        <option value="envoye">Envoyé</option>
                        <option value="accepte">Accepté</option>
                        <option value="refuse">Refusé</option>
                    </select>
                </div>

                <div v-if="quotes.length === 0" class="px-6 py-20 text-center">
                    <h3 class="text-[15px] font-semibold text-[#0a0a0a]">Aucun devis</h3>
                    <p class="text-[13px] text-[#737373] mt-1.5">Créez votre premier devis pour un client.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-[#f5f5f4]">
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-center text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="quote in filtered" :key="quote.id" class="border-b border-[#fafaf9] hover:bg-[#fafaf9]">
                            <td class="px-6 py-4 text-[12px] font-mono font-medium text-[#0a0a0a]">{{ quote.reference }}</td>
                            <td class="px-6 py-4 text-[13px] text-[#0a0a0a]">{{ quote.client?.name }}</td>
                            <td class="px-6 py-4 text-[12px] text-[#737373]">{{ formatDate(quote.created_at) }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#0a0a0a] text-right tabular-nums">{{ formatCFA(quote.total_amount) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium"
                                      :class="statusMap[quote.status]?.cls">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="statusMap[quote.status]?.dot"></span>
                                    {{ statusMap[quote.status]?.label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-0.5">
                                    <Link :href="route('quotes.show', quote.id)" aria-label="Voir"
                                          class="w-8 h-8 rounded-lg text-[#737373] hover:text-[#0a0a0a] hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </Link>
                                    <button @click="deleteQuote(quote.id, quote.reference)" aria-label="Supprimer"
                                            class="w-8 h-8 rounded-lg text-[#737373] hover:text-red-600 hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>