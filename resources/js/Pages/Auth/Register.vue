<template>
    <LandingLayout title="Créez Votre Compte">
    <div class="min-h-screen bg-gradient-to-r from-blue-100 via-white to-blue-100 flex items-center justify-center py-12 px-6">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-5xl">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-8">
                <h1 class="text-3xl font-extrabold">Créez Votre Compte</h1>
                <p class="text-lg mt-2">Remplissez le formulaire ci-dessous pour commencer</p>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submit" class="flex flex-wrap p-8 space-y-6 lg:space-y-0 lg:space-x-6">
                <!-- Colonne gauche -->
                <div class="flex-1 space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nom complet</label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Votre nom complet"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Votre email"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Mot de passe</label>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Créez un mot de passe"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="flex-1 space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nom de l'entreprise</label>
                        <input
                            v-model="form.company_name"
                            type="text"
                            placeholder="Nom de l'entreprise"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Slug de l'entreprise</label>
                        <input
                            v-model="form.company_slug"
                            type="text"
                            placeholder="Slug de l'entreprise"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirmez le mot de passe</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Confirmez le mot de passe"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>
            </form>

            <!-- Sélecteur de forfaits -->
            <div class="p-8 bg-gray-50">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Choisissez un forfait</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div
                        v-for="plan in plans"
                        :key="plan.value"
                        @click="form.forfait = plan.value"
                        :class="[
              'border rounded-lg p-6 cursor-pointer transition',
              plan.value === form.forfait ? 'border-blue-500 shadow-lg' : 'border-gray-200 hover:shadow-md',
            ]"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">{{ plan.name }}</h3>
                        <p class="text-blue-600 text-xl font-bold my-2">{{ plan.price }}</p>
                        <ul class="space-y-2 text-gray-600">
                            <li v-for="feature in plan.features" :key="feature" class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-green-500 mr-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span>{{ feature }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <button
                        type="submit"
                        class="px-6 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg shadow-lg hover:bg-blue-700 transition"
                        :disabled="!form.forfait"
                    >
                        Valider votre inscription
                    </button>
                </div>
            </div>
        </div>
    </div>
    </LandingLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import LandingLayout from "@/Layouts/LandingLayout.vue";

const getURLParams = (key) => {
    const params = new URLSearchParams(window.location.search);
    return params.get(key) || null;
};

const plans = ref([
    {
        name: "Pack Basique",
        price: "3 000€ + 0,25€ / question",
        features: [
            "Tarif fixe avec un coût additionnel par requête",
            "Idéal pour des besoins prévisibles",
            "Support standard inclus",
        ],
        value: "basic",
    },
    {
        name: "Option Flexible",
        price: "0,5€ / question",
        features: [
            "Paiement à l’usage, sans engagement",
            "Idéal pour des besoins variables",
            "Arrêtez quand vous voulez",
        ],
        value: "flexible",
        popular: true,
    },
    {
        name: "Offre Sur Mesure",
        price: "Personnalisé",
        features: [
            "Solutions adaptées à vos besoins spécifiques",
            "Gestion de compte dédiée",
            "Support 24/7 premium",
        ],
        value: "custom",
    },
]);

const form = useForm({
    name: "",
    email: "",
    company_name: "",
    company_slug: "",
    password: "",
    password_confirmation: "",
    forfait: getURLParams("forfait"),
});

const submit = () => {
    if (!form.forfait) {
        alert("Veuillez sélectionner un forfait avant de continuer.");
        return;
    }
    form.post(route("register"), {
        onFinish: () => form.reset(),
    });
};
</script>

<style scoped>
/* Amélioration visuelle */
body {
    background-color: #f8fafc;
}
</style>
