<template>
  <div class="relative min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-4xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-orange-800 mb-2">{{ $t('petitions_page.title') }}</h1>
        <div class="h-1 w-24 bg-orange-500 mx-auto mb-4 rounded-full"></div>
        <p class="text-xl text-orange-700">{{ $t('petitions_page.active_petitions') }}</p>
      </div>

      <!-- Кнопка створення нової петиції -->
      <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-2">
          <button @click="filterPetitions('all')" :class="{ 'bg-orange-600 text-white': currentFilter === 'all', 'bg-white text-orange-600': currentFilter !== 'all' }" class="px-4 py-2 rounded-lg transition-colors border border-orange-600 hover:bg-orange-600 hover:text-white">
            {{ $t('petitions_page.filters.all') }}
          </button>
          <button @click="filterPetitions('active')" :class="{ 'bg-orange-600 text-white': currentFilter === 'active', 'bg-white text-orange-600': currentFilter !== 'active' }" class="px-4 py-2 rounded-lg transition-colors border border-orange-600 hover:bg-orange-600 hover:text-white">
            {{ $t('petitions_page.filters.active') }}
          </button>
          <button @click="filterPetitions('completed')" :class="{ 'bg-orange-600 text-white': currentFilter === 'completed', 'bg-white text-orange-600': currentFilter !== 'completed' }" class="px-4 py-2 rounded-lg transition-colors border border-orange-600 hover:bg-orange-600 hover:text-white">
            {{ $t('petitions_page.filters.completed') }}
          </button>
        </div>

        <Link v-if="$page.props.auth.user.role === 'student'" href="/petitions/create" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-lg transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          {{ $t('petitions_page.create_petition') }}
        </Link>
      </div>

      <!-- Список петицій -->
      <div class="space-y-6">
        <div v-if="petitions.length === 0" class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-orange-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="text-xl font-medium text-orange-700 mb-2">{{ $t('petitions_page.no_active_petitions') }}</h3>
          <p v-if="$page.props.auth.user.role === 'student'" class="text-orange-600 mb-6">{{ $t('petitions_page.create_first_petition') }}</p>
          <Link v-if="$page.props.auth.user.role === 'student'" href="/petitions/create" class="inline-flex items-center bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ $t('petitions_page.create_petition') }}
          </Link>
        </div>
        
        <!-- Петиції з бази даних -->
        <div v-for="petition in processedPetitions" :key="petition.id" 
             class="bg-white p-6 rounded-xl shadow-md border-l-4 backdrop-blur-sm bg-opacity-90 hover:shadow-lg transition-all duration-300"
             :class="statusBorderClass(petition.status)">
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-orange-700">{{ petition.title }}</h2>
            <span v-if="petition.isExpired && petition.status === 'active'" class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">{{ $t('petitions_page.expired') }}</span>
            <span v-else :class="statusBadgeClass(petition.status)" class="px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
              {{ statusText(petition.status) }}
            </span>
          </div>
          <p class="text-orange-600 mb-4">
            {{ petition.description }}
          </p>
          <div class="relative mb-4">
            <div class="overflow-hidden h-5 text-xs flex rounded-full bg-gray-200">
              <div :style="{ width: petition.percentage + '%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-orange-500 transition-all duration-500">
              </div>
            </div>
            <div class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center">
                <span class="text-sm font-bold" :class="{'text-white': petition.percentage > 40, 'text-gray-700': petition.percentage <= 40}">
                    {{ petition.percentage.toFixed(1) }}%
                </span>
            </div>
          </div>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
              <span>{{ $t('petitions_page.signatures') }}: {{ petition.signatures_count }} {{ $t('petitions_page.from') }} {{ petition.signatures_required }}</span>
              <span class="ml-4">{{ $t('petitions_page.author') }}: {{ petition.author }}</span>
              <span class="ml-4">{{ $t('petitions_page.created') }}: {{ petition.created_at }}</span>
              <span class="ml-4">{{ $t('petitions_page.ends') }}: {{ petition.ends_at }}</span>
              <span v-if="petition.target_class" class="ml-4">{{ $t('petitions_page.class') }}: {{ petition.target_class }}</span>
            </div>
            <form v-if="$page.props.auth.user.role === 'student' && petition.status === 'active' && !petition.is_signed && !petition.isExpired" @submit.prevent="sign(petition.id)">
              <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('petitions_page.sign') }}
              </button>
            </form>
            <button v-else-if="petition.is_signed" class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">
              {{ $t('petitions_page.signed') }}
            </button>
            <button @click="toggleComments(petition.id)" class="text-sm text-orange-600 hover:underline">
              {{ $t('comments.comments') }} ({{ petition.comments_count }})
            </button>
            <button v-if="$page.props.auth.user.id === petition.user_id" @click="destroy(petition.id)" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $t('petitions_page.delete') }}
              </button>
          </div>

          <!-- Comments Section -->
          <div v-if="isCommentsVisible(petition.id)" class="mt-4 pt-4 border-t border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $t('comments.comments') }}</h4>
            <div class="space-y-4">
              <div v-for="comment in petition.comments" :key="comment.id" class="bg-gray-50 p-3 rounded-lg">
                <p class="text-gray-700">{{ comment.content }}</p>
                <div class="text-xs text-gray-500 mt-1">
                  <strong>{{ comment.user_name }}</strong> - {{ comment.created_at }}
                </div>
              </div>
              <div v-if="petition.comments.length === 0" class="text-gray-500">
                {{ $t('comments.no_comments') }}
              </div>
            </div>

            <!-- Add Comment Form -->
            <form @submit.prevent="addComment(petition.id)" class="mt-4">
              <textarea v-model="commentForms[petition.id].content" class="w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" rows="2" :placeholder="$t('comments.add_comment_placeholder')"></textarea>
              <button type="submit" class="mt-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ $t('comments.add_comment_button') }}
              </button>
            </form>
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
    <ConfirmationModal 
      :show="showConfirmation" 
      @confirm="confirmDelete" 
      @cancel="cancelDelete"
      :title="$t('petitions_page.confirmation_modal.title')"
      :message="$t('petitions_page.confirmation_modal.message')"
      :confirmButtonText="$t('petitions_page.confirmation_modal.confirm_button')"
      :cancelButtonText="$t('petitions_page.confirmation_modal.cancel_button')"
    />
  </div>
</template>

<script>
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import ConfirmationModal from '@/Shared/ConfirmationModal.vue';

import dayjs from 'dayjs'
import customParseFormat from 'dayjs/plugin/customParseFormat'
dayjs.extend(customParseFormat)

export default {
  components: {
    Head,
    Link,
    ConfirmationModal,
  },
  layout: Layout,
  props: {
    title: String,
    petitions: Array,
    filters: Object,
  },
  data() {
    return {
      showConfirmation: false,
      petitionToDelete: null,
      currentFilter: this.filters.filter || 'all',
      visibleComments: [],
      commentForms: {},
    };
  },
  computed: {
    processedPetitions() {
      return this.petitions.map(petition => {
        const endsAt = dayjs(petition.ends_at, 'DD.MM.YYYY HH:mm');
        const isExpired = dayjs().isAfter(endsAt);
        const percentage = petition.signatures_required > 0 ? Math.min(100, (petition.signatures_count / petition.signatures_required) * 100) : 0;
        return { ...petition, isExpired, percentage };
      });
    }
  },
  watch: {
    petitions: {
      handler: 'initializeCommentForms',
      immediate: true,
    },
  },
  methods: {
    initializeCommentForms() {
      this.petitions.forEach(petition => {
        this.commentForms[petition.id] = useForm({
          content: '',
          commentable_type: 'App\\Models\\Petition',
        });
      });
    },
    statusText(status) {
      return this.$t(`petitions_page.statuses.${status}`);
    },
    statusBadgeClass(status) {
        const classes = {
            active: 'bg-amber-100 text-amber-800',
            pending_review: 'bg-blue-100 text-blue-800',
            approved: 'bg-green-100 text-green-800',
            rejected: 'bg-red-100 text-red-800',
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    },
    statusBorderClass(status) {
        const classes = {
            active: 'border-amber-500',
            pending_review: 'border-blue-500',
            approved: 'border-green-500',
            rejected: 'border-red-500',
        };
        return classes[status] || 'border-gray-500';
    },
    sign(petitionId) {
      const form = useForm({})
      form.post(`/petitions/${petitionId}/sign`, {
        preserveScroll: true,
        onSuccess: () => {
          // Success is handled by the flash messages
        },
      })
    },
    destroy(petitionId) {
      this.petitionToDelete = petitionId;
      this.showConfirmation = true;
    },
    confirmDelete() {
      if (this.petitionToDelete) {
        router.delete(`/petitions/${this.petitionToDelete}`, {
          preserveScroll: true,
          onFinish: () => {
            this.showConfirmation = false;
            this.petitionToDelete = null;
          },
        });
      }
    },
    cancelDelete() {
      this.showConfirmation = false;
      this.petitionToDelete = null;
    },
    toggleComments(petitionId) {
      const index = this.visibleComments.indexOf(petitionId);
      if (index > -1) {
        this.visibleComments.splice(index, 1);
      } else {
        this.visibleComments.push(petitionId);
      }
    },
    isCommentsVisible(petitionId) {
      return this.visibleComments.includes(petitionId);
    },
    addComment(petitionId) {
      const form = this.commentForms[petitionId];
      if (form.content.trim()) {
          form.post(`/comments/${petitionId}`, {
              preserveScroll: true,
              onSuccess: () => {
                  form.reset('content');
              },
          });
      }
    },
    filterPetitions(filter) {
      this.currentFilter = filter;
      router.get('/petitions', { filter: filter }, { preserveState: true });
    }
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