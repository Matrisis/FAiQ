<script setup>

import {onUpdated, ref, watch} from "vue";

const props = defineProps({
    team: Object,
    answer_id: Number
})

const vote = ref(null)

const sendVote = (type) => {
    if (!vote.value && props.answer_id) {
        axios.post(route('public.ask.vote', {team: props.team.id, answer: props.answer_id}), { vote: type})
            .then((response) => {
                vote.value = type === 'incr' ? "Oui" : "Non"
            })
    }
}

watch(() => props.answer_id, (newValue, oldValue) => {
    vote.value = null
})
</script>

<template>

<div v-if="answer_id" class="w-3/4 mt-6 mx-auto flex flex-row justify-center items-center">
    <div class="flex">
        <p>Cette réponse était-elle utile ?</p>
    </div>
    <div class="flex pl-3">
        <div v-if="vote === null" class="flex flex-row">
            <div class="flex pr-4">
                <button @click="sendVote('incr')" class="bg-white hover:bg-gray-500 text-gray-500 hover:text-white border border-gray-500 font-bold py-2 px-4 rounded">Oui</button>
            </div>
            <div class="flex">
                <button @click="sendVote('decr')" class="bg-white hover:bg-gray-500 text-gray-500 hover:text-white border border-gray-500 font-bold py-2 px-4 rounded">Non</button>
            </div>
        </div>
        <div v-else >
            <p>Vous avez voté '{{ vote }}'.</p>
        </div>
    </div>
</div>

</template>

<style scoped>

</style>
