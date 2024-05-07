<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import {ref, computed, watch, nextTick} from 'vue';
import BlueButton from '@/Components/BlueButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import helpers from '@/helper.js';

const {assign, toast} = helpers();
const emit = defineEmits(['close']);
const speakerInput = ref('');
const speakersRef = ref([]);
//Props
const props = defineProps({
    modal:{
        type:Object,
        default:{
            add:false,
            method:'add',
            data:null,
        }
    }
});

//Forms
const form = useForm({
    training_name:'',
    description:'',
    training_start:null,
    training_end:null,
    training_hours:0,
    training_type:{},
    training_venue:{},
    training_address:'',
    training_speakers:[],
    issuance_date:null,
    director_id:null,
    hr_head_id:null,
});

const AddEditTraining = () => {
    form.director_id = props.modal.signatories[0].id;
    form.hr_head_id = props.modal.signatories[1].id;

    form.post(route('training.store'),{
        preserveScroll:true,
        onSuccess: () => {
            toast.success('New Training Added',{
                timeout:2000
            });
            
            close();
        }
    })
}

const close = () => {
    form.reset();
    emit('close')
}
const trainingType = ref([
    {index:0, value:"Leadership"},
    {index:1, value:"HRM"},
]);

const trainingVenue = ref([
    {index:0, value:"Online"},
    {index:1, value:"Face-to-Face"},
    {index:2, value:"Hybrid Set-up"},
    {index:3, value:"Agency Requested"},
]);

//Watchers
watch(speakerInput, async(value) => {
    if(value !== ''){
        form.training_speakers.push({value:value, index:form.training_speakers.length, topic:'', hours:0});
        await nextTick(() => {
            const lastIndex = form.training_speakers.length-1;
            speakersRef.value[lastIndex].focus();
            speakerInput.value = '';
        })
    }
});

//Computed
const getTotalTrainingHours = computed( () => {
    //Calcute the number of days between the start and end dates using (end - start) / (1000 * 60 * 60 * 24). 
    //By adding 1 to the result, it accounts for inclusive counting of both the start and end dates.

    // Then, multiply the number of days by 8 to get the total hours, assuming 8 hours of work per day.
    if (form.training_start && form.training_end) {
        const start = new Date(form.training_start);
        const end = new Date(form.training_end);
        
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        const hours = days * 8;
        return hours;
    }
    else{
        return form.training_hours;
    }
});

const dropdownStyle = "inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50";

</script>

<template>
    <Modal :show="modal.add" @close="close()">
        <div class="relative w-full max-w-4xl max-h-full overflow-y-auto scroll-smooth">
            <div class="p-6 space-y-6">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="training_name" value="Training Name" />

                        <TextInput
                            id="training_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.training_name"
                            required
                            
                            autocomplete="training_name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="training_desc" value="Description" />

                        <TextInput
                            id="training_desc"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.description"                                
                            autocomplete=""
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid grid-cols-6 gap-1 mt-4">
                        <div class="col-span-3">
                            <InputLabel for="training_date" value="Start of Training" />

                            <div>
                                <input :class="dropdownStyle" type="date" v-model="form.training_start"/>
                            </div>

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="training_date" value="End of Training" />

                            <div>
                                <input :class="dropdownStyle" type="date" v-model="form.training_end"/>
                            </div>

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-1 mt-4">
                        <div class="col-span-3">
                            <InputLabel for="training_hours" value="Training Hours" />

                            <div>
                                <input :class="dropdownStyle" type="number" disabled v-model="getTotalTrainingHours"/>
                            </div>

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="issuance_date" value="Date of Issuance" />

                            <div>
                                <input :class="dropdownStyle" type="date" v-model="form.issuance_date"/>
                            </div>

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-1 mt-3">

                        <div class="col-span-3">
                            <InputLabel for="training_type" value="Training Type" />
                                <select :class="dropdownStyle" name="status" id="p_training_type" v-model="form.training_type">
                                    <option v-for="(type,index) in trainingType" :value=type :key="type.index">{{ type.value }}</option>
                                </select>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="col-span-3">
                            <InputLabel for="training_type" value="Training Venue Type" />
                                <select :class="dropdownStyle" name="status" id="p_training_type" v-model="form.training_venue">
                                    <option v-for="(venue,index) in trainingVenue" :value="venue" :key="venue.index">{{venue.value}}</option>
                                </select>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="training_address" value="Training Venue" class="inline-flex mr-2 mt-2"/>
                        <TextInput
                            id="training_address"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.training_address"
                            required
                            
                            autocomplete="on"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="training_speakers" value="Training Speakers" class="inline-flex mr-2 mt-2"/>
                        <div v-for="(speaker,index) in form.training_speakers">
                            <div class="grid grid-cols-6">
                                <div class="col-span-2">
                                    <TextInput
                                        type="text"
                                        placeholder="Enter speaker's name..."
                                        class="training_speaker_input mt-1 block w-full"
                                        v-model="form.training_speakers[index].value"
                                        required
                                        :ref="(el) => {speakersRef[index] = el }"
                                        autocomplete="training_name"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <TextInput
                                        id="training_speaker_topic"
                                        type="text"
                                        placeholder="RSS topic..."
                                        v-model="form.training_speakers[index].topic"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="training_name"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <TextInput
                                        id="training_speaker_hours"
                                        placeholder="RSS Hours..."
                                        v-model="form.training_speakers[index].hours"
                                        type="number"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="training_name"
                                    />
                                </div>
                            </div>
                            
                        </div>
                        <TextInput
                            type="text"
                            class="training_speaker_input mt-1 block w-full"
                            v-model="speakerInput"
                            required
                            autocomplete="training_name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <DangerButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="close()">
                            <InputLabel class="dark:text-gray-900 text-white">Cancel</InputLabel> 
                        </DangerButton>
                        <BlueButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="AddEditTraining()">
                            <InputLabel v-if="modal.method == 'add'" class="dark:text-gray-900 text-white">Create New Training</InputLabel> 
                            <InputLabel v-if="modal.method == 'update'" class="dark:text-gray-900 text-white">UPDATE</InputLabel>
                            <InputLabel v-if="modal.method == 'delete'" class="dark:text-gray-900 text-white">DELETE</InputLabel>  
                        </BlueButton>
                    </div>
                </form>
            </div>
        </div>
    </Modal>
</template>