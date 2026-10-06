<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    invoice: Object,
});

const draft = ref('');
const showModal = ref(false);
const loading = ref(false);
const error = ref('');

const changeStatus = (status) => {
    router.patch(route('invoices.update-status', props.invoice.id), { status });
};

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const statusLabel = (status) => {
    const labels = { impayee: 'Impayée', payee: 'Payée', annulee: 'Annulée' };
    return labels[status] || status;
};

const generateReminder = async () => {
    loading.value = true;
    error.value = '';
    draft.value = '';
    showModal.value = true;

    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const response = await fetch(route('invoices.reminder', props.invoice.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
        });

        const data = await response.json();

        if (!response.ok) {
            error.value = data.error || 'Erreur lors de la génération.';
        } else {
            draft.value = data.draft;
        }
    } catch (e) {
        error.value = 'Erreur réseau. Veuillez réessayer.';
    } finally {
        loading.value = false;
    }
};

const closeModal = () => {
    showModal.value = false;
};

const copyDraft = async () => {
    try {
        await navigator.clipboard.writeText(draft.value);
    } catch (e) {
        // ignore
    }
    showModal.value = false;
};
</script>

<template>
    <Head :title="'Facture ' + invoice.reference" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto">

            <div class="bg-white border border-slate-200 rounded-xl p-6 mb-4">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-[0.14em] font-semibold">Client</div>
                        <div class="font-bold text-[#1e3a8a] mt-1">{{ invoice.client?.name }}</div>
                        <div class="text-[12px] text-slate-500 mt-0.5">{{ invoice.client?.email }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-slate-500 uppercase tracking-[0.14em] font-semibold">Date</div>
                        <div class="font-bold text-[#1e3a8a] mt-1">{{ formatDate(invoice.created_at) }}</div>
                        <div class="text-[12px] text-slate-500 mt-0.5">
                            Statut : <span class="font-semibold">{{ statusLabel(invoice.status) }}</span>
                        </div>
                    </div>
                </div>

                <table class="w-full mb-6">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-[10px] uppercase text-slate-500 font-semibold tracking-[0.1em]">Produit</th>
                            <th class="px-4 py-2 text-right text-[10px] uppercase text-slate-500 font-semibold tracking-[0.1em]">Qté</th>
                            <th class="px-4 py-2 text-right text-[10px] uppercase text-slate-500 font-semibold tracking-[0.1em]">Prix unitaire</th>
                            <th class="px-4 py-2 text-right text-[10px] uppercase text-slate-500 font-semibold tracking-[0.1em]">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in invoice.items" :key="item.id" class="border-b border-slate-100">
                            <td class="px-4 py-2 text-[12px] text-[#1e3a8a]">{{ item.product?.name }}</td>
                            <td class="px-4 py-2 text-[12px] text-right text-slate-600">{{ item.quantity }}</td>
                            <td class="px-4 py-2 text-[12px] text-right text-slate-600">{{ item.unit_price }} €</td>
                            <td class="px-4 py-2 text-[12px] text-right font-bold text-[#1e3a8a]">{{ (item.quantity * item.unit_price).toFixed(2) }} €</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-bold text-[#1e3a8a] text-[13px]">TOTAL</td>
                            <td class="px-4 py-3 text-right font-bold text-[#1e3a8a] text-[15px]">{{ invoice.total_amount }} €</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="border-t border-slate-100 pt-4 flex flex-wrap gap-2">
                    <button
                        v-if="invoice.status !== 'payee'"
                        @click="changeStatus('payee')"
                        class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[12px] font-semibold transition-colors">
                        Marquer comme payée
                    </button>
                    <button
                        v-if="invoice.status !== 'annulee'"
                        @click="changeStatus('annulee')"
                        class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg text-[12px] font-semibold transition-colors">
                        Annuler la facture
                    </button>

                    <button
                        v-if="invoice.status === 'impayee'"
                        @click="generateReminder"
                        class="px-4 py-2 bg-[#1e3a8a] hover:bg-[#152e6b] text-white rounded-lg text-[12px] font-semibold transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Rédiger la relance avec l'IA
                    </button>

                    <a :href="route('invoices.pdf', invoice.id)" target="_blank"
                       class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-[12px] font-semibold transition-colors">
                        Télécharger PDF
                    </a>

                    <Link :href="route('invoices.index')"
                          class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[12px] font-semibold transition-colors">
                        Retour
                    </Link>
                </div>
            </div>

            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-[#1e3a8a]">Relance pour {{ invoice.reference }}</h2>
                        <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 flex-1 overflow-y-auto">
                        <div v-if="loading" class="flex items-center justify-center py-12">
                            <div class="text-[13px] text-slate-500">Génération en cours...</div>
                        </div>

                        <div v-else-if="error" class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-[12px] text-red-700">
                            {{ error }}
                        </div>

                        <div v-else>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-[0.14em] mb-2">
                                Brouillon (modifiable)
                            </label>
                            <textarea
                                v-model="draft"
                                rows="14"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 text-[13px] text-[#1e3a8a] leading-relaxed focus:outline-none focus:border-[#22d3ee] font-mono"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50">
                        <button @click="closeModal" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-[12px] font-semibold">
                            Fermer
                        </button>
                        <button @click="copyDraft" class="px-4 py-2 bg-[#1e3a8a] hover:bg-[#152e6b] text-white rounded-lg text-[12px] font-semibold">
                            Copier et fermer
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>