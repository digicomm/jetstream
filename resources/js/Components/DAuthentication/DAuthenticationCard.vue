<script setup lang="ts">

</script>

<template>
  <!-- Pages: Sign In: Boxed -->
  <!-- Page Container -->
  <div id="page-container"
       class="flex flex-col mx-auto w-full min-h-screen min-w-[320px] bg-gray-100 dark:text-gray-100 dark:bg-gray-900">
    <!-- Page Content -->
    <main id="page-content" class="flex flex-auto flex-col max-w-full">
      <div
          class="min-h-screen flex items-center justify-center relative overflow-hidden max-w-10xl mx-auto p-4 lg:p-8 w-full">
        <!-- Authentication Section -->
        <section class="py-6 w-full max-w-xl">
          <!-- Header -->
          <header class="mb-8 text-center">
            <slot name="logo"/>
          </header>
          <!-- END Header -->

          <div class="flex flex-col rounded-lg shadow-sm bg-white overflow-hidden dark:text-gray-100 dark:bg-gray-800">
            <slot/>
          </div>

        </section>
        <!-- END Authentication Section -->
      </div>
    </main>
    <!-- END Page Content -->
  </div>
  <!-- END Page Container -->
</template>
