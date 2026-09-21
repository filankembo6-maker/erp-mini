<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    client: Object,
});
</script>

<template>
    <Head :title="'Client : ' + client.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails du client
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="space-y-4">
                        <div><span class="font-bold">Nom :</span> {{ client.name }}</div>
                        <div><span class="font-bold">Email :</span> {{ client.email }}</div>
                        <div><span class="font-bold">Téléphone :</span> {{ client.phone || 'Non renseigné' }}</div>
                        <div><span class="font-bold">Adresse :</span> {{ client.address || 'Non renseignée' }}</div>
                        <div><span class="font-bold">Ville :</span> {{ client.city || 'Non renseignée' }}</div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <Link
                            :href="route('clients.edit', client.id)"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                        >
                            Modifier
                        </Link>
                        <Link
                            :href="route('clients.index')"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                        >
                            Retour à la liste
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>