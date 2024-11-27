<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'; // Importation pour la navigation
import LottieAnimation from '@/Components/LottieAnimation.vue'; // Composant Lottie personnalisé
import Rellax from 'rellax'; // Pour l'effet de parallaxe sur l'animation Lottie

import hero from '@/Lotties/hero.json';
import ai from '@/Lotties/ai.json';
import integration from '@/Lotties/integration.json';
import hero2 from '@/Lotties/hero2.json';
import configuration from '@/Lotties/configuration.json';
import time from '@/Lotties/time.json';
import register from '@/Lotties/register.json';
import engineering from '@/Lotties/engineering.json';
import upload from '@/Lotties/upload.json';
import thinking from '@/Lotties/thinking.json';
import LandingLayout from "@/Layouts/LandingLayout.vue";

const rellaxAnimation = ref(null);

const scrollTo = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(() => {
    new Rellax(rellaxAnimation.value, {
        speed: -4,
        center: true,
    });
});
</script>

<template>
    <LandingLayout>
        <!-- Section Héroïque en Plein Écran avec Effet Parallaxe -->
        <section class="overflow-hidden h-screen bg-gradient-to-b from-blue-600 to-blue-300" id="hero">
            <!-- Arrière-plan Parallaxe -->
            <div class="absolute inset-0">
                <div class="parallax-bg"></div>
            </div>
            <!-- Contenu -->
            <div ref="rellaxAnimation" class="container mx-auto flex px-6 md:flex-row flex-col items-center h-full">
                <!-- Contenu Texte -->
                <div class="flex mt-6 md:mt-0 flex-col w-full md:w-1/2 justify-center items-start text-center md:text-left text-white z-10">
                    <h1 class="text-5xl font-bold leading-tight animate-fade-in-down">
                        Bienvenue sur <span class="text-blue-700">FAiQ</span>
                    </h1>
                    <p class="leading-normal text-2xl mt-4 animate-fade-in-up">
                        Augmentez votre satisfaction client grâce à notre service IA de support client, satisfait ou remboursé.
                    </p>
                    <button @click="$inertia.visit(route('register'))"
                            class="mx-auto md:mx-0 mt-6 px-8 py-3 text-white rounded-full bg-blue-700 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 animate-bounce-in">
                        Commencez maintenant
                    </button>
                    <div class="mx-auto md:mx-0 mt-6 flex justify-center md:justify-start animate-fade-in-down">
                        <div @click="scrollTo('discover')" class="text-white hover:underline cursor-pointer">En savoir plus</div>
                    </div>
                </div>
                <!-- Animation Lottie avec Effet Parallaxe -->
                <div class="w-full md:w-1/2 py-6 text-center">
                    <div class="parallax-lottie w-3/4 mx-auto animate-fade-in-down">
                        <LottieAnimation
                            :animation-data="hero2"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Section "Qu'est-ce que c'est ?" -->
        <section class="bg-white py-20" id="discover">
            <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
                <!-- Animation -->
                <div class="w-full md:w-1/2 mt-8 md:mt-0 flex justify-center">
                    <div class="w-1/2">
                        <LottieAnimation
                            :animationData="thinking"
                            :loop="true"
                            :autoplay="true"
                        />
                    </div>
                </div>
                <!-- Contenu Texte -->
                <div class="w-full md:w-1/2">
                    <h2 class="text-4xl font-bold text-blue-800 mb-6">Qu'est-ce que <span class="text-blue-200">FAiQ</span> ?</h2>
                    <p class="text-gray-700 mb-4">
                        FAiQ est une application innovante qui permet aux entreprises de créer une FAQ intelligente, propulsée par l'intelligence artificielle. Elle offre des réponses précises et instantanées aux questions de vos clients, améliorant ainsi leur satisfaction et réduisant la charge de travail de votre équipe support.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Grâce à FAiQ, vous pouvez personnaliser votre FAQ pour qu'elle corresponde à votre image de marque, tout en bénéficiant d'une intégration facile sur votre site web ou application.
                    </p>
                    <button @click="scrollTo('features')" class="mt-4 px-8 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50 ">
                        Découvrir les fonctionnalités
                    </button>
                </div>
            </div>
        </section>

        <!-- Section Fonctionnalités -->
        <section class="bg-blue-50 py-20" id="features">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-blue-800">Découvrez les fonctionnalités de FAiQ</h2>
                <div class="flex flex-col md:flex-row mt-8">
                    <!-- Fonctionnalité 1 -->
                    <div class="w-full md:w-1/2 lg:w-1/3 p-4 flex flex-col items-center">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="flex w-full mx-auto h-1/2">
                                <LottieAnimation
                                    :animation-data="ai"
                                />
                            </div>
                            <div class="h-1/2 flex flex-col items-center justify-center">
                                <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Réponses basées sur l'IA</h3>
                                <p class="text-gray-600 text-center">
                                    Fournit des réponses précises et contextuelles grâce à l'intelligence artificielle.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fonctionnalité 2 -->
                    <div class="flex w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="flex w-full mx-auto h-1/2">
                                <LottieAnimation
                                    :animation-data="integration"
                                />
                            </div>
                            <div class="h-1/2 flex flex-col items-center justify-center">
                                <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Intégration facile</h3>
                                <p class="text-gray-600 text-center">
                                    S'intègre facilement à votre site web ou application sans nécessiter de compétences en programmation.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fonctionnalité 3 -->
                    <div class="flex w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="flex w-full mx-auto h-1/2">
                                <LottieAnimation
                                    :animation-data="configuration"
                                />
                            </div>
                            <div class="h-1/2 flex flex-col items-center justify-center">
                                <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Personnalisation avancée</h3>
                                <p class="text-gray-600 text-center">
                                    Personnalisez l'apparence et le comportement de la FAQ pour correspondre à votre marque.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fonctionnalité 4 -->
                    <div class="flex w-full md:w-1/2 lg:w-1/3 p-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Animation Lottie -->
                            <div class="flex w-full mx-auto h-1/2">
                                <LottieAnimation
                                    :animation-data="time"
                                />
                            </div>
                            <div class="h-1/2 flex flex-col items-center justify-center">
                                <h3 class="text-xl font-semibold mb-2 text-center text-blue-800">Simple d'utilisation</h3>
                                <p class="text-gray-600 text-center">
                                    Mettez en place votre FAQ en quelques minutes sans connaissances techniques.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Ajoutez d'autres fonctionnalités de la même manière -->
                </div>
            </div>
        </section>

        <!-- Section Comment ça marche -->
        <section class="bg-white py-20" id="how-it-works">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-blue-800">Comment ça marche</h2>
                <p class="mt-4 text-center text-gray-600">
                    Suivez ces étapes simples pour commencer à utiliser FAiQ et améliorer votre support client.
                </p>
                <div class="flex flex-wrap mt-12">
                    <!-- Étape 1 -->
                    <div class="w-full md:w-1/5 p-4">
                        <div class="flex flex-col items-center">
                            <!-- Animation Lottie -->
                            <div class="w-48 h-48 mb-4">
                                <LottieAnimation
                                    :animationData="register"
                                    :loop="true"
                                    :autoplay="true"
                                />
                            </div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2 text-center">1. Inscrivez-vous.</h3>
                            <p class="text-gray-600 text-center">
                                Inscrivez-vous sur FAiQ en quelques clics pour accéder à toutes les fonctionnalités.
                            </p>
                        </div>
                    </div>
                    <!-- Étape 2 -->
                    <div class="w-full md:w-1/5 p-4">
                        <div class="flex flex-col items-center">
                            <!-- Animation Lottie -->
                            <div class="w-48 h-48 mb-4">
                                <LottieAnimation
                                    :animationData="engineering"
                                    :loop="true"
                                    :autoplay="true"
                                />
                            </div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2 text-center">2. Nous mettons en place la solution la plus adaptée pour vous.</h3>
                            <p class="text-gray-600 text-center">
                                Nous vous contactons rapidement pour paramétrer votre interface selon vos besoins.
                            </p>
                        </div>
                    </div>
                    <!-- Étape 3 -->
                    <div class="w-full md:w-1/5 p-4">
                        <div class="flex flex-col items-center">
                            <!-- Animation Lottie -->
                            <div class="w-48 h-48 mb-4">
                                <LottieAnimation
                                    :animationData="upload"
                                    :loop="true"
                                    :autoplay="true"
                                />
                            </div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2 text-center">3. Personnalisez votre FAQ</h3>
                            <p class="text-gray-600 text-center">
                                Ajoutez des fichiers à votre base de connaissances pour alimenter votre FAQ.
                            </p>
                        </div>
                    </div>
                    <!-- Étape 4 -->
                    <div class="w-full md:w-1/5 p-4">
                        <div class="flex flex-col items-center">
                            <!-- Animation Lottie -->
                            <div class="w-48 h-48 mb-4">
                                <LottieAnimation
                                    :animationData="configuration"
                                    :loop="true"
                                    :autoplay="true"
                                />
                            </div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2 text-center">4. Choisissez à quoi ressemblera votre FAQ.</h3>
                            <p class="text-gray-600 text-center">
                                Utilisez notre interface pour personnaliser votre FAQ selon vos codes de couleur et images.
                            </p>
                        </div>
                    </div>
                    <!-- Étape 5 -->
                    <div class="w-full md:w-1/5 p-4">
                        <div class="flex flex-col items-center">
                            <!-- Icône SVG -->
                            <div class="w-48 h-48 mb-4">
                                <!-- Vous pouvez remplacer ce SVG par une animation Lottie si vous le souhaitez -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <polygon style="fill:#0BA4E0;" points="202.624,478.016 0,291.36 70.512,214.8 191.968,326.656 431.44,33.984 512,99.904 "/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-blue-800 mb-2 text-center">5. Ça fonctionne !</h3>
                            <p class="text-gray-600 text-center">
                                Mettez à disposition le lien de votre FAQ à vos clients ou prospects.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Bouton d'appel à l'action -->
                <div class="mt-8 text-center">
                    <button @click="$inertia.visit(route('register'))"
                            class="mx-auto md:mx-0 mt-6 px-8 py-3 text-white rounded-full bg-blue-700 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 animate-bounce-in">
                        Démarrez dès maintenant
                    </button>
                </div>
            </div>
        </section>

        <!-- Section Avantages -->
        <section class="bg-blue-50 py-20" id="advantages">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center text-blue-700 mb-12">Les avantages de <span class="text-blue-400">FAiQ</span> pour votre entreprise</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    <!-- Avantage 1 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <!-- Icône de satisfaction client -->
                                <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Amélioration de la satisfaction client</h3>
                        <p class="text-gray-600">
                            Offrez des réponses rapides et précises grâce à l'IA pour une expérience client exceptionnelle.
                        </p>
                    </div>
                    <!-- Avantage 2 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3.99085 14.6599L2.47086 13.1399C1.85086 12.5199 1.85086 11.4999 2.47086 10.8799L3.99085 9.3599C4.25085 9.0999 4.46085 8.58989 4.46085 8.22989V6.07987C4.46085 5.19987 5.18086 4.47989 6.06086 4.47989H8.21085C8.57085 4.47989 9.08085 4.26992 9.34085 4.00992L10.8608 2.4899C11.4808 1.8699 12.5009 1.8699 13.1209 2.4899L14.6409 4.00992C14.9009 4.26992 15.4108 4.47989 15.7708 4.47989H17.9209C18.8009 4.47989 19.5208 5.19987 19.5208 6.07987V8.22989C19.5208 8.58989 19.7308 9.0999 19.9908 9.3599L21.5109 10.8799C22.1309 11.4999 22.1309 12.5199 21.5109 13.1399L19.9908 14.6599C19.7308 14.9199 19.5208 15.43 19.5208 15.79V17.9399C19.5208 18.8199 18.8009 19.54 17.9209 19.54H15.7708C15.4108 19.54 14.9009 19.7499 14.6409 20.0099L13.1209 21.5299C12.5009 22.1499 11.4808 22.1499 10.8608 21.5299L9.34085 20.0099C9.08085 19.7499 8.57085 19.54 8.21085 19.54H6.06086C5.18086 19.54 4.46085 18.8199 4.46085 17.9399V15.79C4.46085 15.42 4.25085 14.9099 3.99085 14.6599Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path opacity="0.4" d="M9 15L15 9" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path opacity="0.4" d="M14.4945 14.5H14.5035" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path opacity="0.4" d="M9.49451 9.5H9.50349" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Réduction des coûts opérationnels</h3>
                        <p class="text-gray-600">
                            Diminuez les dépenses en automatisant les réponses aux questions fréquentes.
                        </p>
                    </div>
                    <!-- Avantage 3 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 6V12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M16.24 16.24L12 12" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Gain de temps pour votre équipe</h3>
                        <p class="text-gray-600">
                            Libérez du temps pour votre équipe support en répondant mieux aux questions fréquentes.
                        </p>
                    </div>
                    <!-- Avantage 4 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 49.442 49.442" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M25.323,2.811c6.39,0,12.095,2.969,15.824,7.592l-2.448,1.73l7.704,2.63l-0.801-7.505l-2.157,1.523 C39.202,3.437,32.66,0,25.323,0c-3.613,0-7.035,0.834-10.085,2.316l0.898,2.696C18.898,3.608,22.018,2.811,25.323,2.811z"></path> <path d="M45.499,28.365c-0.707,3.018-2.108,5.917-4.238,8.441c-4.121,4.886-10.067,7.332-16.007,7.203l0.258-2.988l-6.978,4.193 l6.252,4.228l0.228-2.632c6.818,0.201,13.666-2.584,18.396-8.19c2.33-2.763,3.897-5.915,4.731-9.203L45.499,28.365z"></path> <path d="M5.632,31.42c-2.427-5.91-1.848-12.315,1.014-17.522l2.529,1.61L8.682,7.381l-6.638,3.59l2.229,1.418 c-3.331,5.953-4.027,13.313-1.24,20.099c1.371,3.345,3.442,6.191,5.972,8.451l2.153-1.856C8.809,37.062,6.886,34.477,5.632,31.42z "></path> <path d="M25.325,38.596l0.363-0.031c0.018,0,0.033,0.002,0.05,0.002c4.957,0,9.357-2.415,12.091-6.129 c0.478-0.649,0.901-1.34,1.271-2.062c0.829-1.619,1.374-3.408,1.563-5.302c0.05-0.491,0.075-0.989,0.075-1.493 c0-0.076-0.005-0.152-0.006-0.229c-0.021-1.376-0.229-2.708-0.6-3.972c-0.137-0.467-0.295-0.923-0.475-1.37 c-0.365-0.906-0.815-1.77-1.344-2.579c-0.239-0.367-0.494-0.723-0.764-1.066c-0.739-0.943-1.588-1.795-2.527-2.538 c-0.322-0.254-0.653-0.497-0.995-0.725c-2.377-1.582-5.229-2.506-8.292-2.506c-2.712,0-5.255,0.727-7.453,1.989 c-0.361,0.208-0.713,0.429-1.055,0.664c-0.965,0.667-1.85,1.443-2.634,2.313c-0.303,0.335-0.591,0.684-0.862,1.045 c-0.752,1.001-1.379,2.099-1.863,3.27c-0.186,0.448-0.349,0.906-0.49,1.375c-0.382,1.257-0.598,2.583-0.632,3.954 c-0.003,0.126-0.01,0.25-0.01,0.376c0,0.458,0.024,0.91,0.064,1.356c0.151,1.672,0.578,3.262,1.233,4.729 c0.341,0.763,0.741,1.491,1.199,2.181c2.616,3.937,7.044,6.565,12.085,6.705C25.324,38.575,25.325,38.592,25.325,38.596z M26.088,30.008c1.619-0.018,3.183-0.181,4.681-0.459c-0.354,1.498-0.812,2.848-1.271,3.99c-1.059,0.126-2.146,0.199-3.255,0.204 C26.187,32.624,26.131,31.36,26.088,30.008z M27.729,37.176c-0.417,0.062-0.84,0.103-1.268,0.125 c-0.042-0.593-0.095-1.385-0.149-2.328c0.894-0.006,1.771-0.053,2.632-0.137C28.414,35.99,27.95,36.805,27.729,37.176z M29.922,36.678c-0.194,0.062-0.392,0.114-0.589,0.168c0.301-0.562,0.668-1.301,1.048-2.185c0.555-0.082,1.104-0.173,1.641-0.284 C31.083,35.545,30.267,36.352,29.922,36.678z M30.91,33.334c0.445-1.193,0.876-2.561,1.194-4.072 c1.218-0.295,2.373-0.672,3.463-1.123c-0.607,1.783-1.506,3.385-2.415,4.715C32.427,33.045,31.676,33.202,30.91,33.334z M32.849,35.336c0.354-0.431,0.728-0.912,1.103-1.439c0.451-0.129,0.897-0.266,1.333-0.414 C34.548,34.189,33.727,34.805,32.849,35.336z M36.993,31.492c-0.638,0.291-1.308,0.553-1.994,0.791 c0.84-1.413,1.599-3.045,2.067-4.836c0.773-0.395,1.517-0.816,2.194-1.286C38.889,28.117,38.101,29.924,36.993,31.492z M39.509,23.58c0,0.31-0.014,0.615-0.034,0.92c-0.636,0.51-1.335,0.982-2.088,1.415c0.118-0.767,0.182-1.552,0.168-2.359 c-0.01-0.589-0.063-1.166-0.148-1.733c0.63-0.431,1.213-0.893,1.735-1.387C39.379,21.447,39.509,22.498,39.509,23.58z M38.759,19.112c-0.474,0.502-1.015,0.973-1.607,1.411c-0.248-1.008-0.597-1.973-1.009-2.886c0.495-0.376,0.952-0.778,1.354-1.207 C38.01,17.269,38.434,18.168,38.759,19.112z M34.281,12.803c0.946,0.751,1.795,1.621,2.516,2.591 c-0.35,0.4-0.753,0.777-1.198,1.132c-0.603-1.139-1.286-2.178-1.973-3.093C33.86,13.232,34.08,13.023,34.281,12.803z M34.571,17.246c-0.792,0.492-1.682,0.915-2.648,1.254c-0.303-1.226-0.674-2.385-1.072-3.444c0.641-0.247,1.229-0.548,1.767-0.888 C33.297,15.078,33.979,16.116,34.571,17.246z M33.295,12.089c-0.128,0.136-0.275,0.261-0.42,0.388 c-0.374-0.455-0.737-0.869-1.079-1.238C32.313,11.493,32.817,11.774,33.295,12.089z M28.993,10.22 c0.328,0.284,1.531,1.365,2.871,2.993c-0.445,0.271-0.935,0.511-1.461,0.712c-0.668-1.604-1.353-2.915-1.856-3.805 C28.697,10.152,28.845,10.184,28.993,10.22z M26.515,9.854c0.155,0.009,0.311,0.019,0.463,0.032 c0.414,0.676,1.334,2.27,2.231,4.407c-0.911,0.229-1.896,0.359-2.93,0.359c-0.002,0-0.003,0-0.003,0 C26.37,12.417,26.464,10.715,26.515,9.854z M26.225,15.881c0.019,0,0.037,0.002,0.055,0.002c1.194,0,2.334-0.162,3.386-0.445 c0.397,1.053,0.771,2.207,1.07,3.424c-1.381,0.362-2.882,0.566-4.456,0.566c-0.058,0-0.115-0.004-0.174-0.005 C26.142,18.163,26.183,16.973,26.225,15.881z M25.909,20.582c4.751,0.012,8.312-1.703,9.188-2.236 c0.406,0.923,0.896,2.676,0.951,2.908c0.055,0.232,0.215,0.818,0.206,1.281s-0.041,3.123-0.266,4.107 c-1.011,0.473-5.35,2.131-9.936,2.139c-4.587,0.008-9.145-1.561-10.251-2.066c-0.235-1.006-0.367-4.123-0.094-5.375 c0.117-0.406,0.65-2.322,1.149-3.386C17.672,18.515,20.722,20.219,25.909,20.582z M25.011,33.715 c-0.961-0.035-1.903-0.117-2.822-0.246c-0.445-1.119-0.889-2.434-1.234-3.888c1.255,0.229,2.561,0.372,3.902,0.418 C24.9,31.342,24.954,32.6,25.011,33.715z M20.77,33.236c-0.772-0.148-1.524-0.328-2.254-0.538 c-0.858-1.276-1.695-2.792-2.276-4.474c1.064,0.431,2.193,0.789,3.378,1.072C19.927,30.754,20.339,32.075,20.77,33.236z M17.414,16.864c0.619-1.125,1.32-2.148,2.011-3.039c0.47,0.345,0.993,0.652,1.56,0.921c-0.409,1.06-0.798,2.223-1.117,3.46 C18.962,17.83,18.139,17.378,17.414,16.864z M21.037,18.636c0.313-1.222,0.696-2.374,1.102-3.424 c0.885,0.302,1.845,0.512,2.857,0.608c-0.043,1.095-0.084,2.286-0.12,3.548C23.516,19.264,22.223,19.014,21.037,18.636z M25.283,9.84c-0.052,0.882-0.144,2.563-0.236,4.751c-0.869-0.086-1.687-0.268-2.444-0.517c0.877-2.051,1.76-3.572,2.152-4.213 C24.929,9.849,25.106,9.846,25.283,9.84z M22.862,10.132c0.112-0.024,0.226-0.046,0.339-0.067 c-0.475,0.833-1.116,2.051-1.755,3.546c-0.453-0.217-0.882-0.454-1.26-0.726c1.44-1.694,2.656-2.716,2.686-2.74L22.862,10.132z M20.209,10.993c-0.301,0.317-0.622,0.673-0.955,1.063c-0.124-0.132-0.255-0.263-0.361-0.402 C19.317,11.409,19.758,11.193,20.209,10.993z M17.865,12.306c0.183,0.244,0.388,0.479,0.61,0.703 c-0.699,0.896-1.41,1.925-2.048,3.06c-0.461-0.426-0.866-0.879-1.197-1.361C16,13.798,16.886,12.991,17.865,12.306z M14.453,15.715c0.397,0.52,0.867,1.008,1.396,1.464c-0.521,1.075-0.958,2.228-1.247,3.441c-0.691-0.497-1.312-1.038-1.845-1.619 C13.175,17.824,13.748,16.721,14.453,15.715z M11.995,24.371c-0.016-0.262-0.025-0.525-0.025-0.791 c0-1.122,0.139-2.213,0.394-3.258c0.593,0.573,1.262,1.105,1.993,1.593c-0.076,0.538-0.125,1.085-0.135,1.641 c-0.014,0.84,0.056,1.657,0.184,2.45C13.527,25.514,12.716,24.967,11.995,24.371z M14.122,30.95 c-0.938-1.472-1.604-3.129-1.927-4.907c0.779,0.553,1.632,1.057,2.542,1.508c0.448,1.67,1.147,3.201,1.926,4.541 C15.771,31.754,14.919,31.373,14.122,30.95z M15.764,33.047c0.627,0.254,1.28,0.479,1.947,0.686 c0.519,0.742,1.038,1.4,1.511,1.957C17.934,34.992,16.766,34.1,15.764,33.047z M22.007,36.818 c-0.162-0.146-1.171-1.074-2.352-2.562c0.538,0.123,1.088,0.229,1.645,0.321c0.413,0.97,0.812,1.765,1.129,2.353 C22.288,36.895,22.147,36.858,22.007,36.818z M24.017,37.225c-0.213-0.353-0.708-1.211-1.27-2.444 c0.765,0.088,1.541,0.146,2.331,0.174c0.055,0.954,0.108,1.761,0.151,2.369C24.821,37.309,24.416,37.273,24.017,37.225z"></path> <path d="M23.17,25.646h-2.504v-0.008c0.261-0.158,0.534-0.313,0.819-0.461c0.286-0.147,0.549-0.314,0.788-0.498 c0.238-0.182,0.434-0.396,0.587-0.64c0.154-0.243,0.231-0.544,0.231-0.898c0-0.291-0.055-0.548-0.164-0.768 c-0.106-0.22-0.262-0.401-0.46-0.544c-0.199-0.143-0.438-0.251-0.717-0.322c-0.277-0.071-0.584-0.108-0.917-0.108 c-0.372,0-0.707,0.055-1.007,0.159c-0.298,0.106-0.553,0.262-0.759,0.466c-0.206,0.205-0.36,0.454-0.462,0.751 c-0.101,0.298-0.14,0.638-0.117,1.019h1.494c-0.011-0.34,0.046-0.614,0.171-0.823c0.123-0.21,0.345-0.314,0.664-0.314 c0.186,0,0.343,0.048,0.472,0.148c0.131,0.097,0.195,0.273,0.195,0.527c0,0.101-0.025,0.191-0.075,0.271s-0.114,0.151-0.191,0.214 c-0.076,0.064-0.158,0.123-0.246,0.176s-0.166,0.102-0.235,0.145c-0.173,0.104-0.354,0.211-0.539,0.322 c-0.187,0.106-0.365,0.221-0.537,0.342c-0.173,0.117-0.334,0.246-0.486,0.385c-0.149,0.138-0.284,0.285-0.4,0.445 c-0.138,0.186-0.242,0.393-0.313,0.621c-0.071,0.227-0.1,0.477-0.083,0.746h4.792L23.17,25.646L23.17,25.646z"></path> <path d="M26.105,26.998h1.654v-1.097h0.747v-1.353h-0.747v-3.085h-1.583l-2.528,2.942V25.9h2.457V26.998z M24.753,24.549 l1.352-1.551v1.551H24.753z"></path> <path d="M30.724,24.819c0-0.164,0.021-0.298,0.062-0.399c0.04-0.104,0.089-0.187,0.149-0.242c0.062-0.061,0.129-0.1,0.203-0.121 c0.073-0.021,0.146-0.031,0.215-0.031c0.117,0,0.207,0.021,0.274,0.061c0.065,0.039,0.117,0.096,0.154,0.168 c0.038,0.07,0.062,0.156,0.07,0.258c0.012,0.101,0.018,0.209,0.018,0.326V27h1.574v-2.83c0-0.239-0.039-0.445-0.115-0.621 c-0.077-0.175-0.183-0.319-0.313-0.433c-0.133-0.114-0.284-0.2-0.454-0.259c-0.17-0.059-0.349-0.088-0.531-0.088 c-0.334,0-0.602,0.053-0.795,0.155c-0.196,0.104-0.361,0.234-0.493,0.394h-0.018v-1.995h-1.572V27h1.572V24.819z"></path> </g> </g> </g></svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Disponibilité 24/7</h3>
                        <p class="text-gray-600">
                            Assurez une assistance client à tout moment, même en dehors des heures de bureau.
                        </p>
                    </div>
                    <!-- Avantage 5 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor"  viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M644.7 669.2a7.92 7.92 0 0 0-6.5-3.3H594c-6.5 0-10.3 7.4-6.5 12.7l73.8 102.1c3.2 4.4 9.7 4.4 12.9 0l114.2-158c3.8-5.3 0-12.7-6.5-12.7h-44.3c-2.6 0-5 1.2-6.5 3.3l-63.5 87.8-22.9-31.9zM688 306v-48c0-4.4-3.6-8-8-8H296c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h384c4.4 0 8-3.6 8-8zm-392 88c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H296zm184 458H208V148h560v296c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V108c0-17.7-14.3-32-32-32H168c-17.7 0-32 14.3-32 32v784c0 17.7 14.3 32 32 32h312c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm402.6-320.8l-192-66.7c-.9-.3-1.7-.4-2.6-.4s-1.8.1-2.6.4l-192 66.7a7.96 7.96 0 0 0-5.4 7.5v251.1c0 2.5 1.1 4.8 3.1 6.3l192 150.2c1.4 1.1 3.2 1.7 4.9 1.7s3.5-.6 4.9-1.7l192-150.2c1.9-1.5 3.1-3.8 3.1-6.3V538.7c0-3.4-2.2-6.4-5.4-7.5zM826 763.7L688 871.6 550 763.7V577l138-48 138 48v186.7z"></path> </g></svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Sécurité et confidentialité</h3>
                        <p class="text-gray-600">
                            Vos données sont chiffrées et protégées en toute securité.
                        </p>
                    </div>
                    <!-- Avantage 6 -->
                    <div class="flex flex-col items-center text-center p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <!-- Icône SVG -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="-0.5 0 25 25"  xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M18.8887 10.25C18.5395 10.2462 18.1974 10.151 17.8964 9.97387C17.5954 9.79677 17.3461 9.54393 17.1731 9.24053C17.0002 8.93714 16.9097 8.59372 16.9107 8.2445C16.9117 7.89528 17.0041 7.55241 17.1787 7.24999L17.2987 7.03997C17.4297 6.81781 17.5133 6.57097 17.5443 6.31493C17.5753 6.0589 17.553 5.79924 17.4788 5.55224C17.4046 5.30524 17.2801 5.07626 17.1132 4.87969C16.9462 4.68312 16.7404 4.52317 16.5087 4.40996V4.40996C16.0227 4.17652 15.467 4.13332 14.9507 4.28875C14.4345 4.44418 13.995 4.78704 13.7187 5.24999V5.24999C13.5404 5.54389 13.2894 5.78686 12.9899 5.9555C12.6903 6.12413 12.3524 6.21276 12.0087 6.21276C11.665 6.21276 11.327 6.12413 11.0275 5.9555C10.728 5.78686 10.4769 5.54389 10.2987 5.24999C10.0224 4.78704 9.58291 4.44418 9.06665 4.28875C8.5504 4.13332 7.99469 4.17652 7.5087 4.40996V4.40996C7.27697 4.52317 7.07116 4.68312 6.90421 4.87969C6.73726 5.07626 6.61277 5.30524 6.53858 5.55224C6.46438 5.79924 6.44209 6.0589 6.47309 6.31493C6.50408 6.57097 6.5877 6.81781 6.71869 7.03997L6.83869 7.24999C7.01332 7.55241 7.10571 7.89528 7.10669 8.2445C7.10767 8.59372 7.01721 8.93714 6.84427 9.24053C6.67134 9.54393 6.42196 9.79677 6.12097 9.97387C5.81999 10.151 5.4779 10.2462 5.12869 10.25C4.64217 10.238 4.16698 10.3979 3.78659 10.7015C3.40621 11.005 3.14493 11.4329 3.04868 11.91C2.99868 12.1996 3.01314 12.4967 3.09101 12.7801C3.16887 13.0635 3.30826 13.3263 3.49921 13.5497C3.69016 13.7731 3.92799 13.9516 4.1958 14.0727C4.46362 14.1937 4.75481 14.2543 5.04868 14.25H5.1687C5.5179 14.2538 5.86 14.349 6.16098 14.5261C6.46196 14.7032 6.71131 14.9561 6.88425 15.2595C7.05718 15.5628 7.14768 15.9063 7.1467 16.2555C7.14572 16.6047 7.05333 16.9476 6.87869 17.25L6.82868 17.33C6.56685 17.7935 6.4956 18.3407 6.62998 18.8558C6.76435 19.3709 7.0938 19.8135 7.54868 20.09V20.09C8.00218 20.351 8.53992 20.4239 9.04654 20.293C9.55316 20.1622 9.98834 19.838 10.2587 19.39L10.2787 19.25C10.457 18.9561 10.708 18.7131 11.0075 18.5445C11.307 18.3759 11.6449 18.2872 11.9887 18.2872C12.3324 18.2872 12.6704 18.3759 12.9699 18.5445C13.2694 18.7131 13.5204 18.9561 13.6987 19.25L13.7687 19.39C14.0391 19.8407 14.4761 20.1668 14.9851 20.2978C15.4942 20.4288 16.0343 20.3542 16.4887 20.09C16.9367 19.8197 17.2609 19.3845 17.3917 18.8779C17.5226 18.3712 17.4497 17.8335 17.1887 17.38L17.1287 17.27C16.9541 16.9676 16.8617 16.6247 16.8607 16.2754C16.8597 15.9262 16.9502 15.5829 17.1231 15.2795C17.296 14.9761 17.5454 14.7232 17.8464 14.5461C18.1474 14.369 18.4895 14.2738 18.8387 14.27H18.9587C19.2525 14.2743 19.5438 14.2138 19.8116 14.0927C20.0794 13.9717 20.3172 13.793 20.5082 13.5696C20.6991 13.3462 20.8385 13.0835 20.9164 12.8001C20.9942 12.5167 21.0087 12.2196 20.9587 11.93C20.8669 11.451 20.6088 11.0198 20.2301 10.7124C19.8514 10.405 19.3763 10.2413 18.8887 10.25V10.25Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 14.79C13.3807 14.79 14.5 13.6707 14.5 12.29C14.5 10.9093 13.3807 9.78998 12 9.78998C10.6193 9.78998 9.5 10.9093 9.5 12.29C9.5 13.6707 10.6193 14.79 12 14.79Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800">Gestion rapide et facile</h3>
                        <p class="text-gray-600">
                            Parametrez facilement et rapidement comment votre FAQ repondra aux questions de vos clients.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Essayez FAiQ dès maintenant -->
        <section class="bg-white py-20" id="try">
            <div class="flex">
                <!-- Contenu Texte -->
                <div class="w-full flex flex-col items-center">
                    <h2 class="text-4xl font-bold text-blue-600 mb-6 text-center">Essayez <span class="text-blue-300">FAiQ</span> dès maintenant !</h2>
                    <p class="text-blue-600 text-lg mb-6 md:w-1/2 text-center">
                        Découvrez par vous-même la puissance de notre FAQ intelligente. Accédez à notre version de test et explorez toutes les fonctionnalités que FAiQ a à offrir.
                    </p>
                    <button @click="$inertia.visit(route('public.ask.index', { team: 'faiq' }))"
                            class="mx-auto md:mx-0 mt-6 px-8 py-3 text-white rounded-full bg-blue-700 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 animate-bounce-in">
                        Accéder à la version de test
                    </button>
                </div>
            </div>
        </section>

        <!-- Section CTA Finale -->
        <section class="bg-gradient-to-b from-blue-500 to-blue-600 py-20 overflow-hidden">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold text-white animate-fade-in-down">Prêt à transformer votre support client avec FAiQ ?</h2>
                <button @click="$inertia.visit(route('register'))" class="mt-4 px-8 py-3 bg-blue-200 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50">
                    Inscrivez-vous dès maintenant
                </button>
            </div>
        </section>
    </LandingLayout>
</template>

<style scoped>
/* Vos styles existants restent inchangés */

/* Styles pour le header */
header {
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.8);
}

header nav a {
    font-size: 16px;
    font-weight: 500;
}

/* Styles pour le menu mobile (à implémenter si nécessaire) */

/* Espace pour compenser le header fixe */
.pt-20 {
    padding-top: 80px; /* Ajustez cette valeur si nécessaire */
}
</style>
