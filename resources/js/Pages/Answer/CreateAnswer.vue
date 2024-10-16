<script setup>

import {ref} from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    team: Object
});

const showModal = ref(false);
const questionText = ref("");
const answerText = ref("");
const votes = ref(0);

const createAnswer = () => {
    axios.post(route('admin.answers.create', {team: props.team}), {
        question: questionText.value,
        answer: answerText.value,
        votes: votes.value
    }).then(() => {
        showModal.value = false;
        created_answer()
    })
}

const emit = defineEmits([
    "createdAnswer"
])

const created_answer = () => {
    emit("createdAnswer")
}


</script>

<template>

    <button @click="showModal = true" class="flex flex-row items-center justify-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Ajouter une question et une reponse</span>
    </button>
    <Modal :show="showModal" :closeable="true" @keydown.esc="showModal = false">
        <div class="w-full flex flex-row mt-4">
            <h1 class="flex justify-center text-center w-full text-xl">
                Ajouter une question et une reponse.
            </h1>
        </div>
        <div class="px-4 py-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question</label>
            <input class=" w-full rounded border border-gray-500 min-h-" v-model="questionText"></input>
        </div>
        <div class="px-4 py-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Reponse</label>
            <textarea class=" w-full rounded border border-gray-500 min-h-56" v-model="answerText"></textarea>
        </div>
        <div class="px-4 py-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Votes</label>
            <input type="number" min="0" class=" w-full rounded border border-gray-500 min-h-" v-model="votes"></input>
        </div>
        <div class="flex flex-row w-full items-center justify-center py-6">
            <button class="flex mr-2 py-4 px-8 rounded border border-blue-600 hover:bg-blue-600 hover:text-white" @click="createAnswer">
                Valider
            </button>
            <button class="flex py-4 px-8 rounded border border-red-600 hover:bg-red-600 hover:text-white" @click="showModal = false">
                Annuler
            </button>
        </div>
    </Modal>

</template>

<style scoped>

</style>
