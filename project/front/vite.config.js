import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools';
import path from 'node:path';
import fs from 'node:fs/promises';

function MoveManifestPlugin(desiredManifestPath) {
  let outDir;

  return {
    name: 'move-manifest',
    configResolved(config) {
      outDir = config.build.outDir;
    },
    async writeBundle() {
      const src = path.resolve(outDir, '.vite/manifest.json');
      const dest = path.resolve(desiredManifestPath);

      try {
        await fs.rename(src, dest);
        console.log(`✔ manifest.json moved to: ${dest}`);
      } catch (err) {
        console.error(`✘ Failed to move manifest: ${err.message}`);
      }
    }
  };
}

export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    MoveManifestPlugin('../back/public/build/manifest.json')
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  optimizeDeps: {
    include: ['src/main.js'],
  },
  build: {
    outDir: fileURLToPath(new URL('../back/public/build', import.meta.url)),
    emptyOutDir: true,
    manifest: true,
    manifestFileName: 'manifest.json',
    rollupOptions: {
      input: {
        app: fileURLToPath(new URL('./resources/js/app.js', import.meta.url)),
      }
    }
  },
  publicDir: fileURLToPath(new URL('./public', import.meta.url)),
});
