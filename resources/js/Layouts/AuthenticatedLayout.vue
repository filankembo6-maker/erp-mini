<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? { name: '', email: '' });

const now = ref(new Date());
let clockInterval = null;

onMounted(() => {
    clockInterval = setInterval(() => { now.value = new Date(); }, 30000);
});

onUnmounted(() => {
    if (clockInterval) clearInterval(clockInterval);
});

const dateStr = computed(() => now.value.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }));
const timeStr = computed(() => now.value.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }));

const menuItems = [
    { name: 'Dashboard', route: 'dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Produits', route: 'products.index', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { name: 'Clients', route: 'clients.index', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
    { name: 'Stocks', route: 'stock-movements.index', icon: 'M4 6h16M4 12h16M4 18h10' },
    { name: 'Devis', route: 'quotes.index', icon: 'M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2zm8 0v5h5M9 12h6M9 16h6' },
    { name: 'Factures', route: 'invoices.index', icon: 'M7 3h10a2 2 0 012 2v16l-3-2-4 2-4-2-3 2V5a2 2 0 012-2zm3 5h4m-4 4h5m-5 4h3' },
];

const isActive = (itemRoute) => {
    const current = page.url;
    const base = '/' + itemRoute.split('.')[0];
    return current === base || current.startsWith(base + '/');
};

const initials = computed(() => {
    const name = String(user.value.name || '').trim();
    if (!name) return 'U';
    return name.split(/\s+/).slice(0, 2).map(p => p.charAt(0).toUpperCase()).join('');
});

const handleLogout = () => {
    sessionStorage.removeItem('splash_seen');
};
</script>

<template>
    <div class="min-h-screen bg-[#f0fdfa] text-[#0f172a]">

        <!-- HEADER -->
        <header class="sticky top-0 z-50 bg-white border-b border-[#14b8a6]/25 shadow-sm">

            <div class="max-w-[1500px] mx-auto px-5 lg:px-8">

                <div class="h-[72px] flex items-center gap-4">

                    <!-- LOGO -->
                    <Link :href="route('dashboard')" class="flex items-center gap-3 shrink-0">
                        <div class="w-11 h-11 rounded-[14px] bg-[#0f172a] flex items-center justify-center p-2.5 shadow-md">
                            <img src="/images/logo-icon.png" alt="BISALELI TECH" class="w-full h-full object-contain" />
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-[14px] font-semibold tracking-[-0.01em] text-[#0f172a]">
                                BISALELI TECH
                            </div>
                            <div class="text-[10px] text-[#14b8a6] mt-0.5 font-semibold">
                                Gestion commerciale
                            </div>
                        </div>
                    </Link>

                    <div class="hidden lg:block w-px h-8 bg-[#14b8a6]/25 mx-1"></div>

                    <!-- NAVIGATION -->
                    <nav class="hidden md:flex items-center gap-1 flex-1 min-w-0">

                        <Link
                            v-for="item in menuItems"
                            :key="item.route"
                            :href="route(item.route)"
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-[13px] whitespace-nowrap transition-all duration-200"
                            :class="
                                isActive(item.route)
                                    ? 'bg-[#14b8a6] text-white font-semibold shadow-sm'
                                    : 'text-[#0f172a]/60 hover:bg-[#14b8a6]/15 hover:text-[#0f172a]'
                            "
                        >
                            <svg class="w-[16px] h-[16px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>

                    </nav>

                    <!-- INFOS DROITE -->
                    <div class="ml-auto flex items-center gap-2 sm:gap-3 shrink-0">

                        <div class="hidden xl:block text-right mr-1">
                            <div class="text-[11px] text-[#0f172a]/60">{{ dateStr }}</div>
                            <div class="text-[13px] font-medium text-[#0f172a] mt-0.5">{{ timeStr }}</div>
                        </div>

                        <button type="button" aria-label="Notifications"
                                class="relative w-10 h-10 rounded-xl border border-[#14b8a6]/25 bg-white hover:bg-[#14b8a6]/10 text-[#0f172a]/70 hover:text-[#0f172a] transition-all duration-200">
                            <svg class="w-[17px] h-[17px] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-[8px] right-[8px] w-1.5 h-1.5 rounded-full bg-[#14b8a6] ring-2 ring-white"></span>
                        </button>

                        <Dropdown align="right" width="52">
                            <template #trigger>
                                <button class="flex items-center gap-2 pl-1 pr-2 sm:pr-2.5 py-1 rounded-xl hover:bg-[#14b8a6]/10 transition-colors duration-200">
                                    <div class="w-9 h-9 rounded-[12px] bg-[#0f172a] text-white flex items-center justify-center text-[11px] font-semibold">
                                        {{ initials }}
                                    </div>
                                    <div class="hidden sm:block text-left max-w-[120px]">
                                        <div class="truncate text-[12px] font-semibold text-[#0f172a]">{{ user.name }}</div>
                                        <div class="truncate text-[10px] text-[#14b8a6] mt-0.5 font-semibold">Administrateur</div>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-[#0f172a]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Mon profil</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" @click="handleLogout">Déconnexion</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- NAVIGATION MOBILE -->
                <div class="md:hidden border-t border-[#14b8a6]/25 py-2 overflow-x-auto scrollbar-none">
                    <nav class="flex items-center gap-1 min-w-max">
                        <Link
                            v-for="item in menuItems"
                            :key="`mobile-${item.route}`"
                            :href="route(item.route)"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-[11px] transition-colors"
                            :class="
                                isActive(item.route)
                                    ? 'bg-[#14b8a6] text-white font-semibold'
                                    : 'text-[#0f172a]/60 hover:bg-[#14b8a6]/15'
                            "
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>
                    </nav>
                </div>

            </div>
        </header>

        <main class="max-w-[1500px] mx-auto px-5 lg:px-8 py-7 lg:py-9">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
.scrollbar-none::-webkit-scrollbar { display: none; }
</style>