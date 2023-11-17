<script setup>
import AppLayout from '@/js/Layouts/AppLayout.vue';
import DeleteUserForm from '@/js/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/js/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/js/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/js/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/js/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/js/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import LoginMethods from "@/js/Pages/Profile/Partials/LoginMethods.vue";
import BasePageHeading from "@/js/Components/Base/BasePageHeading.vue";


defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
});
</script>

<template>
  <AppLayout layout="boxed" title="Profile">
    <BasePageHeading title="Profile" :icon="['far','user']"/>
    <div>
      <div class="mx-auto py-10 sm:px-6 lg:px-8 space-y-4 lg:space-y-8">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <UpdateProfileInformationForm :user="$page.props.auth.user"/>

          <!-- <SectionBorder/> -->
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <UpdatePasswordForm class="mt-10 sm:mt-0"/>

          <!-- <SectionBorder/> -->
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <TwoFactorAuthenticationForm
              :requires-confirmation="confirmsTwoFactorAuthentication"
              class="mt-10 sm:mt-0"
          />

          <!-- <SectionBorder/> -->
        </div>

        <div>
          <LoginMethods
              :requires-confirmation="confirmsTwoFactorAuthentication"
              class="mt-10 sm:mt-0"
          />

          <!-- <SectionBorder/> -->
        </div>

        <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0"/>

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <!-- <SectionBorder/> -->

          <DeleteUserForm class="mt-10 sm:mt-0"/>
        </template>
      </div>
    </div>
  </AppLayout>
</template>
