<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="$t('dashboard_page.title')" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-orange-800 mb-2">{{ $t('dashboard_page.platform_title') }}</h1>
        <div class="h-1 w-24 bg-orange-500 mx-auto mb-4 rounded-full"></div>
        <p class="text-xl text-orange-700" v-if="!guestUser">{{ $t('dashboard_page.greeting', { name: $page.props.auth.user.first_name }) }}</p>
        <p class="text-xl text-orange-700" v-else>{{ $t('dashboard_page.guest_greeting') }}</p>
      </div>

      <!-- Короткий опис системи -->
      <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-orange-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
        <h2 class="text-2xl font-bold text-orange-700 mb-3 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ $t('dashboard_page.about_system') }}
        </h2>
        <p class="text-orange-600">
          {{ $t('dashboard_page.about_system_text') }}
        </p>
      </div>

      <!-- Секція "Get Started" -->
      <div class="text-center my-12">
        <h2 class="text-3xl font-bold text-orange-800 mb-4">{{ $t('dashboard_page.get_started_title') }}</h2>
        <p class="text-orange-700 max-w-2xl mx-auto mb-8">{{ $t('dashboard_page.get_started_text') }}</p>
        <div class="flex justify-center gap-4">
          <Link href="/petitions" class="flex-1 bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-orange-500 backdrop-blur-sm bg-opacity-90 text-left">
            <div class="flex items-center mb-2">
              <Icon name="petitions" class="w-8 h-8 text-orange-500 mr-3" />
              <h3 class="text-xl font-bold text-orange-700">{{ $t('dashboard_page.petitions_title') }}</h3>
            </div>
            <p class="text-orange-600">{{ $t('dashboard_page.petitions_text') }}</p>
          </Link>
          <Link href="/votings" class="flex-1 bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 border-green-500 backdrop-blur-sm bg-opacity-90 text-left">
            <div class="flex items-center mb-2">
              <Icon name="votings" class="w-8 h-8 text-green-500 mr-3" />
              <h3 class="text-xl font-bold text-green-700">{{ $t('dashboard_page.votings_title') }}</h3>
            </div>
            <p class="text-green-600">{{ $t('dashboard_page.votings_text') }}</p>
          </Link>
        </div>
      </div>
      
      <!-- Додаткова декоративна секція -->
      <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-orange-500 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300">
        <div class="flex justify-center items-center space-x-4">
          <div class="text-center">
            <div class="inline-block p-3 bg-orange-100 rounded-full text-orange-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-orange-700 text-sm font-medium">{{ $t('dashboard_page.transparency') }}</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-orange-100 rounded-full text-orange-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <p class="text-orange-700 text-sm font-medium">{{ $t('dashboard_page.security') }}</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-orange-100 rounded-full text-orange-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <p class="text-orange-700 text-sm font-medium">{{ $t('dashboard_page.community') }}</p>
          </div>
          <div class="text-center">
            <div class="inline-block p-3 bg-orange-100 rounded-full text-orange-600 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
              </svg>
            </div>
            <p class="text-orange-700 text-sm font-medium">{{ $t('dashboard_page.impact') }}</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Хвилясті узори внизу -->
    <div class="absolute bottom-0 left-0 right-0 h-20 overflow-hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgba(251, 146, 60, 0.2)" fill-opacity="1" d="M0,128L48,138.7C96,149,192,171,288,154.7C384,139,480,85,576,85.3C672,85,768,139,864,165.3C960,192,1056,192,1152,165.3C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link,
    Icon,
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
