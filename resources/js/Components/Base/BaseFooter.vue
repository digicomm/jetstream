<script setup>

</script>

<template>
  <!-- Page Footer -->
  <footer id="page-footer" class="flex flex-none items-center bg-white dark:bg-gray-800 sticky bottom-0">
    <div
        class="text-center flex flex-col md:text-left md:flex-row md:justify-between text-sm max-w-10xl mx-auto px-4 lg:px-8 w-full">
      <div class="pt-4 pb-1 md:pb-4">
        {{ $page.props.app.shortName }} {{ $page.props.app.version }} {{ $page.props.app.stack }}
      </div>
      <div class="pb-2 pt-1 md:pt-2 inline-flex items-center justify-center invisible md:visible">
        <a class="border-gray-300 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700">File
          Times</a>
      </div>
    </div>
  </footer>
  <!-- END Page Footer -->
</template>

<style scoped>

</style>