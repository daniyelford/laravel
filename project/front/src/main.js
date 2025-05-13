import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

createInertiaApp({
  resolve: (name) => {
    return import(`@/Pages/${name}.vue`)
      .then(module => module.default)
      .catch(error => {
        console.error(`Error loading component: ${name}`, error);
      });
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});
