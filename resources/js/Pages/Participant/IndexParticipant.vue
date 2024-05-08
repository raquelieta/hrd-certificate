<script setup>
import axios from 'axios';
import helpers from '@/helper.js';
import {  ref, computed, onMounted, watch } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import ToastPlugin from 'vue-toast-notification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps(['training','training_id']);

const participantLoading = ref(false);

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
</script>
<template>
    <AuthenticatedLayout>
        <div class="p-6 justify-center items-center mb-2">
            <div class="w-full">
                <div class="flex">
                    <div class="absolute right-0 mr-5">
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

            <table>
                
            </table>
        </div>
    </AuthenticatedLayout>
    
</template>