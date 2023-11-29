<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import InputError from '@/js/Components/InputError.vue';
import {PublicClientApplication} from "@azure/msal-browser";
import {useMainStore} from "@/js/Stores/main.js";
import DAuthenticationCardLogo from "@/js/Components/DAuthentication/DAuthenticationCardLogo.vue";
import DAuthenticationCard from "@/js/Components/DAuthentication/DAuthenticationCard.vue";
import DForm from "@/js/Components/DForm/DForm.vue";
import DFormGroup from "@/js/Components/DForm/DFormGroup.vue";
import DFormInput from "@/js/Components/DForm/DFormInput.vue";
import {computed, watch} from "vue";

// Vuelidate
import useVuelidate from "@vuelidate/core";
import {helpers, minLength, required} from "@vuelidate/validators";
import DInputError from "@/js/Components/DInputError.vue";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const store = useMainStore()
const page = usePage()

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

const form = useForm({
  username: '',
  password: '',
  remember: false,
});

const rules = computed(() => {
  return {
    username: {
      required: helpers.withMessage("The username field is required.", required),
      minLength: helpers.withMessage("The username field must be at least 3 characters.", minLength(3)),
    },
    password: {
      required: helpers.withMessage("The password field is required.", required),
      minLength: helpers.withMessage("The password field must be at least 6 characters.", minLength(6)),
    },
  }
})
const $v = useVuelidate(rules, form)

async function submit() {
  const result = await $v.value.$validate()
  if (!result) {
    // notify user form is invalid
    return;
  }


  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onSuccess: (results) => {
      console.log(results)
      if(results.component !== 'Auth/TwoFactorChallenge') {
        store.setDarkModeSystem(page.props.auth.user.dark_mode_system)
        store.setDarkMode(page.props.auth.user.dark_mode)
      }
      form.reset('password')
    },
    onFinish: () => {

    },
  })
};


async function msalSignIn() {
  await msalInstance.initialize()
  await msalInstance
      .loginPopup(accessTokenRequest)
      .then(async () => {

        accessTokenRequest.account = msalInstance.getAllAccounts()[0]

        await msalInstance
            .acquireTokenSilent(accessTokenRequest)
            .then((accessTokenResponse) => {
              accessTokenRequest.account.access_token = accessTokenResponse.accessToken
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


        accessTokenRequest.post(route('msallogin'))

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
}
if (store.getDarkModeSystem) {
  if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    store.setDarkMode(true);
  } else {
    store.setDarkMode(false);
  }
}

window
    .matchMedia('(prefers-color-scheme: dark)')
    .addEventListener('change', (e) => {
        if (e.matches) {
          store.setDarkMode(true);
        } else {
          store.setDarkMode(false);
        }
    });
</script>

<template>
  <Head title="Log in"/>

  <DAuthenticationCard>
    <template #logo>
      <DAuthenticationCardLogo/>
    </template>
    <!-- Sign In Form -->
    <div class="p-5 md:p-8 grow">
      <DForm @submit.prevent="submit">
        <DFormGroup>
          <DFormInput id="username" v-model="form.username" :v-error-message="$v.username.$errors" :v-error="$v.username.$error" :form-error="form.errors.username" label="User" name="username"
                      placeholder="Enter your username" type="text"/>
        </DFormGroup>
        <DFormGroup>
          <DFormInput id="password" v-model="form.password"
                      :v-error-message="$v.password.$errors"
                      :v-error="$v.password.$error" :form-error="form.errors.password" label="Password" name="password"
                      placeholder="Enter your password" type="password"/>
        </DFormGroup>
        <DFormGroup>
          <div class="flex items-center justify-between space-x-2 mb-5">
            <label class="flex items-center">
              <input id="remember" v-model="form.remember"
                     class="border border-gray-200 rounded h-4 w-4 text-digicomm-500 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:ring-offset-gray-900 dark:focus:border-digicomm-500 dark:checked:bg-digicomm-500 dark:checked:border-transparent"
                     name="remember"
                     type="checkbox">
              <span class="text-sm ml-2">Remember me</span>
            </label>
            <Link v-if="canResetPassword" :href="route('password.request')"
                  class="text-sm font-medium inline-block text-digicomm-600 hover:text-digicomm-400 dark:text-digicomm-400 dark:hover:text-digicomm-300">
              Forgot Password?
            </Link>
          </div>
        </DFormGroup>

        <div>

          <button
              class="w-full inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-6 py-3 leading-6 border-digicomm-700 bg-digicomm-700 text-white hover:text-white hover:bg-digicomm-600 hover:border-digicomm-600 focus:ring focus:ring-digicomm-400 focus:ring-opacity-50 active:bg-digicomm-700 active:border-digicomm-700 dark:focus:ring-digicomm-400 dark:focus:ring-opacity-90"
              type="submit">
            <svg aria-hidden="true"
                 class="hi-mini hi-arrow-uturn-right inline-block w-5 h-5 opacity-50" fill="currentColor"
                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd"
                    d="M12.207 2.232a.75.75 0 00.025 1.06l4.146 3.958H6.375a5.375 5.375 0 000 10.75H9.25a.75.75 0 000-1.5H6.375a3.875 3.875 0 010-7.75h10.003l-4.146 3.957a.75.75 0 001.036 1.085l5.5-5.25a.75.75 0 000-1.085l-5.5-5.25a.75.75 0 00-1.06.025z"
                    fill-rule="evenodd"/>
            </svg>
            <span>Sign In</span>
          </button>

          <!-- Divider: With Label -->
          <div class="flex items-center my-5">
            <span aria-hidden="true" class="grow bg-gray-100 rounded h-0.5 dark:bg-gray-700/75"></span>
            <span
                class="text-xs font-medium text-gray-800 bg-gray-100 rounded-full px-3 py-1 dark:bg-gray-700 dark:text-gray-200">or sign in with</span>
            <span aria-hidden="true" class="grow bg-gray-100 rounded h-0.5 dark:bg-gray-700/75"></span>
          </div>
          <!-- END Divider: With Label -->

          <div class="text-center">
            <button
                class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
                type="button" @click.prevent="msalSignIn">
              <font-awesome-icon :icon="['fab', 'windows']" class="w-4 h-4 text-blue-500"/>
              <span>Microsoft</span>
            </button>
            <DInputError :message="page.props.errors.microsoft_login" class="mt-2"/>
          </div>
        </div>
      </DForm>
    </div>
    <div class="p-5 md:px-16 grow text-sm text-center bg-gray-50 dark:bg-gray-700/50">
      Don’t have an account yet?
      <Link :href="route('register')"
            class="font-medium text-digicomm-600 hover:text-digicomm-400 dark:text-digicomm-400 dark:hover:text-digicomm-300">
        Sign Up
      </Link>
    </div>
    <!-- END Sign In Form -->

  </DAuthenticationCard>


</template>
