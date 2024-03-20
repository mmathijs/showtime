<script setup>

const props = defineProps({
    days: {
        type: Array,
        required: true
    },
});

const currentDay = defineModel();

</script>

<template>
    <div class=" w-full flex justify-center gap-4 rounded-xl overflow-hidden">
        <div class=" w-full flex justify-center gap-4">
            <button v-for="day in days" :key="day" @click="currentDay = day.id"
                    class="w-full"
                    :class="`px-4 py-2 rounded-lg ${currentDay === day.id ? 'bg-blue-500 text-white' : 'bg-white text-blue-500'} border border-blue-500`">
                {{ day.name }}
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
