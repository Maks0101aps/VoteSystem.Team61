<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-orange-800 mb-2">{{ $t(title) }}</h1>
        <div class="h-1 w-24 bg-orange-500 mx-auto mb-4 rounded-full"></div>
      </div>

      <!-- Tabs -->
      <div class="mb-8 flex justify-center border-b border-gray-300">
        <button @click="activeTab = 'stats'" :class="['px-4 py-2 text-lg font-medium', activeTab === 'stats' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-orange-500']">
          {{ $t('reports_page.statistics_tab') }}
        </button>
        <button @click="activeTab = 'history'" :class="['px-4 py-2 text-lg font-medium', activeTab === 'history' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-orange-500']">
          {{ $t('reports_page.history_tab') }}
        </button>
      </div>

      <!-- Stats Tab Content -->
      <div v-if="activeTab === 'stats'">
        <!-- Section: Activity Last Month -->
        <div class="mb-12">
          <h2 class="text-2xl font-bold text-orange-700 mb-6 border-b-2 border-orange-200 pb-2">{{ $t('reports_page.monthly_activity') }}</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card for petitionsLastMonth -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
              <div class="p-3 rounded-full bg-orange-100 mr-4">
                <icon name="document-text" class="w-8 h-8 text-orange-500" />
              </div>
              <div>
                <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.petitions_last_month') }}</h3>
                <p class="text-3xl font-bold text-orange-700">{{ stats.petitionsLastMonth }}</p>
              </div>
            </div>
            <!-- Card for acceptancePercentage -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
              <div class="p-3 rounded-full bg-green-100 mr-4">
                <icon name="chart-pie" class="w-8 h-8 text-green-500" />
              </div>
              <div>
                <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.acceptance_percentage') }}</h3>
                <p class="text-3xl font-bold text-green-700">{{ stats.acceptancePercentage }}%</p>
              </div>
            </div>
            <!-- Card for votingsLastMonth -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
              <div class="p-3 rounded-full bg-blue-100 mr-4">
                <icon name="vote" class="w-8 h-8 text-blue-500" />
              </div>
              <div>
                <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.votings_last_month') }}</h3>
                <p class="text-3xl font-bold text-blue-700">{{ stats.votingsLastMonth }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Section: All-Time Statistics -->
        <div>
          <h2 class="text-2xl font-bold text-orange-700 mb-6 border-b-2 border-orange-200 pb-2">{{ $t('reports_page.all_time_stats') }}</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card for totalUsers -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
              <div class="p-3 rounded-full bg-teal-100 mr-4">
                <icon name="users" class="w-8 h-8 text-teal-500" />
              </div>
              <div>
                <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.total_users') }}</h3>
                <p class="text-3xl font-bold text-teal-700">{{ stats.totalUsers }}</p>
              </div>
            </div>
            <!-- Card for totalVotesCast -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center backdrop-blur-sm bg-opacity-90">
              <div class="p-3 rounded-full bg-amber-100 mr-4">
                <icon name="check-circle" class="w-8 h-8 text-amber-500" />
              </div>
              <div>
                <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.total_votes_cast') }}</h3>
                <p class="text-3xl font-bold text-amber-700">{{ stats.totalVotesCast }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- History Tab Content -->
      <div v-if="activeTab === 'history'" class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.title') }}</th>
            <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.type') }}</th>
            <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.created_at') }}</th>
            <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.status') }}</th>
          </tr>
          <tr v-for="item in history" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t px-6 py-4">{{ item.title }}</td>
            <td class="border-t px-6 py-4">{{ getTranslatedType(item.type) }}</td>
            <td class="border-t px-6 py-4">{{ item.created_at_formatted }}</td>
            <td class="border-t px-6 py-4">{{ getTranslatedStatus(item) }}</td>
          </tr>
          <tr v-if="history.length === 0">
            <td class="border-t px-6 py-4" colspan="4">{{ $t('reports_page.history_table.no_history') }}</td>
          </tr>
        </table>
      </div>

    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
import Icon from '@/Shared/Icon.vue';

export default {
  components: {
    Head,
    Icon,
  },
  layout: Layout,
  props: {
    title: String,
    stats: Object,
    history: Array,
  },
  data() {
    return {
      activeTab: 'stats',
    };
  },
  methods: {
    getTranslatedType(type) {
      return this.$t(`reports_page.history_table.type_${type}`);
    },
    getTranslatedStatus(item) {
      if (item.type === 'voting') {
        return this.$t(`votes.${item.status}`);
      }
      return this.$t(`petitions_page.statuses.${item.status}`);
    },
  },
};
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
