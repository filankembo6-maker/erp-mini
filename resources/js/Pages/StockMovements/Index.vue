<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { formatDateTime } from '@/Utils/format';

const props = defineProps({ movements: Array });
const page = usePage();
const search = ref('');
const filter = ref('all');

const stats = computed(() => {
    const entries = props.movements.filter(m => m.type === 'entree').length;
    const exits = props.movements.filter(m => m.type === 'sortie').length;
    return { entries, exits, total: props.movements.length };
});

const filtered = computed(() => {
    let list = props.movements;
    if (filter.value !== 'all') list = list.filter(m => m.type === filter.value);
    if (search.value) {
        const q = search.value.toLowerCase();
        list = list.filter(m =>
            m.product?.name?.toLowerCase().includes(q) ||
            m.product?.sku?.toLowerCase().includes(q)
        );
    }
    return list;
});
</script>

<template>
    <Head title="Mouvements de stock" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto space-y-6">

            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-[26px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Mouvements de stock</h1>
                    <p class="text-[13px] text-[#737373] mt-1">Entrées et sorties de marchandises</p>
                </div>
                <Link :href="route('stock-movements.create')"
                      class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-[13px] font-semibold bg-[#0a0a0a] hover:bg-[#166534] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau mouvement
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Total mouvements</div>
                    <div class="text-[28px] font-semibold text-[#0a0a0a] tabular-nums leading-none mt-3">{{ stats.total }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Entrées</div>
                    <div class="text-[28px] font-semibold text-emerald-700 tabular-nums leading-none mt-3">{{ stats.entries }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Sorties</div>
                    <div class="text-[28px] font-semibold text-[#b45309] tabular-nums leading-none mt-3">{{ stats.exits }}</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                <div class="flex flex-wrap items-center gap-3 p-4 border-b border-[#f5f5f4]">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a3a3a3]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input v-model="search" type="search" placeholder="Rechercher par produit..."
                               class="w-full h-10 pl-10 pr-4 rounded-lg bg-[#fafaf9] border border-[#e7e5e4] text-[13px] focus:outline-none focus:border-[#166534] focus:bg-white" />
                    </div>
                    <div class="flex items-center gap-1 bg-[#fafaf9] border border-[#e7e5e4] rounded-lg p-1">
                        <button @click="filter = 'all'" class="px-3 py-1.5 rounded-md text-[12px] font-medium"
                                :class="filter === 'all' ? 'bg-white text-[#0a0a0a] shadow-sm' : 'text-[#737373]'">Tous</button>
                        <button @click="filter = 'entree'" class="px-3 py-1.5 rounded-md text-[12px] font-medium"
                                :class="filter === 'entree' ? 'bg-white text-emerald-700 shadow-sm' : 'text-[#737373]'">Entrées</button>
                        <button @click="filter = 'sortie'" class="px-3 py-1.5 rounded-md text-[12px] font-medium"
                                :class="filter === 'sortie' ? 'bg-white text-[#b45309] shadow-sm' : 'text-[#737373]'">Sorties</button>
                    </div>
                </div>

                <div v-if="movements.length === 0" class="px-6 py-20 text-center">
                    <h3 class="text-[15px] font-semibold text-[#0a0a0a]">Aucun mouvement</h3>
                    <p class="text-[13px] text-[#737373] mt-1.5">Enregistrez une entrée ou une sortie.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-[#f5f5f4]">
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-center text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Qté</th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Motif</th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Par</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="movement in filtered" :key="movement.id" class="border-b border-[#fafaf9] hover:bg-[#fafaf9]">
                            <td class="px-6 py-4 text-[12px] text-[#737373]">{{ formatDateTime(movement.created_at) }}</td>
                            <td class="px-6 py-4">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ movement.product?.name }}</div>
                                <div class="text-[11px] text-[#a3a3a3] font-mono mt-0.5">{{ movement.product?.sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium"
                                      :class="movement.type === 'entree' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          :class="movement.type === 'entree' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                    {{ movement.type === 'entree' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#0a0a0a] text-right tabular-nums">{{ movement.quantity }}</td>
                            <td class="px-6 py-4 text-[12px] text-[#737373]">{{ movement.reason || '—' }}</td>
                            <td class="px-6 py-4 text-[12px] text-[#737373]">{{ movement.user?.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>