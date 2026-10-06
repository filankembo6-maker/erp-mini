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

    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">

        <!-- Carte avec dégradé -->
        <div class="w-full max-w-md rounded-3xl shadow-2xl p-10 relative overflow-hidden"
             style="background: linear-gradient(135deg, #1e40af 0%, #22d3ee 50%, #10b981 100%);">

            <!-- Halos décoratifs -->
            <div class="absolute w-96 h-96 rounded-full blur-3xl opacity-30"
                 style="background: radial-gradient(circle, #ffffff 0%, transparent 70%); top: -100px; left: -100px;"></div>

            <div class="relative">

                <!-- Cercle avec logo -->
                <div class="flex justify-center mb-8">
                    <div class="w-56 h-56 rounded-full bg-white shadow-2xl flex items-center justify-center p-4">
                        <img
                            src="/images/logo-icon.png"
                            alt="BISALELI TECH"
                            class="w-full h-full object-contain"
                        />
                    </div>
                </div>

                <!-- Titre -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white tracking-tight">
                        Connexion
                    </h1>
                    <p class="text-xs font-semibold tracking-[0.25em] mt-2 text-white/80">
                        ERP BISALELI TECH
                    </p>
                </div>

                <!-- Message de statut -->
                <div v-if="status" class="mb-4 p-3 bg-white/20 text-white text-sm rounded-xl text-center backdrop-blur">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Champ Email -->
                    <div>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Email"
                            required
                            autofocus
                            class="w-full px-6 py-4 rounded-full bg-white text-gray-800 placeholder-gray-400 border-0 shadow-lg focus:ring-4 focus:ring-white/50 focus:outline-none transition"
                        />
                        <div v-if="form.errors.email" class="text-white text-xs mt-2 ml-4 font-medium bg-red-500/40 rounded-full px-3 py-1 inline-block">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Mot de passe"
                            required
                            class="w-full px-6 py-4 rounded-full bg-white text-gray-800 placeholder-gray-400 border-0 shadow-lg focus:ring-4 focus:ring-white/50 focus:outline-none transition"
                        />
                        <div v-if="form.errors.password" class="text-white text-xs mt-2 ml-4 font-medium bg-red-500/40 rounded-full px-3 py-1 inline-block">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Bouton Se connecter -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full px-6 py-4 rounded-full font-bold text-gray-800 bg-white shadow-lg transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50"
                    >
                        <span v-if="!form.processing">Se connecter</span>
                        <span v-else>Connexion...</span>
                    </button>
                </form>

                <!-- Liens bas de page -->
                <div class="mt-6 flex items-center justify-between text-xs text-white/90">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="hover:text-white underline underline-offset-2 transition"
                    >
                        Mot de passe oublié ?
                    </Link>
                    <Link
                        :href="route('register')"
                        class="hover:text-white underline underline-offset-2 transition"
                    >
                        Créer un compte
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>