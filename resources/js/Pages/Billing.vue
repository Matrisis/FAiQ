<script setup>

// Props reçues
const props = defineProps({
    team: Object,
});

const redirect = () => {
    window.location.href = route('admin.billing.checkout', {team: props.team.id});
}

</script>

<template>

    <div class="flex flex-col items-center justify-center w-full h-screen">
        <h1 class="flex text-3xl">Paiement</h1>
        <div class="flex flex-col items-center justify-center mt-4">
            <p class="flex">Merci pour votre inscription.</p>
            <p class="flex">Pour finaliser la transaction, veuillez cliquer sur le lien ci-dessous :</p>
            <button class="flex mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @click="redirect()">
                Payer
            </button>
        </div>

    </div>

</template>

<style scoped>
#card-element {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>
