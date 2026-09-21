<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    sku: '',
    description: '',
    price: '',
    stock_quantity: 0,
    stock_alert: 5,
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>
    <Head title="Ajouter un produit" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter un produit</h2>
        </template>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Nom *</label>
                            <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded" />
                            <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">SKU *</label>
                            <input v-model="form.sku" type="text" class="mt-1 block w-full border-gray-300 rounded" />
                            <div v-if="form.errors.sku" class="text-red-600 text-sm">{{ form.errors.sku }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Description</label>
                            <textarea v-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded"></textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Prix *</label>
                                <input v-model="form.price" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded" />
                                <div v-if="form.errors.price" class="text-red-600 text-sm">{{ form.errors.price }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Stock initial *</label>
                                <input v-model="form.stock_quantity" type="number" class="mt-1 block w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Seuil d'alerte</label>
                                <input v-model="form.stock_alert" type="number" class="mt-1 block w-full border-gray-300 rounded" />
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                                Enregistrer
                            </button>
                            <Link :href="route('products.index')" class="text-gray-600 hover:underline">Annuler</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>