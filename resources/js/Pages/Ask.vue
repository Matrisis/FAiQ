<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Ask from '@/Pages/Ask/Ask.vue';
import AskLayout from "@/Layouts/AskLayout.vue";

const props = defineProps({
    // Settings
    channel: String,
    team : Object,
    load: String,
})


</script>

<template class="font-roboto">

    <AppLayout v-if="props.load === 'admin'" title="Ask">
        <AskLayout :team="team">
            <Ask :channel="channel" :team="team"/>
        </AskLayout>
    </AppLayout>
    <div v-if="props.load === 'public'">
        <AskLayout :team="team">
            <Ask :channel="channel" :team="team"/>
        </AskLayout>
    </div>
</template>
