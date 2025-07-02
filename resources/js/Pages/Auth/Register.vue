<template>
  <Head title="Register" />

  <div class="flex items-center justify-center min-h-screen bg-green-200">
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
        <div class="flex items-center justify-between">
          <loading-button :loading="form.processing" class="w-full btn-green" type="submit">Register</loading-button>
        </div>
        <div class="mt-6 text-center">
            <Link class="text-sm text-gray-600 hover:text-green-500" href="/login">
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
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        password: '',
        role: null,
      }),
    }
  },
  methods: {
    register() {
      this.form.post('/register')
    },
  },
}
</script>
