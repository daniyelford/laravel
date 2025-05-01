<template>
  <div class="register-container">
    <h2>ثبت نام</h2>

    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div v-if="mobile && verify">
        <input 
          v-model="name" 
          type="text" 
          placeholder="نام" 
          required 
        />
        <input 
          v-model="family" 
          type="text" 
          placeholder="نام خانوادگی" 
          required 
        />

        <!-- فیلد انتخاب عکس -->
        <input 
          type="file" 
          @change="handleImageUpload"
          accept="image/*"
        />

        <button type="submit">ثبت نام</button>
      </div>

      <div v-else>
        <p>برای ثبت نام، لطفاً شماره موبایل خود را وارد کنید و کد تایید را دریافت کنید.</p>
        <button @click="goBack">بازگشت به صفحه ورود</button>
      </div>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { usePage } from '@inertiajs/vue3';

  const { props } = usePage();

  const name = ref('');
  const family = ref('');
  const image = ref(null);
  const error = ref('');
  const mobile = ref(props.mobile || '');

  const handleImageUpload = (e) => {
    image.value = e.target.files[0];
  };

  const submitForm = async () => {
    try {
      const formData = new FormData();
      formData.append('name', name.value);
      formData.append('family', family.value);
      formData.append('mobile', mobile.value);
      if (image.value) {
        formData.append('image', image.value);
      }

      await axios.post('/register-request', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      window.location.href = '/dashboard';
    } catch (err) {
      error.value = err.response?.data?.message || 'خطا در ثبت نام';
    }
  };

  const goBack = () => {
    window.location.href = '/login';
  };

  onMounted(() => {
    if (!mobile.value) {
      window.location.href = '/login';
    }
  });
</script>


<style scoped>
  .register-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 50px;
  }

  input {
    padding: 10px;
    margin-bottom: 10px;
    width: 200px;
  }

  button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }

  button:hover {
    background-color: #45a049;
  }

  .error {
    color: red;
    margin-top: 10px;
  }
</style>
