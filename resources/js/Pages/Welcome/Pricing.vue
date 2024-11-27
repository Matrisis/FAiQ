<script setup>
import { ref } from 'vue';
import LandingLayout from "@/Layouts/LandingLayout.vue";

const plans = ref([
    {
        name: 'Basique',
        price: 'Gratuit',
        features: [
            'Accès limité aux fonctionnalités',
            'Support par email',
            'FAQ standard',
        ],
        buttonText: 'Commencer',
        buttonLink: '/register',
        popular: false,
    },
    {
        name: 'Professionnel',
        price: '29€ / mois',
        features: [
            'Toutes les fonctionnalités de Basique',
            'FAQ personnalisable',
            'Intégration facile',
            'Support prioritaire',
        ],
        buttonText: 'Choisir le plan',
        buttonLink: '/register',
        popular: true,
    },
    {
        name: 'Entreprise',
        price: 'Sur mesure',
        features: [
            'Toutes les fonctionnalités de Professionnel',
            'Solutions personnalisées',
            'Gestion de compte dédiée',
            'Support 24/7',
        ],
        buttonText: 'Contactez-nous',
        buttonLink: '/contact',
        popular: false,
    },
]);
</script>

<template>
    <LandingLayout>
        <!-- Section Pricing -->
        <section class="h-full bg-gray-100 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center text-blue-800 mb-12">Nos offres tarifaires</h2>
                <div class="flex flex-col md:flex-row justify-center items-stretch md:space-x-6">
                    <div v-for="(plan, index) in plans" :key="index" class="bg-white rounded-lg shadow-md p-6 mb-6 md:mb-0 w-full md:w-1/3 flex flex-col">
                        <div class="flex-1">
                            <div class="mb-4">
                                <h3 class="text-2xl font-semibold text-center text-gray-800">{{ plan.name }}</h3>
                                <p class="text-center text-gray-600 mt-2 text-xl">{{ plan.price }}</p>
                                <div v-if="plan.popular" class="mt-4">
                                    <span class="block text-center text-sm text-white bg-blue-600 rounded-full px-4 py-1">Le plus populaire</span>
                                </div>
                            </div>
                            <ul class="mt-4 mb-6">
                                <li v-for="(feature, idx) in plan.features" :key="idx" class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">{{ feature }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <button @click="$router.push(plan.buttonLink)" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                                {{ plan.buttonText }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
    </LandingLayout>
</template>

<style scoped>
/* Vos styles restent inchangés */
</style>
