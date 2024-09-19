<script setup>

import MarkdownRenderer from "@/Components/Markdown.vue";
import bodymovin from "lottie-web";
import {onMounted} from "vue";

const props = defineProps({
    answer: String,
    question : String,
    asking: Boolean
})

onMounted(() => {
    loading_animation()
})

const loading_animation = () => {
    bodymovin.loadAnimation({
        container: document.getElementById('loading-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/storage/lotties/loading.json',
    })
}
</script>

<template>
    <fieldset class="flex border custom-border rounded h-64 lg:h-full overflow-y-scroll">
        <legend class="px-2 flex text-xl" v-if="question">{{ question }} : </legend>
        <legend class="px-2 flex text-xl" v-else>Reponse rapide : </legend>
        <div class="p-4 flex w-full">
            <div v-show="asking" class="flex justify-center mx-auto">
                <div id="loading-animation"></div>
            </div>
            <div v-if="answer" class="">
                <MarkdownRenderer :source="answer"/>
            </div>
        </div>
    </fieldset>
</template>

<style scoped>

.custom-border {
    border-color: v-bind('props.team.parameters.text_color') !important;
}

</style>
