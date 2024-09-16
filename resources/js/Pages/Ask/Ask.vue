<script setup>
import {onMounted, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import InstantAnswers from "@/Pages/Ask/InstantAnswers.vue";
import Answer from "@/Pages/Ask/Answer.vue";
import Question from "@/Pages/Ask/Question.vue";
import Vote from "@/Pages/Ask/Vote.vue";

const props = defineProps({
    channel: String,
    team: Object,

    instant_answers: Object,
})

const bg_color = ref(props.team.parameters.background_color);
const txt_color = ref(props.team.parameters.background_color);

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
}

</script>

<template>
    <div class="custom-background-color flex flex-col">
        <div class="py-16 lg:py-12">
            <h2 class="text-3xl text-center lg:text-5xl font-semibold text-white justify-center flex ">{{ props.team.parameters.title }}</h2>
            <div class="lg:w-3/4 mx-auto mt-6 ">
                <label class="flex flex-col-reverse relative focus group lg:w-3/5 mx-auto">
                    <div class="relative flex px-3">
                        <Question @question="onQuestion" :channel="props.channel" :team="props.team" />
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="mt-6 lg:mt-12 w-full lg:w-3/4 mx-auto flex">
        <div class=" lg:grid grid-cols-3 gap-x-8 w-full h-96">
            <div class="h-full col-span-2 w-full p-3">
                <Answer :answer="answer" :question="question" :asking="asking"/>
                <Vote :team="props.team" :answer_id="answer_id" />
            </div>
            <div class="h-full col-span-1 w-full p-3">
                <InstantAnswers :instant_answers="instant_answers" @instantQuestion="onInstantQuestion" :color="txt_color"/>
            </div>
        </div>

    </div>

</template>

<style scoped>
    .custom-background-color {
        background-color: v-bind(bg_color);
    }

    .text-color {
        color: v-bind(txt_color);
    }
</style>
