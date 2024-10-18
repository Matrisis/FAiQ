<script setup>
import {computed, onMounted, reactive, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import InstantAnswers from "@/Pages/Ask/InstantAnswers.vue";
import Answer from "@/Pages/Ask/Answer.vue";
import Question from "@/Pages/Ask/Question.vue";
import Vote from "@/Pages/Ask/Vote.vue";
import FollowupQuestions from "@/Pages/Ask/FollowupQuestions.vue";

const props = defineProps({
    channel: String,
    team: Object,

    instant_answers: Object,
})

let answer = ref('');
let asking = ref(false);
let question = ref("")
let answer_id = ref(null);

const onQuestion = (params) => {
    asking.value = params.asking
    answer.value = params.answer
    if (params.answer_id)
        answer_id.value = params.answer_id
    if(params.question)
        question.value = params.question
}

const onInstantQuestion = (question_answer) => {
    question.value = question_answer.question
    answer.value = question_answer.answer.answer
    answer_id.value = question_answer.answer.id
    asking.value = false
}

const onFollowupQuestion = (fuq) => {
    fuq = fuq.fuq
    question.value = fuq.question
    answer.value = fuq.answer
    asking.value = false
    answer_id.value = fuq.id
}

</script>

<template>
    <div class="all-bg-color custom-text-color h-fit w-full">
    <div class="flex flex-col custom-background-color">
        <div class="py-16 lg:py-12">
            <h2 class="text-3xl text-center lg:text-5xl font-semibold title-color justify-center flex ">{{ props.team.parameters.title }}</h2>
            <div class="lg:w-3/4 mx-auto mt-6 ">
                <label class="flex flex-col-reverse relative focus group lg:w-3/5 mx-auto">
                    <div class="relative flex px-3">
                        <Question @question="onQuestion" :channel="props.channel" :team="props.team" />
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="mt-6 lg:mt-12 w-full xl:w-3/4 mx-auto flex">
        <div class="flex flex-col lg:grid grid-cols-3 gap-x-8 w-full lg:h-96">
            <div class="flex flex-col h-full lg:col-span-2 w-full p-3">
                <Answer :answer="answer" :question="question" :asking="asking" :parameters="props.team.parameters"/>
                <Vote :team="props.team" :answer_id="answer_id" />
            </div>
            <div class="flex flex-col lg:h-full lg:col-span-1 justify-center p-3">
                <InstantAnswers :instant_answers="instant_answers" @instantQuestion="onInstantQuestion" :parameters="props.team.parameters"/>
                <FollowupQuestions :question="question"  :answer="answer" :team="props.team" class="mt-5" @followupQuestion="onFollowupQuestion" />
            </div>
        </div>

    </div>
    </div>

</template>

<style scoped>

    .title-color {
        color: v-bind('props.team.parameters.title_color') !important;
    }

    .custom-text-color {
        color: v-bind('props.team.parameters.text_color') !important;
    }

    .custom-background-color {
        background-color: v-bind('props.team.parameters.question_background_color');
    }

    .all-bg-color {
        background-color: v-bind('props.team.parameters.background_color');
    }
</style>
