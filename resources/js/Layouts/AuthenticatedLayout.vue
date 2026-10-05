<script setup>
import { computed, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? { name: '', email: '' });
const searchQuery = ref('');
const searchOpen = ref(false);

const menuItems = [
    { name: 'Tableau de bord', route: 'dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Produits', route: 'products.index', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { name: 'Clients', route: 'clients.index', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
    { name: 'Stocks', route: 'stock-movements.index', icon: 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2' },
    { name: 'Devis', route: 'quotes.index', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { name: 'Factures', route: 'invoices.index', icon: 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' },
];

const isActive = (itemRoute) => {
    const current = page.url;
    const base = '/' + itemRoute.split('.')[0];
    return current === base || current.startsWith(base + '/');
};

const submitSearch = () => {
    if (!searchQuery.value.trim()) return;
    // On cherchera dans les produits en priorité
    router.get(route('products.index'), { q: searchQuery.value });
    searchOpen.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-[#fafaf9]">

        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 bottom-0 w-[68px] flex flex-col items-center py-5 z-40 bg-[#09090b]">

            <Link :href="route('dashboard')" class="mb-8" aria-label="Accueil">
                <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center p-1.5">
                    <img src="/images/logo-icon.png" alt="Logo" class="w-full h-full object-contain" />
                </div>
            </Link>

            <nav class="flex-1 flex flex-col items-center gap-1">
                <Link
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :aria-label="item.name"
                    class="group relative w-10 h-10 rounded-lg flex items-center justify-center transition-colors duration-150"
                    :class="isActive(item.route)
                        ? 'bg-[#166534] text-white'
                        : 'text-[#71717a] hover:text-white hover:bg-[#18181b]'"
                >
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>
                    <span class="absolute left-[3rem] px-2.5 py-1.5 bg-[#09090b] border border-[#27272a] text-white text-[11px] font-medium rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-100 pointer-events-none z-50">
                        {{ item.name }}
                    </span>
                </Link>
            </nav>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                aria-label="Déconnexion"
                class="w-10 h-10 rounded-lg flex items-center justify-center text-[#71717a] hover:text-[#f87171] hover:bg-[#18181b] transition-colors duration-150 group relative"
            >
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="absolute left-[3rem] px-2.5 py-1.5 bg-[#09090b] border border-[#27272a] text-white text-[11px] font-medium rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-100 pointer-events-none z-50">
                    Déconnexion
                </span>
            </Link>
        </aside>

        <div class="ml-[68px] min-h-screen flex flex-col">

            <header class="h-14 border-b border-[#e4e4e7] bg-white flex items-center px-6 gap-6 sticky top-0 z-30">

                <!-- Fil d'ariane / nom app -->
                <div class="flex items-center gap-2 shrink-0">
                    <div class="text-[13px] font-semibold text-[#09090b]">BISALELI TECH</div>
                    <div class="text-[13px] text-[#a1a1aa]">/</div>
                    <div class="text-[13px] text-[#52525b] capitalize">
                        {{ page.url.split('/')[1] || 'Tableau de bord' }}
                    </div>
                </div>

                <!-- Recherche globale -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-[#a1a1aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            @keydown.enter="submitSearch"
                            type="search"
                            placeholder="Rechercher..."
                            class="w-full h-8 pl-9 pr-12 rounded-md bg-[#f4f4f5] border border-transparent text-[12px] text-[#09090b] placeholder-[#a1a1aa] focus:outline-none focus:bg-white focus:border-[#166534] transition-colors"
                        />
                        <kbd class="absolute right-2 top-1/2 -translate-y-1/2 hidden sm:flex items-center gap-0.5 text-[10px] text-[#a1a1aa] font-mono">
                            <span class="px-1 py-0.5 rounded border border-[#e4e4e7] bg-white">↵</span>
                        </kbd>
                    </div>
                </div>

                <div class="flex-1"></div>

                <div class="flex items-center gap-1 shrink-0">
                    <button aria-label="Notifications" class="w-8 h-8 rounded-md hover:bg-[#f4f4f5] flex items-center justify-center relative">
                        <svg class="w-4 h-4 text-[#52525b]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-[#166534] rounded-full"></span>
                    </button>

                    <div class="w-px h-4 bg-[#e4e4e7] mx-1"></div>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2 pl-1 pr-2 h-8 rounded-md hover:bg-[#f4f4f5] transition-colors">
                                <div class="w-6 h-6 rounded-full bg-[#09090b] flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="text-[12px] font-medium text-[#09090b] hidden sm:block">{{ user.name }}</span>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Mon profil</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Déconnexion</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="flex-1">
                <div class="max-w-[1440px] mx-auto px-8 py-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>