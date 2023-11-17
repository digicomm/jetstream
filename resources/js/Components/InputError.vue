<script setup>
defineProps({
    formMessage: String,
  vMessages: Array,
});
</script>

<template>
  <div v-if="formMessage">
    <p class="text-sm text-red-600 dark:text-red-400">
      {{ formMessage }}
    </p>
  </div>
  <div v-for="(error, index) in vMessages" :key="index">
    <p class="text-sm text-red-600 dark:text-red-400">
      {{ error.$message }}
    </p>
  </div>

</template>
