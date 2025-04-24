<script setup>
  import { ref } from 'vue';
  import GuestLayout from '@/Layouts/GuestLayout.vue';
  import axios from 'axios';
  import { Head, useForm } from '@inertiajs/vue3';
  import InputError from '@/Components/InputError.vue'
  import InputLabel from '@/Components/InputLabel.vue'
  import PrimaryButton from '@/Components/PrimaryButton.vue'
  import TextInput from '@/Components/TextInput.vue'

  const step=ref(false);
  const sendCodeForm = useForm({
    mobile: ''
  })

  // فرم تأیید کد
  const verifyCodeForm = useForm({
    mobile: '', // بهتره با فرم بالا سینک بشه
    code: ''
  })

  const sendCode = async () => {
    await axios.post('/auth/send-code', { mobile: mobile.value })
    step.value = true
    verifyCodeForm.mobile = mobile.value
  }

  // متد تأیید کد
  const verifyCode = () => {
    verifyCodeForm.post('/auth/verify-code', {
      onSuccess: () => {
        alert('ورود موفق')
      }
    })
  }
</script>
<template> 
  <GuestLayout>
    <Head title="Log in" />
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-xl font-bold mb-4">ورود با شماره موبایل</h1>

      <form @submit.prevent="sendCode" v-if="!step" class="space-y-4">
        <div>
          <InputLabel for="mobile" value="شماره موبایل" />
          <TextInput
            id="mobile"
            type="text"
            class="mt-1 block w-full"
            v-model="mobile"
            required
            autofocus
          />
          <InputError :message="sendCodeForm.errors.mobile" class="mt-2" />
        </div>

        <PrimaryButton :class="{ 'opacity-25': sendCodeForm.processing }" :disabled="sendCodeForm.processing">
          ارسال کد
        </PrimaryButton>
      </form>

      <form @submit.prevent="verifyCode" v-if="step" class="space-y-4">
        <div>
          <InputLabel for="code" value="کد تأیید" />
          <TextInput
            id="code"
            type="text"
            class="mt-1 block w-full"
            v-model="verifyCodeForm.code"
            required
          />
          <InputError :message="verifyCodeForm.errors.code" class="mt-2" />
        </div>

        <PrimaryButton :class="{ 'opacity-25': verifyCodeForm.processing }" :disabled="verifyCodeForm.processing">
          تأیید کد
        </PrimaryButton>
      </form>
    </div>
  </GuestLayout>
</template>