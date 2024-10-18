<script setup>

import {ref, watch} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SectionBorder from "@/Components/SectionBorder.vue";

const props = defineProps({
    parameters: Object
})

const checked = ref(props.parameters.accessible)
const errors = ref(null)
const success = ref(null)

const updateConfig = () => {
    errors.value = null
    success.value = null
    axios.post(route('admin.parameters.update', {
        team: props.parameters.team_id,
        params: props.parameters.id
    }), {accessible: checked.value})
        .then((response) => {
           success.value = response.data.success
        }).catch((error) => {
            errors.value = error.response.data.errors
        });
}

watch(checked, (a, b) => {
    updateConfig()
})

</script>


<template>
    <SectionBorder />
    <ActionSection>
        <template #title>
            Configuration
        </template>

        <template #description>
            Configuration globale de la team
        </template>

        <template #content>
            <div class="py-2">
                <div v-if="errors" class="text-red-600">
                    <p v-for="error in errors">{{ error[0] }}</p>
                </div>
                <div v-if="success" class="text-green-600">
                    <p v-for="s in success">{{ s[0] }}</p>
                </div>
            </div>
            <div class="col-span-6">
                <div class="flex flex-row items-center">
                    <InputLabel for="name" value="Page accessible" />
                    <label class="ml-4 inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="checked" class="sr-only peer">
                        <div class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </template>
    </ActionSection>
</template>

