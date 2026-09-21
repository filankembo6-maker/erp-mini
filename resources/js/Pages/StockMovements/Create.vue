<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    products: Array,
});

const form = useForm({
    product_id: '',
    type: 'entree',
    quantity: 1,
    reason: '',
});

const selectedProduct = computed(() => {
    return props.products.find(p => p.id === parseInt(form.product_id));
});

const submit = () => {
    form.post(route('stock-movements.store'));
};
</script>

<template>
    <Head title="Nouveau mouvement de stock" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveau mouvement de stock
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <form @submit.prevent="submit" class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium">Produit *</label>
                            <select v-model="form.product_id" class="mt-1 block w-full border-gray-300 rounded">
                                <option value="">-- Choisir un produit --</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">
                                    {{ p.name }} ({{ p.sku }}) — Stock : {{ p.stock_quantity }}
                                </option>
                            </select>
                            <div v-if="form.errors.product_id" class="text-red-600 text-sm">{{ form.errors.product_id }}</div>
                        </div>

                        <div v-if="selectedProduct" class="p-3 bg-blue-50 rounded text-sm">
                            Stock actuel : <strong>{{ selectedProduct.stock_quantity }}</strong>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Type de mouvement *</label>
                            <div class="mt-2 flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.type" value="entree" class="mr-2" />
                                    <span class="text-green-700 font-semibold">↓ Entrée (ajout)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.type" value="sortie" class="mr-2" />
                                    <span class="text-red-700 font-semibold">↑ Sortie (retrait)</span>
                                </label>
                            </div>
                            <div v-if="form.errors.type" class="text-red-600 text-sm">{{ form.errors.type }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Quantité *</label>
                            <input v-model="form.quantity" type="number" min="1" class="mt-1 block w-full border-gray-300 rounded" />
                            <div v-if="form.errors.quantity" class="text-red-600 text-sm">{{ form.errors.quantity }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Motif</label>
                            <input v-model="form.reason" type="text" placeholder="Ex: Réapprovisionnement, Vente..." class="mt-1 block w-full border-gray-300 rounded" />
                            <div v-if="form.errors.reason" class="text-red-600 text-sm">{{ form.errors.reason }}</div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                            >
                                Enregistrer le mouvement
                            </button>
                            <Link :href="route('stock-movements.index')" class="text-gray-600 hover:underline">
                                Annuler
                            </Link>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>