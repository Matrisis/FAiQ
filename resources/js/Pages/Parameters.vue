<script setup>

import Ask from "@/Pages/Ask.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AskLayout from "@/Layouts/AskLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import Editor from "@/Pages/Parameters/Editor.vue";
import {computed, ref, watch} from "vue";

const props = defineProps({
    load: String,
    channel: String,
    team: Object,

    instant_answers: Object,
})

const onParametersUpdated = (parameters) => {
    props.team.parameters = parameters
}

</script>

<template>
    <AppLayout title="Parameteres">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Paramètres
            </h2>
        </template>

        <div class="border border-gray-500 h-full w-full flex flex-col lg:flex-row">
            <div class="w-full lg:w-4/5">
                <Ask :channel="props.channel" :team="team" :instant_answers="props.instant_answers" :load="props.load" />
            </div>
            <div class="w-full lg:w-1/5">
                <Editor :parameters="props.team.parameters" @parameters-updated="onParametersUpdated" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
