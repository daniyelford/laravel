<template>
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-xl font-bold mb-4">ورود با شماره موبایل</h1>
  
      <form @submit.prevent="sendCode">
        <input v-model="mobile" type="text" placeholder="شماره موبایل" class="border p-2 w-full rounded" />
        <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">ارسال کد</button>
      </form>
  
      <div v-if="step === 'verify'" class="mt-4">
        <input v-model="code" type="text" placeholder="کد پیامک شده" class="border p-2 w-full rounded" />
        <button class="mt-2 px-4 py-2 bg-green-600 text-white rounded" @click="verifyCode">تأیید کد</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const mobile = ref('')
  const code = ref('')
  const step = ref('send')
  
  const sendCode = async () => {
    await axios.post('/auth/send-code', { mobile: mobile.value })
    step.value = 'verify'
  }
  
  const verifyCode = async () => {
    await axios.post('/auth/verify-code', {
      mobile: mobile.value,
      code: code.value
    })
    alert('ورود موفق! 🎉')
  }
  </script>
  