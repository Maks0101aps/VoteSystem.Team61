<template>
  <Head :title="$t('login_page.title')" />
  <div class="flex items-center justify-center min-h-screen bg-orange-100">
    <div class="w-full max-w-xl">
      <h1 class="text-3xl font-bold text-center text-orange-800">VoteSystem.Team61</h1>
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">{{ $t('login_page.welcome_back') }}</h1>
          <div class="mt-6 mx-auto w-24 border-b-2" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="$t('common.email')" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="$t('common.password')" type="password" />
          <label class="flex items-center mt-6 select-none" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">{{ $t('login_page.remember_me') }}</span>
          </label>
        </div>
        <div class="flex items-center justify-between px-10 py-4 bg-gray-100 border-t border-gray-100">
          <a href="/register" class="text-sm text-gray-600 hover:text-orange-500">{{ $t('login_page.register_prompt') }}</a>
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
