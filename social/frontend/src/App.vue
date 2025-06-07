<template>
  <a href="#" v-if="isLoggedIn" @click.prevent="logout">خروج</a>
  <router-view />
</template>
<script>
  import { sendApi } from '@/utils/api'
  export default {
    name: "App",
    data() {
      return {
        isLoggedIn: false,
      }
    },
    mounted() {
      this.checkLogin()
      window.addEventListener("storage", this.checkLogin)
    },
    beforeUnmount() {
      window.removeEventListener("storage", this.checkLogin)
    },
    methods: {
      checkLogin() {
        this.isLoggedIn = !!localStorage.getItem("isLogin")
      },
      async logout() {
        const res = await sendApi({ action: 'users_action/login_handler',handler:'logout' });
        if (res.status === 'success') {
          localStorage.removeItem("isLogin")
          window.dispatchEvent(new Event("storage"))
          this.isLoggedIn = false
          this.$router.push("/")
        } else {
          alert('خطا در خروج از حساب');
        }
      }
    }
  }
</script>