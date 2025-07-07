<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <div class="max-w-4xl mx-auto">
      <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
        This user has been deleted.
      </trashed-message>

      <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="md:grid md:grid-cols-3">
          <!-- Left Column: Profile Info -->
          <div class="md:col-span-1 p-8 bg-gray-50 border-r border-gray-200">
            <div class="flex flex-col items-center">

              <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ form.first_name }} {{ form.last_name }}</h2>
              <p class="text-sm text-gray-500">{{ form.email }}</p>
              
              <div class="mt-6 w-full">
                <div class="flex items-center text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ form.role }}</span>
                </div>
                <div class="flex items-center mt-2 text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                  <span>{{ form.email }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Form -->
          <div class="md:col-span-2">
            <form @submit.prevent="update">
              <div class="p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <text-input v-model="form.first_name" :error="form.errors.first_name" label="First name" icon="user" />
                  <text-input v-model="form.middle_name" :error="form.errors.middle_name" label="Middle name" icon="user" />
                  <text-input v-model="form.last_name" :error="form.errors.last_name" label="Last name" icon="user" />
                  <text-input v-model="form.email" :error="form.errors.email" label="Email" icon="envelope" />
                  <text-input v-model="form.password" :error="form.errors.password" type="password" autocomplete="new-password" label="Password" icon="lock-closed" />
                  <text-input v-model="form.role" :error="form.errors.role" label="Role" icon="briefcase" />
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mt-10 mb-6 border-b pb-4">Educational Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <text-input v-model="form.school" :error="form.errors.school" label="School" icon="academic-cap" />
                  <text-input v-model="form.class" :error="form.errors.class" label="Class" icon="book-open" />
                  <text-input v-model="form.class_letter" :error="form.errors.class_letter" label="Class Letter" icon="identification" />
                  <text-input v-model="form.region" :error="form.errors.region" label="Region" icon="map" />
                  <text-input v-model="form.city" :error="form.errors.city" label="City" icon="office-building" />
                  <text-input v-model="form.district" :error="form.errors.district" label="District" icon="location-marker" />
                </div>
              </div>
              
              <div class="flex items-center justify-between px-8 py-4 bg-gray-50 border-t border-gray-200">
                <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete User</button>
                <loading-button :loading="form.processing" class="btn-orange ml-auto" type="submit">Update User</loading-button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        first_name: this.user.first_name,
        middle_name: this.user.middle_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: '',
        role: this.user.role,
        school: this.user.school,
        class: this.user.class,
        class_letter: this.user.class_letter,
        region: this.user.region,
        city: this.user.city,
        district: this.user.district,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}`, {
        onSuccess: () => this.form.reset('password'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${this.user.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this user?')) {
        this.$inertia.put(`/users/${this.user.id}/restore`)
      }
    },
  },
}
</script>
