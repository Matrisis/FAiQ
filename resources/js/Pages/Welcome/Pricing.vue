<script setup>
import { ref } from 'vue';
import LandingLayout from "@/Layouts/LandingLayout.vue";

const plans = ref([
    {
        name: 'Pack Basique',
        price: '3 000€ + 0,09€ / requête',
        features: [
            'Tarif fixe avec un coût additionnel par requête',
            'Idéal pour des besoins prévisibles',
            'Support standard inclus',
        ],
        buttonText: 'Commencer',
        buttonLink: '/register',
        popular: false,
    },
    {
        name: 'Option Flexible',
        price: '0,15€ / requête',
        features: [
            'Paiement à l’usage, sans engagement',
            'Idéal pour des besoins variables',
            'Arrêtez quand vous voulez',
        ],
        buttonText: 'Choisir ce plan',
        buttonLink: '/register',
        popular: true,
    },
    {
        name: 'Offre Sur Mesure',
        price: 'Personnalisé',
        features: [
            'Solutions adaptées à vos besoins spécifiques',
            'Gestion de compte dédiée',
            'Support 24/7 premium',
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
        <section class="h-screen bg-gradient-to-r from-blue-100 via-white to-blue-100 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-5xl font-extrabold text-center text-blue-800 mb-16">
                    Découvrez nos offres tarifaires
                </h2>
                <div class="flex flex-col lg:flex-row justify-center items-stretch lg:space-x-12">
                    <div
                        v-for="(plan, index) in plans"
                        :key="index"
                        class="bg-white rounded-2xl shadow-xl p-8 mb-8 lg:mb-0 w-full lg:w-1/3 flex flex-col transform hover:scale-105 transition-transform duration-300"
                    >
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold text-center text-gray-800 mb-4">{{ plan.name }}</h3>
                            <p class="text-center text-blue-700 text-2xl font-semibold mb-6">{{ plan.price }}</p>
                            <div v-if="plan.popular" class="mb-6">
                                <span class="block text-center text-sm text-white bg-blue-600 rounded-full px-4 py-1">
                                    Le plus populaire
                                </span>
                            </div>
                            <ul class="mb-6">
                                <li
                                    v-for="(feature, idx) in plan.features"
                                    :key="idx"
                                    class="flex items-center mb-4"
                                >
                                    <svg
                                        class="w-6 h-6 text-green-500 mr-3"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span class="text-gray-700 text-lg">{{ feature }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <button
                                @click="$router.push(plan.buttonLink)"
                                class="px-6 py-3 bg-blue-600 text-white text-lg rounded-full hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50"
                            >
                                {{ plan.buttonText }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-blue-800 text-white py-16">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-6">
                    Prêt à faire passer votre projet au niveau supérieur ?
                </h2>
                <p class="text-lg mb-8">
                    Choisissez l'offre qui vous convient ou contactez-nous pour une solution entièrement personnalisée.
                </p>
                <button
                    @click="$router.push('/contact')"
                    class="px-8 py-4 bg-white text-blue-800 font-semibold text-lg rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300"
                >
                    Contactez-nous dès maintenant
                </button>
            </div>
        </section>
    </LandingLayout>
</template>

<style scoped>
/* Ajout de transitions fluides et styles adaptés */
body {
    background-color: #f8fafc;
}
</style>
