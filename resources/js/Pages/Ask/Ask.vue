<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Qna from "@/Pages/Ask/Partials/qna.vue";


const props = defineProps({
    channel: String,
    team : Object
})

</script>

<template>
    <div class="p-12">
     <qna :channel="channel" :team="team" />
    </div>
</template>
