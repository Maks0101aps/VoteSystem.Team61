<template>
  <button type="button" @click="switchLanguage" class="px-3 py-1 bg-gray-200 text-sm text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
    {{ nextLanguage.toUpperCase() }}
  </button>
</template>

<script>
export default {
  computed: {
    nextLanguage() {
      return this.$i18n.locale === 'en' ? 'ua' : 'en';
    },
  },
  methods: {
    switchLanguage() {
      const newLocale = this.$i18n.locale === 'en' ? 'uk' : 'en';
      this.$i18n.locale = newLocale;
      localStorage.setItem('locale', newLocale);
    },
  },
};
</script>
