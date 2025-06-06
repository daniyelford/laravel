<template>
  <div class="w-100">
    <div class="image">
      <img :src="banner" alt="banner">
    </div>
    <div class="login-container">
      <img class="logo" :src="logo" alt="logo">
      <LoginForm v-if="activeForm === 'login'" @switch="activeForm = 'forgot'" />
      <ForgotPasswordForm v-else @switch="activeForm = 'login'" />
    </div>
  </div>
</template>
<script setup>
  import { ref } from 'vue'
  import LoginForm from '@/components/Users/Login/Login.vue'
  import ForgotPasswordForm from '@/components/Users/Login/Forgot.vue'
  import banner from '@/assets/images/banner.png';
  import logo from '@/assets/images/logo.png';
  const activeForm = ref('login')
</script>


<style scoped>
  .logo{
    width: 51%;
    margin: 0 26% 0 0;
  }
  .image{
    display: inline-block;
    width: 70% !important;
    margin: 50px auto;
  }
  .image img{
    width: 100%;
    height: 380px;
  }
  .login-container {
    max-width: 30%;
    height: 380px;
    float: right;
    direction: rtl;
    margin: 50px auto;
    padding: 20px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 10px gray;
  }
</style>
