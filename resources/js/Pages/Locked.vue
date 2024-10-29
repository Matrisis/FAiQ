<script setup>
// Props reçues
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    team: Object,
});

const redirectHome = () => {
    window.location.href = route('home');
};
</script>

<template>
    <AppLayout title="Équipe verrouillée">
        <div class="flex items-center justify-center min-h-screen bg-gradient-to-b from-indigo-50 to-indigo-100 p-4">
            <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full text-center transform transition-all duration-300 hover:scale-105 md:max-w-lg lg:max-w-xl">
                <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-6">Équipe Verrouillée</h1>

                <!-- Icône de verrouillage -->
                <div class="flex items-center justify-center mb-4">
                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12V8a3 3 0 116 0v4m-3 6h.01" />
                        </svg>
                        Accès restreint
                    </span>
                </div>

                <!-- Message d'information -->
                <p class="text-gray-600 mb-2">Votre équipe est actuellement verrouillée.</p>
                <p class="text-gray-600 mb-6">Veuillez patienter, un administrateur doit valider votre demande pour accéder aux fonctionnalités.</p>

                <!-- Note d'attente -->
                <p class="text-gray-400 mt-6 text-sm">Merci de votre patience, vous recevrez une notification dès que votre accès sera activé.</p>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Style personnalisé si nécessaire */
</style>
