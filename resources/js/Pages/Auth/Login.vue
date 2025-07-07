<template>
  <Head :title="$t('login_page.title')" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-xl">
            <div class="flex justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-center text-gray-700">VoteSystem.Team61</h1>
            <form class="mt-6 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
        <div class="px-10 py-12">
                    <h1 class="text-center text-2xl font-bold text-gray-800">{{ $t('login_page.welcome_back') }}</h1>
          <div class="mt-4 mx-auto w-24 border-b-2 border-orange-500" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="$t('common.email')" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="$t('common.password')" type="password" />
          <label class="flex items-center mt-6 select-none" for="remember">
                        <input id="remember" v-model="form.remember" class="mr-2 rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" type="checkbox" />
            <span class="text-sm">{{ $t('login_page.remember_me') }}</span>
          </label>
        </div>
                <div class="flex items-center justify-between px-10 py-4 bg-orange-50 border-t border-orange-200">
                    <a href="/register" class="text-sm text-orange-600 hover:underline hover:text-orange-800">{{ $t('login_page.register_prompt') }}</a>
          <div class="flex items-center">
            <language-switcher class="mr-4" />
            <loading-button :loading="form.processing" class="btn-orange" type="submit">{{ $t('login_page.login_button') }}</loading-button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import LanguageSwitcher from '@/Shared/LanguageSwitcher.vue'

export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
    LanguageSwitcher,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: 'johndoe@example.com',
        password: 'secret',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post('/login')
    },
  },
}
</script>
