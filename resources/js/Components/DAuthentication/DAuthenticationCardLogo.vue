<script setup>
import { Link } from '@inertiajs/vue3';
import DImg from "@/js/Components/DImg.vue";
</script>

<template>
    <Link :href="'/'">
      <DImg src="/digicomm_map.png" class="h-16 m-auto dark:invert inline"/>
      <DImg src="/digicomm_banner.png" class="h-16 m-auto inline"/>
    </Link>
</template>
