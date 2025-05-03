import '../css/app.css';
import '../sass/app.scss';
import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { route } from 'ziggy-js';
import { Ziggy } from './ziggy'; // ziggy از blade در صفحه inject میشه، نه از node_modules

// تنظیم window.route
window.route = (name, params, absolute) => route(name, params, absolute, Ziggy);

// Inertia progress bar
InertiaProgress.init();

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    return pages[`./Pages/${name.replace('.', '/')}.vue`];
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});

