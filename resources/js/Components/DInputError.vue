<script setup>
defineProps({
  message: {
    type: String,
    required: false,
    default: undefined
  }
})
</script>

<template>

    <p v-show="message" class="text-sm text-red-600 dark:text-red-400">
      {{ message }}
    </p>

</template>
