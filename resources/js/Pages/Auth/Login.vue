<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Connexion - BISALELI TECH" />

    <div class="min-h-screen flex">

        <!-- Colonne gauche : noir profond, logo en couleurs sur carte blanche -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#0a0a0a] flex-col justify-between p-14">

            <!-- Logo -->
            <div>
                <div class="bg-white rounded-2xl inline-flex px-6 py-4 shadow-lg">
                    <img
                        src="/images/logo-splash.png"
                        alt="BISALELI TECH"
                        class="h-10 w-auto"
                    />
                </div>
            </div>

            <!-- Message -->
            <div class="max-w-sm">
                <h1 class="text-[32px] font-semibold text-white leading-tight tracking-[-0.02em]">
                    La gestion commerciale, à la hauteur de votre entreprise.
                </h1>

                <p class="text-[14px] text-[#737373] mt-6 leading-relaxed">
                    Produits, clients, stocks, devis et factures réunis dans une seule interface.
                </p>

                <div class="mt-10 h-px w-16 bg-[#166534]"></div>

                <div class="mt-8 flex items-baseline gap-10">
                    <div>
                        <div class="text-[22px] font-semibold text-white tabular-nums">6</div>
                        <div class="text-[11px] text-[#525252] mt-0.5">modules</div>
                    </div>
                    <div>
                        <div class="text-[22px] font-semibold text-white tabular-nums">3</div>
                        <div class="text-[11px] text-[#525252] mt-0.5">rôles</div>
                    </div>
                    <div>
                        <div class="text-[22px] font-semibold text-white tabular-nums">1</div>
                        <div class="text-[11px] text-[#525252] mt-0.5">interface</div>
                    </div>
                </div>
            </div>

            <!-- Pied -->
            <div class="flex items-center justify-between text-[11px] text-[#404040]">
                <div>© 2026 BISALELI TECH</div>
                <div>ERP Commercial Solutions</div>
            </div>
        </div>

        <!-- Colonne droite : formulaire épuré -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-14 bg-[#fafaf9]">

            <div class="w-full max-w-sm">

                <!-- Logo mobile -->
                <div class="lg:hidden mb-12">
                    <img
                        src="/images/logo-splash.png"
                        alt="BISALELI TECH"
                        class="h-9 w-auto"
                    />
                </div>

                <!-- Titre -->
                <div class="mb-10">
                    <h2 class="text-[22px] font-semibold text-[#0a0a0a] tracking-[-0.02em]">Connexion</h2>
                    <p class="text-[13px] text-[#737373] mt-1.5">Accédez à votre espace de gestion</p>
                </div>

                <!-- Statut -->
                <div v-if="status" class="mb-6 px-4 py-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-800">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Email -->
                    <div>
                        <label class="block text-[12px] font-medium text-[#404040] mb-2">Adresse e-mail</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full h-12 px-4 rounded-xl bg-white border border-[#e7e5e4] text-[14px] text-[#0a0a0a] placeholder-[#a3a3a3] focus:outline-none focus:border-[#166534] focus:ring-2 focus:ring-[#166534]/10 transition-colors"
                        />
                        <div v-if="form.errors.email" class="text-[11px] text-red-600 mt-1.5">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[12px] font-medium text-[#404040]">Mot de passe</label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-[11px] text-[#166534] hover:underline font-medium"
                            >
                                Mot de passe oublié
                            </Link>
                        </div>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full h-12 px-4 rounded-xl bg-white border border-[#e7e5e4] text-[14px] text-[#0a0a0a] placeholder-[#a3a3a3] focus:outline-none focus:border-[#166534] focus:ring-2 focus:ring-[#166534]/10 transition-colors"
                        />
                        <div v-if="form.errors.password" class="text-[11px] text-red-600 mt-1.5">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Se souvenir -->
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="w-4 h-4 rounded border-[#d6d3d1] text-[#166534] focus:ring-[#166534]/20"
                        />
                        <span class="text-[12px] text-[#525252]">Se souvenir de moi</span>
                    </label>

                    <!-- Bouton -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full h-12 rounded-xl text-[13px] font-semibold bg-[#0a0a0a] hover:bg-[#166534] text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#166534] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="!form.processing">Se connecter</span>
                        <span v-else>Connexion...</span>
                    </button>
                </form>

                <!-- Séparateur -->
                <div class="flex items-center gap-3 my-8">
                    <div class="flex-1 h-px bg-[#e7e5e4]"></div>
                    <span class="text-[10px] text-[#a3a3a3] uppercase tracking-[0.2em]">ou</span>
                    <div class="flex-1 h-px bg-[#e7e5e4]"></div>
                </div>

                <!-- Inscription -->
                <Link
                    :href="route('register')"
                    class="block w-full h-12 rounded-xl text-[13px] font-medium bg-white border border-[#e7e5e4] hover:border-[#0a0a0a] text-[#0a0a0a] flex items-center justify-center transition-colors"
                >
                    Créer un compte
                </Link>

                <!-- Mention -->
                <p class="text-[11px] text-[#a3a3a3] text-center mt-10">
                    © 2026 BISALELI TECH
                </p>

            </div>
        </div>
    </div>
</template>