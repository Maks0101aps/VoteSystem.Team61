<template>
  <div>
    <Head :title="$t('reports_page.title')" />
    <h1 class="mb-8 text-3xl font-bold">{{ $t('reports_page.title') }}</h1>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <icon name="petition" class="w-12 h-12 text-green-500 mr-4" />
        <div>
          <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.petitions_last_month') }}</h3>
          <p class="text-3xl font-bold text-green-700">{{ stats.petitionsLastMonth }}</p>
        </div>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <icon name="protest" class="w-12 h-12 text-green-500 mr-4" />
        <div>
          <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.acceptance_percentage') }}</h3>
          <p class="text-3xl font-bold text-green-700">{{ stats.acceptancePercentage }}%</p>
        </div>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
        <icon name="vote" class="w-12 h-12 text-green-500 mr-4" />
        <div>
          <h3 class="text-gray-500 font-semibold">{{ $t('reports_page.total_votings') }}</h3>
          <p class="text-3xl font-bold text-green-700">{{ stats.totalVotings }}</p>
        </div>
      </div>
    </div>

    <!-- Votings Section -->
    <div v-if="votings.length === 0" class="bg-white p-6 rounded-lg shadow-md text-center">
      <p class="text-gray-500">{{ $t('reports_page.no_votings') }}</p>
    </div>
    <div v-else>
      <div v-for="voting in votings" :key="voting.id" class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-bold mb-2">{{ voting.title }}</h2>
          <p class="text-gray-600 mb-4">{{ voting.description }}</p>
          <div v-if="voting.options.length > 0" class="space-y-4">
            <div v-for="option in voting.options" :key="option.id">
              <div class="flex justify-between items-center mb-1">
                <span class="font-semibold">{{ option.title }}</span>
                <span class="text-sm font-medium text-gray-500">{{ option.user_votes_count }} {{ $t('reports_page.votes') }} ({{ getVotePercentage(voting, option) }}%)</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-500 h-2.5 rounded-full" :style="{ width: getVotePercentage(voting, option) + '%' }"></div>
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
