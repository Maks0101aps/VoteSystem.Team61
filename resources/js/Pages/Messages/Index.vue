<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">{{ $t('Messages') }}</h1>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue'

export default {
  metaInfo() {
    return { title: this.$t('Messages') }
  },
  layout: Layout,
}
</script>
