<script setup>
import ApiTokenManager from '@/js/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from '@/js/Layouts/AppLayout.vue';
import BasePageHeading from "@/js/Components/Base/BasePageHeading.vue";

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

let title = 'API Tokens'
</script>

<template>
    <AppLayout :title="title" layout="boxed">
      <BasePageHeading :title="title" :icon="['far','webhook']"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="mx-auto py-10 sm:px-6 lg:px-8">
                <ApiTokenManager
                    :tokens="tokens"
                    :available-permissions="availablePermissions"
                    :default-permissions="defaultPermissions"
                />
            </div>
        </div>
    </AppLayout>
</template>
