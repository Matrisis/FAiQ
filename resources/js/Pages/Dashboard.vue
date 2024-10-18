<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Stats from '@/Pages/Dashboard/Stats.vue';

const props = defineProps({
    team: Object
})

</script>


<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-100 dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <Stats :team="team" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
/* No additional styles needed; styling is handled within components */
</style>
