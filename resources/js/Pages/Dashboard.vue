<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Stats from '@/Pages/Dashboard/Stats.vue';
import Flash from "@/Components/Flash.vue";

const props = defineProps({
    team: Object,
    flash_message: {
        type: String,
        default: ''
    }
})

</script>


<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Flash :message="flash_message"/>
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
