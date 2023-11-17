<script setup>
defineProps({
  title: {
    type: String,
    required: false,
    default: '',
  },
  subtitle: {
    type: String,
    required: false,
    default: undefined,
  },
  singleLine: Boolean,
  icon: {
    type: Array,
    required: false,
    default: undefined,
  }
})
</script>

<template>
  <!-- Page Heading -->
  <div
      class="text-center sm:text-left sm:flex sm:items-center sm:justify-between sm:border-b-2 sm:border-gray-200 mb-1 dark:border-gray-700">

    <h2 v-if="singleLine" class="text-2xl font-bold py-2">
      <span class="inline-flex items-center space-x-2">
      <font-awesome-icon v-if="icon" :icon="icon" class="inline-block w-6 h-6 opacity-50"/>
      <span>{{ title }}</span>
      <span v-if="subtitle"
            class="block font-medium text-lg text-gray-600 dark:text-gray-400">{{ subtitle }}</span>
      </span>
    </h2>

    <h2 v-else class="text-2xl font-bold py-2">
      <span class="items-center space-x-2">
        <font-awesome-icon v-if="icon" :icon="icon" class="inline-block w-6 h-6 opacity-50"/>
      <span>{{ title }}</span>
      <span v-if="subtitle" class="block font-medium text-lg text-gray-600 dark:text-gray-400">{{ subtitle }}</span>
      </span>
    </h2>


    <!-- Actions -->
    <div class="flex items-center justify-center sm:justify-end space-x-2 py-3 rounded px-2 sm:px-0">
      <slot v-if="$slots.actions" name="actions"></slot>
    </div>
    <!-- END Actions -->
  </div>
  <!-- END Page Heading -->
</template>

<style scoped>

</style>