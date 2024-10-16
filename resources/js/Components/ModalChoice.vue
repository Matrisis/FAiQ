<script setup>

import Modal from "@/Components/Modal.vue";

const props = defineProps({
    showModal: Boolean,
    modalText: String,
    clickedItem: Object,
    clickAction: Function
})

</script>

<template>
    <Modal :show="showModal" :closeable="true" @keydown.esc="showModal = false">
        <div class="w-full flex flex-row mt-4">
            <h1 class="flex justify-center text-center w-full text-xl">{{ modalText }}</h1>
        </div>
        <div class="flex flex-row w-full items-center justify-center py-6">
            <button class="flex mr-2 py-4 px-8 rounded border border-red-600 hover:bg-red-600 hover:text-white" @click="clickAction(clickedItem)">Oui</button>
            <button class="flex py-4 px-8 rounded border border-blue-600 hover:bg-blue-600 hover:text-white" @click="showModal = false">Non</button>
        </div>
    </Modal>
</template>

<style scoped>

</style>
