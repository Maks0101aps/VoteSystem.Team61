import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import uk from './locales/uk.json';

const i18n = createI18n({
  legacy: false, // must be set to false to use Composition API
  locale: localStorage.getItem('locale') || 'uk', // set default locale from localStorage or 'uk'
  fallbackLocale: 'en', // set fallback locale
  messages: {
    en,
    uk,
  },
});

export default i18n;
