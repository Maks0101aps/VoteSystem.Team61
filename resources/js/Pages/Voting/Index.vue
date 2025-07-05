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

      <!-- Фільтри і кнопка створення -->
      <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-2">
          <button @click="filterVotings('all')" :class="{ 'bg-green-600 text-white': currentFilter === 'all', 'bg-white text-green-600': currentFilter !== 'all' }" class="px-4 py-2 rounded-lg transition-colors border border-green-600 hover:bg-green-600 hover:text-white">
            {{ $t('petitions_page.filters.all') }}
          </button>
          <button @click="filterVotings('active')" :class="{ 'bg-green-600 text-white': currentFilter === 'active', 'bg-white text-green-600': currentFilter !== 'active' }" class="px-4 py-2 rounded-lg transition-colors border border-green-600 hover:bg-green-600 hover:text-white">
            {{ $t('petitions_page.filters.active') }}
          </button>
          <button @click="filterVotings('completed')" :class="{ 'bg-green-600 text-white': currentFilter === 'completed', 'bg-white text-green-600': currentFilter !== 'completed' }" class="px-4 py-2 rounded-lg transition-colors border border-green-600 hover:bg-green-600 hover:text-white">
            {{ $t('petitions_page.filters.completed') }}
          </button>
        </div>
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
    <div class="flex justify-between items-start mb-4">
        <h2 class="text-2xl font-bold text-green-700">{{ voting.title }}</h2>
        <div class="text-sm text-gray-500 text-right">
            <div v-if="voting.ends_at">
                <div v-if="isEnded(voting)" class="font-bold text-red-500">Голосування завершено</div>
                <div v-else>
                    <div class="font-semibold">Залишилось:</div>
                    <div>{{ formatRemainingTime(voting) }}</div>
                </div>
            </div>
        </div>
    </div>
    <p class="text-gray-600 mb-4">
        {{ voting.description }}
    </p>

    <!-- Vote Counts -->
    <div class="flex justify-around items-center mb-4 p-2 bg-gray-50 rounded-lg">
        <div class="text-center">
            <p class="font-bold text-lg text-green-600">{{ voting.votes_for_count }}</p>
            <p class="text-sm text-gray-500">За</p>
        </div>
        <div class="text-center">
            <p class="font-bold text-lg text-red-600">{{ voting.votes_against_count }}</p>
            <p class="text-sm text-gray-500">Против</p>
        </div>
        <div class="text-center">
            <p class="font-bold text-lg text-gray-600">{{ voting.votes_abstain_count }}</p>
            <p class="text-sm text-gray-500">Воздержались</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div v-if="(voting.votes_for_count + voting.votes_against_count + voting.votes_abstain_count) > 0" class="mb-4">
        <div class="h-3 rounded-full flex overflow-hidden text-xs">
            <div :style="{ width: getVotePercentage(voting, 'for') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-lime-500 transition-all duration-500"></div>
            <div :style="{ width: getVotePercentage(voting, 'against') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-500"></div>
            <div :style="{ width: getVotePercentage(voting, 'abstain') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gray-400 transition-all duration-500"></div>
        </div>
    </div>

    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500">
            <span>Автор: {{ voting.user ? `${voting.user.first_name} ${voting.user.last_name}` : 'Анонім' }}</span>
            <span class="ml-4">{{ voting.created_at }}</span>
        </div>
        
        <!-- Voting buttons or user's vote -->
        <div v-if="isEnded(voting)">
             <p class="font-semibold text-red-600">Голосування завершено</p>
        </div>
        <div v-else-if="voting.user_vote">
            <p class="font-semibold text-gray-700">Вы проголосовали: <span class="capitalize" :class="{
                'text-green-600': voting.user_vote === 'for',
                'text-red-600': voting.user_vote === 'against',
                'text-gray-600': voting.user_vote === 'abstain',
            }">{{ translateVote(voting.user_vote) }}</span></p>
        </div>
        <div v-else class="flex space-x-2">
            <button @click="castVote(voting.id, 'for')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                За
            </button>
            <button @click="castVote(voting.id, 'against')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                Против
            </button>
            <button @click="castVote(voting.id, 'abstain')" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors">
                Воздержусь
            </button>
        </div>
    </div>
</div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
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
    server_time: String,
    filters: Object,
  },
  data() {
    return {
      now: new Date(this.server_time),
      interval: null,
      currentFilter: this.filters.filter || 'all',
    }
  },
  mounted() {
    this.interval = setInterval(() => {
      this.now = new Date(this.now.getTime() + 1000);
    }, 1000);
  },
  unmounted() {
    clearInterval(this.interval);
  },
  methods: {
    castVote(votingId, choice) {
      const form = useForm({
        choice: choice,
      });
      form.post(`/votings/${votingId}/vote`, {
        preserveScroll: true,
      });
    },
    translateVote(choice) {
      const translations = {
        for: 'За',
        against: 'Против',
        abstain: 'Воздержался',
      };
      return translations[choice] || choice;
    },
    isEnded(voting) {
      if (!voting.ends_at) return false;
      return new Date(voting.ends_at) < this.now;
    },
    formatRemainingTime(voting) {
      if (!voting.ends_at) return '';
      
      const endsAt = new Date(voting.ends_at);
      const diff = endsAt - this.now;

      if (diff <= 0) {
        return 'Голосування завершено';
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((diff / 1000 / 60) % 60);
      const seconds = Math.floor((diff / 1000) % 60);

      let parts = [];
      if (days > 0) parts.push(`${days}д`);
      if (hours > 0) parts.push(`${hours}г`);
      if (minutes > 0) parts.push(`${minutes}хв`);
      if (seconds > 0) parts.push(`${seconds}с`);

      return parts.join(' ');
    },
    filterVotings(filter) {
      this.currentFilter = filter;
      router.get('/votings', { filter: filter }, { preserveState: true });
    },
    getVotePercentage(voting, choice) {
      const totalVotes = voting.votes_for_count + voting.votes_against_count + voting.votes_abstain_count;
      if (totalVotes === 0) {
        return 0;
      }
      switch (choice) {
        case 'for':
          return (voting.votes_for_count / totalVotes) * 100;
        case 'against':
          return (voting.votes_against_count / totalVotes) * 100;
        case 'abstain':
          return (voting.votes_abstain_count / totalVotes) * 100;
        default:
          return 0;
      }
    },
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
