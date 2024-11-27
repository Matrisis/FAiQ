<script setup>


import {ref} from "vue";
const isMenuOpen = ref(false); // État pour le menu mobile

const scrollTo = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};

</script>

<template>
    <div id="main-content">
        <!-- Header -->
        <header class="bg-white shadow-md fixed w-full z-50">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <!-- Logo -->
                <div class="text-2xl font-bold text-blue-700 cursor-pointer" @click="scrollTo('hero')">
                    FAiQ
                </div>
                <!-- Navigation pour écrans larges -->
                <nav class="hidden md:flex space-x-6">
                    <a href="#" @click.prevent="scrollTo('discover')" class="text-gray-700 hover:text-blue-700">Qu'est-ce que c'est ?</a>
                    <a href="#" @click.prevent="scrollTo('features')" class="text-gray-700 hover:text-blue-700">Fonctionnalités</a>
                    <a href="#" @click.prevent="scrollTo('how-it-works')" class="text-gray-700 hover:text-blue-700">Comment ça marche</a>
                    <a href="#" @click.prevent="scrollTo('advantages')" class="text-gray-700 hover:text-blue-700">Avantages</a>
                    <a href="#" @click.prevent="scrollTo('try')" class="text-gray-700 hover:text-blue-700">Essayer</a>
                </nav>
                <!-- Bouton Inscription pour écrans larges -->
                <div class="hidden md:block">
                    <button @click="$inertia.visit(route('register'))"
                            class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        S'inscrire
                    </button>
                </div>
                <!-- Menu Mobile -->
                <div class="md:hidden">
                    <button @click="isMenuOpen = !isMenuOpen" class="text-gray-700 focus:outline-none focus:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Menu déroulant pour mobiles -->
            <div v-if="isMenuOpen" class="md:hidden bg-white shadow-md">
                <nav class="px-6 pt-4 pb-4 space-y-6" >
                    <a href="#" @click.prevent="scrollTo('discover'); isMenuOpen = false" class="block text-gray-700 hover:text-blue-700">Qu'est-ce que c'est ?</a>
                    <a href="#" @click.prevent="scrollTo('features'); isMenuOpen = false" class="block text-gray-700 hover:text-blue-700">Fonctionnalités</a>
                    <a href="#" @click.prevent="scrollTo('how-it-works'); isMenuOpen = false" class="block text-gray-700 hover:text-blue-700">Comment ça marche</a>
                    <a href="#" @click.prevent="scrollTo('advantages'); isMenuOpen = false" class="block text-gray-700 hover:text-blue-700">Avantages</a>
                    <a href="#" @click.prevent="scrollTo('try'); isMenuOpen = false" class="block text-gray-700 hover:text-blue-700">Essayer</a>
                    <button @click="$inertia.visit(route('register')); isMenuOpen = false"
                            class="w-full mt-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        S'inscrire
                    </button>
                </nav>
            </div>
        </header>

        <slot />

        <footer class="bg-gray-900 py-8">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Logo et Copyright -->
                    <div class="text-gray-400 mb-4 md:mb-0">
                        <span class="text-xl font-bold text-white">FAiQ</span> &copy; 2023. Tous droits réservés.
                    </div>
                    <!-- Liens utiles -->
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">Contact</a>
                        <a href="#" class="text-gray-400 hover:text-white">Mentions légales</a>
                        <a href="#" class="text-gray-400 hover:text-white">Politique de confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white">Conditions d'utilisation</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

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

