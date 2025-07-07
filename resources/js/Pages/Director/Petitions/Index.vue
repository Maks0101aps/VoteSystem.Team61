<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
        <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-orange-800 mb-2">{{ $t('director_petitions.title') }}</h1>
        <div class="h-1 w-24 bg-orange-500 mx-auto mb-4 rounded-full"></div>
                <p class="text-xl text-orange-700">{{ $t('director_petitions.subtitle') }}</p>
      </div>

      <div class="space-y-6">
        <div v-if="petitions.length === 0" class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-orange-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
                    <h3 class="text-xl font-medium text-orange-700 mb-2">{{ $t('director_petitions.no_petitions_title') }}</h3>
                    <p class="text-orange-600 mb-6">{{ $t('director_petitions.no_petitions_subtitle') }}</p>
        </div>
        
        <div v-for="petition in petitions" :key="petition.id" 
             class="bg-white p-6 rounded-xl shadow-md border-l-4 border-orange-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-orange-700">{{ petition.title }}</h2>
          </div>
          <p class="text-orange-600 mb-4">
            {{ petition.description }}
          </p>
          <div class="border-t border-gray-200 pt-4 mt-4">
            <h4 class="text-md font-semibold text-gray-700 mb-2">Детальна інформація:</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-600">

              <div class="flex">
                <strong class="w-32 shrink-0">Автор:</strong>
                <span>{{ petition.user.name }}</span>
              </div>

              <div class="flex" v-if="petition.user.class">
                <strong class="w-32 shrink-0">Клас автора:</strong>
                <span>{{ petition.user.class }}-{{ petition.user.class_letter }}</span>
              </div>

              <div class="flex">
                <strong class="w-32 shrink-0">Email автора:</strong>
                <span>{{ petition.user.email }}</span>
              </div>

              <div class="flex">
                <strong class="w-32 shrink-0">Підписи:</strong>
                <span>{{ petition.signatures_count }} / {{ petition.signatures_required }}</span>
              </div>

              <div class="flex">
                <strong class="w-32 shrink-0">Створено:</strong>
                <span>{{ formatDate(petition.created_at) }}</span>
              </div>

              <div class="flex">
                <strong class="w-32 shrink-0">Закінчується:</strong>
                <span>{{ formatDate(petition.ends_at) }}</span>
              </div>

              <div class="flex" v-if="petition.school_class">
                <strong class="w-32 shrink-0">Петиція для класу:</strong>
                <span>{{ petition.school_class.class_number }}-{{ petition.school_class.class_letter }}</span>
              </div>

            </div>
          </div>
          <div class="flex justify-end space-x-4">
            <form @submit.prevent="reject(petition.id)">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('director_petitions.reject') }}
              </button>
            </form>
            <form @submit.prevent="approve(petition.id)">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('director_petitions.approve') }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import dayjs from 'dayjs'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    title: {
      type: String,
      default: 'Розгляд петицій',
    },
    petitions: Array,
  },
  methods: {
    approve(petitionId) {
      const form = useForm({})
      form.post(`/director/petitions/${petitionId}/approve`, {
        preserveScroll: true,
      })
    },
    reject(petitionId) {
      const form = useForm({})
      form.post(`/director/petitions/${petitionId}/reject`, {
        preserveScroll: true,
      })
    },
    formatDate(dateString) {
      return dayjs(dateString).format('DD.MM.YYYY HH:mm');
    }
  }
}
</script>

<style>
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}
</style>
