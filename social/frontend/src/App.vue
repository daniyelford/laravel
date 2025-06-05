<template>
  <router-link to="/telegram">telegram</router-link>
  <router-link to="/instagram">instagram</router-link>
  <a href="#" v-if="isLoggedIn" @click.prevent="logout">خروج</a>
  hi
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
      localStorage.removeItem("isLogin")
      this.isLoggedIn = false
      const res = await sendApi(JSON.stringify({ action: 'logout' }));
      if (res.status === 'success') {
        this.$router.push("/login")
      } else {
        alert('خطا در خروج از حساب');
      }
    }
  }
}
</script>