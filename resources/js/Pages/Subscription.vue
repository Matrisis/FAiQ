<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    team: Object,
    active_subscription: Boolean,
});

const resumeSubscription = () => {
    window.location.href = route('admin.subscription.resume', { team: props.team.id });
}

const cancelSubscription = () => {
    if (props.active_subscription && confirm("Voulez-vous annuler votre abonnement ?")) {
        window.location.href = route('admin.subscription.cancel', {team: props.team.id})
    } else
        alert("Abonnement non actif")
}

</script>
<template>
    <AppLayout title="Gestion de l'abonnement">
        <div class=" bg-gray-50 flex flex-col items-center py-12 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
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

                <div class="flex flex-col lg:flex-row px-20 w-full">
                    <div class="w-full lg:w-1/2">

                    </div>
                    <div class="w-full lg:w-1/2">

                    </div>
                </div>

                <!--
                <div class="bg-white shadow sm:rounded-lg text-center">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Détails de l'abonnement
                        </h3>
                        <div v-if="subscription" class="mt-5">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Statut
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ subscription.stripe_status | statusLabel }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Plan
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ subscription.name }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-2" v-if="subscriptionEndsAt">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Fin de l'abonnement
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        Votre abonnement se terminera le {{ subscriptionEndsAt }}.
                                    </dd>
                                </div>
                            </dl>
                            <div class="mt-6 flex space-x-3">
                                <button
                                    v-if="canCancel"
                                    @click="cancelSubscription"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    Annuler l'abonnement
                                </button>
                                <button
                                    v-if="canResume"
                                    @click="resumeSubscription"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                >
                                    Reprendre l'abonnement
                                </button>
                            </div>
                        </div>
                        <div v-else class="mt-5">
                            <p class="text-sm text-gray-500">
                                Vous n'avez pas d'abonnement actif.
                            </p>
                            <button
                                @click="redirectToCheckout"
                                class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Souscrire à un abonnement
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Vos factures
                    </h3>
                    <div class="mt-5 bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div v-if="invoices.length">
                                <div class="flex flex-col">
                                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Montant
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Actions
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="invoice in invoices" :key="invoice.id">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ formatDate(invoice.date) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ formatAmount(invoice.total) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <a :href="downloadInvoiceUrl(invoice.id)" class="text-indigo-600 hover:text-indigo-900">Télécharger</a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-sm text-gray-500">
                                    Aucune facture disponible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Demander un remboursement
                    </h3>
                    <div class="mt-5">
                        <button
                            @click="requestRefund"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400"
                        >
                            Demander un remboursement pour la dernière facture
                        </button>
                    </div>
                </div>
                !-->
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Styles personnalisés pour améliorer l'apparence */
</style>
