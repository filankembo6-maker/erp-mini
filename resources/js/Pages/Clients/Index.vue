<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    clients: Array,
    filters: Object,
});

const page = usePage();
const search = ref(props.filters?.search ?? '');
const sort = ref(props.filters?.sort ?? 'created_at');
const direction = ref(props.filters?.direction ?? 'desc');

let searchTimeout = null;

const applyFilters = () => {
    router.get(route('clients.index'), {
        search: search.value,
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

const sortBy = (column) => {
    if (sort.value === column) {
        direction.value = direction.value === 'asc' ? 'desc' : 'asc';
    } else {
        sort.value = column;
        direction.value = 'asc';
    }
    applyFilters();
};

const deleteClient = (id, name) => {
    if (confirm(`Supprimer "${name}" ?`)) {
        router.delete(route('clients.destroy', id));
    }
};
</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <div class="max-w-[1400px] mx-auto space-y-6">

            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-[26px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Clients</h1>
                    <p class="text-[13px] text-[#737373] mt-1">Répertoire commercial</p>
                </div>
                <Link :href="route('clients.create')"
                      class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-[13px] font-semibold bg-[#0a0a0a] hover:bg-[#166534] text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau client
                </Link>
            </div>

            <div v-if="page.props.flash?.success" class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-800">
                {{ page.props.flash.success }}
            </div>

            <div class="bg-white rounded-2xl border border-[#e7e5e4]">
                <div class="p-4 border-b border-[#f5f5f4]">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a3a3a3]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input v-model="search" type="search" placeholder="Rechercher un client..."
                               class="w-full h-10 pl-10 pr-4 rounded-lg bg-[#fafaf9] border border-[#e7e5e4] text-[13px] focus:outline-none focus:border-[#166534] focus:bg-white" />
                    </div>
                </div>

                <div v-if="clients.length === 0" class="px-6 py-20 text-center">
                    <h3 class="text-[15px] font-semibold text-[#0a0a0a]">Aucun client</h3>
                    <p class="text-[13px] text-[#737373] mt-1.5">Ajoutez votre premier client.</p>
                </div>

                <table v-else class="w-full">
                    <thead>
                        <tr class="border-b border-[#f5f5f4]">
                            <th @click="sortBy('name')" class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1">
                                    Client
                                    <svg v-if="sort === 'name'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th @click="sortBy('email')" class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1">
                                    Contact
                                    <svg v-if="sort === 'email'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th @click="sortBy('city')" class="px-6 py-3 text-left text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider cursor-pointer hover:text-[#0a0a0a] select-none">
                                <span class="inline-flex items-center gap-1">
                                    Ville
                                    <svg v-if="sort === 'city'" class="w-3 h-3" :class="direction === 'asc' ? '' : 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6l6 6H4z" />
                                    </svg>
                                </span>
                            </th>
                            <th class="px-6 py-3 text-right text-[10px] font-semibold text-[#a3a3a3] uppercase tracking-wider w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients" :key="client.id" class="border-b border-[#fafaf9] hover:bg-[#fafaf9]">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#0a0a0a] flex items-center justify-center text-white text-[12px] font-semibold shrink-0">
                                        {{ client.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ client.name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a :href="`mailto:${client.email}`" class="text-[12px] text-[#166534] hover:underline block">{{ client.email }}</a>
                                <a v-if="client.phone" :href="`tel:${client.phone}`" class="text-[12px] text-[#737373] hover:underline">{{ client.phone }}</a>
                            </td>
                            <td class="px-6 py-4 text-[12px] text-[#737373]">{{ client.city || '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-0.5">
                                    <Link :href="route('clients.show', client.id)" aria-label="Voir"
                                          class="w-8 h-8 rounded-lg text-[#737373] hover:text-[#0a0a0a] hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </Link>
                                    <Link :href="route('clients.edit', client.id)" aria-label="Modifier"
                                          class="w-8 h-8 rounded-lg text-[#737373] hover:text-[#166534] hover:bg-white flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button @click="deleteClient(client.id, client.name)" aria-label="Supprimer"
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