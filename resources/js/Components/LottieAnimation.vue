<template>
    <div ref="animationContainer" :style="{ width, height }"></div>
</template>

<script setup>
import lottie from "lottie-web";
import {ref, onMounted} from 'vue';

const props = defineProps({
    animationData: {
        type: Object,
        required: true,
    },
    loop: {
        type: Boolean,
        default: true,
    },
    autoplay: {
        type: Boolean,
        default: true,
    },
    width: {
        type: String,
        default: '100%',
    },
    height: {
        type: String,
        default: '100%',
    },
});

const animationContainer = ref(null);

onMounted(() => {
    lottie.loadAnimation({
        container: animationContainer.value,
        renderer: 'svg',
        loop: props.loop,
        autoplay: props.autoplay,
        animationData: props.animationData,
    });
});
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
</style>
