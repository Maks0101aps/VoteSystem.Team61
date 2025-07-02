<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ $page.props.auth.user.first_name }}!</h1>
    <p class="mb-10 text-gray-600">Here's a summary of what's happening in your school community.</p>

    <!-- Sections for Votings and Petitions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Active Votings Section -->
      <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Active Votings</h2>
        <div class="space-y-4">
          <!-- Voting Card 1 -->
          <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow">
            <h3 class="font-bold text-lg text-gray-800">New School Mascot</h3>
            <p class="text-sm text-gray-500 mb-3">Ends in 3 days</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
              <div class="bg-blue-500 h-2.5 rounded-full" style="width: 45%"></div>
            </div>
            <button class="btn-green text-sm">View Details</button>
            <button class="w-full btn-green">Vote Now</button>
          </div>
          <!-- Voting Card 2 -->
          <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow">
            <h3 class="font-bold text-lg text-gray-800">Charity Bake Sale Date</h3>
            <p class="text-sm text-gray-500 mb-3">You have voted</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
              <div class="bg-gray-400 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <button class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed">Voted</button>
          </div>
        </div>
      </div>

      <!-- Active Petitions Section -->
      <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Active Petitions</h2>
        <div class="space-y-4">
          <!-- Petition Card 1 -->
          <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow">
            <h3 class="font-bold text-lg text-gray-800">Longer Lunch Breaks</h3>
            <p class="text-sm text-gray-500 mb-3">125 / 250 signatures</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
              <div class="bg-green-500 h-2.5 rounded-full" style="width: 50%"></div>
            </div>
            <button class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">Support Petition</button>
          </div>
          <!-- Petition Card 2 -->
          <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow">
            <h3 class="font-bold text-lg text-gray-800">More Books in Library</h3>
            <p class="text-sm text-gray-500 mb-3">Goal reached! Awaiting review.</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-3">
              <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <button class="w-full bg-yellow-400 text-white font-bold py-2 px-4 rounded-lg cursor-not-allowed">Goal Reached</button>
          </div>
        </div>
      </div>

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
}
</script>
