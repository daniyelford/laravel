<template>
  <div class="form-container">
    <h2>تایید کد</h2>
    <form @submit.prevent="submitForm">
      <input v-model="form.code" type="text" placeholder="کد تایید" required inputmode="numeric" autocomplete="one-time-code">
      <button type="submit">تایید کد</button>
      <p v-if="form.errors.code" class="error">{{ form.errors.code }}</p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  code: '',
})

const submitForm = () => {
  form.post('/verify-code')
}
</script>
