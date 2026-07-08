<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const flash = page.props?.flash || {};

const show = ref(false);
const type = ref('success');
const message = ref('');

watch(
    () => flash,
    (newVal) => {
        if (newVal.success) {
            type.value = 'success';
            message.value = newVal.success;
            show.value = true;
            setTimeout(() => show.value = false, 5000);
        } else if (newVal.error) {
            type.value = 'error';
            message.value = newVal.error;
            show.value = true;
            setTimeout(() => show.value = false, 5000);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <div v-if="show" :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
    }" class="fixed top-4 right-4 z-50 text-white px-6 py-3 rounded-lg shadow-lg transition-all">
        {{ message }}
    </div>
</template>