<script lang="ts" setup>
import type { Header, Item, ServerOptions } from "vue3-easy-data-table";
import {ref, computed, watch, onMounted} from "vue";
import axios from "axios";
import Markdown from "@/Components/Markdown.vue";
import Modal from "@/Components/Modal.vue";
import ModalChoice from "@/Components/ModalChoice.vue";

const props = defineProps({
    team: Object
});

const headers: Header[] = [
    { text: "Question", value: "question", sortable: true,  width: 400 },
    { text: "Reponse", value: "answer", sortable: true },
    { text: "Actions", value: "actions", sortable: false, width: 100 },
];

const items = ref<Item[]>([]);

const loading = ref(false);
const serverItemsLength = ref(0);
const serverOptions = ref({
    page: 1,
    rowsPerPage: 5,
    sortType: 'desc',
    sortBy: 'answer',
    search: '',
});

const loadFromServer = async () => {
    loading.value = true;
    let serverCurrentPageItems = null;
    let serverTotalItemsLength = null;
    await axios.get(route('admin.answers.get', {team: props.team}), {
        params: serverOptions.value
    }).then((response) =>
    {
        serverCurrentPageItems = response.data.response.data;
        serverTotalItemsLength = response.data.response.total;
    })
    items.value = serverCurrentPageItems;
    serverItemsLength.value = serverTotalItemsLength;
    loading.value = false;
};

const showModal = ref(false);
const modalItem = ref(null);
const modalText = ref("");
const updateText = ref("");
const modalType = ref("");
const openModal = (answer, text, type) => {
    modalType.value = type;
    modalText.value = text;
    modalItem.value = answer;
    showModal.value = true;
    updateText.value = answer.answer;
}

const clickAction = (answer) => {
    if(modalType.value === 'delete') {
        deleteAnswer(answer);
    } else {
        updateAnswer(answer, updateText.value);
    }
}

const updateAnswer = (answer, newAnswer) => {
    axios.put(route('admin.answers.update', { team: props.team.id, answer: answer }), {answer: newAnswer}).then($response =>{
        showModal.value = false;
        loadFromServer();
    })
}

const deleteAnswer = (answer) => {
    console.log(answer)
    axios.delete(route('admin.answers.delete', { team: props.team.id, answer: answer })).then($response =>{
        showModal.value = false;
        loadFromServer();
    })
}

// initial load
loadFromServer();

watch(serverOptions, (value) => { loadFromServer(); }, { deep: true });

</script>

<template>
    <div class="flex flex-col">
        <Modal :show="showModal" :closeable="true" @keydown.esc="showModal = false">
            <div class="w-full flex flex-row mt-4">
                <h1 class="flex justify-center text-center w-full text-xl">{{ modalText }}</h1>
            </div>
            <div v-if="modalType === 'update'" class="px-4 py-6">
                <textarea class=" w-full rounded border border-gray-500 min-h-96"  v-model="updateText">

                </textarea>
            </div>
            <div v-if="modalType === 'delete'" class="flex flex-row w-full items-center justify-center py-6">
                <button class="flex mr-2 py-4 px-8 rounded border border-red-600 hover:bg-red-600 hover:text-white" @click="clickAction(modalItem)">
                    Oui
                </button>
                <button class="flex py-4 px-8 rounded border border-blue-600 hover:bg-blue-600 hover:text-white" @click="showModal = false">
                    Non
                </button>
            </div>
            <div v-if="modalType === 'update'" class="flex flex-row w-full items-center justify-center py-6">
                <button class="flex mr-2 py-4 px-8 rounded border border-blue-600 hover:bg-blue-600 hover:text-white" @click="clickAction(modalItem)">
                    Oui
                </button>
                <button class="flex py-4 px-8 rounded border border-red-600 hover:bg-red-600 hover:text-white" @click="showModal = false">
                    Non
                </button>
            </div>
        </Modal>
        <div class="p-3">
            <span>Rechercher : </span>
            <input class="p" type="text" v-model="serverOptions.search">
        </div>
        <EasyDataTable
            table-class-name="customize-table"
            buttons-pagination
            v-model:server-options="serverOptions"
            :server-items-length="serverItemsLength"
            :loading="loading"
            :headers="headers"
            :items="items"
            must-sort
            alternating
        >
            <template #item-actions="item" class="flex flex-row justify-center items-center w-full">
                <button @click="openModal(item, 'Modifier la reponse', 'update')" class="">
                    <svg class="feather feather-edit" fill="none" height="24" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button @click="openModal(item, 'Voulez-vous supprimer cette reponse ?', 'delete')" class="">
                    <svg class="feather feather-edit" fill="" height="24" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M432 80h-82.38l-34-56.75C306.1 8.827 291.4 0 274.6 0H173.4C156.6 0 141 8.827 132.4 23.25L98.38 80H16C7.125 80 0 87.13 0 96v16C0 120.9 7.125 128 16 128H32v320c0 35.35 28.65 64 64 64h256c35.35 0 64-28.65 64-64V128h16C440.9 128 448 120.9 448 112V96C448 87.13 440.9 80 432 80zM171.9 50.88C172.9 49.13 174.9 48 177 48h94c2.125 0 4.125 1.125 5.125 2.875L293.6 80H154.4L171.9 50.88zM352 464H96c-8.837 0-16-7.163-16-16V128h288v320C368 456.8 360.8 464 352 464zM224 416c8.844 0 16-7.156 16-16V192c0-8.844-7.156-16-16-16S208 183.2 208 192v208C208 408.8 215.2 416 224 416zM144 416C152.8 416 160 408.8 160 400V192c0-8.844-7.156-16-16-16S128 183.2 128 192v208C128 408.8 135.2 416 144 416zM304 416c8.844 0 16-7.156 16-16V192c0-8.844-7.156-16-16-16S288 183.2 288 192v208C288 408.8 295.2 416 304 416z"/></svg>                </button>
            </template>
            <template #item-answer="item">
                <Markdown :source="item.answer" />
            </template>
            <template #loading>
                Chargement...
            </template>
            <template #empty-message>
                Aucune reponse.
            </template>
        </EasyDataTable>
    </div>
</template>

<style scoped>
.customize-table {
    --easy-table-header-font-size: 600;
    --easy-table-text-font-size: 600;
}
</style>
