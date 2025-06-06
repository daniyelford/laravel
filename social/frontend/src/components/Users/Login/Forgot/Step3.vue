<template>
  <form @submit.prevent="resetPassword">
    <label>رمز عبور جدید:</label>
    <input v-model="password" type="password" required />
    <label>تکرار رمز:</label>
    <input v-model="password_confirmation" type="password" required />
    <button type="submit" :disabled="loading">
      {{ loading ? 'در حال تغییر...' : 'تغییر رمز عبور' }}
    </button>
    <div v-if="message" class="success">{{ message }}</div>
    <div v-if="error" class="error">{{ error }}</div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { sendApi } from '@/utils/api'

const props = defineProps(['phone'])
const emit = defineEmits(['reset-complete'])

const password = ref('')
const password_confirmation = ref('')
const message = ref('')
const error = ref('')
const loading = ref(false)

const resetPassword = async () => {
  error.value = ''
  message.value = ''
  loading.value = true
  try {
    const response = await sendApi({
      action: 'users_action/login_handler',
      handler: 'resetPassword',
      data: {
        phone: props.phone,
        password: password.value,
        password_confirmation: password_confirmation.value
      }
    })
    if (response.status === 'success') {
      message.value = 'رمز با موفقیت تغییر یافت.'
      emit('reset-complete')
    } else {
      error.value = response.message || 'خطا در تغییر رمز'
    }
  } catch {
    error.value = 'خطا در ارتباط با سرور'
  } finally {
    loading.value = false
  }
}
</script>
