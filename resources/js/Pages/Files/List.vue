<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import { VueGoodTable } from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';
import Modal from "@/Components/Modal.vue";
import Upload from "@/Pages/Files/Upload.vue";

const props = defineProps({
    team: Object
});

const columns = [
    {
        label: 'Nom',
        field: 'name',
        sortable: true,
        index: 0,
    },
    {
        label: 'Status',
        field: 'imported',
        type: 'boolean',
        formatFn: (value) => (value ? 'Oui' : 'Non'),
        sortable: true,
        index: 1,
    },
    {
        label: "En cours d'import",
        field: 'importing',
        type: 'boolean',
        formatFn: (value) => (value ? 'Oui' : 'Non'),
        sortable: true,
        index: 2,
    },
    {
        label: 'Actions',
        field: 'actions',
        sortable: false,
        index: 3,
    },
];

const rows = ref([]);
const totalRecords = ref(0);
const serverParams = reactive({
    page: 1,
    perPage: 10,
    sortType: '',
    sortField: '',
    sortColumnIndex: 0,
    name: '',
    imported: '',
});

const fetchData = async () => {
    try {
        const requestColumns = columns.map((col) => ({
            data: col.field,
            name: col.field,
            searchable: true,
            orderable: col.sortable,
        }));

        const response = await axios.get(route('admin.files.list', { team: props.team.id }), {
            params: {
                draw: 1,
                columns: requestColumns,
                order: [
                    {
                        column: serverParams.sortColumnIndex,
                        dir: serverParams.sortType || 'asc',
                    },
                ],
                start: (serverParams.page - 1) * serverParams.perPage,
                length: serverParams.perPage,
                search: {
                    value: '',
                    regex: false,
                },
                name: serverParams.name,
                team_id: props.team.id,
                imported: serverParams.imported,
            },
        });
        rows.value = response.data.data;
        totalRecords.value = response.data.recordsTotal;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    fetchData();
    Echo.private(`file.${props.team.id}`)
        .listen('NewFileEvent', (event) => {
            fetchData();
        });
});


const onSortChange = (params) => {
    const sortedColumn = columns.find((col) => col.field === params[0].field);
    if (sortedColumn) {
        serverParams.sortField = params[0].field;
        serverParams.sortType = params[0].type;
        serverParams.sortColumnIndex = sortedColumn.index;
        fetchData();
    }
};

const processFile = (file) => {
    showModal.value = true;
    modalText.value = "Importer \"" + file.name + "\" ?";
    clickedFile.value = file;
    action.value = "processFileReq";
};

const processFileReq = (file) => {
    axios.put(route('admin.files.process', { team: props.team.id, file: file.id })).then($response =>{
        fetchData();
    }).then(() => {
        showModal.value = false
    })
};

const deleteFileReq = (file) => {
    axios.delete(route('admin.files.delete', { team: props.team.id, file: file.id })).then($response =>{
        fetchData();
    }).then(() => {
        showModal.value = false
    })
};

const deleteFile = (file) => {
    showModal.value = true;
    modalText.value = "Supprimer \"" + file.name + "\" ?";
    clickedFile.value = file;
    action.value = "deleteFileReq";
};

const clickAction = (file) => {
    if (action.value === 'processFileReq') {
        processFileReq(file);
    } else if (action.value === 'deleteFileReq') {
        deleteFileReq(file);
    }
};

const showModal = ref(false);
const modalText = ref('');
const clickedFile = ref(null);
const action  = ref('');

</script>

<template>
    <Modal :show="showModal" :closeable="true" @keydown.esc="showModal = false">
        <div class="w-full flex flex-row mt-4">
            <h1 class="flex justify-center text-center w-full text-xl">{{ modalText }}</h1>
        </div>
        <div class="flex flex-row w-full items-center justify-center py-6">
            <button class="flex mr-2 py-4 px-8 rounded border border-red-600 hover:bg-red-600 hover:text-white" @click="clickAction(clickedFile)">Oui</button>
            <button class="flex py-4 px-8 rounded border border-blue-600 hover:bg-blue-600 hover:text-white" @click="showModal = false">Non</button>
        </div>
    </Modal>
    <div class="max-w-full mx-auto p-4 font-sans">
        <h2 class="text-black dark:text-white">Fichiers <span v-if="totalRecords > 0">({{ totalRecords }})</span></h2>
        <vue-good-table
        :columns="columns"
        :rows="rows"
        :totalRows="totalRecords"
        :pagination-options="{
          enabled: true,
          mode: 'pages',
          perPage: serverParams.perPage,
          nextLabel: 'Suivant',
          prevLabel: 'Précédent',
        }"
        :search-options="{ enabled: true }"
        :sort-options="{ enabled: true }"
        @on-page-change="(params) => { serverParams.page = params.currentPage; fetchData(); }"
        @on-per-page-change="(params) => { serverParams.perPage = params.currentPerPage; fetchData(); }"
        @on-sort-change="onSortChange"
    >
        <template #table-row="{ column, row }">
            <span v-if="column.field === 'actions'" class="flex flex-col lg:flex-row justify-center items-center">
                <button v-if="!row.importing && !row.imported" @click="processFile(row)" class="rounded border border-gray-500 py-4 px-2 text-gray-500 hover:text-white hover:bg-gray-500">
                    Importer
                </button>
                <button @click="deleteFile(row)" class="ml-2 rounded border border-red-500 py-4 px-2 text-red-500 hover:text-white hover:bg-red-500">
                    Supprimer
                </button>
            </span>
            <span v-else>
                <!-- Gestion des champs booléens avec formatFn -->
                <span v-if="column.type === 'boolean'">
                    {{ column.formatFn(row[column.field]) }}
                </span>
                <!-- Rendu par défaut pour les autres champs -->
                <span v-else>
                    {{ row[column.field] }}
                </span>
            </span>
        </template>
    </vue-good-table>
    </div>
</template>

<style scoped>
</style>
