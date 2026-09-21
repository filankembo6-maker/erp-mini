<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bienvenue - ERP Mini" />

    <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">
        <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full text-center">
            
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                ERP Mini
            </h1>
            
            <p class="text-gray-600 mb-8">
                Système de gestion commerciale — Produits, Clients, Stocks, Devis & Factures
            </p>

            <div class="space-x-4">
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Se connecter
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-block px-6 py-3 bg-gray-600 text-white rounded hover:bg-gray-700"
                >
                    S'inscrire
                </Link>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
                <p><strong>Comptes de test :</strong></p>
                <p class="mt-2">admin@erp.com / password</p>
                <p>commercial@erp.com / password</p>
                <p>magasinier@erp.com / password</p>
            </div>

        </div>
    </div>
</template>