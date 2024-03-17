<script setup>
import {ref} from "vue";

const props = defineProps({
    act: {
        type: Object,
        required: true
    }, currentAct: {
        type: Object,
        required: true
    }
});

const open = ref(false);

</script>

<template>
    <!-- A three dots menu        -->
    <div class="relative" @click="open = !open">
        <div class="w-6 h-6">
            <!-- Three dots above eac other svg            -->
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                ></path>
            </svg>
        </div>
        <div
            v-show="open"
            class="absolute right-2 z-50 top-6 bg-gray-900 text-gray-300 shadow-lg rounded-lg p-2"
        >
            <div class="text-sm text-right">
                <a href="#" class="block p-1">Edit</a>
                <a href="#" class="block p-1">Delete</a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
