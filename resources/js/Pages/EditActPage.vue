<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    act: {
        type: Object,
        required: true
    }
});

function updateAct() {
    axios.put(route('act.update', props.act.id), {
        name: props.act.name,
        type: props.act.type,
        display_type: props.act.display_type,
        start_time: props.act.start_time,
        people: props.act.people,
        description: props.act.description,
        material_required: props.act.material_required,
        hidden: props.act.hidden,
        day: props.act.day
    }).then(() => {
        router.visit(route('dashboard'));
    });
}

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Act</h2>
        </template>

        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-x-auto shadow-sm sm:rounded-lg">
                    <!-- Edit Act form, to edit the name, type, display_type, description, people and material_required                    -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                                <input type="text" name="name" id="name" v-model="act.name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type</label>
                                <input type="text" name="type" id="type" v-model="act.type"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="display_type"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Display
                                    Type</label>
                                <input type="text" name="display_type" id="display_type" v-model="act.display_type"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="start_time"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start
                                    Time</label>
                                <input type="text" name="start_time" id="start_time" v-model="act.start_time"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="people"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">People</label>
                                <input type="text" name="people" id="people" v-model="act.people"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="description"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                                <input type="text" name="description" id="description" v-model="act.description"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <label for="material_required"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Material
                                    Required</label>
                                <input type="text" name="material_required" id="material_required"
                                       v-model="act.material_required"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="hidden"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hidden</label>
                                <input type="text" name="hidden" id="hidden" v-model="act.hidden"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="day_id"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-200">Day</label>
                                <input type="text" name="day_id" id="day_id" v-model="act.day"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>

                            <!-- Save Button                            -->
                            <div class="col-span-6 sm:col-span-6">
                                <button type="submit"
                                        @click="updateAct"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
