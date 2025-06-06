<template>
  <div class="forget-password-container">
    <h2>فراموشی رمز عبور</h2>

    <div v-if="step === 1">
      <form @submit.prevent="sendResetCode">
        <label for="phone">شماره موبایل:</label>
        <input
          id="phone"
          v-model="phone"
          type="text"
          placeholder="مثلاً 09123456789"
          required
        />
        <button type="submit" :disabled="loading">
          {{ loading ? 'در حال ارسال...' : 'ارسال کد بازیابی' }}
        </button>
      </form>
    </div>

    <div v-if="step === 2">
      <form @submit.prevent="verifyResetCode">
        <label for="token">کد بازیابی:</label>
        <input
          id="token"
          v-model="token"
          type="text"
          placeholder="کد ارسال شده را وارد کنید"
          required
        />
        <button type="submit" :disabled="loading">
          {{ loading ? 'در حال بررسی...' : 'بررسی کد' }}
        </button>
      </form>
    </div>

    <div v-if="step === 3">
      <form @submit.prevent="resetPassword">
        <label for="password">رمز عبور جدید:</label>
        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="رمز عبور جدید"
          required
        />

        <label for="password_confirmation">تایید رمز عبور جدید:</label>
        <input
          id="password_confirmation"
          v-model="password_confirmation"
          type="password"
          placeholder="تایید رمز عبور"
          required
        />

        <button type="submit" :disabled="loading">
          {{ loading ? 'در حال تغییر...' : 'تغییر رمز عبور' }}
        </button>
      </form>
    </div>

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
    import { sendApi } from '@/utils/api';
    const step = ref(1);
    const phone = ref('');
    const token = ref('');
    const password = ref('');
    const password_confirmation = ref('');
    const loading = ref(false);
    const errorMessage = ref('');
    const successMessage = ref('');
    const sendResetCode = async () => {
    errorMessage.value = '';
    successMessage.value = '';
    loading.value = true;
    try {
        const response = await sendApi({
            action: 'users_action/login_handler',
            handler: 'sendResetCode',
            data: { phone: phone.value }
        });
        if (response.status === 'success') {
            successMessage.value = 'کد بازیابی ارسال شد.';
            step.value = 2;
        } else {
            errorMessage.value = response.message || 'خطا در ارسال کد';
        }
    } catch {
        errorMessage.value = 'خطا در اتصال به سرور';
    } finally {
        loading.value = false;
    }
    };

    const verifyResetCode = async () => {
    errorMessage.value = '';
    successMessage.value = '';
    loading.value = true;
    try {
        const response = await sendApi({
        action: 'users_action/login_handler',
        handler: 'verifyResetCode',
        data: {
            phone: phone.value,
            token: token.value
        }
        });
        if (response.status === 'success') {
        successMessage.value = 'کد تایید شد.';
        step.value = 3;
        } else {
        errorMessage.value = response.message || 'کد اشتباه است';
        }
    } catch {
        errorMessage.value = 'خطا در اتصال به سرور';
    } finally {
        loading.value = false;
    }
    };

    const resetPassword = async () => {
    errorMessage.value = '';
    successMessage.value = '';
    loading.value = true;
    try {
        const response = await sendApi({
        action: 'users_action/login_handler',
        handler: 'resetPassword',
        data: {
            phone: phone.value,
            token: token.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        }
        });
        if (response.status === 'success') {
        successMessage.value = 'رمز عبور با موفقیت تغییر یافت.';
        step.value = 1;
        phone.value = '';
        token.value = '';
        password.value = '';
        password_confirmation.value = '';
        } else {
        errorMessage.value = response.message || 'خطا در تغییر رمز';
        }
    } catch {
        errorMessage.value = 'خطا در اتصال به سرور';
    } finally {
        loading.value = false;
    }
    };
</script>

<style scoped>
    .forget-password-container {
    max-width: 400px;
    margin: 40px auto;
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
