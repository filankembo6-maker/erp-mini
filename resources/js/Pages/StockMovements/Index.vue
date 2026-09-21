<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    movements: Array,
});

const page = usePage();

const formatDate = (date) => {
    return new Date(date).toLocaleString('fr-FR');
};
</script>

<template>
    <Head title="Mouvements de stock" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mouvements de Stock
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
                                :href="route('stock-movements.create')"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                + Nouveau mouvement
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Par</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="movement in movements" :key="movement.id">
                                        <td class="px-6 py-4 text-sm">{{ formatDate(movement.created_at) }}</td>
                                        <td class="px-6 py-4">
                                            {{ movement.product?.name }}
                                            <span class="text-xs text-gray-500">({{ movement.product?.sku }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                :class="movement.type === 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                class="px-2 py-1 rounded text-xs font-bold uppercase"
                                            >
                                                {{ movement.type === 'entree' ? 'Entrée' : 'Sortie' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold">{{ movement.quantity }}</td>
                                        <td class="px-6 py-4 text-sm">{{ movement.reason || '-' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ movement.user?.name }}</td>
                                        <td class="px-6 py-4">
                                            <Link
                                                :href="route('stock-movements.destroy', movement.id)"
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:underline"
                                                @click.prevent="confirm('Annuler ce mouvement ? Le stock sera restauré.') && $event.target.closest('a').click()"
                                            >
                                                Annuler
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="movements.length === 0">
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Aucun mouvement enregistré.
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