<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    quote: Object,
});

const convertToInvoice = () => {
    if (confirm('Transformer ce devis en facture ?')) {
        router.post(route('quotes.convert', props.quote.id));
    }
};

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');
</script>

<template>
    <Head :title="'Devis ' + quote.reference" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Devis {{ quote.reference }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <!-- Infos générales -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="text-sm text-gray-500">Client</div>
                            <div class="font-bold">{{ quote.client?.name }}</div>
                            <div class="text-sm">{{ quote.client?.email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Date</div>
                            <div class="font-bold">{{ formatDate(quote.created_at) }}</div>
                            <div class="text-sm mt-1">
                                Statut :
                                <span class="font-bold uppercase">{{ quote.status }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lignes -->
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
                            <tr v-for="item in quote.items" :key="item.id" class="border-b">
                                <td class="px-4 py-2">{{ item.product?.name }} <span class="text-xs text-gray-500">({{ item.product?.sku }})</span></td>
                                <td class="px-4 py-2 text-right">{{ item.quantity }}</td>
                                <td class="px-4 py-2 text-right">{{ item.unit_price }} €</td>
                                <td class="px-4 py-2 text-right font-bold">{{ (item.quantity * item.unit_price).toFixed(2) }} €</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-lg">{{ quote.total_amount }} €</td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3 border-t pt-4">
                        <button
                            v-if="!quote.invoice"
                            @click="convertToInvoice"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        >
                            Transformer en facture
                        </button>
                        <div v-else class="text-green-700 font-bold py-2">
                            ✅ Déjà facturé (Facture #{{ quote.invoice.id }})
                        </div>

                        <a
                            :href="route('quotes.pdf', quote.id)"
                            target="_blank"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        >
                            📄 Télécharger PDF
                        </a>

                        <Link
                            :href="route('quotes.index')"
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