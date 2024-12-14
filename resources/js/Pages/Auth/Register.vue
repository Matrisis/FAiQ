<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import LandingLayout from "@/Layouts/LandingLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";

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
        pricing_id: 1,
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
        pricing_id: 2,
        popular: true,
    },
]);

const form = useForm({
    name: "",
    email: "",
    company_name: "",
    company_slug: "",
    password: "",
    password_confirmation: "",
    pricing_id: getURLParams("forfait"),
});

const submit = () => {
    if (!form.pricing_id) {
        alert("Veuillez sélectionner un forfait avant de continuer.");
        return;
    }
    form.post(route("register"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <LandingLayout title="Créez Votre Compte">
    <div class="min-h-screen bg-gradient-to-r from-blue-100 via-white to-blue-100 flex items-center justify-center py-12 px-6">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-5xl">
            <!-- En-tête -->
            <div class="bg-blue-500 text-white text-center py-8">
                <h1 class="text-3xl font-extrabold">Créez Votre Compte</h1>
                <p class="text-lg mt-2">Remplissez le formulaire ci-dessous pour commencer</p>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submit" class="flex flex-col justify-center lg:flex-row p-6 lg:flex-wrap lg:p-8 space-y-6 lg:space-y-0 lg:space-x-6">
                <!-- Colonne gauche -->
                <div class="flex-1 space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nom complet</label>
                        <input
                            required
                            autofocus
                            autocomplete="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Votre nom complet"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.password">{{ form.errors.name }}</div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input
                            required
                            autocomplete="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Votre email"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Mot de passe</label>
                        <input
                            v-model="form.password"
                            required
                            type="password"
                            placeholder="Créez un mot de passe"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.password">{{ form.errors.password }}</div>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="flex-1 space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nom de l'entreprise</label>
                        <input
                            required
                            autocomplete="organization"
                            v-model="form.company_name"
                            type="text"
                            placeholder="Nom de l'entreprise"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.company_name">{{ form.errors.company_name }}</div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Slug de l'entreprise</label>
                        <input
                            required
                            autocomplete="organization"
                            v-model="form.company_slug"
                            type="text"
                            placeholder="Slug de l'entreprise"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.company_slug">{{ form.errors.company_slug }}</div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirmez le mot de passe</label>
                        <input
                            required
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Confirmez le mot de passe"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        />
                        <div class="text-red-600 text-sm" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</div>
                    </div>
                </div>

                <!-- Sélecteur de pricings -->
                <div class="flex flex-col items-center p-8">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Choisissez un forfait</h2>
                    <div class="w-full text-center text-red-600 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
                    <div class="flex flex-col lg:flex-row flex-wrap justify-center items-center">
                        <div
                            v-for="plan in plans"
                            :key="plan.pricing_id"
                            @click="form.pricing_id = plan.pricing_id"
                            :class="[
                              'flex flex-col  w-full mt-4 lg:mt-0 h-64 flex-1 items-center bg-white border rounded-lg p-6 cursor-pointer transition duration-300 ease-in-out lg:ml-2 lg:mr-2',
                              plan.pricing_id == form.pricing_id ? 'border-blue-500 shadow-lg' : 'border-gray-200 hover:shadow-md',
                            ]">
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
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <InputLabel for="terms">
                            <div class="flex items-center">
                                <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                                <div class="ms-2">
                                    I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Privacy Policy</a>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.terms" />
                        </InputLabel>
                    </div>
                    <div class="mt-8 text-center">
                        <button
                            type="submit"
                            class="px-6 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg shadow-lg hover:bg-blue-700 transition cursor-pointer"
                            :disabled="!form.pricing_id"
                        >
                            Valider votre inscription
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </LandingLayout>
</template>

<style scoped>
/* Amélioration visuelle */
body {
    background-color: #f8fafc;
}
</style>
