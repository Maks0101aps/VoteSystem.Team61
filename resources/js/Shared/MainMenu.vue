<template>
  <div class="flex items-center justify-between w-full">
    <!-- App Logo and Title -->
    <Link class="flex items-center group" href="/">
      <icon name="protest" class="w-8 h-8 fill-green-600 mr-2" />
      <span class="font-bold text-lg text-green-700">VoteSystem.Team61</span>
    </Link>

    <!-- Navigation Icons - Centered -->
    <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center space-x-1">
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Головна" href="/">
        <icon name="house" class="w-5 h-5" :class="isUrl('') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Голосування" href="/voting">
        <icon name="vote" class="w-5 h-5" :class="isUrl('voting') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Петиції" href="/petitions">
        <icon name="petition" class="w-5 h-5" :class="isUrl('petitions') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Звіти" href="/reports">
        <icon name="printer" class="w-5 h-5" :class="isUrl('reports') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
    </div>
    
    <!-- Empty div to maintain flex layout -->
    <div class="w-[100px]"></div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
