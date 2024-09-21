<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import { VueGoodTable } from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';

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
        label: 'Importé',
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
    try {
        if (confirm("Êtes-vous sûr de vouloir d'importer ce fichier ?")) {
            axios.put(route('admin.files.process', { team: props.team.id, file: file.id })).then($response =>{
                fetchData();
            })
        }
    } catch (error) {
        console.error(error);
    }
};

const deleteFile = (file) => {
    try {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
            axios.delete(route('admin.files.delete', { team: props.team.id, file: file.id })).then($response =>{
                fetchData();
            })
        }
    } catch (error) {
        console.error(error);
    }
};



</script>

<template>
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
</template>

<style scoped>
/* Vous pouvez ajouter des styles personnalisés ici si nécessaire */
</style>
