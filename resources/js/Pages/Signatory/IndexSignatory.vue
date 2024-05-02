<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BlueButton from '@/Components/BlueButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import helpers from '@/helper.js';
import {  ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3'
import ToastPlugin from 'vue-toast-notification';

const {assign, moment} = helpers();
const props = defineProps(['signatories']);
const showAddEl = ref(false);

const form = useForm({
    last_name:'',
    first_name:'',
    middle_initial:'',
    position:'',
    designation:'',
    is_attested:false,
    is_approved:false,
    is_msd:false,
    signatoryType:null,
});

const showAdd = () => {
    showAddEl.value = !showAddEl.value;
}

const addSignatory = () =>{
    form.post(route('signatory.store'),{
        onSuccess: () => {
            form.reset();
            toast.success('New Signatory added',{
                timeout:2000
            });
        }
    })
}

const signatory = ref ([
    {index: 0, value:'Approval'},
    {index: 1, value:'Attest'},
    {index: 2, value:'Admin Officer'},
]);

//style
const dropdownStyle = "inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50";
</script>


<template>
    <AuthenticatedLayout>
        <div>
            <div class="p-6 text-gray-900 float-right">
                <BlueButton @click="showAdd()"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Add Signatory
                </BlueButton> 
            </div>
        </div>
        

        <div v-show="showAddEl" class="text-center py-6">
            
            <form @submit.prevent="addSignatory">
                <div>
                    <div class="relative w-full max-w-screen max-h-screen scroll-smooth mt-4">
                        <p class="font-bold text-md">ADD SIGNATORY</p>
                        <div class="p-12 flex w-full items-center  justify-center">
                            <div class="w-96 mr-5">
                                <InputLabel for="last_name" value="Last Name: " class="w-24 mr-1 font-semibold uppercase"/>

                                <TextInput 
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.last_name"
                                    required
                                    autofocus
                                    autocomplete="last name"
                                />

                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>
                            
                            <div class="w-96 mr-5">
                                <InputLabel for="first_name" value="First Name: " class="w-24 mr-1 font-semibold uppercase"/>

                                <TextInput 
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.first_name"
                                    required
                                    autofocus
                                    autocomplete="first name"
                                />

                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>

                            <div class="w-60">
                                <InputLabel for="middle_initial" value="Middle Initial: " class="w-32 mr-1 font-semibold uppercase"/>

                                <TextInput 
                                    id="middle_initial"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.middle_initial"
                                    required
                                    autofocus
                                    autocomplete="first name"
                                />

                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>
                           
                        </div>

                        <!-- Position -->
                        <div class="flex w-full items-center  justify-center">
                            <div class="w-96 mr-5">
                                <InputLabel for="position" value="Position: " class="w-24 mr-1 font-semibold uppercase"/>

                                <TextInput 
                                    id="position"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.position"
                                    required
                                    autofocus
                                    autocomplete="position"
                                />

                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>
                            
                            <div class="w-96 mr-5">
                                <InputLabel for="designation" value="Designation: " class="w-24 mr-1 font-semibold uppercase"/>

                                <TextInput 
                                    id="designation"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.designation"
                                    required
                                    autofocus
                                    autocomplete="designation"
                                />

                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>

                            <div class="w-60">
                                <InputLabel for="training_desc" value="Special Power" />
                                <select :class="dropdownStyle" name="status" id="province" v-model="form.signatoryType">
                                    <option v-for="sig in signatory" :value="sig">{{ sig.value }}</option>
                                </select>
                            </div>
                           
                        </div>
                    </div>
                    
                </div>
                <div class="p-6 w-full float-right">
                    <BlueButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="addSignatory()">
                        <InputLabel class="text-white">Add New Signatory</InputLabel>
                    </BlueButton>
                </div>
            </form>
        </div>

        <div class="py-12 h-screen mt-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 h-full overflow-auto">
                        <p class="text-center mb-5 uppercase font-semibold">Signatories</p>
                        <table class="table-fixed border border-slate-500 border-spacing-0 border-separate w-full">
                            <thead class="border-b-2 border-gray-200 text-gray-800">
                                <tr>
                                    <th class="p-3">Employee Name</th>
                                    <th class="p-3">Position</th>
                                    <th class="p-3">Designation</th>
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>

                            <tbody class="">
                                <tr v-for="(signatory, index) in props.signatories" class="even:bg-gray-200 even:text-gray-900 
                                    dark:odd:bg-white-100 bg-gray-700 text-white even:hover:bg-slate-600 
                                    even:hover:text-gray-200 odd:hover:text-gray-600 odd:hover:bg-white">
                                    <td class="p-3 whitespace-nowrap text-center">
                                        {{signatory.last_name}}, {{ signatory.first_name }} {{ signatory.middle_initial }} 
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-center">{{ signatory.position }}</td>
                                    <td class="p-3 whitespace-nowrap text-center">{{ signatory.designation }}</td>
                                    <!-- <td class="p-3 whitespace-nowrap text-center">{{ moment(new Date(training.training_start)).format('MM/DD/YYYY') }} - {{ moment(new Date(training.training_end)).format('MM/DD/YYYY') }}</td> -->
                                    <td>
                                        <div class="flex flex-col-items-center justify-center">
                                            <button class="text-xs float-right rounded-full bg-blue-400 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2">
                                                Manage
                                            </button>
                                            <button class="text-xs float-right rounded-full bg-red-400 px-5 py-2 font-semibold inline-flex items-center mr-2 mb-2">
                                                Deactivate
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
    </AuthenticatedLayout>
    
    
</template>