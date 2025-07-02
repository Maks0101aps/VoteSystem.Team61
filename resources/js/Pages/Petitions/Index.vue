<template>
  <div class="relative min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-green-800 mb-2">{{ title }}</h1>
        <div class="h-1 w-24 bg-green-500 mx-auto mb-4 rounded-full"></div>
        <p class="text-xl text-green-700">Активні петиції</p>
      </div>

      <!-- Кнопка створення нової петиції -->
      <div class="mb-6 flex justify-end">
        <Link href="/petitions/create" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Створити петицію
        </Link>
      </div>

      <!-- Список петицій -->
      <div class="space-y-6">
        <div v-if="petitions.length === 0" class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-green-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="text-xl font-medium text-green-700 mb-2">Наразі немає активних петицій</h3>
          <p class="text-green-600 mb-6">Створіть першу петицію, щоб почати збирати підписи</p>
          <Link href="/petitions/create" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Створити петицію
          </Link>
        </div>
        
        <!-- Петиції з бази даних -->
        <div v-for="petition in petitions" :key="petition.id" 
             class="bg-white p-6 rounded-xl shadow-md border-l-4 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300"
             :class="{'border-green-500': !petition.is_completed, 'border-green-300': petition.is_completed}">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-green-700">{{ petition.title }}</h2>
            <span v-if="!petition.is_completed" class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">Збір підписів</span>
            <span v-else class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Підтримано</span>
          </div>
          <p class="text-green-600 mb-4">
            {{ petition.description }}
          </p>
          <div class="mb-4 bg-gray-100 rounded-full h-2">
            <div class="bg-green-500 h-2 rounded-full" 
                 :style="{width: Math.min(100, (petition.signatures_count / petition.signatures_required) * 100) + '%'}"></div>
          </div>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
              <span>Підписів: {{ petition.signatures_count }} з {{ petition.signatures_required }}</span>
              <span class="ml-4">Автор: {{ petition.author }}</span>
              <span class="ml-4">Створено: {{ petition.created_at }}</span>
            </div>
            <form v-if="!petition.is_signed && !petition.is_completed" @submit.prevent="sign(petition.id)">
              <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                Підписати
              </button>
            </form>
            <button v-else-if="petition.is_signed" class="bg-gray-300 text-white px-4 py-2 rounded-lg cursor-not-allowed">
              Підписано
            </button>
            <span v-else class="bg-green-100 text-green-700 px-4 py-2 rounded-lg">
              Виконано
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Хвилясті узори внизу -->
    <div class="absolute bottom-0 left-0 right-0 h-20 overflow-hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgba(52, 211, 153, 0.2)" fill-opacity="1" d="M0,128L48,138.7C96,149,192,171,288,154.7C384,139,480,85,576,85.3C672,85,768,139,864,165.3C960,192,1056,192,1152,165.3C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import { useForm } from '@inertiajs/vue3'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    title: String,
    petitions: Array,
  },
  methods: {
    sign(petitionId) {
      const form = useForm({})
      form.post(`/petitions/${petitionId}/sign`, {
        preserveScroll: true,
        onSuccess: () => {
          // Success is handled by the flash messages
        },
      })
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