<template>
  <div v-if="isLoggedIn" class="nav">
    <NavMenu @logout="logout" />
  </div>
  <div class="container">
    <router-view />
  </div>
</template>
<script>
  import { sendApi } from '@/utils/api'
  import NavMenu from './components/Pages/NavMenu.vue'
  export default {
    name: "App",
    components:{NavMenu},
    data() {
      return {
        isLoggedIn: false,
      }
    },
    created() {
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
<style scoped>
  .nav{
    position: fixed;
    top: 0%;
    left: 0%;
    right: 0;
    height: 60px;
    box-shadow: 0 0 5px gray;
  }
  .container{
    margin-top: 60px;
    height: calc(100% - 60px);
  }
</style>