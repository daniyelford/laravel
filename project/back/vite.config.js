import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'], // ورودی فایل‌ها
      refresh: true,  // برای رفرش خودکار هنگام تغییرات
    }),
    vue(), // افزونه Vue برای پشتیبانی از Vue.js
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'), // تنظیم آلیاس برای مسیرها
    },
  },
  build: {
    outDir: path.resolve(__dirname, '../public/build'), // مسیر خروجی فایل‌های تولیدی
    emptyOutDir: true,  // پاک کردن پوشه‌ی خروجی قبل از ساخت فایل‌ها
    manifest: true, // تولید فایل manifest برای ارتباط با لاراول
    rollupOptions: {
      input: path.resolve(__dirname, 'resources/js/app.js'), // فایل ورودی
    },
  },
});
