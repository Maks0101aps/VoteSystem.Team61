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
        <p class="text-xl text-green-700">Активні голосування</p>
      </div>

      <!-- Список голосувань -->
      <div class="space-y-6">
        <!-- Картка голосування (приклад) -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-green-700">Вибори президента шкільного самоврядування</h2>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Активне</span>
          </div>
          <p class="text-green-600 mb-4">
            Голосування за кандидатів на посаду президента шкільного самоврядування на 2023-2024 навчальний рік.
          </p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
              <span>Закінчується через: 3 дні</span>
            </div>
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
              Голосувати
            </button>
          </div>
        </div>

        <!-- Ще одна картка голосування (приклад) -->
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-green-700">Тематика шкільного свята</h2>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Активне</span>
          </div>
          <p class="text-green-600 mb-4">
            Голосування за тематику наступного шкільного свята. Виберіть один з запропонованих варіантів.
          </p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
              <span>Закінчується через: 7 днів</span>
            </div>
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
              Голосувати
            </button>
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
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    title: String
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