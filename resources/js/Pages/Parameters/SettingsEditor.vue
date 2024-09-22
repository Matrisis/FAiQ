<script setup>

import {ref, watch} from "vue";

const props = defineProps({
    parameters: Object
})

const checked = ref(props.parameters.accessible)
const errors = ref(null)

const updateConfig = () => {
    axios.put(route('admin.parameters.update', {
        team: props.parameters.team_id,
        params: props.parameters.id
    }), {accessible: checked.value})
        .then((response) => {
           console.log(response.params.accessible)
        }).catch((error) => {

        });
}

watch(checked, (a, b) => {
    updateConfig()
})

</script>

<template>
    <div class="w-full min-h-screen flex flex-col items-center mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h3 class="text-2xl font-bold text-center uppercase">Configuration</h3>
        <div class="max-w-sm mx-auto w-full mt-6 opacity-80">
            <div v-if="errors" >
                <p v-for="error in errors">{{ error[0] }}</p>
            </div>
            <div class="mt-6 flex flex-col">
                <label for="accessible" class="uppercase">Page accessible</label>
                <label class="mt-6 inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="checked" class="sr-only peer">
                    <div class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
