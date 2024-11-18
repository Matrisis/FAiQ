<script setup>
import { ref, onMounted } from 'vue';
import LottieAnimation from '@/Components/LottieAnimation.vue'; // Composant Lottie personnalisé
import Rellax from 'rellax'; // Pour l'effet de parallaxe sur l'animation Lottie

import hero from '@/Lotties/hero.json';
import ai from '@/Lotties/ai.json';

const rellaxAnimation = ref(null);

const scrollToFeatures = () => {
    const featuresSection = document.getElementById('features');
    featuresSection.scrollIntoView({ behavior: 'smooth' });
}

onMounted(() => {
    new Rellax(rellaxAnimation.value, {
        speed: -4,
        center: true,
    });
});
</script>

<template>
    <div id="main-content">
        <!-- Section Héroïque en Plein Écran avec Effet Parallaxe -->
        <section class=" overflow-hidden h-screen bg-gradient-to-b from-blue-600 to-blue-300" id="hero">
            <!-- Arrière-plan Parallaxe -->
            <div class="absolute inset-0">
                <div class="parallax-bg"></div>
            </div>
            <!-- Contenu -->
            <div class="container mx-auto flex px-6 md:flex-row flex-col items-center h-full  ">
                <!-- Contenu Texte -->
                <div class="flex mt-6 md:mt-0 flex-col w-full md:w-1/2 justify-center items-start text-center md:text-left text-white z-10">
                    <h1 class="text-5xl font-bold leading-tight animate-fade-in-down">
                        Bienvenue sur <span class="text-blue-700">FAiQ</span>
                    </h1>
                    <p class="leading-normal text-2xl mt-4 animate-fade-in-up">
                        Créez votre propre FAQ propulsée par l'IA pour améliorer votre support client.
                    </p>
                    <button @click="$inertia.visit(route('register'))"
                        class="mx-auto md:mx-0 mt-6 px-8 py-3 text-white rounded-full bg-blue-700 hover:bg-blue-500  focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 animate-bounce-in">
                        Commencez maintenant
                    </button>
                    <div class="mx-auto md:mx-0 mt-6 flex justify-center md:justify-start">
                        <a @click="scrollToFeatures" class="text-white hover:underline hover:cursor-pointer">En savoir plus</a>
                    </div>
                </div>
                <!-- Animation Lottie avec Effet Parallaxe -->
                <div class="w-full md:w-1/2 py-6 text-center">
                    <div ref="rellaxAnimation" class="parallax-lottie  w-3/4 mx-auto animate-fade-in-down">
                        <LottieAnimation
                            :animation-data="hero"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Fonctionnalités -->
        <section class="bg-blue-50 py-12" id="features">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-blue-800">Découvrez les fonctionnalités de FAiQ</h2>
                <div class="flex mt-8">
                    <!-- Fonctionnalité 1 -->
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4 flex flex-col items-center">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="flex">
                                <LottieAnimation
                            :animation-data="ai"
                        />
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Réponses basées sur l'IA</h3>
                            <p class="text-gray-600 text-center">
                                Fournit des réponses précises et contextuelles grâce à l'intelligence artificielle.
                            </p>
                        </div>
                    </div>
                    <!-- Fonctionnalité 2 -->
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="mb-4">
                                <LottieAnimation
                                    :path="'/storage/lotties/hero.json'"
                                />
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Intégration facile</h3>
                            <p class="text-gray-600 text-center">
                                S'intègre facilement à votre site web ou application sans nécessiter de compétences en programmation.
                            </p>
                        </div>
                    </div>
                    <!-- Fonctionnalité 3 -->
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="mb-4">
                                <LottieAnimation
                                    :path="'/storage/lotties/hero.json'"
                                />
                            <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Personnalisation avancée</h3>
                            <p class="text-gray-600 text-center">
                                Personnalisez l'apparence et le comportement de la FAQ pour correspondre à votre marque.
                            </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fonctionnalité 4 -->
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="mb-4">
                                <LottieAnimation
                                    :path="'/storage/lotties/hero.json'"
                                />
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Support multilingue</h3>
                            <p class="text-gray-600 text-center">
                                Prend en charge plusieurs langues pour servir une clientèle internationale.
                            </p>
                        </div>
                    </div>
                    <!-- Ajoutez d'autres fonctionnalités de la même manière -->
                </div>
            </div>
        </section>


        <!-- Section Avantages -->
        <section class="bg-gradient-to-br from-green-200 to-blue-200 py-12">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800">Les avantages pour votre entreprise</h2>
                <div class="mt-8 flex flex-wrap">
                    <!-- Avantage 1 -->
                    <div class="w-full md:w-1/2 p-4">
                        <div class="flex">
                            <div class="mr-4 animate-wiggle">
                                <!-- Icône -->
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <!-- Icône SVG -->
                                    <path d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm8-3a1 1 0 100 2 1 1 0 000-2zm1 4H9v5h2v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">Amélioration de la satisfaction client</h3>
                                <p class="text-gray-700">
                                    Des réponses précises et rapides pour une meilleure expérience client.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Avantage 2 -->
                    <div class="w-full md:w-1/2 p-4">
                        <div class="flex">
                            <div class="mr-4 animate-spin">
                                <!-- Icône -->
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <!-- Icône SVG -->
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12H9v5h2V6zM9 13h2v2H9v-2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">Réduction des coûts</h3>
                                <p class="text-gray-700">
                                    Moins de ressources nécessaires pour le support client grâce à l'automatisation.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Ajoutez d'autres avantages si nécessaire -->
                </div>
            </div>
        </section>

        <!-- Section Témoignages -->
        <section class="bg-white dark:bg-gray-800 py-12">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100">Ce que disent nos clients</h2>
                <div class="mt-8 flex flex-wrap">
                    <!-- Témoignage 1 -->
                    <div class="w-full md:w-1/3 p-4">
                        <div class="bg-blue-50 dark:bg-gray-700 p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                            <p class="text-gray-600 dark:text-gray-300 italic">
                                "FAiQ a révolutionné notre support client ! Nos clients obtiennent des réponses instantanément."
                            </p>
                            <div class="mt-4 flex items-center">
                                <img class="w-10 h-10 rounded-full border-2 border-blue-600" src="" alt="Client 1">
                                <div class="ml-3">
                                    <p class="text-gray-800 dark:text-gray-100 font-semibold">Jean Dupont</p>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">CEO, Entreprise X</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section CTA Finale -->
        <section class="bg-gradient-to-r from-purple-600 to-indigo-600 py-16 overflow-hidden">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold text-white animate-fade-in-down">Prêt à transformer votre support client avec FAiQ ?</h2>
                <button class="mt-6 px-8 py-3 bg-white text-purple-600 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 animate-bounce-in">
                    Inscrivez-vous dès maintenant
                </button>
            </div>
            <!-- Particules animées -->
            <div class="absolute inset-0 pointer-events-none">
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 py-6">
            <div class="container mx-auto px-6 text-center text-gray-400">
                &copy; 2023 FAiQ. Tous droits réservés.
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Animations Tailwind CSS */

/* Effet Parallaxe */
.parallax-bg {
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    width: 100%;
    height: 100%;
}

/* Ajustements pour l'effet de parallaxe sur l'animation Lottie */
.parallax {
    perspective: 1px;
}

.parallax > * {
    transform: translateZ(0);
}

/* Animations supplémentaires */
@keyframes fade-in-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.5s ease-out;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out;
}

.animate-bounce-in {
    animation: bounceIn 1s;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes wiggle {
    0%,
    100% {
        transform: rotate(-3deg);
    }
    50% {
        transform: rotate(3deg);
    }
}

.animate-wiggle {
    animation: wiggle 1s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(-10px);
    }
    50% {
        transform: translateY(10px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

@keyframes blob {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.animate-blob {
    animation: blob 8s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animate-spin-slow {
    animation: spin 4s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Styles supplémentaires */
</style>
