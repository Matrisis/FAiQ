<script setup>

import {Head} from "@inertiajs/vue3";
import Welcome from "@/Components/Welcome.vue";
defineProps({
    title: String,
    text_color: String,
    icon_path: String,
    logo_path: String
});


</script>

<template>
    <Head :title="title"  />
    <div class="">
        <header class="w-3/4 mx-auto py-7 flex flex-row justify-between">
            <div class="flex">
                LOGO
            </div>
            <div class="flex">
                COMPANY
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
