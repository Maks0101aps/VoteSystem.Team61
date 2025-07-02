<template>
  <div class="relative min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head title="Головна" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-green-800 mb-2">Платформа шкільного голосування</h1>
        <div class="h-1 w-24 bg-green-500 mx-auto mb-4 rounded-full"></div>
        <p class="text-xl text-green-700" v-if="!guestUser">Привіт, {{ $page.props.auth.user.first_name }}!</p>
        <p class="text-xl text-green-700" v-else>Привіт, гість!</p>
      </div>

      <!-- Короткий опис системи -->
      <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
        <h2 class="text-2xl font-bold text-green-700 mb-3 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Про систему
        </h2>
        <p class="text-green-600">
          Ласкаво просимо до платформи шкільного голосування! Ця система дозволяє учням та вчителям 
          брати участь у голосуваннях та створювати петиції з важливих питань шкільного життя.
          Кожен голос важливий, і разом ми можемо зробити наше шкільне середовище кращим.
        </p>
      </div>

      <!-- Інструкція "Як користуватись" -->
      <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
        <h2 class="text-2xl font-bold text-green-700 mb-4 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          Як користуватись
        </h2>
        <div class="space-y-3">
          <div class="flex items-start p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <div class="bg-green-500 text-white font-bold rounded-full w-7 h-7 flex items-center justify-center mr-3 flex-shrink-0">1</div>
            <div>
              <h3 class="font-bold text-base text-green-800">Переглядайте активні голосування</h3>
              <p class="text-green-600 text-sm">Відвідайте розділ голосувань, щоб побачити всі поточні голосування та петиції</p>
            </div>
          </div>
          
          <div class="flex items-start p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <div class="bg-green-500 text-white font-bold rounded-full w-7 h-7 flex items-center justify-center mr-3 flex-shrink-0">2</div>
            <div>
              <h3 class="font-bold text-base text-green-800">Прийміть участь у голосуванні</h3>
              <p class="text-green-600 text-sm">Ознайомтеся з темою, виберіть відповідь та підтвердіть свій вибір</p>
            </div>
          </div>
          
          <div class="flex items-start p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <div class="bg-green-500 text-white font-bold rounded-full w-7 h-7 flex items-center justify-center mr-3 flex-shrink-0">3</div>
            <div>
              <h3 class="font-bold text-base text-green-800">Створюйте петиції</h3>
              <p class="text-green-600 text-sm">Пропонуйте власні ідеї для вирішення шкільних питань та збирайте підтримку</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Додаткова декоративна секція -->
      <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
        <div class="flex justify-center items-center space-x-4">
          <div class="text-center">
            <div class="inline-block p-3 bg-green-100 rounded-full text-green-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-green-700 text-sm font-medium">Прозорість</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-green-100 rounded-full text-green-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <p class="text-green-700 text-sm font-medium">Безпека</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-green-100 rounded-full text-green-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <p class="text-green-700 text-sm font-medium">Спільнота</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-green-100 rounded-full text-green-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
              </svg>
            </div>
            <p class="text-green-700 text-sm font-medium">Вплив</p>
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
    guestUser: Boolean
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
