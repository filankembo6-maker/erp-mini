<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ quotes: Array });

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const statusMap = {
    brouillon: { label: 'Brouillon', class: 'bg-slate-100 text-slate-600' },
    envoye: { label: 'Envoyé', class: 'bg-cyan-100 text-cyan-700' },
    accepte: { label: 'Accepté', class: 'bg-emerald-100 text-emerald-700' },
    refuse: { label: 'Refusé', class: 'bg-red-100 text-red-700' },
};

const deleteQuote = (id) => {
    if (confirm('Supprimer ce devis ?')) router.delete(route('quotes.destroy', id));
};
</script>

<template>
    <Head title="Devis" />

    <AuthenticatedLayout>
        <div class="w-full space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-[22px] font-bold text-[#1e3a8a]">Devis</h1>
                    <p class="text-[12px] text-slate-500 mt-0.5">Propositions commerciales</p>
                </div>
                <Link :href="route('quotes.create')"
                      class="inline-flex items-center gap-2 h-9 px-4 rounded-lg text-[12px] font-semibold bg-[#1e3a8a] hover:bg-[#152e6b] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau devis
                </Link>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div v-if="quotes.length === 0" class="px-6 py-16 text-center">
                    <h3 class="text-[14px] font-semibold text-[#1e3a8a]">Aucun devis</h3>
                    <p class="text-[12px] text-slate-500 mt-1">Créez votre premier devis.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Référence</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Client</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Date</th>
                            <th class="px-5 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Statut</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Total</th>
                            <th class="px-5 py-3 w-24"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="quote in quotes" :key="quote.id" class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-[12px] font-bold text-[#1e3a8a] font-mono">{{ quote.reference }}</td>
                            <td class="px-5 py-3 text-[12px] text-slate-600">{{ quote.client?.name }}</td>
                            <td class="px-5 py-3 text-[11px] text-slate-500">{{ formatDate(quote.created_at) }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold" :class="statusMap[quote.status]?.class">
                                    {{ statusMap[quote.status]?.label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-[13px] font-bold text-[#1e3a8a] text-right tabular-nums">{{ quote.total_amount }} €</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="route('quotes.show', quote.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-[#0891b2] hover:bg-cyan-50 flex items-center justify-center transition-colors" title="Voir">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </Link>
                                    <button @click="deleteQuote(quote.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Supprimer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
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