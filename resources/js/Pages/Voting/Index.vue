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
          <button @click="filterVotings('my')" :class="{ 'bg-green-600 text-white': currentFilter === 'my', 'bg-white text-green-600': currentFilter !== 'my' }" class="px-4 py-2 rounded-lg transition-colors border border-green-600 hover:bg-green-600 hover:text-white">
            {{ $t('voting_page.filters.my_votings') }}
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
        <div v-if="!votings || votings.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h2 class="text-2xl font-bold text-gray-700 mb-2">{{ $t('voting_page.no_active_votings') }}</h2>
          <p class="text-gray-500 mb-6">{{ $t('voting_page.be_the_first') }}</p>
          <Link href="/votings/create" class="inline-block bg-green-500 text-white font-bold px-6 py-3 rounded-full hover:bg-green-600 transition-colors duration-300">
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
                <div v-if="isEnded(voting)" class="font-bold text-red-500">{{ $t('voting_page.voting_ended') }}</div>
                <div v-else>
                    <div class="font-semibold">{{ $t('voting_page.remaining') }}</div>
                    <div>{{ formatRemainingTime(voting) }}</div>
                </div>
            </div>
        </div>
    </div>
    <p class="text-gray-600 mb-4">
        {{ voting.description }}
    </p>

    <div class="text-sm text-gray-500 mb-4" v-if="voting.visibility && voting.visibility.length > 0">
        {{ buildVisibilityText(voting) }}
    </div>

    <!-- Vote Counts -->
    <div class="flex justify-around items-center mb-4 p-2 bg-gray-50 rounded-lg">
        <div class="text-center">
            <p class="font-bold text-lg text-green-600">{{ voting.votes_for_count }}</p>
            <p class="text-sm text-gray-500">{{ $t('votes.for') }}</p>
        </div>
        <div class="text-center">
            <p class="font-bold text-lg text-red-600">{{ voting.votes_against_count }}</p>
            <p class="text-sm text-gray-500">{{ $t('votes.against') }}</p>
        </div>
        <div class="text-center">
            <p class="font-bold text-lg text-gray-600">{{ voting.votes_abstain_count }}</p>
            <p class="text-sm text-gray-500">{{ $t('votes.abstain') }}</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div v-if="(voting.votes_for_count + voting.votes_against_count + voting.votes_abstain_count) > 0" class="mb-4">
        <div class="h-3 rounded-full flex overflow-hidden text-xs">
            <div :style="{ width: getVotePercentage(voting, 'for') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-lime-500 transition-all duration-500"></div>
            <div :style="{ width: getVotePercentage(voting, 'against') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-500"></div>
            <div :style="{ width: getVotePercentage(voting, 'abstain') + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gray-400 transition-all duration-500"></div>
        </div>
        <div class="flex justify-around text-xs text-gray-600 mt-1.5">
            <div class="flex items-center space-x-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-lime-500"></div>
                <span class="font-medium text-lime-600">{{ getVotePercentage(voting, 'for').toFixed(1) }}% {{ $t('votes.for') }}</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                <span class="font-medium text-red-600">{{ getVotePercentage(voting, 'against').toFixed(1) }}% {{ $t('votes.against') }}</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-gray-400"></div>
                <span class="font-medium text-gray-500">{{ getVotePercentage(voting, 'abstain').toFixed(1) }}% {{ $t('votes.abstain') }}</span>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500 flex items-center">
            <span>{{ $t('voting_page.author') }}: {{ voting.user ? `${voting.user.first_name} ${voting.user.last_name}` : $t('voting_page.anonymous') }}</span>
            <span class="ml-4">{{ formatCreationTime(voting.created_at) }}</span>
            <span class="mx-2">•</span>
            <button @click="toggleComments(voting.id)" class="text-orange-600 hover:underline">
              {{ $t('comments.comments') }} ({{ voting.comments_count }})
            </button>
            <button v-if="voting.user && $page.props.auth.user.id === voting.user.id" @click="destroy(voting.id)" class="ml-4 inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition-colors text-xs font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $t('petitions_page.delete') }}
            </button>
        </div>
        
        <!-- Voting buttons or user's vote -->
        <div v-if="isEnded(voting)">
             <p class="font-semibold text-red-600">{{ $t('voting_page.voting_ended') }}</p>
        </div>
        <div v-else-if="voting.user_vote">
            <p class="font-semibold text-gray-700">{{ $t('votes.you_voted') }}: <span class="capitalize" :class="{
                'text-green-600': voting.user_vote === 'for',
                'text-red-600': voting.user_vote === 'against',
                'text-gray-600': voting.user_vote === 'abstain',
            }">{{ $t('votes.' + voting.user_vote) }}</span></p>
        </div>
        <div v-else class="flex space-x-2">
            <button @click="castVote(voting.id, 'for')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('votes.for') }}
            </button>
            <button @click="castVote(voting.id, 'against')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('votes.against') }}
            </button>
            <button @click="castVote(voting.id, 'abstain')" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('votes.abstain') }}
            </button>
        </div>
    </div>

    <!-- Comments Section -->
    <div v-if="isCommentsVisible(voting.id)" class="mt-4 pt-4 border-t border-gray-200">
      <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $t('comments.comments') }}</h4>
      <div class="space-y-4">
        <div v-for="comment in voting.comments" :key="comment.id" class="bg-gray-50 p-3 rounded-lg">
          <p class="text-gray-700">{{ comment.content }}</p>
          <div class="text-xs text-gray-500 mt-1">
            <strong>{{ comment.user_name }}</strong> - {{ comment.created_at }}
          </div>
        </div>
        <div v-if="voting.comments.length === 0" class="text-gray-500">
          {{ $t('comments.no_comments') }}
        </div>
      </div>

      <!-- Add Comment Form -->
      <form @submit.prevent="addComment(voting.id)" class="mt-4">
        <textarea v-model="commentForms[voting.id].content" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" rows="2" :placeholder="$t('comments.add_comment_placeholder')"></textarea>
        <div v-if="commentForms[voting.id] && commentForms[voting.id].errors.content" class="text-red-500 text-sm mt-1">
            {{ commentForms[voting.id].errors.content }}
        </div>
        <button type="submit" class="mt-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors" :disabled="commentForms[voting.id] && commentForms[voting.id].processing">
          {{ $t('comments.add_comment_button') }}
        </button>
      </form>
    </div>
        </div> <!-- End of v-for item -->
      </div> <!-- End of space-y-6 -->

      <ConfirmationModal 
        :show="showConfirmation" 
        @confirm="confirmDelete" 
        @cancel="cancelDelete"
        :title="$t('voting_page.confirmation_modal.title')"
        :message="$t('voting_page.confirmation_modal.message')"
        :confirmButtonText="$t('voting_page.confirmation_modal.confirm_button')"
        :cancelButtonText="$t('voting_page.confirmation_modal.cancel_button')"
      />
    </div> <!-- End of container -->
  </div> <!-- End of root element -->
</template>

<script>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import ConfirmationModal from '@/Shared/ConfirmationModal.vue';
import Layout from '@/Shared/Layout.vue';
import Icon from '@/Shared/Icon.vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/uk';
import 'dayjs/locale/en';

dayjs.extend(relativeTime);

export default {
  components: {
    Head,
    Link,
    ConfirmationModal,
    Icon,
  },
  layout: Layout,
  setup(props) {
    const visibleComments = ref([]);
    const commentForms = ref({});

    const initializeCommentForms = () => {
      props.votings.forEach(voting => {
        commentForms.value[voting.id] = useForm({ content: '', commentable_type: 'App\Models\Voting' });
      });
    };

    watch(() => props.votings, initializeCommentForms, { immediate: true });

    const toggleComments = (votingId) => {
      const index = visibleComments.value.indexOf(votingId);
      if (index > -1) {
        visibleComments.value.splice(index, 1);
      } else {
        visibleComments.value.push(votingId);
      }
    };

    const isCommentsVisible = (votingId) => {
      return visibleComments.value.includes(votingId);
    };

    const addComment = (votingId) => {
    const form = commentForms.value[votingId];
    if (form.content.trim()) {
        form.transform((data) => ({
            ...data,
            commentable_type: 'App\\Models\\Voting',
        })).post(`/comments/${votingId}`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('content');
            },
        });
    }
};

    return { visibleComments, commentForms, toggleComments, isCommentsVisible, addComment };
  },
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
      showConfirmation: false,
      votingToDelete: null,
      confirmationTitle: '',
      confirmationMessage: '',
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

  computed: {
    votingToDeleteTitle() {
      if (!this.votingToDelete) return '';
      const voting = this.votings.find(v => v.id === this.votingToDelete);
      return voting ? voting.title : '';
    },
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

    isEnded(voting) {
      if (!voting.ends_at) return false;
      return new Date(voting.ends_at) < this.now;
    },
    formatRemainingTime(voting) {
        if (!voting.ends_at) return '';
        
        const endsAt = dayjs(voting.ends_at);
        const diff = endsAt.diff(this.now);

        if (diff <= 0) {
            return this.$t('voting_page.voting_ended');
        }

        return endsAt.locale(this.$i18n.locale).from(this.now, true);
    },
    formatCreationTime(createdAt) {
        return dayjs(createdAt).locale(this.$i18n.locale).fromNow();
    },
    buildVisibilityText(voting) {
        const parts = voting.visibility.map(visibility => {
            const role = this.$t('roles.' + visibility.role);
            if (visibility.role === 'student' && visibility.class_number && visibility.class_letter) {
                return `${role} (${visibility.class_number}-${visibility.class_letter})`;
            }
            return role;
        });

        if (parts.length === 0) {
            return '';
        }

        return `${this.$t('voting_page.for_whom_prefix')} ${parts.join(', ')}`;
    },
    filterVotings(filter) {
      this.currentFilter = filter;
      let query = {};
{
        query.filter = filter;
      }
      router.get('/votings', query, { preserveState: true, replace: true });
    },
    destroy(votingId) {
      this.votingToDelete = votingId;
      this.confirmationTitle = this.$t('voting_page.confirmation_modal.trash_title');
      this.confirmationMessage = this.$t('voting_page.confirmation_modal.trash_message');
      this.showConfirmation = true;
    },
    restore(votingId) {
      router.put(`/votings/${votingId}/restore`, {}, {
        preserveScroll: true,
      });
    },
    confirmDelete() {
      if (this.votingToDelete) {
        router.delete(`/votings/${this.votingToDelete}`, {
          preserveScroll: true,
          onFinish: () => {
            this.showConfirmation = false;
            this.votingToDelete = null;
          },
        });
      }
    },
    cancelDelete() {
      this.showConfirmation = false;
      this.votingToDelete = null;
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
