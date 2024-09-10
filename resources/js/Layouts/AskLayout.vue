<script setup>

import {Head} from "@inertiajs/vue3";
import Welcome from "@/Components/Welcome.vue";
import {onMounted} from "vue";

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

    <Head :title="props.team.parameters.title" />
    <div class="">
        <header class="w-3/4 mx-auto py-7 flex flex-row justify-start">
            <div class="flex">
                <img :src="props.team.parameters.logo_path" class="w-7 h-7" alt="logo"/>
            </div>
        </header>
        <div id="background" class="">
            <div class="w-full mx-auto ">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
