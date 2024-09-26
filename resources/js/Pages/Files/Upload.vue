<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const props = defineProps({
    team: Object
});

const isOver = ref(false);
const files = ref([]);
const fileInput = ref(null);
const uploadProgress = reactive({});
const uploadStatus = ref('');
const validationErrors = ref({}); // Pour les erreurs de validation

const dragOver = () => {
    isOver.value = true;
};

const dragLeave = () => {
    isOver.value = false;
};

const dropFiles = (event) => {
    isOver.value = false;
    handleFiles(event.dataTransfer.files);
};

const selectFiles = () => {
    fileInput.value.click();
};

const filesSelected = (event) => {
    handleFiles(event.target.files);
};

const handleFiles = (selectedFiles) => {
    for (let file of selectedFiles) {
        files.value.push({
            file: file,
            name: file.name,
            size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
            preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
            progress: 0,
        });
    }
};

const removeFile = (index) => {
    const fileWrapper = files.value[index];

    // Si une prévisualisation a été créée, on révoque l'URL pour libérer la mémoire
    if (fileWrapper.preview) {
        URL.revokeObjectURL(fileWrapper.preview);
    }

    files.value.splice(index, 1);
};

const uploadFiles = () => {
    uploadStatus.value = '';
    validationErrors.value = {};
    const formData = new FormData();

    files.value.forEach(fileWrapper => {
        formData.append('files[]', fileWrapper.file);
    });

    axios.post(route("admin.files.store", { team: props.team.id }), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        onUploadProgress: (progressEvent) => {
            const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            // Mettre à jour la progression globale si nécessaire
        }
    })
        .then(response => {
            uploadStatus.value = 'Fichiers téléchargés avec succès';
            // Réinitialiser les fichiers après l'upload
            files.value.forEach(fileWrapper => {
                if (fileWrapper.preview) {
                    URL.revokeObjectURL(fileWrapper.preview);
                }
            });
            files.value = [];
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                // Erreurs de validation
                validationErrors.value = error.response.data.errors;
                uploadStatus.value = 'Erreur lors du téléchargement des fichiers';
            } else {
                uploadStatus.value = 'Une erreur est survenue lors du téléchargement';
                console.error(error);
            }
        });
};
</script>

<template>
    <div class="max-w-full mx-auto p-4 font-sans">
        <div class="">
            <h3>Ajouter de nouveaux fichiers (pdf) (max. 50Mo) : </h3>
            <!-- Zone de dépôt -->
            <div
                @dragover.prevent="dragOver"
                @dragleave.prevent="dragLeave"
                @drop.prevent="dropFiles"
                @click="selectFiles"
                :class="[
                    'border-2 border-dashed rounded-lg min-h-[150px] text-center p-4 md:p-5 flex items-center justify-center cursor-pointer transition-colors duration-300',
                    isOver ? 'border-gray-600 text-gray-600' : 'border-gray-300 text-gray-500',
                    'text-sm md:text-base'
                ]"
            >
                <p>Glissez-déposez vos fichiers ici ou cliquez pour sélectionner</p>
                <input type="file" multiple ref="fileInput" class="hidden" @change="filesSelected">
            </div>

            <!-- Liste des fichiers -->
            <div class="mt-5" v-if="files.length">
                <div class="flex flex-col items-start mb-4 md:flex-row md:items-center"
                     v-for="(fileWrapper, index) in files" :key="index">
                    <div class="flex items-center w-full">
                        <div v-if="fileWrapper.preview">
                            <img :src="fileWrapper.preview" alt="Image Preview"
                                 class="w-12 h-12 object-cover rounded mb-2.5 mr-4">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold m-0 break-words text-sm md:text-base">{{ fileWrapper.name }}</p>
                            <p class="my-1 text-gray-600 text-sm md:text-base">{{ fileWrapper.size }}</p>
                            <div class="bg-gray-200 rounded overflow-hidden h-2.5" v-if="fileWrapper.progress > 0">
                                <div class="bg-green-500 h-full" :style="{ width: fileWrapper.progress + '%' }"></div>
                            </div>
                        </div>
                        <!-- Bouton de suppression -->
                        <button @click="removeFile(index)" class="text-red-600 hover:text-red-800 ml-4">
                            Supprimer
                        </button>
                    </div>
                    <!-- Afficher l'erreur de validation pour ce fichier -->
                    <div v-if="validationErrors[`files.${index}`]" class="text-red-600 mt-2">
                        <span v-for="error in validationErrors[`files.${index}`]" :key="error">
                            {{ error }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Affichage des erreurs de validation générales -->
            <div v-if="Object.keys(validationErrors).length > 0" class="mt-5 text-red-600">
                <ul>
                    <li v-for="(errors, field) in validationErrors" :key="field">
                        <span v-for="error in errors">{{ error }}</span>
                    </li>
                </ul>
            </div>

            <!-- Bouton d'upload -->
            <button
                class="w-full py-2.5 bg-blue-500 text-white rounded mt-5 text-base md:text-lg cursor-pointer hover:bg-blue-700"
                @click="uploadFiles"
                v-if="files.length"
            >
                Uploader les fichiers
            </button>

            <!-- Message de statut -->
            <p class="text-center mt-5 font-bold text-green-600" v-if="uploadStatus">{{ uploadStatus }}</p>
        </div>
    </div>
</template>
