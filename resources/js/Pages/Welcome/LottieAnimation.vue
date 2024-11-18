<!-- LottieAnimation.vue -->
<template>
    <div id="loading-animation">
    </div>
</template>

<script setup>
import bodymovin from "lottie-web";
import { onMounted, ref } from 'vue';

onMounted(() => {
    bodymovin.loadAnimation({
        container: document.getElementById('loading-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/storage/lotties/hero.json',
    })
});
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
</style>
