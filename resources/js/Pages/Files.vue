<script setup>

import Ask from "@/Pages/Ask.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AskLayout from "@/Layouts/AskLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import List from "@/Pages/Files/List.vue";
import Upload from "@/Pages/Files/Upload.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";

const props = defineProps({
    team: Object
})

const showModal = ref(false);

const onEmitClose = () => {
    showModal.value = false
}

</script>

<template>
    <AppLayout title="Fichiers">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Modal :show="showModal" :closeable="true" @keydown.esc="showModal = false">
                    <div class="w-full flex flex-row mb-4">
                        <h1 class="flex justify-center text-center w-full text-xl">Ajouter des fichiers</h1>
                        <button @click="showModal = false" class="cursor-pointer text-sm hover:text-blue-400 text-gray-500 justify-center text-center items-center p-1">X</button>
                    </div>
                    <Upload :team="team "/>
                </Modal>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <button class="rounded-xl bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700" @click="showModal = true">
                    Ajouter des fichiers
                </button>
                <List :team="team "/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
