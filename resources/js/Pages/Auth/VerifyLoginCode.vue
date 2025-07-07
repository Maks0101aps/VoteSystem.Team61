<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-xl">
      <div>
        <div class="flex justify-center mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Verify Your Login</h2>
        <div class="mt-4 mx-auto w-24 border-b-2 border-orange-500" />
      </div>

      <div class="mt-8 space-y-6">
        <p class="text-center text-gray-600">
          We've sent a verification code to your email. Please check your inbox and enter the code below to complete your login.
        </p>

        <form class="mt-8 space-y-6" @submit.prevent="submit">
          <div class="rounded-md shadow-sm">
            <div>
              <label for="code" class="sr-only">Verification Code</label>
              <input
                id="code"
                v-model="form.code"
                name="code"
                type="text"
                maxlength="6"
                required=""
                class="relative block w-full rounded-md border-gray-300 py-2.5 text-gray-900 placeholder-gray-400 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                placeholder="Verification code"
              />
            </div>
          </div>

          <div class="text-sm text-center text-red-500" v-if="form.errors.code">
            {{ form.errors.code }}
          </div>

          <div>
            <button
              type="submit"
              :disabled="form.processing"
              class="group relative flex w-full justify-center rounded-md bg-orange-600 py-2 px-3 text-sm font-semibold text-white hover:bg-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600"
            >
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 group-hover:text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </span>
              Verify & Login
            </button>
          </div>
        </form>

        <!-- Debug Section -->
        <div v-if="loginCode" class="mt-4 text-center">
          <button @click="showDebugInfo = true" v-if="!showDebugInfo" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            Show Debug Code
          </button>
          <div v-if="showDebugInfo && !showDebugCode" class="p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
            <p class="font-bold">Debug Mode</p>
            <p class="text-sm">This shows the code because the app is in debug mode. This will not be visible in production.</p>
            <button @click="showDebugCode = true" class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Continue</button>
          </div>
          <div v-if="showDebugCode" class="mt-4 text-lg font-bold text-green-600">
            Login Code: {{ loginCode }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  loginCode: String,
});

const showDebugInfo = ref(false);
const showDebugCode = ref(false);

const form = useForm({
  code: '',
});

const submit = () => {
  form.post(route('login.verify'), {
    onFinish: () => form.reset('code'),
  });
};
</script>
