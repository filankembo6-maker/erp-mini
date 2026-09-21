<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    clients: Array,
    products: Array,
});

const form = useForm({
    client_id: '',
    status: 'brouillon',
    items: [
        { product_id: '', quantity: 1, unit_price: 0 }
    ],
});

const total = computed(() => {
    return form.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
});

const addItem = () => {
    form.items.push({ product_id: '', quantity: 1, unit_price: 0 });
};

const removeItem = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

const onProductChange = (index) => {
    const productId = form.items[index].product_id;
    const product = props.products.find(p => p.id === parseInt(productId));
    if (product) {
        form.items[index].unit_price = parseFloat(product.price);
    }
};

const submit = () => {
    form.post(route('quotes.store'));
};
</script>

<template>
    <Head title="Nouveau devis" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveau devis
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <form @submit.prevent="submit" class="space-y-6">

                        <!-- Client + Statut -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Client *</label>
                                <select v-model="form.client_id" class="mt-1 block w-full border-gray-300 rounded">
                                    <option value="">-- Choisir un client --</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">
                                        {{ c.name }} ({{ c.email }})
                                    </option>
                                </select>
                                <div v-if="form.errors.client_id" class="text-red-600 text-sm">{{ form.errors.client_id }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Statut *</label>
                                <select v-model="form.status" class="mt-1 block w-full border-gray-300 rounded">
                                    <option value="brouillon">Brouillon</option>
                                    <option value="envoye">Envoyé</option>
                                    <option value="accepte">Accepté</option>
                                    <option value="refuse">Refusé</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lignes de produits -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold">Lignes du devis</h3>
                                <button
                                    type="button"
                                    @click="addItem"
                                    class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                >
                                    + Ajouter une ligne
                                </button>
                            </div>

                            <div class="space-y-2">
                                <div
                                    v-for="(item, index) in form.items"
                                    :key="index"
                                    class="grid grid-cols-12 gap-2 items-end border p-3 rounded bg-gray-50"
                                >
                                    <div class="col-span-5">
                                        <label class="block text-xs">Produit</label>
                                        <select
                                            v-model="item.product_id"
                                            @change="onProductChange(index)"
                                            class="mt-1 block w-full border-gray-300 rounded text-sm"
                                        >
                                            <option value="">-- Choisir --</option>
                                            <option v-for="p in products" :key="p.id" :value="p.id">
                                                {{ p.name }} ({{ p.sku }}) — {{ p.price }}€
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs">Quantité</label>
                                        <input
                                            v-model.number="item.quantity"
                                            type="number"
                                            min="1"
                                            class="mt-1 block w-full border-gray-300 rounded text-sm"
                                        />
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs">Prix unitaire (€)</label>
                                        <input
                                            v-model.number="item.unit_price"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            class="mt-1 block w-full border-gray-300 rounded text-sm"
                                        />
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="text-xs text-gray-500">Sous-total</div>
                                        <div class="font-bold text-sm">{{ (item.quantity * item.unit_price).toFixed(2) }} €</div>
                                        <button
                                            type="button"
                                            @click="removeItem(index)"
                                            class="text-xs text-red-600 hover:underline mt-1"
                                            :disabled="form.items.length === 1"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.errors.items" class="text-red-600 text-sm mt-2">{{ form.errors.items }}</div>
                        </div>

                        <!-- Total -->
                        <div class="text-right text-xl font-bold border-t pt-4">
                            TOTAL : {{ total.toFixed(2) }} €
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                            >
                                Enregistrer le devis
                            </button>
                            <Link :href="route('quotes.index')" class="text-gray-600 hover:underline">
                                Annuler
                            </Link>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>