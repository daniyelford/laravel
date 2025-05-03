<template>
    <button @click="logout" class="text-red-500">
      خروج از حساب
    </button>
  </template>
  
  <script setup>
  import { router } from '@inertiajs/vue3'
  
  const logout = () => {
    router.post(route('logout'), {}, {
      onSuccess: () => {
        console.log('خروج موفقیت‌آمیز بود')
      }
    })
  }
  </script>
  