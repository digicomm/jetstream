<script setup>

</script>

<template>
  <div class="flex flex-col overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800 dark:text-gray-100">
    <div class="grow p-0 md:flex">
      <div
          class="border-b dark:border-gray-700 md:mb-0 p-5 lg:p-8 md:w-1/3 md:flex-none md:border-0">
        <h3 class="mb-1 flex items-center justify-start space-x-2 font-semibold">
          <slot name="icon"/>
          <span><slot name="title"/></span>
        </h3>
        <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
          <slot name="description"/>
        </p>
      </div>
      <div class="md:w-2/3 p-5 p-5 lg:p-8">
        <slot name="content"/>
      </div>
    </div>
  </div>
</template>
