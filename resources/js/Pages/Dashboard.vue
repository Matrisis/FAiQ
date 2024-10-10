<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Stats from "@/Pages/Dashboard/Stats.vue";
import Modal from "@/Components/Modal.vue";
</script>

<template>

    <AppLayout title="Dashboard">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Stats />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
