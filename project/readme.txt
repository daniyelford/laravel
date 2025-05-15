command lines:

mkdir project
cd project
mkdir back
cd back
composer create-project laravel/laravel .
composer require inertiajs/inertia-laravel
composer install

mv back/resources/js front/src
mv back/resources/css front/src/assets  
mv back/resources/js/Pages front/src/Pages
mv back/vite.config.js front/vite.config.js

in new terminal:
cd project
mkdir front
cd front
npm init -y
npm install vue@3 @inertiajs/inertia @inertiajs/inertia-vue3 vite @vitejs/plugin-vue --save-dev
npm install 

chnage name app.js to main.js
import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

createInertiaApp({
  resolve: name => import(`./Pages/${name}.vue`).then(m => m.default),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});

create App.vue in front/src
<template>
  <component :is="Component" v-bind="props" />
</template>

<script>
export default {
  props: {
    Component: Object,
    props: Object
  }
}
</script>

create Pages folder in front/src and Index.vue inner
<template>
    hi
</template>

change vite.config.js
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

package.json in fornt must be:
"scripts": {
    "dev": "vite",
    "build": "vite build"
},

package.json in back must be:
"scripts": {
    "dev":  "concurrently \"php artisan serve\" \"cd ../front && npm run dev\""
},

in back/routes/web.php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
});

change name welcom.blade to app.blade
<!DOCTYPE html>
<html lang="en">
<head>
    @vite('src/main.js')
</head>
<body>
    @inertia
</body>
</html>

remove node_modules in back if exists

create tsconfig.json in front folder
{
  "compilerOptions": {
    "target": "ESNext",
    "module": "ESNext",
    "moduleResolution": "Node",
    "strict": true,
    "jsx": "preserve",
    "esModuleInterop": true,
    "allowJs": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true,
    "baseUrl": ".",
    "paths": {
      "@/*": ["src/*"]
    },
    "types": ["vite/client"],
    "outDir": "./.ts-temp" 
  },
  "include": ["src/**/*", "vite.config.js", "env.d.ts"],
  "exclude": ["node_modules", "dist", "build"]
}

create env.d.ts in front folder
/// <reference types="vite/client" />

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}


in terminal:
cd front
npm run build

in terminal:
cd back

composer require nwidart/laravel-modules
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
php artisan module:make User
php artisan module:make-model User User

composer dump-autoload

php artisan serv

back/routes/web.php
use Nwidart\Modules\Facades\Module;
Module::load('User');

back/composer.json
"autoload": {
  "psr-4": {
      "App\\": "app/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/",
      "Modules\\": "Modules/"
  }
},

back/modules/Users/modules.json 
"providers": [
  "Modules\\User\\app\\Providers\\UserServiceProvider"
],

change all files namespaces in back/modules/Users/app folders

