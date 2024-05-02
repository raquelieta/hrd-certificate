<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {ref, computed} from 'vue';
import BlueButton from '@/Components/BlueButton.vue';
import CreateTrainingModal from '@/Components/modal/CreateTrainingModal.vue';
import helpers from '@/helper.js'

const {assign, moment } = helpers();
const props = defineProps(['trainings','signatories']);

const showModalComp = ref({
    training:{
        add:false,
        method:'add',
        data:null,
        signatories:props.signatories,
    }
})

const showModal = (method, index=-1) => {
    
    if(props.signatories.length > 0){
        showModalComp.value.training.add = true;
        showModalComp.value.training.method = method;
        if(index >=0){
            showModalComp.value.training.data = assign(props.trainings[index]);

        }
        else{
            showModalComp.value.training.data = null;
            showModalComp.value.training.signatories = props.signatories;
        }

        return showModalComp;
    }
    else{
        alert("Please add signatories first");
    }
}

const closeModal = (modal) => {
    showModalComp.value.training[modal] = false;
    showModalComp.value.training.method ='';
    showModalComp.value.training.data = null;
}

const trainingTitle = computed( () => props.trainings.map((training,index) => {
    {
        let desc = training.training_name.toString();
        if(training.training_name.length > 20){
            
            return training.training_name.substring(0,20) + "...";
        }
        else{
            return training.description;
        }
    }
}));

const trainingDesc = computed( () => props.trainings.map((training,index) => {
    {
        let desc = training.description.toString();
        if(training.description.length > 30){
            
            return training.description.substring(0,20) + "...";
        }
        else{
            return training.description;
        }
    }
}));

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template> -->

        <div class="flex p-6">
            <div class="absolute right-5 mr-3">
                <div class="text-gray-900 float-right">
                    <BlueButton @click="showModal('add')"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Create Training
                    </BlueButton> 
                </div>
            </div>
            
        </div>
        

        <div class="py-12 w-full">
            <div class="max-w-screen mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 h-full overflow-auto" v-if="props.trainings.length > 0">
                        <table class="table-fixed border border-slate-500 border-spacing-0 border-separate w-full">
                            <thead class="bg-gray-200 border-b-2 border-gray-200 text-gray-800">
                                <tr>
                                    <th class="p-3">Training Name</th>
                                    <th class="p-3">Description</th>
                                    <th class="p-3">Date Created</th>
                                    <th class="p-3">Training Date</th>
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>

                            <tbody class="">
                                <tr v-for="(training, index) in props.trainings" 
                                    class="even:bg-gray-200 even:text-gray-900 dark:odd:bg-white-100 bg-gray-700 text-white even:hover:bg-slate-600 
                                    even:hover:text-gray-200 odd:hover:text-gray-600 odd:hover:bg-white">
                                    <td class="p-3 whitespace-nowrap">
                                        <Link class="px-6 py-4 item-center" :href="route('participant.index',training.id)" :data="{id:training.id, name:training.training_name}">
                                            {{ trainingTitle[index] }}
                                        </Link>
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-center">{{ trainingDesc[index] }}</td>
                                    <td class="p-3 whitespace-nowrap text-center">{{ moment(new Date(training.updated_at)).format('MM/DD/YY LT') }}</td>
                                    <td class="p-3 whitespace-nowrap text-center">{{ moment(new Date(training.training_start)).format('MM/DD/YYYY') }} - {{ moment(new Date(training.training_end)).format('MM/DD/YYYY') }}</td>
                                    <td>
                                        <div class="flex flex-col-items-center justify-center">
                                            <button class="text-xs float-right rounded-full bg-blue-400 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2" @click="showModal('update',index)">
                                                Manage
                                            </button>
                                            <button class="text-xs float-right rounded-full bg-red-400 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2" @click="showModal('delete',index)">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <CreateTrainingModal :modal="showModalComp.training" @close="closeModal('add')"></CreateTrainingModal>
    </AuthenticatedLayout>
</template>
