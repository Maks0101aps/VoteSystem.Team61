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
      <div v-if="activeTab === 'history'">
        <!-- Voting History Section -->
        <div class="mb-12">
          <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                <path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 0 0-1.5 0v11.5a.75.75 0 0 0 .75.75h1.5v-12.25ZM21.75 2.25a.75.75 0 0 1 1.5 0v11.5a.75.75 0 0 1-.75.75h-1.5v-12.25ZM12 15.75a.75.75 0 0 1 .75.75v3.19l1.47-1.47a.75.75 0 1 1 1.06 1.06l-2.75 2.75a.75.75 0 0 1-1.06 0l-2.75-2.75a.75.75 0 1 1 1.06-1.06l1.47 1.47v-3.19a.75.75 0 0 1 .75-.75Zm-9-7.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75ZM3 12.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
              </svg>
              <h3 class="text-lg leading-6 font-medium text-gray-900 ml-3">
                История созданных голосований
              </h3>
            </div>
            <div class="border-t border-gray-200 overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.title') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.created_at') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.status') }}</th>
                </tr>
                <tr v-for="item in createdVotings" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t px-6 py-4">{{ item.title }}</td>
                  <td class="border-t px-6 py-4">{{ item.created_at_formatted }}</td>
                  <td class="border-t px-6 py-4">{{ getTranslatedStatus(item) }}</td>
                </tr>
                <tr v-if="createdVotings.length === 0">
                  <td class="border-t px-6 py-4" colspan="3">{{ $t('reports_page.history_table.no_history') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Petition History Section -->
        <div class="mb-12">
          <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a.375.375 0 0 1-.375-.375V6.75A3.75 3.75 0 0 0 9 3H5.625ZM12.75 12a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V18a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V12Z" clip-rule="evenodd" />
                <path d="M14.25 7.5a.75.75 0 0 0 .75.75h.008a.75.75 0 0 0 .75-.75V4.525A2.25 2.25 0 0 0 13.5 2.25H12a.75.75 0 0 0 0 1.5h1.5v3.75Z" />
              </svg>
              <h3 class="text-lg leading-6 font-medium text-gray-900 ml-3">
                История созданных петиций
              </h3>
            </div>
            <div class="border-t border-gray-200 overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.title') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.created_at') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.status') }}</th>
                </tr>
                <tr v-for="item in createdPetitions" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t px-6 py-4">{{ item.title }}</td>
                  <td class="border-t px-6 py-4">{{ item.created_at_formatted }}</td>
                  <td class="border-t px-6 py-4">{{ getTranslatedStatus(item) }}</td>
                </tr>
                <tr v-if="createdPetitions.length === 0">
                  <td class="border-t px-6 py-4" colspan="3">{{ $t('reports_page.history_table.no_history') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Voting Participation History Section -->
        <div class="mb-12">
          <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12c0 1.357-.6 2.573-1.549 3.397a4.49 4.49 0 0 1-1.307 3.498 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75c-1.357 0-2.573-.6-3.397-1.549a4.49 4.49 0 0 1-3.498-1.307 4.491 4.491 0 0 1-1.307-3.497A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.498 4.491 4.491 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
              </svg>
              <h3 class="text-lg leading-6 font-medium text-gray-900 ml-3">
                История участий в голосованиях
              </h3>
            </div>
            <div class="border-t border-gray-200 overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.title') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.created_at') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.status') }}</th>
                </tr>
                <tr v-for="item in participatedVotings" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t px-6 py-4">{{ item.title }}</td>
                  <td class="border-t px-6 py-4">{{ item.created_at_formatted }}</td>
                  <td class="border-t px-6 py-4">{{ getTranslatedStatus(item) }}</td>
                </tr>
                <tr v-if="participatedVotings.length === 0">
                  <td class="border-t px-6 py-4" colspan="3">{{ $t('reports_page.history_table.no_history') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Petition Participation History Section -->
        <div>
          <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
              </svg>
              <h3 class="text-lg leading-6 font-medium text-gray-900 ml-3">
                История участий в петициях
              </h3>
            </div>
            <div class="border-t border-gray-200 overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.title') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.created_at') }}</th>
                  <th class="px-6 pt-6 pb-4">{{ $t('reports_page.history_table.status') }}</th>
                </tr>
                <tr v-for="item in participatedPetitions" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t px-6 py-4">{{ item.title }}</td>
                  <td class="border-t px-6 py-4">{{ item.created_at_formatted }}</td>
                  <td class="border-t px-6 py-4">{{ getTranslatedStatus(item) }}</td>
                </tr>
                <tr v-if="participatedPetitions.length === 0">
                  <td class="border-t px-6 py-4" colspan="3">{{ $t('reports_page.history_table.no_history') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
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
    createdVotings: Array,
    createdPetitions: Array,
    participatedVotings: Array,
    participatedPetitions: Array,
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
