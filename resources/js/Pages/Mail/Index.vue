<script setup>
import { ref } from 'vue';
import {
  InboxIcon,
  TrashIcon,
  PaperAirplaneIcon,
  UserCircleIcon,
} from '@heroicons/vue/24/outline';

const emails = ref([
  { id: 1, from: 'John Doe', subject: 'Regarding your recent inquiry', snippet: 'Thank you for reaching out to us. We have received your message and...', time: '10:45 AM', read: false },
  { id: 2, from: 'Jane Smith', subject: 'Your order has been shipped!', snippet: 'Great news! Your order #12345 has been shipped and is on its way...', time: 'Yesterday', read: true },
  { id: 3, from: 'Support Team', subject: 'Password Reset Request', snippet: 'We received a request to reset your password. If this was you, please...', time: '2 days ago', read: true },
  { id: 4, from: 'Marketing Weekly', subject: 'New deals just for you!', snippet: 'Check out our latest offers and discounts, available for a limited time...', time: '3 days ago', read: false },
]);

const selectedEmail = ref(emails.value[0]);

const selectEmail = (email) => {
  selectedEmail.value = email;
  email.read = true;
};
</script>

<template>
  <div class="flex h-screen font-sans bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-1/3 bg-white border-r border-gray-200 lg:w-1/4">
      <div class="p-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Inbox</h2>
        <p class="text-sm text-gray-500">{{ emails.filter(e => !e.read).length }} unread</p>
      </div>
      <div class="overflow-y-auto">
        <ul>
          <li
            v-for="email in emails"
            :key="email.id"
            @click="selectEmail(email)"
            class="p-4 border-b border-gray-200 cursor-pointer hover:bg-orange-50"
            :class="{ 'bg-orange-100 font-bold': !email.read, 'bg-white': email.read, 'bg-orange-200': selectedEmail && selectedEmail.id === email.id }"
          >
            <div class="flex items-center justify-between">
              <p class="text-sm text-gray-800">{{ email.from }}</p>
              <p class="text-xs text-gray-500">{{ email.time }}</p>
            </div>
            <p class="text-sm text-gray-900 truncate">{{ email.subject }}</p>
            <p class="text-xs text-gray-600 truncate">{{ email.snippet }}</p>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <div v-if="selectedEmail" class="w-full h-full bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
          <div>
            <h3 class="text-lg font-bold text-gray-900">{{ selectedEmail.subject }}</h3>
            <div class="flex items-center mt-1 text-sm text-gray-600">
              <UserCircleIcon class="w-5 h-5 mr-2 text-gray-400" />
              <span>From: {{ selectedEmail.from }}</span>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100 hover:text-orange-500">
              <PaperAirplaneIcon class="w-5 h-5" />
            </button>
            <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100 hover:text-orange-500">
              <TrashIcon class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Email Body -->
        <div class="p-6 text-gray-800">
          <p>Hi there,</p>
          <br />
          <p>
            This is a placeholder for the full email content. The actual content for the email with the subject
            <strong>{{ selectedEmail.subject }}</strong> would be displayed here.
          </p>
          <br />
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
          </p>
          <br />
          <p>Best regards,</p>
          <p><strong>{{ selectedEmail.from }}</strong></p>
        </div>
      </div>
      <div v-else class="flex items-center justify-center w-full h-full text-gray-500">
        <div class="text-center">
            <InboxIcon class="w-16 h-16 mx-auto text-gray-300"/>
            <p>Select an email to read</p>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Scoped styles can be added here if needed */
</style>
