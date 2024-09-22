<script setup>

import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";

const props = defineProps({
    parameters: Object
})

const emit = defineEmits(["parametersUpdated"]);

const paramsForm = useForm({
    background_color : props.parameters.background_color,
    question_background_color : props.parameters.question_background_color,
    icon_path : props.parameters.icon_path,
    logo_path : props.parameters.logo_path,
    text_color : props.parameters.text_color,
    title_color : props.parameters.title_color,
    title : props.parameters.title,
})

const onParametersUpdated = () => {
    emit("parametersUpdated", props.parameters)
}

watch(paramsForm, (a, b) => {
    props.parameters.background_color = paramsForm.background_color
    props.parameters.question_background_color = paramsForm.question_background_color
    props.parameters.icon_path = paramsForm.icon_path
    props.parameters.logo_path = paramsForm.logo_path
    props.parameters.text_color = paramsForm.text_color
    props.parameters.title_color = paramsForm.title_color
    props.parameters.title = paramsForm.title
    success.value = false
    errors.value = null
    onParametersUpdated()
})

const defaultParams =  JSON.parse(JSON.stringify(props.parameters))

const resetParameters = () => {
    paramsForm.background_color = defaultParams.background_color
    paramsForm.question_background_color = defaultParams.question_background_color
    paramsForm.icon_path = defaultParams.icon_path
    paramsForm.logo_path = defaultParams.logo_path
    paramsForm.text_color = defaultParams.text_color
    paramsForm.title_color = defaultParams.title_color
    paramsForm.title = defaultParams.title
    success.value = false
    errors.value = null
}

const success = ref(false);
const errors = ref(null);
const updateParameters = () => {
    success.value = false
    errors.value = null
    axios.put(route('admin.parameters.update', {
        team: props.parameters.team_id,
        params: props.parameters.id
    }), paramsForm)
    .then((response) => {
        success.value = true
    })
    .catch((error) => {
        errors.value = error.response.data.errors
    });
}

</script>

<template>
    <div class="flex flex-col justify-start items-center w-full shadow-2xl shadow-black h-full pt-12 text-gray-800 p-4 border-gray-500 bg-white">
        <h3 class="text-2xl font-bold text-center uppercase">Paramètres d'affichage</h3>
        <div v-if="success" class="mt-6 flex text-green-900 w-full justify-center items-center py-6 px-3 ">
            <p>Correctement mis à jour</p>
        </div>
        <div v-if="errors" class="mt-6 flex text-red-900 w-full justify-center items-center py-6 px-3 ">
            <p v-for="error in errors">{{ error[0] }}</p>
        </div>
        <div class="max-w-sm mx-auto w-full mt-6 opacity-80">
            <div class="mt-6">
                <label for="title" class="uppercase">Titre</label>
                <input type="text"
                       ref="title"
                       id="title"
                       v-model="paramsForm.title"
                       class="flex w-full mt-3 rounded"
                       required />
            </div>
            <div class="mt-6">
                <label for="title_color" class="uppercase">Couleur du titre</label>
                <input type="color"
                       ref="title_color"
                       id="title_color"
                       class="flex w-full mt-3 rounded"
                       v-model="paramsForm.title_color" required />
            </div>
            <div class="mt-6">
                <label for="text_color" class="uppercase">Couleur du texte</label>
                <input type="color"
                       ref="text_color"
                       id="text_color"
                       class="flex w-full mt-3 rounded"
                       v-model="paramsForm.text_color" required />
            </div>
            <div class="mt-6">
                <label for="background_color" class="uppercase">Couleur du fond</label>
                <input type="color"
                       ref="background_color"
                       id="background_color"
                       class="flex w-full mt-3 rounded"
                       v-model="paramsForm.background_color" required />
            </div>
            <div class="mt-6">
                <label for="question_background_color" class="uppercase">Couleur contextuelle</label>
                <input type="color"
                       ref="question_background_color"
                       id="question_background_color"
                       class="flex w-full mt-3 rounded"
                       v-model="paramsForm.question_background_color" required />
            </div>

        </div>
        <div class="flex flex-row mt-12 items-center justify-center">
            <button @click="updateParameters" type="submit" class="flex py-3 px-1 text-gray-600 bg-white border border-gray-600 hover:bg-gray-600 hover:text-white rounded">Enregistrer</button>
            <button @click="resetParameters"  class="ml-4 flex py-3 px-1 text-red-600 bg-white border border-red-600 hover:bg-red-600 hover:text-white rounded">Reinitialiser</button>
        </div>
    </div>

</template>

<style scoped>

</style>
