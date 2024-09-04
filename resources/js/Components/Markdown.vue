<template>
    <div v-html="marked.parse(source)" />
</template>

<script setup lang="ts">
import { marked } from "marked";

const render = (text: string) => marked.parse(text);

defineProps({
    source: {
        type: String,
        default: ""
    }
});
</script>
