<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import InputLabel from '@/js/Components/InputLabel.vue';
import DAuthenticationCard from "@/js/Components/DAuthentication/DAuthenticationCard.vue";
import DAuthenticationCardLogo from "@/js/Components/DAuthentication/DAuthenticationCardLogo.vue";
import DFormInput from "@/js/Components/DFormInput.vue";
import DInputError from "@/js/Components/DInputError.vue";
import DButton from "@/js/Components/Button/DButton.vue";

defineProps({
  status: String,
});
const page = usePage()

const form = useForm({
  email: '',
});

function goBack() {
  window.history.back()
}

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <Head title="Forgot Password"/>

  <DAuthenticationCard>
    <template #logo>
      <DAuthenticationCardLogo/>
    </template>
    <!-- Password Reset DForm -->
    <div class="p-5 md:p-8 grow text-sm space-y-3">
      <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ status }}
      </div>
      <form class="space-y-6" @submit.prevent="submit">
        <div class="space-y-1">
          <InputLabel for="email" value="Email"/>
          <DFormInput id="email"
                      v-model="form.email"
                      auto-complete
                      autocomplete="email"
                      autofocus
                      class="w-full block border placeholder-gray-500 px-5 py-3 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                      placeholder="Enter your email"
                      type="email"/>
          <DInputError :message="form.errors.email" class="mt-2"/>
        </div>
        <div>
          <DButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" block size="lg" text-size="sm"
                   type="submit" variant="digicomm">Email Password Reset Link
          </DButton>
        </div>
      </form>
    </div>
    <div class="p-5 md:px-16 grow text-sm text-center bg-gray-50 dark:bg-gray-700/50">
      <Link class="font-medium text-digicomm-600 hover:text-digicomm-400 dark:text-digicomm-400 dark:hover:text-digicomm-300" href="#"
            @click="goBack">
        Return to Sign In
      </Link>
    </div>
    <!-- END Password Reset DForm -->
  </DAuthenticationCard>
</template>
