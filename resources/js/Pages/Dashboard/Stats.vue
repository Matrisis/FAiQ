<template>
    <div>
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-200">Statistiques du tableau de bord</h1>

        <!-- Filtres de date -->
        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 mb-6">
            <div class="flex flex-col mb-4 md:mb-0">
                <label for="startDate" class="mb-1 text-gray-600 dark:text-gray-400">Date de début</label>
                <input type="date" v-model="startDate" id="startDate" class="input" />
            </div>
            <div class="flex flex-col mb-4 md:mb-0">
                <label for="endDate" class="mb-1 text-gray-600 dark:text-gray-400">Date de fin</label>
                <input type="date" v-model="endDate" id="endDate" class="input" />
            </div>
            <button @click="fetchData" class="mt-2 md:mt-6 btn">
                Filtrer
            </button>
        </div>

        <!-- État de chargement -->
        <div v-if="loading" class="text-center py-10">
            <span class="text-gray-500 dark:text-gray-400">Chargement...</span>
        </div>

        <!-- État d'erreur -->
        <div v-if="error" class="text-center py-10 text-red-500">
            <span>{{ error }}</span>
        </div>

        <!-- Contenu des statistiques -->
        <div v-if="!loading && !error">
            <!-- Cartes des statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-6">
                <!-- Nombre total de réponses -->
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre total de réponses disponibles</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.totalAnswers }}</p>
                </div>
                <div class="card">
                    <h2 class="text-lg font-semiboll d text-gray-700 dark:text-gray-300 mb-2">Nombre de nouvelles réponses</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.newAnswers }}</p>
                </div>
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre d'anciennes réponses</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.oldAnswers }}</p>
                </div>

                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Prix des nouvelles réponses</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.newAnswersPrice }} €</p>
                </div>
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Prix des d'anciennes réponses</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.oldAnswersPrice }} €</p>
                </div>

            </div>
            <div class="mt-6">
                <!-- Top des réponses les mieux notées -->
                <div class="card col-span-1 md:col-span-2 xl:col-span-4">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Top des réponses les mieux notées</h2>
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Question
                            </th>
                            <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Votes
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="answer in stats.topVotedAnswers" :key="answer.id" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                                {{ answer.question }}
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                                {{ answer.votes }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    team: Object,
});

const stats = ref({});
const startDate = ref('');
const endDate = ref('');
const loading = ref(true);
const error = ref(null);

// Fonction pour récupérer les données de l'API
const fetchData = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get(route('admin.stats.index', { team: props.team }), {
            params: {
                start_date: startDate.value,
                end_date: endDate.value,
            },
        });
        stats.value = response.data;
        // Aucune création de graphique nécessaire
    } catch (err) {
        console.error('Erreur lors de la récupération des statistiques :', err);
        error.value = 'Impossible de charger les statistiques. Veuillez réessayer plus tard.';
    } finally {
        loading.value = false;
    }
};

// Récupérer les données au montage du composant
onMounted(fetchData);

// Récupérer les données à nouveau lorsque les filtres changent
watch([startDate, endDate], () => {
    fetchData();
});
</script>

<style scoped>
.card {
    @apply p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-300;
}

.input {
    @apply border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200;
}

.input:focus {
    @apply outline-none ring-2 ring-blue-500;
}

.btn {
    @apply px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-300;
}
</style>
