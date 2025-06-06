<template>
  <div class="login-container">
    <h2>ورود به حساب کاربری</h2>

    <form @submit.prevent="submitLogin">
      <div>
        <label for="phone">شماره موبایل:</label>
        <input
          id="phone"
          v-model="phone"
          type="text"
          placeholder="مثلاً 09123456789"
          required
        />
      </div>

      <div>
        <label for="password">رمز عبور:</label>
        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="رمز عبور"
          required
        />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'در حال ورود...' : 'ورود' }}
      </button>
    </form>

    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>

    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>
  </div>
</template>

<script setup>
    import { ref } from 'vue';
    import { sendApi } from '@/utils/api'
    import router from '@/router';
    const phone = ref('');
    const password = ref('');
    const loading = ref(false);
    const errorMessage = ref('');
    const successMessage = ref('');
    const submitLogin = async () => {
        errorMessage.value = '';
        successMessage.value = '';
        loading.value = true;
        try {
            const response = await sendApi({
                action: 'users_action/login_handler',
                handler: 'login',
                data: {
                    phone: phone.value,
                    password: password.value,
                }
            });
            if (response.status === 'success') {
                successMessage.value = 'ورود موفقیت‌آمیز بود';
                router.push({ name: 'dashboard' })
            } else {
                errorMessage.value = response.message || 'خطا در ورود';
            }
        } catch (err) {
            errorMessage.value = 'خطا در اتصال به سرور';
        } finally {
            loading.value = false;
        }
    };
</script>

<style scoped>
    .login-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    }
    input {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    margin-bottom: 12px;
    box-sizing: border-box;
    }
    button {
    width: 100%;
    padding: 10px;
    background-color: #2c7be5;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }
    button:disabled {
    background-color: #8ba9dc;
    cursor: not-allowed;
    }
    .error-message {
    color: red;
    margin-top: 12px;
    }
    .success-message {
    color: green;
    margin-top: 12px;
    }
</style>
