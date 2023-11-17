<script setup>
import {computed, nextTick, ref} from 'vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import AuthenticationCard from '@/js/Components/AuthenticationCard.vue';
import DAuthenticationCardLogo from "@/js/Components/DAuthentication/DAuthenticationCardLogo.vue";
import DInputError from "@/js/Components/DInputError.vue";
import InputLabel from "@/js/Components/InputLabel.vue";
import TextInput from "@/js/Components/TextInput.vue";
import {useMainStore} from "@/js/Stores/main.js";

const store = useMainStore()
const page = usePage()
const recovery = ref(false);

const form = useForm({
  num1Val: '',
  num2Val: '',
  num3Val: '',
  num4Val: '',
  num5Val: '',
  num6Val: '',
  code: '',
  recovery_code: '',
});

const toggleRecovery = async () => {
  recovery.value ^= true;

  await nextTick();

  if (recovery.value) {
    recoveryCodeInput.value.focus();
    form.reset()

  } else {
    num1.value.focus();
    form.reset()
  }
};

const handleSubmit = () => {
  submitButton.value.focus()
  form.transform((data) => ({
    ...data,
    code: form.num1Val + form.num2Val + form.num3Val + form.num4Val + form.num5Val + form.num6Val
  })).post(route('two-factor.login'), {
    onError: () => {form.reset();num1.value.focus();},
    onSuccess: () => {
      store.setDarkModeSystem(page.props.auth.user.dark_mode_system)
      store.setDarkMode(page.props.auth.user.dark_mode)
      form.reset()
    },
  })

}


const submit = () => {

};

// Set input references
const num1 = ref(null);
const num2 = ref(null);
const num3 = ref(null);
const num4 = ref(null);
const num5 = ref(null);
const num6 = ref(null);
const recoveryCodeInput = ref(null);
const submitButton = ref(null);

// Handle input paste
const handlePaste = (e) => {
  let num = e.clipboardData.getData('text/plain').trim();

  if (num.length === 6 && num.match(/^[0-9]+$/g)) {
    form.num1Val = num.charAt(0);
    form.num2Val = num.charAt(1);
    form.num3Val = num.charAt(2);
    form.num4Val = num.charAt(3);
    form.num5Val = num.charAt(4);
    form.num6Val = num.charAt(5);

    handleSubmit();
  }
};

// Check if is number
const isNumber = (value) => {
  if (value.match(/^[0-9]$/g)) {
    return true;
  }
};

const labelValue = computed(() => {
  return recovery.value ? 'Recovery Code' : 'Authentication Code'
})
</script>

<template>
  <Head title="Two-factor Confirmation"/>

  <AuthenticationCard>
    <template #logo>
      <DAuthenticationCardLogo/>
    </template>
    <template v-if="! recovery" #notice>
      Please confirm access to your account by entering the authentication code provided by your authenticator
      application.
    </template>

    <template v-else #notice>
      Please confirm access to your account by entering one of your emergency recovery codes.
    </template>

    <!-- Two Factor Form -->
    <div class="flex flex-col rounded-lg shadow-sm bg-white overflow-hidden dark:text-gray-100 dark:bg-gray-800">
      <div class="p-5 sm:px-32 sm:py-12 grow">
        <form class="space-y-4" @submit.prevent="handleSubmit">
          <InputLabel :value="labelValue" for="recovery_code" class="text-center"/>
          <div v-if="!recovery" class="flex items-center justify-between">
            <input id="num1" ref="num1" v-model="form.num1Val" autofocus
                   class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                   maxlength="1" name="num1" type="text" @paste="handlePaste"
                   @input.change="() => {isNumber(form.num1Val) ? num2.focus() : form.num1Val = '';}">
            <input id="num2" ref="num2" v-model="form.num2Val"
                   class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                   maxlength="1" name="num2" type="text" @paste="handlePaste"
                   @input.change="() => {isNumber(form.num2Val) ? num3.focus() : form.num2Val = '';}"
                   @keydown.backspace="() => {form.num2Val === '' ? num1.focus() : null}">
            <input
                id="num3"
                ref="num3"
                v-model="form.num3Val"
                class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                maxlength="1"
                name="num3"
                type="text"
                @paste="handlePaste"
                @input.change="() => {
                      isNumber(form.num3Val) ? num4.focus() : form.num3Val = '';
                    }"
                @keydown.backspace="() => {
                      form.num3Val === '' ? num2.focus() : null
                    }"
            >
            <span>-</span>
            <input
                id="num4"
                ref="num4"
                v-model="form.num4Val"
                class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                maxlength="1"
                name="num4"
                type="text"
                @paste="handlePaste"
                @input.change="() => {
                      isNumber(form.num4Val) ? num5.focus() : form.num4Val = '';
                    }"
                @keydown.backspace="() => {
                      form.num4Val === '' ? num3.focus() : null
                    }"
            >
            <input
                id="num5"
                ref="num5"
                v-model="form.num5Val"
                class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                maxlength="1"
                name="num5"
                type="text"
                @paste="handlePaste"
                @input.change="() => {
                      isNumber(form.num5Val) ? num6.focus() : form.num5Val = '';
                    }"
                @keydown.backspace="() => {
                      form.num5Val === '' ? num4.focus() : null
                    }"
            >
            <input
                id="num6"
                ref="num6"
                v-model="form.num6Val"
                class="w-9 sm:w-11 text-center block border placeholder-gray-500 px-0 py-2 leading-6 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400"
                maxlength="1"
                name="num6"
                type="text"
                @paste="handlePaste"
                @input.change="() => {
                      isNumber(form.num6Val) ? handleSubmit() : form.num6Val = '';
                    }"
                @keydown.backspace="() => {
                      form.num6Val === '' ? num5.focus() : null
                    }"
            >

          </div>
          <div v-else>
            <TextInput
                id="recovery_code"
                ref="recoveryCodeInput"
                v-model="form.recovery_code"
                autocomplete="one-time-code"
                class="mt-1 block w-full text-center"
                type="text"
            />
          </div>
          <DInputError :message="form.errors.code" class="text-center"/>
          <div>
            <button
                ref="submitButton"
                :class="{'opacity-25': form.processing}"
                :disabled="form.processing"
                class="w-full inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-6 py-3 leading-6 border-digicomm-700 bg-digicomm-700 text-white hover:text-white hover:bg-digicomm-600 hover:border-digicomm-600 focus:ring focus:ring-digicomm-400 focus:ring-opacity-50 active:bg-digicomm-700 active:border-digicomm-700 dark:focus:ring-digicomm-400 dark:focus:ring-opacity-90"
                type="submit">
              <span>Sign In</span>
            </button>
          </div>

        </form>
      </div>
      <div class="p-5 md:px-16 grow text-sm text-center bg-gray-50 dark:bg-gray-700/50">
        <button class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer"
                type="button"
                @click.prevent="toggleRecovery">
          <template v-if="! recovery">
            Use a recovery code
          </template>

          <template v-else>
            Use an authentication code
          </template>
        </button>
      </div>
    </div>
    <!-- END Two Factor Form -->


    <!-- END Two Factor Form -->
  </AuthenticationCard>
</template>
