<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionBorder from "@/Components/SectionBorder.vue";
import {ref} from "vue";

const props = defineProps({
    team: Object,
});

const form = useForm({
    prompt: props.team.prompts ? props.team.prompts.prompt : '',
});

const errors = ref(null)
const success = ref(null)


const updatePrompt = () => {
    errors.value = null
    success.value = null
    axios.put(route('admin.prompt.update', { team: props.team.id }), {
        prompt: form.prompt
    })
    .then((response) => {
        console.log(response)
        success.value = response.data.success
    }).catch((error) => {
        console.log(error)
        errors.value = error.response.data.errors
    })
};
</script>

<template class="">
    <FormSection class="mt-6 lg:mt-0 " @submitted="updatePrompt">
        <template #title>
            Prompt
        </template>

        <template #description>
            Prompt FAiQ
        </template>

        <template #form>
            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="prompt" value="Team Prompt" />

                <textarea
                    id="prompt"
                    v-model="form.prompt"
                    type="text"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm
                    focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                    w-full h-48 lg:h-96"
                />

                <InputError v-if="errors" :message="errors.prompt[0]" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <ActionMessage v-if="success" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
