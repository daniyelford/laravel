<template>
  <div class="form-container">
    <h2>ورود به حساب</h2>
    <form @submit.prevent="submitForm">
      <input v-model="form.mobile" type="text" placeholder="شماره موبایل" required>
      <button type="submit">ارسال کد تایید</button>
      <p v-if="form.errors.mobile" class="error">{{ form.errors.mobile }}</p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  mobile: '',
})

const submitForm = () => {
  form.post('/send-login-code')
}
</script>
