<template>
  <div class="form-container">
    <h2>ورود به حساب</h2>
    <form @submit.prevent="submitForm">
      <input v-model="form.mobile" type="text" placeholder="شماره موبایل" required>
      <button type="submit">ارسال کد تایید</button>
      <p v-if="form.errors.mobile" class="error">{{ form.errors.mobile }}</p>
    </form>
    <a 
      v-if="showFingerprintLogin" 
      href="/webauthn/login" 
      class="block mt-4 text-blue-500"
    >
      ورود با اثر انگشت
    </a>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
const form = useForm({
  mobile: '',
})
const submitForm = () => {
  form.post('send-login-code', {
    onSuccess: () => {
      console.log('کد تایید ارسال شد')
      window.location.href = '/verify'
    }
  })
}
const showFingerprintLogin = ref(false)
onMounted(async () => {
  if (window.PublicKeyCredential && 
      typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable === 'function') {
    const isAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable()
    const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent)
    if (isAvailable && isMobile) {
      showFingerprintLogin.value = true
    }
  }
})
</script>
