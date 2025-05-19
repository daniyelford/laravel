// back/Modules/User/vite.config.module.js
import { fileURLToPath } from 'url'
import { dirname, resolve } from 'path'
const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)
export default {
  resolve: {
    alias: {
      '@user': resolve(__dirname, 'Resources/js'),
    },
  },
  build: {
    rollupOptions: {
      input: {
        user: resolve(__dirname, 'Resources/js/app.js'),
      },
    },
  },
}