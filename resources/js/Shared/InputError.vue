<template>
  <div v-if="message" class="text-sm text-red-600">
    {{ message }}
  </div>
</template>

<script>
export default {
  props: {
    message: String,
  },
}
</script>
