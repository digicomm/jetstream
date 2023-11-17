<script setup>
import {computed, ref} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import ActionSection from '@/js/Components/ActionSection.vue';
import DangerButton from '@/js/Components/DangerButton.vue';
import PrimaryButton from '@/js/Components/PrimaryButton.vue';
import {PublicClientApplication} from "@azure/msal-browser";
import DActionSection from "@/js/Components/DActionSection.vue";

const page = usePage();
const enabling = ref(false);
const disabling = ref(false);

const microsoftLoginEnabled = computed(
    () => page.props.auth.user.microsoft_enabled
)

const msalInstance = new PublicClientApplication({
  auth: {
    clientId: '34f726a7-ae4b-4aeb-9d94-a54b1b2ae95f',
    authority: 'https://login.microsoftonline.com/919dc015-2f8a-465d-b7bb-8dac5ded2d17'
  },
  cache: {
    cacheLocation: 'sessionStorage'
  }
});
const accessTokenRequest = useForm({
  scopes: ['openid', 'email', 'profile', 'offline_access', 'user.read', 'user.readbasic.all', 'mail.send', 'mail.readwrite'],
  prompt: 'select_account',
  domainHint: 'digicomm.com',
  account: undefined
});

const disableMicrosoft = async () => {
  disabling.value = true
  await accessTokenRequest.transform(data => ({
    ...data,
    action: 'disable'
  })).patch('/userprofile', {preserveScroll: true})
  disabling.value = false
}
const setMicrosoftId = async () => {
  accessTokenRequest.transform(data => ({
    ...data,
    action: 'enable'
  })).patch('/userprofile', {preserveScroll: true, only: ['auth']})
}
const enableMicrosoft = async () => {
  enabling.value = true
  await msalInstance.initialize()
  await msalInstance
      .loginPopup(accessTokenRequest)
      .then(async () => {

        accessTokenRequest.account = msalInstance.getAllAccounts()[0]

        await msalInstance
            .acquireTokenSilent(accessTokenRequest)
            .then((accessTokenResponse) => {
              accessTokenRequest.account.access_token = accessTokenResponse.accessToken
              setMicrosoftId()
            })
            .catch((error) => {
              let error_type = error.name
              let error_name = error.message.split(':')[0].trim()
              let error_message = error.message.split(':')[1].trim()
              //console.log(error_type)
              //console.log(error_name)
              //console.log(error_message)
              switch (error_type) {
                case 'InteractionRequiredAuthError':
                  msalInstance
                      .acquireTokenPopup(accessTokenRequest)
                      .then((accessTokenResponse) => {
                        accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                        setMicrosoftId()
                      })
                      .catch((error) => {
                        page.props.errors.microsoft_login = 'Interactive Authentication Required'
                        console.log(error)
                      })
                  break
                case 'BrowserAuthError':
                  switch (error_name) {
                    case 'no_account_error':
                      msalInstance.acquireTokenPopup(accessTokenRequest).then((accessTokenResponse) => {
                        accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                        setMicrosoftId()
                      }).catch((error) => {
                        page.props.errors.microsoft_login = 'No Microsoft Account Provided'
                        console.log(error)
                      })
                      break
                    case 'user_cancelled':
                      page.props.errors.microsoft_login = 'User Cancelled Login Flow'
                      break
                    default:
                      break
                  }
              }
            })
      })
      .catch((error) => {
        let error_type = error.name
        let error_name = error.message.split(':')[0].trim()
        let error_message = error.message.split(':')[1].trim()
        //console.log(error_type)
        //console.log(error_name)
        //console.log(error_message)
        switch (error_type) {
          case 'InteractionRequiredAuthError':
            msalInstance
                .acquireTokenPopup(accessTokenRequest)
                .then((accessTokenResponse) => {
                  accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                  setMicrosoftId()
                })
                .catch((error) => {
                  page.props.errors.microsoft_login = 'Interactive Authentication Required'
                  console.log(error)
                })
            break
          case 'BrowserAuthError':
            switch (error_name) {
              case 'no_account_error':
                msalInstance.acquireTokenPopup(accessTokenRequest).then((accessTokenResponse) => {
                  accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                  setMicrosoftId()
                }).catch((error) => {
                  page.props.errors.microsoft_login = 'No Microsoft Account Provided'
                  console.log(error)
                })
                break
              case 'user_cancelled':
                page.props.errors.microsoft_login = 'User Cancelled Login Flow'
                break
              default:
                break
            }
        }
      })


  enabling.value = false
}

</script>

<template>
  <DActionSection>
    <template #title>
      Login Methods
    </template>

    <template #description>
      Add additional login methods to your account.
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Using existing login information from Microsoft to sign into DigiSmart. (Required for sending build sheets and
        end of day emails)
      </div>

      <div class="mt-5">
        <img alt="Sign In with Microsoft" class="inline-block mr-2 h-9"
             src="/storage/public/ms-signin_light.svg">

        <PrimaryButton v-if="! microsoftLoginEnabled" :class="{ 'opacity-25': enabling }" :disabled="enabling"
                       type="button" @click="enableMicrosoft"> Enable
        </PrimaryButton>
        <DangerButton v-else :class="{ 'opacity-25': disabling }" :disabled="disabling" type="button"
                      @click="disableMicrosoft"> Disable
        </DangerButton>
      </div>
    </template>
  </DActionSection>
</template>
