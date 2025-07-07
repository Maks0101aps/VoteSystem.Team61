import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import uk from './locales/uk.json';

import votesEn from '../../lang/en/votes.json';
import votesUk from '../../lang/uk/votes.json';
import petitionsEn from '../../lang/en/petitions.json';
import petitionsUk from '../../lang/uk/petitions.json';
import votingPageEn from '../../lang/en/voting_page.json';
import votingPageUk from '../../lang/uk/voting_page.json';
import commentsEn from '../../lang/en/comments.json';
import commentsUk from '../../lang/uk/comments.json';

import reportsPageEn from '../../lang/en/reports_page.json';
import reportsPageUk from '../../lang/uk/reports_page.json';

const i18n = createI18n({
  locale: localStorage.getItem('locale') || 'uk', // set default locale from localStorage or 'uk'
  fallbackLocale: 'en', // set fallback locale
  messages: {
    en: {
        ...en,
        ...votesEn,
        ...petitionsEn,
        voting_page: votingPageEn,
        comments: commentsEn,
        reports_page: reportsPageEn,
    },
    uk: {
        ...uk,
        ...votesUk,
        ...petitionsUk,
        voting_page: votingPageUk,
        comments: commentsUk,
        reports_page: reportsPageUk,
    },
  },
  legacy: false, // you must set `false`, to use Composition API
});

export default i18n;
