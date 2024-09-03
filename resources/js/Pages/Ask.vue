<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Ask from '@/Pages/Ask/Ask.vue';

const props = defineProps({
    channel: String,
    team : Object
})


</script>

<template>
    <AppLayout title="Ask">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ask
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Ask :channel="channel" :team="team" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
