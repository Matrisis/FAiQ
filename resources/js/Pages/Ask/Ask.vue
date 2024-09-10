<script setup>
import {onMounted, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import ActionSection from "@/Components/ActionSection.vue";
import MarkdownRenderer from "@/Components/Markdown.vue";

const props = defineProps({
    channel: String,
    team: Object,


    background_color: String,
})

const form = useForm({
    question: '',
});

const answer = ref('Error: Call to undefined method App\\Services\\ChattingService::steam() in /var/www/html/app/Jobs/AskStreamJob.php:40\n' +
    'Stack trace:\n' +
    '#0 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\AskStreamJob->handle()\n' +
    '#1 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n' +
    '#2 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n' +
    '#3 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n' +
    '#4 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php(690): Illuminate\\Container\\BoundMethod::call()\n' +
    '#5 /var/www/html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n' +
    '#6 /var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n' +
    '#7 /var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n' +
    '#8 /var/www/html/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n' +
    '#9 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow()\n' +
    '#10 /var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n' +
    '#11 /var/www/html/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n' +
    '#12 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(123): Illuminate\\Pipeline\\Pipeline->then()\n' +
    '#13 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(71): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n' +
    '#14 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n' +
    '#15 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n' +
    '#16 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(389): Illuminate\\Queue\\Worker->process()\n' +
    '#17 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(176): Illuminate\\Queue\\Worker->runJob()\n' +
    '#18 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(139): Illuminate\\Queue\\Worker->daemon()\n' +
    '#19 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(122): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n' +
    '#20 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n' +
    '#21 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n' +
    '#22 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure()\n' +
    '#23 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n' +
    '#24 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php(690): Illuminate\\Container\\BoundMethod::call()\n' +
    '#25 /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php(213): Illuminate\\Container\\Container->call()\n' +
    '#26 /var/www/html/vendor/symfony/console/Command/Command.php(279): Illuminate\\Console\\Command->execute()\n' +
    '#27 /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Symfony\\Component\\Console\\Command\\Command->run()\n' +
    '#28 /var/www/html/vendor/symfony/console/Application.php(1047): Illuminate\\Console\\Command->run()\n' +
    '#29 /var/www/html/vendor/symfony/console/Application.php(316): Symfony\\Component\\Console\\Application->doRunCommand()\n' +
    '#30 /var/www/html/vendor/symfony/console/Application.php(167): Symfony\\Component\\Console\\Application->doRun()\n' +
    '#31 /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n' +
    '#32 /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1203): Illuminate\\Foundation\\Console\\Kernel->handle()\n' +
    '#33 /var/www/html/artisan(13): Illuminate\\Foundation\\Application->handleCommand()\n' +
    '#34 {main}');

const asking = ref(false);
const asked_question = ref('');

onMounted(() => {
    Echo.private(`ask.${props.channel}`)
        .listen('Ask', (event) => {
            asking.value = false
            if (answer.value === null) {
                answer.value = event.answer.answer
            } else {
                props.asked_question = form.question
                answer.value = answer.value + event.answer.answer
            }
        })
})

const sendquestion = () => {
    answer.value = ''
    axios.post(route('public.ask.create', {team: props.team.id}), {
        question: form.question,
        channel: props.channel
    }).then(() => {
        asking.value = true
    }).catch((error) => {
        form.errors = error.response.data.errors;
    })
};
</script>

<template>
    <div class="custom-background-color">
        <div class="w-3/4 mx-auto py-10">
            <label class="flex flex-col-reverse relative focus group">
                <TextInput
                    ref="question"
                    v-model="form.question"
                    type="text"
                    class="border-2 border-black leading-9"
                    @keyup.enter="sendquestion"
                />

                <span class="absolute text-xl transform -translate-y-3 left-4 transition leading-10 group-focus-within:-translate-y-16">
                    Question :
                </span>
                <span class="ml-auto leading-10">* Required</span>
            </label>
        </div>
    </div>

    <div class="mt-4 w-3/4 mx-auto">
        <div v-if="asking" class="bg-white p-4">
            <p>Asking...</p>
        </div>
        <div v-if="answer !== ''" class="bg-white p-4">
            <fieldset>
                <legend v-if="asked_question">{{ asked_question }}</legend>
                <MarkdownRenderer :source="answer"/>
            </fieldset>
        </div>
        <div v-if="answer" class="text-red bg-white p-4">
            <div v-for="error in form.errors">
                <p>{{ error }}</p>
            </div>
        </div>
    </div>

</template>

<style scoped>
.custom-background-color {
    background-color: v-bind(background_color);
}

#text-color {
    color: v-bind(text_color);
}
</style>
