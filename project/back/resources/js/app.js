import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue'); // مسیر صحیح
    const page = pages[`./Pages/${name}.vue`]; // بارگذاری کامپوننت مورد نظر

    if (page) {
      return page(); // بازگشت به کامپوننت
    } else {
      console.error(`کامپوننت ${name} یافت نشد`);
    }
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});
