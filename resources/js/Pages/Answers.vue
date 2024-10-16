<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AnswersList from "@/Pages/Answer/AnswersList.vue";

const props = defineProps({
    team: Object
})

</script>

<template>

    <AppLayout title="Question / Réponses">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <AnswersList :team="team" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
