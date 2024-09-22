<script setup>

import Ask from "@/Pages/Ask.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AskLayout from "@/Layouts/AskLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import Editor from "@/Pages/Parameters/DisplayEditor.vue";
import {computed, ref, watch} from "vue";
import DisplayEditor from "@/Pages/Parameters/DisplayEditor.vue";
import SettingsEditor from "@/Pages/Parameters/SettingsEditor.vue";

const props = defineProps({
    load: String,
    channel: String,
    team: Object,

    instant_answers: Object,
})

const onParametersUpdated = (parameters) => {
    props.team.parameters = parameters
}

const type = ref('display')

</script>

<template>
    <AppLayout title="Parameteres">
        <template #header class="w-full">
            <div class="flex flex-row justify-around w-full pb-4 shadow-xl">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <div :class="type === 'display' ? 'underline underline-offset-4 decoration-blue-500 cursor-pointer' : 'cursor-pointer'" @click="type = 'display'">Paramètres d'affichage</div>
                </h2>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <div :class="type === 'config' ? 'underline underline-offset-4 decoration-blue-500 cursor-pointer' : 'cursor-pointer'" @click="type = 'config'">Configuration</div>
                </h2>
            </div>
        </template>

        <div v-if="type === 'display'" class="h-full w-full flex flex-col lg:flex-row">
            <div class="w-full lg:w-4/5">
                <Ask :channel="props.channel" :team="team" :instant_answers="props.instant_answers" :load="props.load" />
            </div>
            <div class="w-full lg:w-1/5">
                <DisplayEditor :parameters="props.team.parameters" @parameters-updated="onParametersUpdated" />
            </div>
        </div>
        <div v-else class=" h-full w-full flex flex-col lg:flex-row">
            <SettingsEditor :parameters="props.team.parameters" @parameters-updated="onParametersUpdated" />
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
