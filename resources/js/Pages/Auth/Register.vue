<template>
  <Head title="Register" />

  <div class="flex items-center justify-center min-h-screen bg-orange-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Create Account</h1>
      <form @submit.prevent="register">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <text-input v-model="form.first_name" :error="form.errors.first_name" label="First Name" type="text" autofocus autocapitalize="off" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" label="Last Name" type="text" />
        </div>
        <div class="mb-4">
            <text-input v-model="form.middle_name" :error="form.errors.middle_name" label="Middle Name (Optional)" type="text" />
        </div>
        <div class="mb-4">
          <text-input v-model="form.email" :error="form.errors.email" label="Email" type="email" />
        </div>
        <div class="mb-4">
          <text-input v-model="form.password" :error="form.errors.password" label="Password" type="password" />
        </div>
        <div class="mb-6">
          <select-input v-model="form.role" :error="form.errors.role" label="Role">
            <option :value="null">Select a role</option>
            <option value="student">Student</option>
            <option value="parent">Parent</option>
            <option value="teacher">Teacher</option>
            <option value="director">Director</option>
          </select-input>
        </div>

        <!-- Conditional fields for Student -->
        <div v-if="form.role === 'student'" class="mb-4">
          <text-input 
            v-model="form.school" 
            :error="form.errors.school" 
            label="School Number" 
            type="number" 
            min="1"
            pattern="\d*"
          />
        </div>
        <div v-if="form.role === 'student'" class="mb-6 grid grid-cols-2 gap-4">
          <div>
            <select-input v-model="form.class" :error="form.errors.class" label="Class Number">
              <option :value="null">Select number</option>
              <option v-for="c in schoolClasses" :key="c" :value="c">{{ c }}</option>
            </select-input>
          </div>
          <div>
            <select-input v-model="form.class_letter" :error="form.errors.class_letter" label="Class Letter">
              <option :value="null">Select letter</option>
              <option v-for="l in classLetters" :key="l" :value="l">{{ l }}</option>
            </select-input>
          </div>
        </div>

        <!-- Location fields for all roles -->
        <div class="mb-6">
          <select-input v-model="form.region" :error="form.errors.region" label="Region">
            <option :value="null">Select a region</option>
            <option v-for="r in regions" :key="r" :value="r">{{ r }}</option>
          </select-input>
        </div>
        <div v-if="form.region && form.region !== 'м.Київ'" class="mb-6">
          <select-input v-model="form.city" :error="form.errors.city" label="City">
            <option :value="null">Select a city</option>
            <option v-for="c in availableCities" :key="c" :value="c">{{ c }}</option>
          </select-input>
        </div>
        <div v-if="form.region === 'м.Київ'" class="mb-6">
          <select-input v-model="form.district" :error="form.errors.district" label="District">
            <option :value="null">Select a district</option>
            <option v-for="d in districtsOfKyiv" :key="d" :value="d">{{ d }}</option>
          </select-input>
        </div>

        <div class="flex items-center justify-between">
          <loading-button :loading="form.processing" class="w-full btn-orange" type="submit">Register</loading-button>
        </div>
        <div class="mt-6 text-center">
            <Link class="text-sm text-gray-600 hover:text-orange-500" href="/login">
                Already registered?
            </Link>
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
import { regions, citiesByRegion, districtsOfKyiv, schoolClasses, classLetters } from '@/Shared/locations.js'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    SelectInput,
  },
  data() {
    return {
      regions,
      districtsOfKyiv,
      schoolClasses,
      classLetters,
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        password: '',
        role: null,
        school: '',
        class: null,
        class_letter: null,
        region: null,
        city: null,
        district: null,
      }),
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
