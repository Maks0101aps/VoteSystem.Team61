<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="$t('reports_page.title')" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-orange-800 mb-2">{{ $t('reports_page.title') }}</h1>
        <div class="h-1 w-24 bg-orange-500 mx-auto mb-4 rounded-full"></div>
      </div>

      <!-- Statistics Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
          <div class="p-3 rounded-full bg-orange-100 mr-4">
            <icon name="clipboard-list" class="w-8 h-8 text-orange-500" />
          </div>
          <div>
            <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.petitions_last_month') }}</h3>
            <p class="text-3xl font-bold text-orange-700">{{ stats.petitionsLastMonth }}</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
          <div class="p-3 rounded-full bg-green-100 mr-4">
            <icon name="badge-check" class="w-8 h-8 text-green-500" />
          </div>
          <div>
            <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.acceptance_percentage') }}</h3>
            <p class="text-3xl font-bold text-green-700">{{ stats.acceptancePercentage }}%</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
          <div class="p-3 rounded-full bg-blue-100 mr-4">
            <icon name="archive" class="w-8 h-8 text-blue-500" />
          </div>
          <div>
            <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.total_votings') }}</h3>
            <p class="text-3xl font-bold text-blue-700">{{ stats.totalVotings }}</p>
          </div>
        </div>
      </div>

      <!-- Votings Section -->
      <div v-if="votings.length === 0" class="bg-white p-6 rounded-lg shadow-md text-center backdrop-blur-sm bg-opacity-90">
        <p class="text-gray-500">{{ $t('reports_page.no_votings') }}</p>
      </div>
      <div v-else>
        <div v-for="voting in votings" :key="voting.id" class="mb-6 bg-white rounded-lg shadow-md overflow-hidden backdrop-blur-sm bg-opacity-90">
          <div class="p-6">
            <h2 class="text-xl font-bold mb-2 text-orange-800">{{ voting.title }}</h2>
            <p class="text-gray-600 mb-4">{{ voting.description }}</p>
            <div v-if="voting.options.length > 0" class="space-y-4">
              <div v-for="option in voting.options" :key="option.id">
                <div class="flex justify-between items-center mb-1">
                  <span class="font-semibold text-orange-700">{{ option.title }}</span>
                  <span class="text-sm font-medium text-gray-500">{{ option.user_votes_count }} {{ $t('reports_page.votes') }} ({{ getVotePercentage(voting, option) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-orange-500 h-2.5 rounded-full" :style="{ width: getVotePercentage(voting, option) + '%' }"></div>
                </div>
              </div>
            </div>
            <div v-else>
              <p class="text-gray-500">{{ $t('reports_page.no_options') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Wavy patterns -->
    <div class="absolute bottom-0 left-0 right-0 h-20 overflow-hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgba(251, 146, 60, 0.2)" fill-opacity="1" d="M0,128L48,138.7C96,149,192,171,288,154.7C384,139,480,85,576,85.3C672,85,768,139,864,165.3C960,192,1056,192,1152,165.3C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Head,
    Icon,
  },
  layout: Layout,
  props: {
    votings: Array,
    stats: Object,
  },
  methods: {
    getTotalVotes(voting) {
      if (!voting.options || voting.options.length === 0) {
        return 0;
      }
      return voting.options.reduce((total, option) => total + option.user_votes_count, 0);
    },
    getVotePercentage(voting, option) {
      const totalVotes = this.getTotalVotes(voting);
      if (totalVotes === 0) {
        return 0;
      }
      return ((option.user_votes_count / totalVotes) * 100).toFixed(1);
    },
  },
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
