<template>
    <h3 class="h3">ورود به ادوایس</h3>
    <form @submit.prevent="submitLogin">
        <div>
            <label for="phone">شماره موبایل:</label>
            <input
            class="input"
            id="phone"
            v-model="phone"
            type="tel"
            maxlength="11"
            pattern="[0-9]*"
            placeholder="مثلاً 09123456789"
            required
            />
        </div>
        <div>
            <label for="password">رمز عبور:</label>
            <input
            class="input"
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
    <div class="forgot-password">
      <a href="#" @click.prevent="$emit('switch')">بازیابی رمز عبور</a>
    </div>
    <div v-if="errorMessage" class="error-message">
        {{ errorMessage }}
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
    const submitLogin = async () => {
        errorMessage.value = '';
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
    .input{
        border: none;
        border-radius: 5px;
        height: 40px;
        margin-top: 6px;
    }
    .h3{
        text-align: center;
        padding-bottom: 10px;
        font-weight: bold;
    }
    .forgot-password a {
        color: rgb(44, 41, 41);
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        width: 100%;
        background: #e8e8e8;
        display: inline-block;
        padding: 8px;
        margin-top: 5px;
        border-radius: 10px;
        text-align: center;
    }
    input {
        background: #d3d3d385;
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        margin-bottom: 12px;
        box-sizing: border-box;
    }
    button {
        width: 100%;
        padding: 10px;
        background-color: #1452a1;
        color: white;
        font-weight: bolder;
        font-size: 18px;
        border: none;
        border-radius: 10px;
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