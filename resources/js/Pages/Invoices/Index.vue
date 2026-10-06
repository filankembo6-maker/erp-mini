<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ invoices: Array });

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const statusMap = {
    impayee: { label: 'Impayée', class: 'bg-amber-100 text-amber-700' },
    payee: { label: 'Payée', class: 'bg-emerald-100 text-emerald-700' },
    annulee: { label: 'Annulée', class: 'bg-slate-200 text-slate-600' },
};

const deleteInvoice = (id) => {
    if (confirm('Supprimer cette facture ?')) router.delete(route('invoices.destroy', id));
};
</script>

<template>
    <Head title="Factures" />

    <AuthenticatedLayout>
        <div class="w-full space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-[22px] font-bold text-[#1e3a8a]">Factures</h1>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div v-if="invoices.length === 0" class="px-6 py-16 text-center">
                    <h3 class="text-[14px] font-semibold text-[#1e3a8a]">Aucune facture</h3>
                    <p class="text-[12px] text-slate-500 mt-1">Les factures apparaîtront ici après transformation d'un devis.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Référence</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Client</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Date</th>
                            <th class="px-5 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Statut</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Total</th>
                            <th class="px-5 py-3 w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoices" :key="invoice.id" class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-[12px] font-bold text-[#1e3a8a] font-mono">{{ invoice.reference }}</td>
                            <td class="px-5 py-3 text-[12px] text-slate-600">{{ invoice.client?.name }}</td>
                            <td class="px-5 py-3 text-[11px] text-slate-500">{{ formatDate(invoice.created_at) }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold" :class="statusMap[invoice.status]?.class">
                                    {{ statusMap[invoice.status]?.label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-[13px] font-bold text-[#1e3a8a] text-right tabular-nums">{{ invoice.total_amount }} €</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a :href="route('invoices.pdf', invoice.id)" target="_blank" class="w-7 h-7 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Télécharger PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6m0 0l-2-2m2 2l2-2" /></svg>
                                    </a>
                                    <Link :href="route('invoices.show', invoice.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-[#0891b2] hover:bg-cyan-50 flex items-center justify-center transition-colors" title="Voir">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </Link>
                                    <button @click="deleteInvoice(invoice.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Supprimer">
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