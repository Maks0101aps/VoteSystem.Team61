import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import uk from './locales/uk.json';

import votesEn from '../../lang/en/votes.json';
import votesUk from '../../lang/uk/votes.json';
import petitionsEn from '../../lang/en/petitions.json';
import petitionsUk from '../../lang/uk/petitions.json';

const i18n = createI18n({
  locale: localStorage.getItem('locale') || 'uk', // set default locale from localStorage or 'uk'
  fallbackLocale: 'en', // set fallback locale
  messages: {
    en: {
        ...en,
        ...votesEn,
        ...petitionsEn,
    },
    uk: {
        ...uk,
        ...votesUk,
        ...petitionsUk,
    },
  },
  legacy: false, // you must set `false`, to use Composition API
});

export default i18n;
