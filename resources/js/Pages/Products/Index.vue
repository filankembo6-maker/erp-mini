<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { formatCFA } from '@/Utils/format';

const props = defineProps({
    products: Array,
    filters: Object,
});

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
        search: search.value,
        filter: filter.value,
        sort: sort.value,
        direction: direction.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => applyFilters(), 300);
});

watch(filter, () => applyFilters());

const sortBy = (column) => {
    if (sort.value === column) {
        direction.value = direction.value === 'asc' ? 'desc' : 'asc';
    } else {
        sort.value = column;
        direction.value = 'asc';
    }
    applyFilters();
};

const stockStatus = (product) => {
    if (product.stock_quantity === 0) return { label: 'Rupture', color: 'red' };
    if (product.stock_quantity <= product.stock_alert) return { label: 'Bas', color: 'amber' };
    return { label: 'OK', color: 'emerald' };
};

const deleteProduct = (id, name) => {
    if (confirm(`Supprimer "${name}" ?`)) {
        router.delete(route('products.destroy', id));
    }
};
</script>

<template>
    <Head title="Produits" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto space-y-6">

            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-[26px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Produits</h1>
                    <p class="text-[13px] text-[#737373] mt-1">Catalogue et niveaux de stock</p>
                </div>
                <Link :href="route('products.create')"
                      class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-[13px] font-semibold bg-[#0a0a0a] hover:bg-[#166534] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau produit
                </Link>
            </div>

            <div v-if="page.props.flash?.success" class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-800">
                {{ page.props.flash.success }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Total produits</div>
                    <div class="text-[28px] font-semibold text-[#0a0a0a] tabular-nums leading-none mt-3">{{ stats.total }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">En alerte</div>
                    <div class="text-[28px] font-semibold tabular-nums leading-none mt-3"
                         :class="stats.alerts > 0 ? 'text-[#b45309]' : 'text-[#0a0a0a]'">{{ stats.alerts }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-[#e7e5e4] p-5">
                    <div class="text-[11px] font-semibold text-[#737373] uppercase tracking-wider">Valeur du stock</div>
                    <div class="text-[22px] font-semibold text-[#0a0a0a] tabular-nums leading-tight mt-3">{{ formatCFA(stats.value) }}</div>
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
                        <input v-model="search" type="search" placeholder="Rechercher par nom ou référence..."
                               class="w-full h-10 pl-10 pr-4 rounded-lg bg-[#fafaf9] border border-[#e7e5e4] text-[13px] focus:outline-none focus:border-[#166534] focus:bg-white" />
                    </div>
                    <div class="flex items-center gap-1 bg-[#fafaf9] border border-[#e7e5e4] rounded-lg p-1">
                        <button @click="filter = 'all'" class="px-3 py-1.5 rounded-md text-[12px] font-medium transition-colors"
                                :class="filter === 'all' ? 'bg-white text-[#0a0a0a] shadow-sm' : 'text-[#737373]'">Tous</button>
                        <button @click="filter = 'alert'" class="px-3 py-1.5 rounded-md text-[12px] font-medium transition-colors"
                                :class="filter === 'alert' ? 'bg-white text-[#b45309] shadow-sm' : 'text-[#737373]'">En alerte</button>
                    </div>
                </div>

                <div v-if="products.length === 0" class="px-6 py-20 text-center">
                    <h3 class="text-[15px] font-semibold text-[#0a0a0a]">Aucun produit</h3>
                    <p class="text-[13px] text-[#737373] mt-1.5">Ajoutez votre premier produit pour démarrer.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-[#f5f5f4]">
                            <th @click="sortBy('name')" class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1">
                                    Produit
                                    <svg v-if="sort === 'name'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider">Référence</th>
                            <th @click="sortBy('price')" class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1 justify-end">
                                    Prix
                                    <svg v-if="sort === 'price'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th @click="sortBy('stock_quantity')" class="px-6 py-3 text-center text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1 justify-center">
                                    Stock
                                    <svg v-if="sort === 'stock_quantity'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id" class="border-b border-[#fafaf9] hover:bg-[#fafaf9]">
                            <td class="px-6 py-4 text-[13px] font-medium text-[#0a0a0a]">{{ product.name }}</td>
                            <td class="px-6 py-4 text-[12px] text-[#737373] font-mono">{{ product.sku }}</td>
                            <td class="px-6 py-4 text-[13px] font-medium text-[#0a0a0a] text-right tabular-nums">{{ formatCFA(product.price) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-[13px] font-medium text-[#0a0a0a] tabular-nums">{{ product.stock_quantity }}</span>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium"
                                          :class="{
                                              'bg-emerald-50 text-emerald-700': stockStatus(product).color === 'emerald',
                                              'bg-amber-50 text-amber-700': stockStatus(product).color === 'amber',
                                              'bg-red-50 text-red-700': stockStatus(product).color === 'red',
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="{
                                                  'bg-emerald-500': stockStatus(product).color === 'emerald',
                                                  'bg-amber-500': stockStatus(product).color === 'amber',
                                                  'bg-red-500': stockStatus(product).color === 'red',
                                              }"></span>
                                        {{ stockStatus(product).label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-0.5">
                                    <Link :href="route('products.show', product.id)" aria-label="Voir"
                                          class="w-8 h-8 rounded-lg text-[#737373] hover:text-[#0a0a0a] hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </Link>
                                    <Link :href="route('products.edit', product.id)" aria-label="Modifier"
                                          class="w-8 h-8 rounded-lg text-[#737373] hover:text-[#166534] hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button @click="deleteProduct(product.id, product.name)" aria-label="Supprimer"
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