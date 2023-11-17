<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import DFormInput from "@/js/Components/DFormInput.vue";
import DFormSection from "@/js/Components/DForm/DFormSection.vue";
import DActionMessage from "@/js/Components/DActionMessage.vue";
import DButton from "@/js/Components/Button/DButton.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('user-password.update'), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }

      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <DFormSection @submitted="updatePassword">
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <div class="col-span-7">
        <DFormInput id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    input-class="mt-1 block w-full"
                    type="password"
                    label="Current Password"
                    label-for="current_password"
                    :message="form.errors.current_password"
                    />
      </div>

      <div class="col-span-6">
        <DFormInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            autocomplete="new-password"
            class="mt-1 block w-full"
            type="password"
            label="New Password"
            label-for="password"
            aria-autocomplete="none"
            :message="form.errors.password"
        />
      </div>

      <div class="col-span-6">
        <DFormInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            autocomplete="off"
            class="mt-1 block w-full"
            type="password"
            label="Confirm Password"
            label-for="password_confirmation"
            aria-autocomplete="none"
            :message="form.errors.password_confirmation"
        />
      </div>
    </template>

    <template #actions>
      <DActionMessage :on="form.recentlySuccessful" class="mr-3">
        Saved.
      </DActionMessage>

      <DButton type="submit" :disabled="form.processing" :loading="form.processing" loading-text="Saving" size="md" text-size="xs"
               uppercase
               variant="digicomm">Save
      </DButton>


    </template>
  </DFormSection>
</template>
