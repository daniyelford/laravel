<template>
  <form @submit.prevent="verifyCode">
    <label>کد ارسال شده:</label>
    <input v-model="token" placeholder="کد را وارد کنید" required />
    <button type="submit" :disabled="loading">
      {{ loading ? 'در حال بررسی...' : 'بررسی کد' }}
    </button>
    <div v-if="error" class="error">{{ error }}</div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { sendApi } from '@/utils/api'

const props = defineProps(['phone'])
const emit = defineEmits(['success'])

const token = ref('')
const error = ref('')
const loading = ref(false)

const verifyCode = async () => {
  error.value = ''
  loading.value = true
  try {
    const response = await sendApi({
      action: 'users_action/login_handler',
      handler: 'verifyResetCode',
      data: {
        phone: props.phone,
        token: token.value
      }
    })
    if (response.status === 'success') {
      emit('success')
    } else {
      error.value = response.message || 'کد اشتباه است'
    }
  } catch {
    error.value = 'خطا در ارتباط با سرور'
  } finally {
    loading.value = false
  }
}
</script>
