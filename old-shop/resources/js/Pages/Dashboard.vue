<template>
  <div v-if="isComponentLoaded">
    <AppLayout>
      <h2 class="text-2xl">خوش آمدید!</h2>
      <p>اینجا پنل شماست.</p>
    </AppLayout>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const isComponentLoaded = ref(true);
</script>
