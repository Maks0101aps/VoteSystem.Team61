<template>
  <div>
    <div id="dropdown" />
    <header class="fixed top-0 left-0 right-0 z-40 w-full bg-white/90 backdrop-blur-sm border-b border-gray-200/80">
      <div class="flex items-center justify-between h-14 px-6">
        <div class="flex-grow flex items-center justify-between">
          <main-menu class="hidden md:flex w-full" />
        </div>
        <div class="flex items-center ml-4">
          <dropdown v-if="auth.user" class="mt-1" placement="bottom-end">
            <template #default>
              <div class="group flex items-center cursor-pointer select-none">
                <div class="mr-1 text-gray-700 group-hover:text-green-600 focus:text-green-600 whitespace-nowrap">
                  <span>{{ auth.user.first_name }}</span>
                  <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                </div>
                <icon class="w-5 h-5 fill-gray-700 group-hover:fill-green-600 focus:fill-green-600" name="cheveron-down" />
              </div>
            </template>
            <template #dropdown>
              <div class="mt-2 py-2 text-sm bg-white rounded-lg shadow-xl">
                <Link class="block px-6 py-2 hover:bg-green-500 hover:text-white" :href="`/users/${auth.user.id}/edit`">My Profile</Link>
                <Link class="block px-6 py-2 hover:bg-green-500 hover:text-white" href="/users">Manage Users</Link>
                <Link class="block w-full px-6 py-2 text-left hover:bg-green-500 hover:text-white" href="/logout" method="delete" as="button">Logout</Link>
              </div>
            </template>
          </dropdown>
        </div>
      </div>
    </header>

    <div class="pt-20">
      <main class="p-4 md:p-8 lg:p-12">
        <flash-messages />
        <transition name="fly-out" appear>
          <div :key="$page.url">
            <slot />
          </div>
        </transition>
      </main>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Logo from '@/Shared/Logo.vue'
import Dropdown from '@/Shared/Dropdown.vue'
import MainMenu from '@/Shared/MainMenu.vue'
import FlashMessages from '@/Shared/FlashMessages.vue'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    MainMenu,
  },
  props: {
    auth: Object,
  },
}
</script>

<style>
.fly-out-enter-active {
  transition: all 0.4s ease-out;
}

.fly-out-enter-from {
  transform: translateY(20px);
  opacity: 0;
}
</style>
