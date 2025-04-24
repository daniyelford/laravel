<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const balance = ref(0)

const fetchBalance = async () => {
  try {
    const response = await axios.get('/user/wallet') // درخواست برای دریافت موجودی
    balance.value = response.data.balance
  } catch (error) {
    console.error('خطا در بارگذاری موجودی:', error)
  }
}

onMounted(() => {
  fetchBalance() // هنگام لود شدن کامپوننت، موجودی رو می‌گیریم
})
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-2xl shadow">
    <h1 class="text-xl font-bold mb-4">کیف پول من</h1>
    <p class="mb-4">موجودی: {{ balance }} تومان</p>

    <!-- فرم واریز -->
    <form @submit.prevent="submit('deposit')" class="mb-4">
      <label class="block mb-1 font-semibold">مبلغ واریز:</label>
      <input v-model="amount" type="number" class="w-full p-2 border rounded mb-2" />
      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">واریز</button>
    </form>

    <!-- فرم برداشت -->
    <form @submit.prevent="submit('withdraw')">
      <label class="block mb-1 font-semibold">مبلغ برداشت:</label>
      <input v-model="amount" type="number" class="w-full p-2 border rounded mb-2" />
      <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">برداشت</button>
    </form>
  </div>
</template>
