<script setup>
// Props reçues
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    team: Object,
});

const redirect = () => {
    window.location.href = route('admin.billing.checkout', { team: props.team.id });
};
</script>

<template>
    <AppLayout title="Billing">
        <div class="flex items-center justify-center min-h-screen bg-gradient-to-b from-indigo-50 to-indigo-100 p-4">
            <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full text-center transform transition-all duration-300 hover:scale-105 md:max-w-lg lg:max-w-xl">
                <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-6">Finalisez votre inscription</h1>

                <!-- Badge de sécurité -->
                <div class="flex items-center justify-center mb-4">
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Paiement sécurisé
                    </span>
                </div>

                <!-- Message d'encouragement -->
                <p class="text-gray-600 mb-2">Merci de vous être inscrit !</p>
                <p class="text-gray-600 mb-6">Profitez de notre offre exclusive en complétant le paiement maintenant.</p>

                <!-- Bouton de paiement avec icône -->
                <button
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center justify-center gap-2"
                    @click="redirect">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Payer Maintenant
                </button>

                <!-- Note d’encouragement supplémentaire -->
                <p class="text-gray-400 mt-6 text-sm">Accédez à toutes nos fonctionnalités en validant votre paiement.</p>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
#card-element {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>
