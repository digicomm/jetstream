<script lang="ts" setup>

import {Link, useForm, usePage} from "@inertiajs/vue3";
import DImg from "@/js/Components/DImg.vue";
import {computed} from "vue";
import {helpers, minLength, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import DForm from "@/js/Components/DForm/DForm.vue";
import {useMainStore} from "@/js/Stores/main";
import {PublicClientApplication} from "@azure/msal-browser";

defineProps({
  canResetPassword: Boolean,
  status: String,
});
const store = useMainStore();
const page = usePage();

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
});

const $v = useVuelidate(rules, form);

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

async function submitForm() {
  const result = await $v.value.$validate();
  if (!result) {
    // notify user form is invalid
    return;
  }


  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onSuccess: (results) => {
      console.log(results);
      if (results.component !== 'Auth/TwoFactorChallenge') {
        store.setDarkModeSystem(page.props.auth.user.dark_mode_system);
        store.setDarkMode(page.props.auth.user.dark_mode)
      }
      form.reset('password')
    },
    onFinish: () => {

    },
  })
}

async function msalSignIn() {
  await msalInstance.initialize();
  await msalInstance
      .loginPopup(accessTokenRequest)
      .then(async () => {

        accessTokenRequest.account = msalInstance.getAllAccounts()[0];

        await msalInstance
            .acquireTokenSilent(accessTokenRequest)
            .then((accessTokenResponse) => {
              accessTokenRequest.account.access_token = accessTokenResponse.accessToken
            })
            .catch((error) => {
              let error_type = error.name;
              let error_name = error.message.split(':')[0].trim();
              let error_message = error.message.split(':')[1].trim();
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
                        page.props.errors.microsoft_login = 'Interactive Authentication Required';
                        console.log(error)
                      });
                  break;
                case 'BrowserAuthError':
                  switch (error_name) {
                    case 'no_account_error':
                      msalInstance.acquireTokenPopup(accessTokenRequest).then((accessTokenResponse) => {
                        accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                      }).catch((error) => {
                        page.props.errors.microsoft_login = 'No Microsoft Account Provided';
                        console.log(error)
                      });
                      break;
                    case 'user_cancelled':
                      page.props.errors.microsoft_login = 'User Cancelled Login Flow';
                      break;
                    default:
                      break
                  }
              }
            });


        accessTokenRequest.post(route('msallogin'))

      })
      .catch((error) => {
        let error_type = error.name;
        let error_name = error.message.split(':')[0].trim();
        let error_message = error.message.split(':')[1].trim();
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
                  page.props.errors.microsoft_login = 'Interactive Authentication Required';
                  console.log(error)
                });
            break;
          case 'BrowserAuthError':
            switch (error_name) {
              case 'no_account_error':
                msalInstance.acquireTokenPopup(accessTokenRequest).then((accessTokenResponse) => {
                  accessTokenRequest.account.access_token = accessTokenResponse.accessToken
                }).catch((error) => {
                  page.props.errors.microsoft_login = 'No Microsoft Account Provided';
                  console.log(error)
                });
                break;
              case 'user_cancelled':
                page.props.errors.microsoft_login = 'User Cancelled Login Flow';
                break;
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
    })
</script>

<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8 bg-gray-100 dark:bg-gray-900">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm mb-10">
      <div class="w-fit mx-auto mb-2">
        <Link :href="'/'">
          <DImg class="h-16 m-auto dark:invert inline" src="/digicomm_map.png"/>
          <DImg class="h-16 m-auto inline" src="/digicomm_banner.png"/>
        </Link>
      </div>
      <h2 class="text-center text-sm font-medium text-gray-500 dark:text-gray-400">Welcome, please sign in to your
        account</h2>
    </div>
    <div class="flex flex-col sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="grow bg-white px-6 py-12 shadow rounded-2xl sm:px-12 dark:bg-gray-800">
        <div class="grow">
          <DForm class="space-y-6" @submit.prevent="submitForm">
            <div>
              <div class="relative -space-y-px rounded-md shadow-sm">
                <div
                    class="pointer-events-none absolute inset-0 z-10 rounded-md ring-1 ring-inset ring-gray-300 dark:ring-gray-600"/>
                <div>
                  <label class="sr-only" for="username">Username</label>
                  <input id="username" v-model="form.username" aria-autocomplete="none" autocomplete="off"
                         class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:z-10 focus:ring focus:ring-inset focus:ring-opacity-50 focus:ring-digicomm-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-0"
                         name="username" placeholder="Username"
                         type="text"/>
                </div>
                <div>
                  <label class="sr-only" for="password">Password</label>
                  <input id="password" v-model="form.password" aria-autocomplete="none" autocomplete="off"
                         class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:z-10 focus:ring focus:ring-inset focus:ring-opacity-50 focus:ring-digicomm-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-0"
                         name="password" placeholder="Password"
                         type="password"/>
                </div>
              </div>
              <div class="mt-3">
                <p v-for="error in $v.$errors" class="text-sm text-red-600 dark:text-red-400">
                  {{ error.$message }}
                </p>
                <p v-for="error in form.errors" class="text-sm text-red-600 dark:text-red-400">
                  {{ error }}
                </p>
              </div>

            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input id="remember-me" v-model="form.remember"
                       class="h-4 w-4 rounded border-gray-300 text-digicomm-600 focus:ring-digicomm-600 dark:bg-white/5 dark:border-gray-600"
                       name="remember-me"
                       type="checkbox"/>
                <label class="ml-3 block text-sm leading-6 text-gray-900 dark:text-gray-300" for="remember-me">Remember
                  me</label>
              </div>

              <div class="text-sm leading-6">
                <Link v-if="canResetPassword" :href="route('password.request')"
                      class="font-semibold text-digicomm-600 hover:text-digicomm-500">
                  Forgot Password?
                </Link>
              </div>
            </div>

            <div>
              <button
                  class="flex w-full justify-center rounded-md bg-digicomm-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-digicomm-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-digicomm-600"
                  type="submit">
                Sign in
              </button>
            </div>
          </DForm>
        </div>


        <div>
          <div class="relative mt-10">
            <div aria-hidden="true" class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-100 dark:border-gray-700"/>
            </div>
            <div class="relative flex justify-center text-sm font-medium leading-6">
              <span class="bg-gray-100 px-6 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-full">or sign in with</span>
            </div>
          </div>

          <div class="mt-6 text-center">
            <button
                class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white dark:bg-blue-500 text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700 w-48"
                type="button" @click.prevent="msalSignIn">
              <font-awesome-icon :icon="['fab', 'windows']" class="w-4 h-4 text-blue-500 dark:text-gray-100"/>
              <span>Microsoft</span>
            </button>

          </div>
        </div>

      </div>


    </div>
  </div>
</template>

<style scoped>

</style>