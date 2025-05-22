<template>
  <button @click="logout" class="text-red-500">
    خروج از حساب
  </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
// import { Inertia } from '@inertiajs/inertia'


const logout = () => {
  // ارسال درخواست به مسیر درست
  router.post('/logout', {}, {
    onSuccess: () => {
      console.log('خروج موفقیت‌آمیز بود');
      // بعد از خروج می‌توانید به صفحه ورود هدایت کنید
      // router.visit('/login');
    },
    onError: () => {
      console.log('خطا در خروج');
    }
  });
}
</script>

<script>
export default {
  name: 'LogoutButton',
};
</script>
