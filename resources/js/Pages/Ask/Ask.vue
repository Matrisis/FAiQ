<script setup>
import {onMounted, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import MarkdownRenderer from "@/Components/Markdown.vue";
import bodymovin from "lottie-web";
import InstantAnswers from "@/Pages/Ask/InstantAnswers.vue";

const props = defineProps({
    channel: String,
    team: Object,

    instant_answers: Object,
})

const bg_color = ref(props.team.parameters.background_color);
const txt_color = ref(props.team.parameters.background_color);

const form = useForm({
    question: '',
});
let answer = ref('');
let asking = ref(true);
let asked_question = ref("")

onMounted(() => {
    asking.value = false
    loading_animation()
    Echo.channel(`ask.${props.channel}`)
        .listen('Ask', (event) => {
            asking.value = false
            if (answer.value === null) {
                answer.value = event.answer.answer
            } else {
                answer.value = answer.value + event.answer.answer
            }
        })
})

const sendquestion = () => {
    answer.value = ''
    asked_question.value = form.question
    axios.post(route('public.ask.create', {team: props.team.id}), {
        question: form.question,
        channel: props.channel
    }).then(() => {
        form.question = ''
        asking.value = true
    }).catch((error) => {
        form.errors = error.response.data.errors;
    })
};

const loading_animation = () => {
    bodymovin.loadAnimation({
        container: document.getElementById('loading-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/storage/lotties/loading.json',
    })
}

const onInstantQuestion = (question_answer) => {
    asked_question.value = question_answer.question
    answer.value = question_answer.answer
}

</script>

<template>
    <div class="custom-background-color flex flex-col">
        <div class="py-12">
            <h2 class="text-5xl font-semibold text-white justify-center flex ">{{ props.team.parameters.title }}</h2>
            <div class="w-3/4 mx-auto mt-6 ">
            <label class="flex flex-col-reverse relative focus group w-3/5 mx-auto">
                <div class="relative flex px-3">
                    <input
                        ref="question"
                        v-model="form.question"
                        type="text"
                        class=" border-2 border-grey leading-10 text-xl  rounded-lg w-full focus:border-gray-50"
                        placeholder="Posez votre question :"
                        autofocus
                        @keyup.enter="sendquestion"
                    />
                    <i @click="sendquestion" class="flex absolute inset-y-0 right-0 pr-7 items-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                    </i>
                </div>
            </label>
        </div>
        </div>
    </div>

    <div class="mt-12 w-3/4 mx-auto flex">
        <div class="grid grid-cols-3 gap-x-8 w-full h-96">
            <fieldset class="col-span-2 flex border border-black rounded h-full overflow-y-scroll">
                <legend class="px-2 flex text-xl" v-if="asked_question">{{ asked_question }} : </legend>
                <legend class="px-2 flex text-xl" v-else>Reponse rapide : </legend>
                <div class="p-4 flex w-full">
                    <div v-show="asking" class="flex justify-center mx-auto">
                        <div id="loading-animation"></div>
                    </div>
                    <div v-if="answer" class="">
                        <MarkdownRenderer :source="answer"/>
                        <div class="text-red">
                            <div v-for="error in form.errors">
                                <p>{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
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
