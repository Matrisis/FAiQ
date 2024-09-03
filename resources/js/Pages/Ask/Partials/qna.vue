<script setup>
import {onMounted, ref} from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import ActionSection from "@/Components/ActionSection.vue";

const props = defineProps({
    channel: String,
    team : Object
})

const form = useForm({
    question: '',
});

const answer = ref(null);
const asking = ref(false);

onMounted(() => {
    Echo.private(`ask.${props.channel}`)
        .listen('Ask', (event) => {
            asking.value = false
            answer.value = event.answer.answer
        })
})

/*
const sendQuestionStream = await axios.post(route("ask.create", {question: form.question}, {responseType: 'stream'}));
const streamAnswer = sendQuestionStream.data;
streamAnswer.on('answer', (data) => {
    console.log(data)
})
*/

const sendquestion = () => {
    answer.value = null
    axios.post(route('ask.create', {team: props.team.id}), {
        question: form.question,
        channel: props.channel
    }).then(() => {
        asking.value = true
    }).catch((error) => {
        form.errors = error.response.data.errors;
    })
};
</script>

<template>
    <ActionSection>
        <template #title>
            Ask a question
        </template>

        <template #description>
            Ask a question to the support
        </template>

        <template #content>
            <TextInput
                ref="question"
                v-model="form.question"
                type="text"
                class="mt-1 block w-full"
                @keyup.enter="sendquestion"
            />

            <div class="mt-4">
                <div v-if="asking" class="bg-white p-4">
                    <p>Asking...</p>
                </div>
                <div v-if="answer" class="bg-white p-4">
                    {{answer}}
                </div>
                <div v-if="answer" class="text-red bg-white p-4">
                    <div v-for="error in form.errors">
                        <p>{{ error }}</p>
                    </div>
                </div>
            </div>
        </template>
    </ActionSection>
</template>
