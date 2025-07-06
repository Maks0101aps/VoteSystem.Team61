<template>
  <GuestLayout title="Verify Email">
    <div class="w-full max-w-md space-y-8">
      <div class="mt-8 space-y-6">
        <p class="text-center text-gray-600">
          We've sent a verification code to your email. Please check your inbox and enter the code below.
        </p>

        <form class="mt-8 space-y-6" @submit.prevent="submit">
          <div class="-space-y-px rounded-md shadow-sm">
            <div>
              <label for="code" class="sr-only">Verification Code</label>
              <input
                id="code"
                v-model="form.code"
                name="code"
                type="text"
                maxlength="6"
                required=""
                class="relative block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
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
              class="group relative flex w-full justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <LockClosedIcon
                  class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                  aria-hidden="true"
                />
              </span>
              Verify Email
            </button>
          </div>

          <div class="text-sm text-center">
            Didn't receive the code?
            <button @click="resendCode" class="font-medium text-indigo-600 hover:text-indigo-500">
              Resend
            </button>
          </div>
        </form>

        <!-- Debug Section -->
        <div v-if="verificationCode" class="mt-4 text-center">
          <button @click="showDebugInfo = true" v-if="!showDebugInfo" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            Show Debug Code
          </button>
          <div v-if="showDebugInfo && !showDebugCode" class="p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
            <p class="font-bold">Debug Mode</p>
            <p class="text-sm">This shows the code because the app is in debug mode. This will not be visible in production.</p>
            <button @click="showDebugCode = true" class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Continue</button>
          </div>
          <div v-if="showDebugCode" class="mt-4 text-lg font-bold text-green-600">
            Verification Code: {{ verificationCode }}
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Shared/GuestLayout.vue';
import { LockClosedIcon } from '@heroicons/vue/20/solid';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  verificationCode: String,
});

const showDebugInfo = ref(false);
const showDebugCode = ref(false);

const form = useForm({
  code: '',
});

const submit = () => {
  form.post(route('verification.verify'), {
    onSuccess: () => {
      form.reset();
    },
  });
};

const resendCode = () => {
  form.get(route('verification.resend'), {
    onSuccess: () => {
      // Notification could be added here
    },
  });
};
</script>
