<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { formatCFA } from '@/Utils/format';

const props = defineProps({ products: Array, filters: Object });
const page = usePage();

const search = ref(props.filters?.search ?? '');
const filter = ref(props.filters?.filter ?? 'all');
const sort = ref(props.filters?.sort ?? 'created_at');
const direction = ref(props.filters?.direction ?? 'desc');

const stats = computed(() => {
    const total = props.products.length;
    const alerts = props.products.filter(p => p.stock_quantity <= p.stock_alert).length;
    const value = props.products.reduce((sum, p) => sum + (p.price * p.stock_quantity), 0);
    return { total, alerts, value };
});

let searchTimeout = null;
const applyFilters = () => {
    router.get(route('products.index'), {
        search: search.value, filter: filter.value, sort: sort.value, direction: direction.value,
    }, { preserveState: true, preserveScroll: true, replace: true });
};
watch(search, () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => applyFilters(), 300); });
watch(filter, () => applyFilters());

const sortBy = (column) => {
    if (sort.value === column) direction.value = direction.value === 'asc' ? 'desc' : 'asc';
    else { sort.value = column; direction.value = 'asc'; }
    applyFilters();
};

const stockStatus = (product) => {
    if (product.stock_quantity === 0) return { label: 'Rupture', class: 'bg-red-100 text-red-700', dot: 'bg-red-500' };
    if (product.stock_quantity <= product.stock_alert) return { label: 'Bas', class: 'bg-amber-100 text-amber-700', dot: 'bg-amber-500' };
    return { label: 'OK', class: 'bg-emerald-100 text-emerald-700', dot: 'bg-emerald-500' };
};

const deleteProduct = (id, name) => {
    if (confirm(`Supprimer "${name}" ?`)) router.delete(route('products.destroy', id));
};
</script>

<template>
    <Head title="Produits" />

    <AuthenticatedLayout>
        <div class="w-full space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-[22px] font-bold text-[#1e3a8a]">Produits</h1>
                <Link :href="route('products.create')"
                      class="inline-flex items-center gap-2 h-9 px-4 rounded-lg text-[12px] font-semibold bg-[#1e3a8a] hover:bg-[#152e6b] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau produit
                </Link>
            </div>

            <div v-if="page.props.flash?.success" class="px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-[12px] text-emerald-800">
                {{ page.props.flash.success }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-white border border-slate-200 rounded-xl p-4">
                    <div class="text-[9px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Total produits</div>
                    <div class="text-[26px] font-bold text-[#1e3a8a] tabular-nums leading-none mt-2">{{ stats.total }}</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4">
                    <div class="text-[9px] font-semibold text-slate-500 uppercase tracking-[0.1em]">En alerte</div>
                    <div class="text-[26px] font-bold tabular-nums leading-none mt-2"
                         :class="stats.alerts > 0 ? 'text-[#dc2626]' : 'text-[#1e3a8a]'">{{ stats.alerts }}</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl p-4">
                    <div class="text-[9px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Valeur du stock</div>
                    <div class="text-[20px] font-bold text-[#1e3a8a] tabular-nums leading-tight mt-2">{{ formatCFA(stats.value) }}</div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="flex flex-wrap items-center gap-3 p-4 border-b border-slate-100">
                    <div class="relative flex-1 min-w-[220px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input v-model="search" type="search" placeholder="Rechercher par nom ou référence..."
                               class="w-full h-9 pl-9 pr-3 rounded-lg bg-slate-50 border border-slate-200 text-[12px] text-[#0f172a] focus:outline-none focus:border-[#22d3ee] focus:bg-white" />
                    </div>
                    <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-1">
                        <button @click="filter = 'all'" class="px-3 py-1.5 rounded-md text-[11px] font-medium transition-colors"
                                :class="filter === 'all' ? 'bg-white text-[#1e3a8a] shadow-sm' : 'text-slate-500'">Tous</button>
                        <button @click="filter = 'alert'" class="px-3 py-1.5 rounded-md text-[11px] font-medium transition-colors"
                                :class="filter === 'alert' ? 'bg-[#dc2626] text-white shadow-sm' : 'text-slate-500'">En alerte</button>
                    </div>
                </div>

                <div v-if="products.length === 0" class="px-6 py-16 text-center">
                    <h3 class="text-[14px] font-semibold text-[#1e3a8a]">Aucun produit</h3>
                    <p class="text-[12px] text-slate-500 mt-1">Ajoutez votre premier produit.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th @click="sortBy('name')" class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em] cursor-pointer hover:text-[#1e3a8a] select-none">
                                <span class="inline-flex items-center gap-1">Produit<svg v-if="sort === 'name'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6l6 6H4z" /></svg></span>
                            </th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em]">Référence</th>
                            <th @click="sortBy('price')" class="px-5 py-3 text-right text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em] cursor-pointer hover:text-[#1e3a8a] select-none">
                                <span class="inline-flex items-center gap-1 justify-end">Prix<svg v-if="sort === 'price'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6l6 6H4z" /></svg></span>
                            </th>
                            <th @click="sortBy('stock_quantity')" class="px-5 py-3 text-center text-[10px] font-semibold text-slate-500 uppercase tracking-[0.1em] cursor-pointer hover:text-[#1e3a8a] select-none">
                                <span class="inline-flex items-center gap-1 justify-center">Stock<svg v-if="sort === 'stock_quantity'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6l6 6H4z" /></svg></span>
                            </th>
                            <th class="px-5 py-3 w-24"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id" class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-[12px] font-medium text-[#1e3a8a]">{{ product.name }}</td>
                            <td class="px-5 py-3 text-[11px] text-slate-500 font-mono">{{ product.sku }}</td>
                            <td class="px-5 py-3 text-[12px] font-bold text-[#1e3a8a] text-right tabular-nums">{{ formatCFA(product.price) }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-[12px] font-medium text-[#1e3a8a] tabular-nums">{{ product.stock_quantity }}</span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold" :class="stockStatus(product).class">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="stockStatus(product).dot"></span>
                                        {{ stockStatus(product).label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="route('products.show', product.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-[#0891b2] hover:bg-cyan-50 flex items-center justify-center transition-colors" title="Voir">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </Link>
                                    <Link :href="route('products.edit', product.id)" class="w-7 h-7 rounded-md text-slate-400 hover:text-[#10b981] hover:bg-emerald-50 flex items-center justify-center transition-colors" title="Modifier">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </Link>
                                    <button @click="deleteProduct(product.id, product.name)" class="w-7 h-7 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors" title="Supprimer">
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