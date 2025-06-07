<template>
  <nav class="navbar">
    <div class="user-info">
      <span>{{ userName }}</span>
      <a href="#" @click.prevent="logout">خروج</a>
    </div>
    <div class="logo"><img :src="logo" alt="logo" /></div>
  </nav>
</template>
<script>
  import { sendApi } from '@/utils/api'
  import logo from '@/assets/images/logo.png'
  export default {
    name: 'NavMenu',
    emits: ['logout'],
    data() {
      return {
        userName: '',
        logo
      }
    },
    async mounted() {
      this.getUserInfo()
    },
    methods: {
      async getUserInfo() {
        try {
          const info = await sendApi({ action: 'users_action/login_handler', handler: 'get_info' })
          console.log(info)
          if (info.status === 'success') {
            this.userName = info.data.name
          }
        } catch (e) {
          console.error("خطا در گرفتن اطلاعات کاربر", e)
        }
      },
      logout() {
        this.$emit('logout')
      }
    }
  }
</script>
<style scoped>
  .navbar {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px;
  }
  .user-info {
    display: flex;
    gap: 1rem;
    align-items: center;
  }
  .logo img {
    height: 38px;
    margin-top: 6px;
  }
</style>
