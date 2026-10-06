<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ movements: Array });

const formatDate = (date) => new Date(date).toLocaleString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });

const deleteMovement = (id) => {
    if (confirm('Annuler ce mouvement ? Le stock sera restauré.')) {
        router.delete(route('stock-movements.destroy', id));
    }
};
</script>

<template>
    <Head title="Mouvements de stock" />

    <AuthenticatedLayout>
        <div class="w-full space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-[22px] font-bold text-[#1e3a8a]">Mouvements de stock</h1>
                    <p class="text-[12px] text-slate-500 mt-0.5">Historique des entrées et sorties</p>
                </div>
                <Link :href="route('stock-movements.create')"
                      class="inline-flex items-center gap-2 h-9 px-4 rounded-lg text-[12px] font-semibold bg-[#1e3a8a] hover:bg-[#152e6b] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau mouvement
                </Link>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div v-if="movements.length === 0" class="px-6 py-16 text-center">
                    <h3 class="text-[14px] font-semibold text-[#1e3a8a]">Aucun mouvement</h3>
                    <p class="text-[12px] text-slate-500 mt-1">Enregistrez une entrée ou une sortie de stock.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Date</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Produit</th>
                            <th class="px-5 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Type</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Quantité</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Motif</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Par</th>
                            <th class="px-5 py-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movement in movements" :key="movement.id" class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-[11px] text-slate-500">{{ formatDate(movement.created_at) }}</td>
                            <td class="px-5 py-3">
                                <div class="text-[12px] font-medium text-[#1e3a8a]">{{ movement.product?.name }}</div>
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5">{{ movement.product?.sku }}</div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                      :class="movement.type === 'entree' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="movement.type === 'entree' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                    {{ movement.type === 'entree' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-[12px] font-bold text-[#1e3a8a] text-right tabular-nums">{{ movement.quantity }}</td>
                            <td class="px-5 py-3 text-[11px] text-slate-500">{{ movement.reason || '—' }}</td>
                            <td class="px-5 py-3 text-[11px] text-slate-500">{{ movement.user?.name }}</td>
                            <td class="px-5 py-3 text-right">
                                <button @click="deleteMovement(movement.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Annuler">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>