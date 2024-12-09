<script setup>

import LandingLayout from "@/Layouts/LandingLayout.vue";
import {reactive, ref} from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    user: {
        type: Object,
        default: null,
        required: false
    },
    team: {
        type: Object,
        default: null,
        required: false
    }
})

const form = reactive({
    name: '',
    email: '',
    company: '',
    phone: '',
    subject: '',
    message: ''
})

const errors = ref(null);
const success = ref(false);
const failed = ref(false);

function submit() {
    failed.value = false
    success.value = false
    errors.value = null
    axios.post(route('landing.contact.create'), form).then((response) => {
        success.value = true
    }).catch((error) => {
        failed.value = true
        errors.value = error.response.data.errors
        console.log(errors.value)
    })
}

</script>

<template>

    {{ user }}
    <LandingLayout v-if="user == null" title="Contact">
        <div class="py-12">
            <div class="min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center ">
                <div class="p-4 mx-auto max-w-xl bg-white font-[sans-serif]">
                    <h1 class="text-3xl text-gray-800 font-extrabold text-center">Contactez-nous</h1>
                    <div class="py-4 flex justify-center" v-if="failed">
                        <div class="text-red-500">
                            <p v-for="error in errors" :key="error">{{ error[0] }}</p>
                        </div>
                    </div>
                    <div class="py-4 flex justify-center" v-if="success">
                        <div class="text-green-500">
                            <p>Message envoyé</p>
                        </div>
                    </div>
                    <form class="mt-6" @submit.prevent="submit">
                        <input type='text' placeholder='Nom'
                               v-model="form.name"
                               class="w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                                required />
                        <input type='email' placeholder='Email'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.email"
                                required />
                        <input type='text' placeholder='Entreprise'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.company"
                        />
                        <input type='tel' placeholder='Téléphone'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.phone"
                                required />
                        <input type='text' placeholder='Sujet'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.subject"
                               required />
                        <textarea placeholder='Message' rows="6"
                                  v-model="form.message"
                                  class="mt-4 w-full rounded-md px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm pt-3 outline-blue-500">
                        </textarea>
                        <button type='submit' :disabled="form.processing"
                                class="mt-6 text-white bg-blue-500 hover:bg-blue-600 tracking-wide rounded-md text-sm px-4 py-3 w-full">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </LandingLayout>
    <AppLayout v-else title="Contact">
        <div class="py-12">
            <div class="min-h-screen max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center ">
                <div class="p-4 mx-auto max-w-xl bg-white font-[sans-serif]">
                    <h1 class="text-3xl text-gray-800 font-extrabold text-center">Contactez-nous</h1>
                    <div class="py-4 flex justify-center" v-if="failed">
                        <div class="text-red-500">
                            <p v-for="error in errors" :key="error">{{ error[0] }}</p>
                        </div>
                    </div>
                    <div class="py-4 flex justify-center" v-if="success">
                        <div class="text-green-500">
                            <p>Message envoyé</p>
                        </div>
                    </div>
                    <form class="mt-6" @submit.prevent="submit">
                        <input type='text' placeholder='Nom'
                               v-model="form.name"
                               class="w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               required />
                        <input type='email' placeholder='Email'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.email"
                               required />
                        <input type='text' placeholder='Entreprise'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.company"
                        />
                        <input type='tel' placeholder='Téléphone'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.phone"
                               required />
                        <input type='text' placeholder='Sujet'
                               class="mt-4 w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500"
                               v-model="form.subject"
                               required />
                        <textarea placeholder='Message' rows="6"
                                  v-model="form.message"
                                  class="mt-4 w-full rounded-md px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm pt-3 outline-blue-500">
                        </textarea>
                        <button type='submit' :disabled="form.processing"
                                class="mt-6 text-white bg-blue-500 hover:bg-blue-600 tracking-wide rounded-md text-sm px-4 py-3 w-full">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
