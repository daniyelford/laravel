<template>
  <div>
    <ForgotStep1_SendCode v-if="step === 1" :phone="phone" @success="goToStep2" />
    <ForgotStep2_VerifyCode v-if="step === 2" :phone="phone" @success="goToStep3" />
    <ForgotStep3_ResetPassword v-if="step === 3" :phone="phone" @reset-complete="resetFlow" />
  </div>
</template>
<script setup>
    import { ref } from 'vue'
    import ForgotStep1_SendCode from './Forgot/Step1.vue'
    import ForgotStep2_VerifyCode from './Forgot/Step2.vue'
    import ForgotStep3_ResetPassword from './Forgot/Step3.vue'
    const step = ref(1)
    const phone = ref('')
    const goToStep2 = (value) => {
    phone.value = value
    step.value = 2
    }
    const goToStep3 = () => {
    step.value = 3
    }
    const resetFlow = () => {
    phone.value = ''
    step.value = 1
    }
</script>