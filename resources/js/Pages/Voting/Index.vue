<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-green-800 mb-2">{{ $t('voting_page.title') }}</h1>
        <div class="h-1 w-24 bg-green-500 mx-auto mb-4 rounded-full"></div>
        <p class="text-xl text-green-700">{{ $t('voting_page.active_votings') }}</p>
      </div>

      <!-- Кнопка створення нового голосування -->
      <div class="mb-6 flex justify-end">
        <Link href="/votings/create" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          {{ $t('voting_page.create_vote') }}
        </Link>
      </div>

      <!-- Список голосувань -->
      <div class="space-y-6">
        <div v-if="!votings || votings.length === 0" class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-green-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="text-xl font-medium text-green-700 mb-2">{{ $t('voting_page.no_active_votings') }}</h3>
          <p class="text-green-600 mb-6">{{ $t('voting_page.be_the_first') }}</p>
          <Link href="/votings/create" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ $t('voting_page.create_vote') }}
          </Link>
        </div>
        
        <!-- Тут буде список голосувань з бази даних -->
        <div v-for="voting in votings" :key="voting.id" 
             class="bg-white p-6 rounded-xl shadow-md border-l-4 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300 border-green-500">
          <!-- Вміст картки голосування -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
  props: {
    title: String,
    votings: Array,
  },
}
</script>

<style>
/* Можна додати специфічні стилі, якщо потрібно */
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}
</style>
