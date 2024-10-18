<script setup>

import {useForm} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";

const props = defineProps({
    channel: String,
    team: Object,
})
const emit = defineEmits([
    "question"
])

const form = useForm({
    question: '',
});

let answer = ref('');
let asking = ref(false);
let answer_id = ref(null);
let errors = ref({});

const sendquestion = () => {
    errors.value = {}
    answer.value = ''
    axios.post(route('public.ask.create', {team: props.team.id}), {
        question: form.question,
        channel: props.channel
    }).then(() => {
        asking.value = true
        onQuestion(asking.value, form.question, "")
        form.question = ''
    }).catch((error) => {
        errors.value = error.response.data.errors;
    })
};

const onQuestion = (asking, question, answer) => {
   emit("question", {asking: asking, question: question, answer: answer, answer_id: answer_id.value})
}

const cleanAnswer = (event) => {
    asking.value = false
    if (answer.value === null) {
        answer.value = event.answer.answer
    } else if (typeof event.answer.answer === 'string') {
        answer.value = answer.value + event.answer.answer
    }
    if(answer_id.value !== event.answer.id) {
        answer_id.value = event.answer.id
    }
}

onMounted(() => {
    Echo.channel(`ask.${props.channel}`)
        .listen('Ask', (event) => {
            console.log(event)
            cleanAnswer(event)
            onQuestion(asking.value, null, answer.value)
        })
})

</script>

<template>
    <div class="w-full flex flex-col">
        <div class="w-full flex flex-row justify-center items-center">
            <input
                ref="question"
                v-model="form.question"
                type="text"
                :class="'text-black border-2 border-grey leading-10 text-xl  rounded-lg w-full focus:border-gray-50 ' + (asking ? 'bg-gray-200' : '')"
                placeholder="Posez votre question :"
                autofocus
                autocomplete="off"
                spellcheck="false"
                required
                @keyup.enter="sendquestion"
                :readonly="asking"
                id="question"
            />
            <i @click="sendquestion" class="flex absolute inset-y-0 right-0 pr-7 items-center cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </i>
        </div>
        <div v-if="errors" class="w-full text-white text-sm">
            <p v-for="e in errors">{{ e[0] }}</p>
        </div>
    </div>
</template>

<style scoped>

.custom-border {
    border-color: v-bind('props.team.parameters.text_color') !important;
}

</style>
