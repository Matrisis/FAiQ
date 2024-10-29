<script setup>
import {computed, onMounted, ref, watch} from "vue";

const props = defineProps({
    question: String,
    answer: String,
    team: Object,
})
const parameters = computed(() => props.team.parameters)

const emit = defineEmits([
    "followupQuestion"
])

const followupQuestion = (fuq) => {
    emit("followupQuestion", {fuq})
}
const followupQuestions = ref([])

const getFollowupQuestions = (question) => {
    axios.get(route('admin.questions.get', {team: props.team.id, question:question})).then((response) => {
        followupQuestions.value = response.data.response
    })
}

watch(() => props.question, (value) => {
    if(value)
        getFollowupQuestions(value)
})

</script>

<template>
    <div class="h-full">
        <h4 class="text-lg lg:text-2xl font-bold">Questions similaires :</h4>
        <div v-if="followupQuestions.length === 0">Aucune question similaire</div>
        <div v-else class="flex flex-col justify-between px-4 border-b border-r custom-border rounded-xl">
            <div  v-for="fuq in followupQuestions" class="flex py-4 flex-row items-center">
                <div class="pr-2">
                    <svg class="w-6 h-6 svg-color" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <a @click="followupQuestion(fuq)" class="flex hover:underline cursor-pointer font-bold text-lg text-color ">
                    {{ fuq.question }}
                </a>
            </div>
        </div>
    </div>
</template>

<style scoped>
.text-color {
    color: v-bind('parameters.question_background_color') !important;
}
.custom-border {
    border-color: v-bind('parameters.text_color') !important;
}
.svg-color {
    color: v-bind('parameters.text_color') !important;
}

</style>
