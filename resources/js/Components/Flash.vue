<script setup>

const props = defineProps({
    message: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: 'success'
    }
})

</script>

<template>

    <div v-if="message" class="w-full">
        <div v-if="type === 'success'" class="p-4 mb-4 text-md text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
            <p class="text-center">{{ message }}</p>
        </div>
        <div v-if="type === 'error'" class="p-4 mb-4 text-md text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
            <p class="text-center">{{ message }}</p>
        </div>
    </div>

</template>

<style scoped>

</style>
