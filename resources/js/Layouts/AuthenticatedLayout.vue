<script setup>
import { computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? { name: '', email: '' });

const now = new Date();
const dateStr = now.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
const timeStr = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

const menuItems = [
    { name: 'Dashboard', route: 'dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
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
</script>

<template>
    <div class="min-h-screen bg-sky-100">

        <!-- Sidebar -->
        <aside class="fixed left-4 top-4 bottom-4 w-20 rounded-3xl flex flex-col items-center py-6 z-40 bg-gradient-to-b from-sky-500 to-blue-800 shadow-lg">

            <Link :href="route('dashboard')" class="mb-8">
                <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-md p-2">
                    <img src="/images/logo-icon.png" alt="Logo" class="w-full h-full object-contain" />
                </div>
            </Link>

            <nav class="flex-1 flex flex-col items-center gap-3">
                <Link
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="group relative w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-150"
                    :class="isActive(item.route)
                        ? 'bg-white shadow-md'
                        : 'bg-white/15 hover:bg-white/25'"
                >
                    <svg class="w-5 h-5"
                         :class="isActive(item.route) ? 'text-blue-700' : 'text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>

                    <span class="absolute left-16 px-3 py-1.5 bg-slate-900 text-white text-xs rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none z-50">
                        {{ item.name }}
                    </span>
                </Link>
            </nav>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-12 h-12 rounded-full bg-white/15 hover:bg-red-600 flex items-center justify-center transition-colors duration-150 group relative"
            >
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="absolute left-16 px-3 py-1.5 bg-slate-900 text-white text-xs rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none z-50">
                    Déconnexion
                </span>
            </Link>
        </aside>

        <!-- Contenu -->
        <div class="ml-28 mr-4 py-4 min-h-screen flex flex-col gap-4">

            <!-- Topbar -->
            <header class="h-16 rounded-2xl flex items-center justify-between px-6 bg-sky-200 shadow-sm border border-sky-300/60">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/70 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-sky-800/70 font-medium">{{ dateStr }}</div>
                        <div class="text-sm font-semibold text-sky-900">{{ timeStr }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-full bg-white/70 hover:bg-white flex items-center justify-center transition-colors duration-150 relative">
                        <svg class="w-5 h-5 text-sky-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full bg-white/70 hover:bg-white transition-colors duration-150">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm bg-blue-800">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="text-sky-900 text-sm font-medium">{{ user.name }}</span>
                                <svg class="w-4 h-4 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
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
                <slot />
            </main>
        </div>
    </div>
</template>