<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    invoice: Object,
});

const changeStatus = (status) => {
    router.patch(route('invoices.update-status', props.invoice.id), { status });
};

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const statusLabel = (status) => {
    const labels = { impayee: 'Impayée', payee: 'Payée', annulee: 'Annulée' };
    return labels[status] || status;
};
</script>

<template>
    <Head :title="'Facture ' + invoice.reference" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facture {{ invoice.reference }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="text-sm text-gray-500">Client</div>
                            <div class="font-bold">{{ invoice.client?.name }}</div>
                            <div class="text-sm">{{ invoice.client?.email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Date</div>
                            <div class="font-bold">{{ formatDate(invoice.created_at) }}</div>
                            <div class="mt-2">
                                Statut :
                                <span class="font-bold uppercase">{{ statusLabel(invoice.status) }}</span>
                            </div>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs uppercase">Produit</th>
                                <th class="px-4 py-2 text-right text-xs uppercase">Qté</th>
                                <th class="px-4 py-2 text-right text-xs uppercase">Prix unitaire</th>
                                <th class="px-4 py-2 text-right text-xs uppercase">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in invoice.items" :key="item.id" class="border-b">
                                <td class="px-4 py-2">{{ item.product?.name }} <span class="text-xs text-gray-500">({{ item.product?.sku }})</span></td>
                                <td class="px-4 py-2 text-right">{{ item.quantity }}</td>
                                <td class="px-4 py-2 text-right">{{ item.unit_price }} €</td>
                                <td class="px-4 py-2 text-right font-bold">{{ (item.quantity * item.unit_price).toFixed(2) }} €</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-lg">{{ invoice.total_amount }} €</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="border-t pt-4 flex flex-wrap gap-3">
                        <button
                            v-if="invoice.status !== 'payee'"
                            @click="changeStatus('payee')"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        >
                            Marquer comme payée
                        </button>
                        <button
                            v-if="invoice.status !== 'annulee'"
                            @click="changeStatus('annulee')"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        >
                            Annuler la facture
                        </button>

                        <a
                            :href="route('invoices.pdf', invoice.id)"
                            target="_blank"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        >
                            📄 Télécharger PDF
                        </a>

                        <Link
                            :href="route('invoices.index')"
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