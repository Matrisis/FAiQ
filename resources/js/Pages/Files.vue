<script setup>

import Ask from "@/Pages/Ask.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AskLayout from "@/Layouts/AskLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import List from "@/Pages/Files/List.vue";
import Upload from "@/Pages/Files/Upload.vue";

const props = defineProps({
    team: Object
})

</script>

<template>
    <AppLayout title="Fichiers">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Upload :team="team "/>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <List :team="team "/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
