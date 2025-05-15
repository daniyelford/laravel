import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
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
    MoveManifestPlugin('../back/public/build/manifest.json')
  ],
  resolve: {
    alias: {
      '@': '/src',
    },
  },
  build: {
    rollupOptions: {
      input: 'src/main.js',
    },
    outDir: '../back/public/build',
    emptyOutDir: true,
    manifest: true,
    manifestFileName: 'manifest.json',
  },
});
