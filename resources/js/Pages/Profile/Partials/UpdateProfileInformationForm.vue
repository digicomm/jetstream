<script setup>
import {ref} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import ActionMessage from '@/js/Components/ActionMessage.vue';
import FormSection from '@/js/Components/FormSection.vue';
import DFormInput from "@/js/Components/DFormInput.vue";
import DButton from "@/js/Components/Button/DButton.vue";
import DActionMessage from "@/js/Components/DActionMessage.vue";
import DInputError from "@/js/Components/DInputError.vue";
import DFormSection from "@/js/Components/DForm/DFormSection.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
  user: Object,
});


const form = useForm({
  _method: 'PUT',
  given_name: props.user.given_name,
  surname: props.user.surname,
  email: props.user.email,
  photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0];
  }

  form.post(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => clearPhotoFileInput(),
  });
};

const sendEmailVerification = () => {
  verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
  photoInput.value.click();
};

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    photoPreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
};

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null;
      clearPhotoFileInput();
    },
  });
};

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
};
</script>

<template>
  <DFormSection @submitted="updateProfileInformation">
    <template #icon>
      <font-awesome-icon :icon="['far','circle-user']" class="h-5 w-5 text-digicomm-500"></font-awesome-icon>
    </template>
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <div class="space-y-1">
        <!-- Profile Photo -->
        <div v-if="$page.props.jetstream.managesProfilePhotos" class="text-center">

          <div
              class="space-y-4 sm:flex sm:items-center sm:space-x-4 sm:space-y-0"
          >
            <!-- Profile Photo File Input -->
            <input
                ref="photoInput"
                class="hidden"
                type="file"
                @change="updatePhotoPreview"
            >
            <!-- END Profile Photo File Input -->
            <!-- Current Profile Photo -->
            <div v-if="! photoPreview" class="inline-flex shrink-0 mt-2">
              <img :alt="user.name" :src="user.profile_photo_url"
                   class="rounded-full h-16 w-16 object-cover inline-block">
            </div>
            <!-- END Current Profile Photo -->
            <!-- New Profile Photo Preview -->
            <div v-else="photoPreview" class="inline-flex mt-2">
                    <span
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                        class="inline-block rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                    />
            </div>
            <!-- END New Profile Photo Preview -->
            <DButton class="mt-2 mr-2" size="md" text-size="xs" uppercase @click.prevent="selectNewPhoto" block>
              Select a New Photo
            </DButton>
            <DButton v-if="user.profile_photo_path" class="mt-2" size="md" text-size="xs" uppercase block
                     @click.prevent="deletePhoto">Remove Photo
            </DButton>

            <DInputError :message="form.errors.photo" class="mt-2"/>

          </div>
        </div>
          <!-- First Name -->
          <DFormInput
              class="space-y-1"
              id="given_name"
              v-model="form.given_name"
              :message="form.errors.given_name"
              input-class="mt-1 block w-full"
              label="First Name"
              label-for="given_name"
              required
              type="text"
          />
          <!-- Last Name -->
          <DFormInput
              id="surname"
              v-model="form.surname"
              :message="form.errors.surname"
              input-class="mt-1 block w-full"
              label="Last Name"
              label-for="surname"
              required
              type="text"
          />
          <!-- Email -->
          <DFormInput
              id="email"
              v-model="form.email"
              :message="form.errors.email"
              input-class="mt-1 block w-full bg-gray-200"
              label="Email"
              label-for="email"
              readonly
              required
              type="email"
          />
      </div>
      <div>

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
