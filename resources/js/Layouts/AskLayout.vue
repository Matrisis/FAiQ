<script setup>

import {Head} from "@inertiajs/vue3";
import Welcome from "@/Components/Welcome.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    team: Object,
});

const setFavicon = (icon_path) => {
    let link = document.querySelector("link[rel~='icon']");
    if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.head.appendChild(link);
    }
    link.href = icon_path;
}
onMounted(function(){
    setFavicon(props.team.parameters.icon_path)
})

</script>

<template>
    <Head :title="'FAQ - ' + props.team.name" />
    <div class="all-bg-color">
        <header class="w-3/4 mx-auto py-7 flex flex-row justify-start">
            <div class="flex">
                <img :src="props.team.parameters.logo_path" class="w-16" alt="logo"/>
            </div>
        </header>
        <div class="all-bg-color min-h-screen w-full">
            <div class="w-full mx-auto ">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.all-bg-color {
    background-color: v-bind('props.team.parameters.background_color');
}
</style>
