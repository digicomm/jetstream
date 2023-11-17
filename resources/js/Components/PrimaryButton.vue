<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-900 focus:ring focus:ring-blue-400 focus:ring-opacity-50 dark:focus:ring-blue-400 dark:focus:ring-opacity-90 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
