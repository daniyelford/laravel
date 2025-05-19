// front/vite.alias.js
import { fileURLToPath } from 'url'
import { dirname, resolve } from 'path'
const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)
export const alias = {
  '@': resolve(__dirname, './src'),
  '@node': resolve(__dirname, './node_modules'),
  '@user': resolve(__dirname, '../back/Modules/User/Resources/js'),
  '@inertiajs/inertia-vue3': resolve(__dirname, './node_modules/@inertiajs/inertia-vue3'),
  'laravel-vite-plugin/inertia-helpers': resolve(__dirname, './node_modules/laravel-vite-plugin/inertia-helpers'),
}