<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ListTeams from "@/Pages/Management/ListTeams.vue";
</script>

<template>
    <AppLayout title="Management">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ListTeams />
            </div>
        </div>
    </AppLayout>
</template>
