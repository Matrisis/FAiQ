<script setup>
import bodymovin from "lottie-web";
import {Head} from "@inertiajs/vue3";
import {onMounted} from "vue";

const loading_animation = () => {
    bodymovin.loadAnimation({
        container: document.getElementById('loading-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/storage/lotties/construction.json',
    })
}

onMounted(() => {
    loading_animation()
});

</script>

<template>
    <Head title="Maintenance" />

    <div class="min-h-screen flex justify-center items-center flex-col">
        <h1 class="text-3xl font-bold ">PAGE EN CONSTRUCTION</h1>
        <div id="loading-animation" class="w-26"></div>
    </div>

</template>

<style scoped>

</style>
