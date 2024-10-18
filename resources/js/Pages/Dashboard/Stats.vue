<template>
    <div>
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-200">Dashboard Statistics</h1>

        <!-- Date Filters -->
        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 mb-6">
            <div class="flex flex-col mb-4 md:mb-0">
                <label for="startDate" class="mb-1 text-gray-600 dark:text-gray-400">Start Date</label>
                <input type="date" v-model="startDate" id="startDate" class="input" />
            </div>
            <div class="flex flex-col mb-4 md:mb-0">
                <label for="endDate" class="mb-1 text-gray-600 dark:text-gray-400">End Date</label>
                <input type="date" v-model="endDate" id="endDate" class="input" />
            </div>
            <button @click="fetchData" class="mt-2 md:mt-6 btn">
                Filter
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-10">
            <span class="text-gray-500 dark:text-gray-400">Loading...</span>
        </div>

        <!-- Error State -->
        <div v-if="error" class="text-center py-10 text-red-500">
            <span>{{ error }}</span>
        </div>

        <!-- Statistics Content -->
        <div v-if="!loading && !error">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Total Answers -->
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Answers</h2>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ stats.totalAnswers }}</p>
                </div>

                <!-- Answers by Type -->
                <!--
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Answers by Type</h2>
                    <canvas ref="answersByTypeChart"></canvas>
                </div>
                -->

                <!-- Answers by Channel -->
                <!--
                <div class="card col-span-1 md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Answers by Channel</h2>
                    <canvas ref="answersByChannelChart"></canvas>
                </div>
                -->

                <!-- Top Voted Answers -->
                <div class="card col-span-1 md:col-span-2 xl:col-span-4">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Top Voted Answers</h2>
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
import Chart from 'chart.js/auto';
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

// Refs for canvas elements
const answersByTypeChartRef = ref(null);
const answersByChannelChartRef = ref(null);

// Chart instances
let answersByTypeChartInstance = null;
let answersByChannelChartInstance = null;

// Function to fetch data from API
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
        createCharts(stats.value);
    } catch (err) {
        console.error('Error fetching stats:', err);
        error.value = 'Failed to load statistics. Please try again later.';
    } finally {
        loading.value = false;
    }
};

// Function to create charts
const createCharts = (data) => {
    // Destroy existing charts to prevent duplication
    if (answersByTypeChartInstance) answersByTypeChartInstance.destroy();
    if (answersByChannelChartInstance) answersByChannelChartInstance.destroy();

    // Answers by Type Chart
    if (answersByTypeChartRef.value) {
        const typeCtx = answersByTypeChartRef.value.getContext('2d');
        answersByTypeChartInstance = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: data.answersByType.map((item) => item.type),
                datasets: [
                    {
                        data: data.answersByType.map((item) => item.count),
                        backgroundColor: ['#4CAF50', '#FFC107'],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#4B5563', // Text color
                        },
                    },
                },
            },
        });
    }

    // Answers by Channel Chart
    if (answersByChannelChartRef.value) {
        const channelCtx = answersByChannelChartRef.value.getContext('2d');
        answersByChannelChartInstance = new Chart(channelCtx, {
            type: 'bar',
            data: {
                labels: data.answersByChannel.map((item) => item.channel),
                datasets: [
                    {
                        label: 'Answers per Channel',
                        data: data.answersByChannel.map((item) => item.count),
                        backgroundColor: '#3B82F6',
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            color: '#4B5563', // Text color
                        },
                    },
                    y: {
                        ticks: {
                            color: '#4B5563', // Text color
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#4B5563', // Text color
                        },
                    },
                },
            },
        });
    }
};

// Fetch data on component mount
onMounted(fetchData);

// Re-fetch data when date filters change
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
