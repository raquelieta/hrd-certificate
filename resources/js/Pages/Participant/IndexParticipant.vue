<script setup>
import axios from 'axios';
import helpers from '@/helper.js';
import {  ref, computed, onMounted, watch } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import ToastPlugin from 'vue-toast-notification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps(['participants','training_id','message','participants']);

const participantLoading = ref(false);
const searchParticipant = ref('');
const showUpload = ref(false);
const pdfIframe = ref(null);

const form = useForm({
    file:null,
    training_id:props.training_id,
})

const uploadFile = async() => {
    if(form.file == null){
        alert("No file uploaded")
    }
    else{
        participantLoading.value = true;

        event.preventDefault();
        const formData = new FormData();
        formData.append('file',form.file);
        formData.append('training_id',form.training_id);

        new Promise((resolve, reject) => {
            form.post(route('participant.store'),formData,{
                forceFormData:true,
                preserveScroll:true,
                headers:{'Content-Type': 'multipart/form-data'},
                onSuccess:(response) => {
                    toast.success("Data Imported Successfully",{
                        timeout:2000,
                    });
                    console.log('upload successes')
                close();
                },
                onFinish: () => {
                    participantLoading.value = false;
                    
                },
                
            })
        })
    }
    
    form.reset('file');
    showModalComp.value.participant.import = false;
    participantLoading.value = false;

    
};

const printCertificate = (index) => {
    axios.post(route('certificate.store'),{training_id:index})
    .then(response => {
        const pdfBlob = new Blob([response.data], {type: 'application/pdf'});
        const pdfUrl = URL.createObjectURL(pdfBlob);
        pdfIframe.value.src = pdfUrl;
    })
    .catch(error => {
        console.error('error:', error)
    });
}

const showImport = () => {
    showUpload.value = !showUpload.value;
}

const filteredData = computed( () => props.participants.filter(participants => {
    const fullName = `${participants.full_name}`.toLowerCase();
    return fullName.includes(searchParticipant.value.toLocaleLowerCase());
}));

</script>
<template>
    <AuthenticatedLayout>
        <div>
            <div class="p-6 justify-center items-center mb-5">
                <div class="w-full">
                    <div class="flex">
                        <div class="absolute right-0 mr-1">
                            <button class="cursor-pointer text-xs float-right hover:bg-green-600 hover:text-white rounded-full bg-green-400 px-5 py-2 font-semibold inline-flex items-center mr-2" @click="showImport()">
                                Import file
                            </button>
                            <button class="text-xs float-right rounded-full bg-yellow-300 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2" @click="printCertificate(props.training_id)">
                                Print Certificate
                            </button>

                            <div v-if="showUpload" class="h-10 py-2 justify-center items-center mb-5">
                                <form @submit.prevent = "uploadFile()" enctype="multipart/form-data">
                                    <div class="mr-4">
                                        <input type="file" class=" mb-3 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400" ref="fileInput" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/csv" name="fileInput" @change="getFile()" @input="form.file = $event.target.files[0]">
                                        <button type="submit" class="cursor-pointer text-xs rounded-full bg-green-400 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2">
                                            Upload file
                                        </button>
                                        
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="py-6 h-screen">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 dark:bg-gray-100 shadow-sm sm:rounded-lg -mx-4 sm:-mx-6 md:mx-0">
                    <div v-if="props.participants.length != 0" class="flex-none min-w-full p-6 text-gray-100 dark:text-gray-900 h-4/6 overflow-auto max-h-screen lg:supports-scrollbars:pr-2 lg:max-h-auto">
                        <table class="table-fixed border border-slate-500 border-spacing-0 border-separate w-full">
                            <thead class="bg-gray-200 border-b-2 border-gray-200 text-gray-800 sticky sm:sticky lg:-top-6 sm:-top-6">
                                <tr>
                                    <th class="p-3">Participant Name</th>
                                    <th class="p-3">Agency</th>
                                    <th class="p-3">Province</th>
                                    <th class="p-3">Sex</th>
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="participant in filteredData" class="hover:bg-gray-400 hover:text-white">
                                    <td class="p-3 whitespace-nowrap uppercase">{{ participant.full_name }}</td>
                                    <td class="p-2 uppercase">{{ participant.agency_name }}</td>
                                    <td class="p-2 uppercase text-center items-center"> {{ participant.province }}</td>
                                    <td class="p-2 uppercase text-center items-center">{{ participant.sex }}</td>
                                    <td>
                                        <button class="text-xs rounded-full bg-blue-300 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2" @click="updatePayment(index)">
                                            Send Certificate
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <iframe :ref="pdfIframe" style="width: 100%; height: 500px;"></iframe>
    </AuthenticatedLayout>
    
</template>