<template>
    <div class="max-w-xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">📝 پست‌ها</h1>
  
      <!-- فرم ساخت پست -->
      <form @submit.prevent="createPost" class="mb-6 space-y-4">
        <div>
          <label for="title" class="block mb-1">عنوان</label>
          <input id="title" v-model="title" type="text" class="w-full border p-2 rounded" required />
        </div>
        <div>
          <label for="content" class="block mb-1">محتوا</label>
          <textarea id="content" v-model="content" class="w-full border p-2 rounded" required></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">ارسال پست</button>
      </form>
  
      <!-- نمایش پست‌ها -->
      <ul class="space-y-4">
        <li v-for="post in posts" :key="post.id" class="border p-4 rounded shadow">
          <h2 class="text-lg font-semibold">{{ post.title }}</h2>
          <p class="text-gray-700">{{ post.content }}</p>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const posts = ref([])
  const title = ref('')
  const content = ref('')
  
  const fetchPosts = async () => {
    const response = await axios.get('/posts')
    posts.value = response.data
  }
  
  const createPost = async () => {
    try {
      const response = await axios.post('/posts', {
        title: title.value,
        content: content.value
      })
      posts.value.unshift(response.data) // پست جدیدو بیار بالا
      title.value = ''
      content.value = ''
    } catch (error) {
      console.error('خطا در ارسال پست:', error.response?.data || error)
    }
  }
  
  onMounted(fetchPosts)
  </script>
  