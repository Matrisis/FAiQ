<!-- LottieAnimation.vue -->
<template>
    <div id="loading-animation">
    </div>
</template>

<script setup>
import bodymovin from "lottie-web";
import { onMounted, ref } from 'vue';

const props = defineProps({
    path: String,
});

onMounted(() => {
    bodymovin.loadAnimation({
        container: document.getElementById('loading-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: props.path,
    })
});
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
</style>
