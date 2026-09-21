<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    quotes: Array,
});

const page = usePage();

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const statusLabel = (status) => {
    const labels = {
        brouillon: 'Brouillon',
        envoye: 'Envoyé',
        accepte: 'Accepté',
        refuse: 'Refusé',
    };
    return labels[status] || status;
};

const statusColor = (status) => {
    const colors = {
        brouillon: 'bg-gray-100 text-gray-800',
        envoye: 'bg-blue-100 text-blue-800',
        accepte: 'bg-green-100 text-green-800',
        refuse: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Devis" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Devis
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div v-if="page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ page.props.flash.success }}
                </div>
                <div v-if="page.props.flash?.error" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ page.props.flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="mb-4 flex justify-end">
                            <Link
                                :href="route('quotes.create')"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                + Nouveau devis
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="quote in quotes" :key="quote.id">
                                        <td class="px-6 py-4 font-mono text-sm">{{ quote.reference }}</td>
                                        <td class="px-6 py-4">{{ quote.client?.name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ formatDate(quote.created_at) }}</td>
                                        <td class="px-6 py-4 font-bold">{{ quote.total_amount }} €</td>
                                        <td class="px-6 py-4">
                                            <span :class="statusColor(quote.status)" class="px-2 py-1 rounded text-xs font-bold uppercase">
                                                {{ statusLabel(quote.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <Link
                                                :href="route('quotes.show', quote.id)"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Voir
                                            </Link>
                                            <Link
                                                :href="route('quotes.destroy', quote.id)"
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:underline"
                                                @click.prevent="confirm('Supprimer ce devis ?') && $event.target.closest('a').click()"
                                            >
                                                Supprimer
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="quotes.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Aucun devis pour le moment.
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