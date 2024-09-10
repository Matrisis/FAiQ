<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Ask from '@/Pages/Ask/Ask.vue';
import AskLayout from "@/Layouts/AskLayout.vue";

const props = defineProps({
    // Settings
    channel: String,
    team : Object,
    load: String,

    // Page custom parameters
    background_color: String,
    text_color: String,
    icon_path: String,
    logo_path: String,
})


</script>

<template>

    <AppLayout v-if="props.load === 'admin'" title="Ask">
        <AskLayout :title="props.team.name"
                   :text_color="props.text_color"
                   :icon_path="props.icon_path"
                   :logo_path="props.logo_path">
            <Ask :channel="channel" :team="team" :background_color="props.background_color"/>
        </AskLayout>
    </AppLayout>
    <div v-if="props.load === 'public'">
        <AskLayout :title="props.team.name"

                   :text_color="props.text_color"
                   :icon_path="props.icon_path"
                   :logo_path="props.logo_path">
            <Ask :channel="channel" :team="team" :background_color="props.background_color"/>
        </AskLayout>
    </div>
</template>
