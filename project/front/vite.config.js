// front/viteConfigModule.config.js

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { fileURLToPath } from 'url'
import { dirname } from 'path'
import fs from 'fs/promises'
import { alias } from './vite.alias.js'
import userConfig from '../back/Modules/User/vite.config.module.js'
const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)
function MoveManifestPlugin(destPath) {
  let outDir
  return {
    name: 'move-manifest',
    configResolved(config) {
      outDir = config.build.outDir
    },
    async writeBundle() {
      const src = path.resolve(outDir, '.vite/manifest.json')
      const dest = path.resolve(destPath)
      try {
        await fs.rename(src, dest)
        console.log(`✔ manifest.json moved to: ${dest}`)
      } catch (err) {
        console.error(`✘ Failed to move manifest: ${err.message}`)
      }
    }
  }
}
export default defineConfig({
  plugins: [
    vue(),
    MoveManifestPlugin('../back/public/build/manifest.json'),
  ],
  resolve: {
    alias: {
      ...alias,
      ...(userConfig.resolve?.alias || {})
    }
  },
  build: {
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/main.js'),
        ...(userConfig.build?.rollupOptions?.input || {})
      },
    },
    outDir: path.resolve(__dirname, '../back/public/build'),
    emptyOutDir: true,
    manifest: true,
    manifestFileName: 'manifest.json',
  },
})