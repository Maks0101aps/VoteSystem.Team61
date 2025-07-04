<template>
  <div class="flex items-center justify-between w-full">
    <!-- App Logo and Title -->
    <Link class="flex items-center group" href="/">
      <icon name="protest" class="w-8 h-8 fill-green-600 mr-2" />
      <span class="font-bold text-lg text-green-700">VoteSystem.Team61</span>
    </Link>

    <!-- Navigation Icons - Centered -->
    <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center space-x-1">
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.home')" href="/">
        <icon name="house" class="w-5 h-5" :class="isUrl('') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.voting')" href="/voting">
        <icon name="vote" class="w-5 h-5" :class="isUrl('voting') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.petitions')" href="/petitions">
        <icon name="petition" class="w-5 h-5" :class="isUrl('petitions') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
      <Link class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.reports')" href="/reports">
        <icon name="printer" class="w-5 h-5" :class="isUrl('reports') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
            <Link v-if="$page.props.auth.user.role === 'director'" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.messages')" href="/messages">
        <icon name="messages" class="w-5 h-5" :class="isUrl('messages') ? 'fill-green-600' : 'fill-gray-500 group-hover:fill-green-500'" />
      </Link>
    </div>
    
    <!-- Language Switcher -->
    <div class="w-[100px] flex justify-end">
        <button @click="switchLanguage" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-200/80 transition-colors duration-200" :title="$t('main_menu.switch_language')">
            <span class="font-bold text-sm text-gray-600">{{ $i18n.locale.toUpperCase() }}</span>
        </button>
    </div>
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
    switchLanguage() {
      const newLocale = this.$i18n.locale === 'uk' ? 'en' : 'uk';
      this.$i18n.locale = newLocale;
      localStorage.setItem('locale', newLocale);
      location.reload();
    }
  },
}
</script>
