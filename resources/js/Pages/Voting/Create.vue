<template>
  <div class="relative min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="container mx-auto px-4 relative z-10 max-w-2xl">
      <Head :title="title" />
      
      <div class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-green-800 mb-2">{{ $t('voting_create_page.form_title') }}</h1>
          <div class="h-1 w-20 bg-green-500 mx-auto rounded-full"></div>
        </div>

        <form @submit.prevent="submit">
          <div class="mb-6">
            <label for="title" class="block mb-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.vote_title_label') }}</label>
            <input type="text" id="title" v-model="form.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" :placeholder="$t('voting_create_page.vote_title_placeholder')" required>
          </div>

          <div class="mb-6">
            <label for="description" class="block mb-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.description_label') }}</label>
            <textarea id="description" v-model="form.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" :placeholder="$t('voting_create_page.description_placeholder')"></textarea>
          </div>

          <div class="flex items-center mb-6">
            <input id="for_all" type="checkbox" v-model="form.for_all" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
            <label for="for_all" class="ml-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.for_all_label') }}</label>
          </div>

          <div class="mb-6" v-if="!form.for_all">
            <label for="roles" class="block mb-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.roles_label') }}</label>
            <select multiple id="roles" v-model="form.roles" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
              <option v-for="(translatedRole, roleKey) in roles" :key="roleKey" :value="roleKey">{{ translatedRole }}</option>
            </select>
          </div>

          <div v-if="form.for_all || form.roles.includes('student')" class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <label for="class" class="block mb-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.class_label') }}</label>
              <select id="class" v-model="form.class" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                <option :value="null">{{ $t('voting_create_page.class_placeholder') }}</option>
                <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
              </select>
            </div>
            <div>
              <label for="class_letter" class="block mb-2 text-sm font-medium text-green-700">{{ $t('voting_create_page.class_letter_label') }}</label>
              <select id="class_letter" v-model="form.class_letter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                <option :value="null">{{ $t('voting_create_page.class_letter_placeholder') }}</option>
                <option v-for="l in class_letters" :key="l" :value="l">{{ l }}</option>
              </select>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <Link href="/voting" class="text-sm font-medium text-green-600 hover:underline">{{ $t('voting_create_page.cancel_button') }}</Link>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              {{ $t('voting_create_page.create_button') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    title: String,
    roles: Object,
    classes: Array,
    class_letters: Array,
  },
  setup() {
    const form = useForm({
      title: '',
      description: '',
      roles: [],
      class: null,
      class_letter: null,
      for_all: false,
    })

    function submit() {
      form.post('/votings');
    }

    return { form, submit }
  },
}
</script>
