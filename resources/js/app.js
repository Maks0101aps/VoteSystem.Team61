import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import i18n from './i18n';
import { ZiggyVue } from 'ziggy-js';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Включаем отладку для Pusher
Pusher.logToConsole = true;

// Инициализация Laravel Echo
window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY || '9dc4872fd4678abf41ef',
  cluster: 'eu',
  forceTLS: true
});

console.log('Pusher ключ:', import.meta.env.VITE_PUSHER_APP_KEY || '9dc4872fd4678abf41ef');
console.log('Pusher кластер:', 'mt1');

// Проверяем соединение
window.Echo.connector.pusher.connection.bind('connected', () => {
  console.log('✅ Pusher work!');
});

window.Echo.connector.pusher.connection.bind('error', (err) => {
  console.error('❌ not work Pusher:', err);
});

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  title: title => {
    if (!title) return 'VoteSystem';
    
    // Just use the title directly without translation
    return `${title} - VoteSystem`;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(ZiggyVue)
      .mount(el)
  },
})
