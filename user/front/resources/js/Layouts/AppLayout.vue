<template>
    <div>
      <!-- سربرگ -->
      <header class="p-4 bg-gray-100 flex justify-between items-center">
        <h1 class="text-xl font-bold">🏠 داشبورد من</h1>
        
        <!-- دکمه خروج -->
        <LogoutButton />
      </header>
  
      <!-- بدنه -->
      <main class="p-4">
        <slot />
      </main>
    </div>
  </template>
  
  <script setup>
  import LogoutButton from '@/Components/LogoutButton.vue'
  </script>
  