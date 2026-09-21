<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    clients: Array,
});

const page = usePage();
</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Clients
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ page.props.flash.success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        <div class="mb-4 flex justify-end">
                            <Link
                                :href="route('clients.create')"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                + Ajouter un client
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="client in clients" :key="client.id">
                                        <td class="px-6 py-4">{{ client.name }}</td>
                                        <td class="px-6 py-4">{{ client.email }}</td>
                                        <td class="px-6 py-4">{{ client.phone || '-' }}</td>
                                        <td class="px-6 py-4">{{ client.city || '-' }}</td>
                                        <td class="px-6 py-4 space-x-2">
                                            <Link
                                                :href="route('clients.show', client.id)"
                                                class="text-gray-600 hover:underline"
                                            >
                                                Voir
                                            </Link>
                                            <Link
                                                :href="route('clients.edit', client.id)"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Modifier
                                            </Link>
                                            <Link
                                                :href="route('clients.destroy', client.id)"
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:underline"
                                                @click.prevent="confirm('Supprimer ce client ?') && $event.target.closest('a').click()"
                                            >
                                                Supprimer
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="clients.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Aucun client pour le moment.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>