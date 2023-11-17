<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
      <section class="py-6 w-full max-w-xl">
        <!-- Header -->
        <header class="mb-10 text-center">
          <h1 class="text-2xl font-bold inline-flex items-center mb-2 space-x-2">
            <slot name="logo" />
          </h1>

          <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:px-10">
            <slot name="notice" />
          </h2>
        </header>
        <!-- END Header -->

        <slot />
      </section>
    </div>
</template>
<script setup lang="ts">
</script>