<template>
  <form @submit.prevent="sendResetCode">
    <label>شماره موبایل:</label>
    <input v-model="localPhone" placeholder="مثلاً 09123456789" required />
    <button type="submit" :disabled="loading">
      {{ loading ? 'در حال ارسال...' : 'ارسال کد بازیابی' }}
    </button>
    <div v-if="error" class="error">{{ error }}</div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { sendApi } from '@/utils/api'

const props = defineProps(['phone'])
const emit = defineEmits(['success'])

const localPhone = ref(props.phone || '')
const error = ref('')
const loading = ref(false)

const sendResetCode = async () => {
  error.value = ''
  loading.value = true
  try {
    const response = await sendApi({
      action: 'users_action/login_handler',
      handler: 'sendResetCode',
      data: { phone: localPhone.value }
    })
    if (response.status === 'success') {
      emit('success', localPhone.value)
    } else {
      error.value = response.message || 'خطا در ارسال کد'
    }
  } catch {
    error.value = 'خطا در ارتباط با سرور'
  } finally {
    loading.value = false
  }
}
</script>
