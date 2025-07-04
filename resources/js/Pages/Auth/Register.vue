<template>
  <Head :title="$t('register_page.title')" />

  <div class="flex items-center justify-center min-h-screen bg-orange-100">
    <div class="w-full max-w-xl p-8 bg-white rounded-lg shadow-lg">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">{{ $t('register_page.create_account') }}</h1>
      <form @submit.prevent="register">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <text-input v-model="form.first_name" :error="form.errors.first_name" :label="$t('register_page.first_name')" type="text" autofocus autocapitalize="off" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" :label="$t('register_page.last_name')" type="text" />
        </div>
        <div class="mb-4">
          <text-input v-model="form.middle_name" :error="form.errors.middle_name" :label="$t('register_page.middle_name')" type="text" />
        </div>
        <div class="mb-4">
          <text-input v-model="form.email" :error="form.errors.email" :label="$t('common.email')" type="email" />
        </div>
        <div class="mb-4">
          <text-input v-model="form.password" :error="form.errors.password" :label="$t('common.password')" type="password" />
        </div>
        <div class="mb-6">
          <select-input v-model="form.role" :error="form.errors.role" :label="$t('register_page.role')">
            <option :value="null">{{ $t('register_page.select_role') }}</option>
            <option value="student">{{ $t('roles.student') }}</option>
            <option value="parent">{{ $t('roles.parent') }}</option>
            <option value="teacher">{{ $t('roles.teacher') }}</option>
            <option value="director">{{ $t('roles.director') }}</option>
          </select-input>
        </div>

        <!-- Conditional fields for Student -->
        <div v-if="form.role === 'student'" class="mb-4">
          <text-input 
            v-model="form.school" 
            :error="form.errors.school" 
            :label="$t('register_page.school_number')" 
            type="number" 
            min="1"
            pattern="\d*"
          />
        </div>
        <div v-if="form.role === 'student'" class="mb-6 grid grid-cols-2 gap-4">
          <div>
            <select-input v-model="form.class" :error="form.errors.class" :label="$t('register_page.class_number')">
              <option :value="null">{{ $t('register_page.select_number') }}</option>
              <option v-for="c in schoolClasses" :key="c" :value="c">{{ c }}</option>
            </select-input>
          </div>
          <div>
            <select-input v-model="form.class_letter" :error="form.errors.class_letter" :label="$t('register_page.class_letter')">
              <option :value="null">{{ $t('register_page.select_letter') }}</option>
              <option v-for="l in classLetters" :key="l" :value="l">{{ l }}</option>
            </select-input>
          </div>
        </div>

        <!-- Location fields for all roles -->
        <div class="mb-6">
          <select-input v-model="form.region" :error="form.errors.region" :label="$t('register_page.region')">
            <option :value="null">{{ $t('register_page.select_region') }}</option>
            <option v-for="r in regions" :key="r" :value="r">{{ r }}</option>
          </select-input>
        </div>
        <div v-if="form.region && form.region !== 'м.Київ'" class="mb-6">
          <select-input v-model="form.city" :error="form.errors.city" :label="$t('register_page.city')">
            <option :value="null">{{ $t('register_page.select_city') }}</option>
            <option v-for="c in availableCities" :key="c" :value="c">{{ c }}</option>
          </select-input>
        </div>
        <div v-if="form.region === 'м.Київ'" class="mb-6">
          <select-input v-model="form.district" :error="form.errors.district" :label="$t('register_page.district')">
            <option :value="null">{{ $t('register_page.select_district') }}</option>
            <option v-for="d in districtsOfKyiv" :key="d" :value="d">{{ d }}</option>
          </select-input>
        </div>

        <div class="flex items-center justify-between mt-4">
          <Link class="text-sm text-gray-600 hover:text-orange-500" href="/login">
              {{ $t('register_page.login_prompt') }}
          </Link>
          <div class="flex items-center">
            <language-switcher class="mr-4" />
            <loading-button :loading="form.processing" class="btn-orange" type="submit">{{ $t('register_page.register_button') }}</loading-button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import LanguageSwitcher from '@/Shared/LanguageSwitcher.vue'
import { regions, citiesByRegion, districtsOfKyiv, schoolClasses, classLetters } from '@/Shared/locations.js'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    SelectInput,
    LanguageSwitcher,
  },
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        password: '',
        role: null,
        school: null,
        class: null,
        class_letter: null,
        region: null,
        city: null,
        district: null,
      }),
      regions,
      districtsOfKyiv,
      schoolClasses,
      classLetters,
    }
  },
  computed: {
    availableCities() {
      return this.form.region ? citiesByRegion[this.form.region] || [] : []
    },
  },
  watch: {
    'form.region'(newRegion) {
      this.form.city = null
      this.form.district = null
    },
  },
  methods: {
    register() {
      this.form.post('/register')
    },
  },
}
</script>
