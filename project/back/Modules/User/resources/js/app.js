// back/Modules/User//resources/js/app.js
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import.meta.glob('/Modules/**/Resources/js/Pages/**/*.vue')
const pagesPath = 'Modules/User/Resources/js/Pages/';
createInertiaApp({
  resolve: name => resolvePageComponent(
    `${pagesPath}${name}.vue`,
    import.meta.glob('./Pages/**/*.vue')
  ),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})