<template>
  <div class="flex items-center space-x-1">
    <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Dashboard" href="/">
      <icon name="dashboard" class="w-5 h-5" :class="isUrl('') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
    </Link>
    <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" title="Reports" href="/reports">
      <icon name="printer" class="w-5 h-5" :class="isUrl('reports') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
    </Link>
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
