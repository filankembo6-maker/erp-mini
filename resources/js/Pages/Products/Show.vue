<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});
</script>

<template>
    <Head :title="'Produit : ' + product.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails du produit
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="space-y-4">
                        <div>
                            <span class="font-bold">Nom :</span> {{ product.name }}
                        </div>
                        <div>
                            <span class="font-bold">SKU :</span> {{ product.sku }}
                        </div>
                        <div>
                            <span class="font-bold">Description :</span> {{ product.description || 'Aucune' }}
                        </div>
                        <div>
                            <span class="font-bold">Prix :</span> {{ product.price }} €
                        </div>
                        <div>
                            <span class="font-bold">Stock :</span> 
                            <span :class="product.stock_quantity <= product.stock_alert ? 'text-red-600 font-bold' : ''">
                                {{ product.stock_quantity }}
                            </span>
                        </div>
                        <div>
                            <span class="font-bold">Seuil d'alerte :</span> {{ product.stock_alert }}
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <Link
                            :href="route('products.edit', product.id)"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                        >
                            Modifier
                        </Link>
                        <Link
                            :href="route('products.index')"
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