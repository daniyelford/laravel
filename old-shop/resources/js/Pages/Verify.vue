<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'

// فرم تایید کد
const form = useForm({
  code: '',
})

// تایید کد
const submitForm = () => {
  form.post('/login/verify-code', {
    onSuccess: () => {
      router.visit('/dashboard')
    }
  })
}

// پیام موفقیت
const successMessage = ref('')

// گرفتن موبایل از props
const mobile = usePage().props.mobile

// فرم ارسال مجدد
const resendForm = useForm({
  mobile: mobile,
})

// ارسال مجدد کد
const resendCode = () => {
  successMessage.value = ''
  resendForm.post('/login/resend-code', {
    onSuccess: () => {
      successMessage.value = 'کد جدید ارسال شد ✅'
      startTimer()
    }
  })
}

// تایمر
const timer = ref(60)
let timerInterval = null

const startTimer = () => {
  timer.value = 60
  clearInterval(timerInterval)
  timerInterval = setInterval(() => {
    timer.value--
    if (timer.value <= 0) {
      clearInterval(timerInterval)
    }
  }, 1000)
}

// دکمه برگشت به صفحه ورود
const goBack = () => {
  router.visit('/login')
}

onMounted(() => {
  startTimer()
})

onBeforeUnmount(() => {
  clearInterval(timerInterval)
})
</script>

<template>
  <div class="form-container">
    <button @click="goBack" class="back-btn">بازگشت به ورود</button>
    <h2>تایید کد</h2>

    <!-- فرم تایید کد -->
    <form @submit.prevent="submitForm">
      <input 
        v-model="form.code" 
        type="text" 
        placeholder="کد تایید" 
        required 
        inputmode="numeric" 
        autocomplete="one-time-code"
      >
      <button type="submit">تایید کد</button>
      <p v-if="form.errors.code" class="error">{{ form.errors.code }}</p>
    </form>

    <!-- دکمه ارسال مجدد با تایمر -->
    <button 
      @click="resendCode" 
      class="resend-btn"
      :disabled="timer > 0"
    >
      {{ timer > 0 ? `ارسال مجدد (${timer})` : 'ارسال مجدد کد' }}
    </button>

    <!-- پیام موفقیت -->
    <p v-if="successMessage" class="success">{{ successMessage }}</p>
  </div>
</template>

<style>
  .form-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
  }
  input {
    width: 95%;
    padding: 10px;
    margin: 10px 0;
  }
  button {
    padding: 10px 20px;
    margin-top: 10px;
  }
  .resend-btn {
    background-color: #f0f0f0;
    border: none;
    margin-top: 20px;
    cursor: pointer;
  }
  .resend-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  .success {
    color: green;
    margin-top: 10px;
  }
  .error {
    color: red;
  }
  .back-btn {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    margin-bottom: 20px;
    font-size: 14px;
  }
  .back-btn:hover {
    text-decoration: underline;
  }
</style>
