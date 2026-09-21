<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
});

const submit = () => {
    form.post(route('clients.store'));
};
</script>

<template>
    <Head title="Ajouter un client" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ajouter un client
            </h2>
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
                            <label class="block text-sm font-medium">Email *</label>
                            <input v-model="form.email" type="email" class="mt-1 block w-full border-gray-300 rounded" />
                            <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Téléphone</label>
                            <input v-model="form.phone" type="text" class="mt-1 block w-full border-gray-300 rounded" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Adresse</label>
                            <input v-model="form.address" type="text" class="mt-1 block w-full border-gray-300 rounded" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Ville</label>
                            <input v-model="form.city" type="text" class="mt-1 block w-full border-gray-300 rounded" />
                        </div>

                        <div class="flex items-center space-x-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                            >
                                Enregistrer
                            </button>
                            <Link :href="route('clients.index')" class="text-gray-600 hover:underline">
                                Annuler
                            </Link>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>