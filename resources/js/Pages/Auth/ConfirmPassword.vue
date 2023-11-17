<script setup>
import {ref} from 'vue';
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/js/Components/AuthenticationCard.vue';
import InputError from '@/js/Components/InputError.vue';
import InputLabel from '@/js/Components/InputLabel.vue';
import PrimaryButton from '@/js/Components/PrimaryButton.vue';
import TextInput from '@/js/Components/TextInput.vue';
import DAuthenticationCardLogo from "@/js/Components/DAuthentication/DAuthenticationCardLogo.vue";

const form = useForm({
  password: '',
});

const passwordInput = ref(null);

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => {
      form.reset();

      passwordInput.value.focus();
    },
  });
};
</script>

<template>
  <Head title="Secure Area"/>

  <AuthenticationCard>
    <template #logo>
      <DAuthenticationCardLogo/>
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="password" value="Password"/>
        <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            autocomplete="current-password"
            autofocus
            class="mt-1 block w-full"
            required
            type="password"
        />
        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div class="flex justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ml-4">
          Confirm
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
