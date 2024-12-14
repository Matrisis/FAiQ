<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    team: Object,
    active_subscription: Boolean,
    invoices: Object
});


const headers = [
    { text: "Numero", value: "number", sortable: true},
    { text: "Periode de début", value: "period_start", sortable: true },
    { text: "Periode de fin", value: "period_end", sortable: true },
    { text: "Payée", value: "paid", sortable: true },
    { text: "Montant", value: "amount_paid", sortable: true },
    { text: "Voir", value: "hosted_invoice_url", sortable: false },
];

const resumeSubscription = () => {
    window.location.href = route('admin.subscription.resume', { team: props.team.id });
}

const cancelSubscription = () => {
    if (props.active_subscription && confirm("Voulez-vous    annuler votre abonnement ?")) {
        window.location.href = route('admin.subscription.cancel', {team: props.team.id})
    } else
        alert("Abonnement non actif")
}

</script>
<template>
    <AppLayout title="Gestion de l'abonnement">
        <div class="min-h-screen bg-gray-50 flex flex-col items-center py-12 sm:px-6 lg:px-8 w-full">
            <div class="flex flex-col max-w-md w-full space-y-8 w-full">
                <div class="text-center">
                    <h2 class=" text-3xl font-extrabold text-gray-900">
                        Gérer votre abonnement
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Consultez les détails de votre abonnement et gérez vos factures
                    </p>
                </div>

                <div class="flex flex-col justify-center items-center border-gray-500 border p-4 rounded mt-12">
                    <h2 class="text-xl font-extrabold text-gray-900">Abonnement en cours : </h2>
                    <div class="flex mt-2">
                        <h2 class="text-xl font-extrabold text-blue-600" v-if="active_subscription">{{ team.pricing.name }}</h2>
                        <h2 class="text-xl font-extrabold text-red-600" v-else>Abonnement non actif</h2>
                    </div>
                    <div v-if="!active_subscription" class="mt-6 flex flex-col justify-center items-center">
                        <h2 class="flex">Reprendre l'abonnement</h2>
                        <div class="flex justify-center mt-2 ">
                            <button @click="resumeSubscription" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Reprendre l'abonnement
                            </button>
                        </div>
                    </div>
                    <div v-else class="mt-6 flex flex-col justify-center items-center">
                        <h2 class="flex">Annuler l'abonnement</h2>
                        <div class="flex justify-center mt-2">
                            <button @click="cancelSubscription" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Annuler l'abonnement
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center items-center w-full">
                    <EasyDataTable
                        v-if="props.invoices"
                        table-class-name="customize-table"
                        buttons-pagination
                        :headers="headers"
                        :items="props.invoices"
                        must-sort
                        alternating
                    >
                        <template #item-number="item">
                            <a :href="item.hosted_invoice_url" target="_blank" class="text-blue-600">
                                {{ item.number }}
                            </a>
                        </template>
                        <template #item-period_start="item">
                            {{ new Date(item.period_start *1000).toLocaleDateString() }}
                        </template>
                        <template #item-period_end="item">
                            {{ new Date(item.period_end *1000).toLocaleDateString() }}
                        </template>
                        <template #item-amount_paid="item">
                            {{ item.amount_paid }} {{ item.currency }}
                        </template>
                        <template #item-paid="item">
                            <span v-if="item.paid" class="text-green-600">Oui</span>
                            <span v-else class="text-red-600">Non</span>
                        </template>
                        <template #item-hosted_invoice_url="item">
                            <a :href="item.hosted_invoice_url" target="_blank" class="text-blue-600">
                                Voir
                            </a>
                        </template>
                        <template #loading>
                            Chargement...
                        </template>
                        <template #empty-message>
                            Aucune reponse.
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Styles personnalisés pour améliorer l'apparence */
</style>
