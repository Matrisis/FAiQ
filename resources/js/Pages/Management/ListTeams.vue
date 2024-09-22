<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import { VueGoodTable } from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';

const columns = [
    {
        label: 'Nom',
        field: 'name',
        sortable: true,
        index: 0,
    },
    {
        label: 'Verouillé',
        field: 'locked',
        sortable: true,
        index: 1,
    },
    {
        label: 'Actions',
        field: 'actions',
        sortable: false,
        index: 2,
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

        const response = await axios.get(route('admin.management.list'), {
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
                locked: serverParams.imported,
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

const viewTeam = (team) => {
    alert(team.id)
    axios.get(route('admin.management.view', { team: team.id })).then((response) => {
        window.location.href = response.data.route
    })

}

const unlockTeam = (team) => {
    axios.put(route("admin.management.unlock", { team: team.id })).then((response) => {
        fetchData();
    })
}

</script>

<template>
    <div class="max-w-full mx-auto p-4 font-sans">
        <h2 class="text-black dark:text-white">Teams
            <span v-if="totalRecords > 0">({{ totalRecords }})</span>
        </h2>
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
                     <button @click="viewTeam(row)" class="rounded p-4 border border-gray-500 text-gray-500 hover:text-white hover:bg-gray-500">
                        Voir
                    </button>
                    <button v-if="row.locked" @click="unlockTeam(row)" class="ml-4 rounded p-4 border border-gray-500 text-gray-500 hover:text-white hover:bg-gray-500">
                        Dévérouiller
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
