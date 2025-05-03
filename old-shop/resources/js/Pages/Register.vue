<script setup>
import { ref, onMounted } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'

// Props
const { props } = usePage()
const mobile = ref(props.mobile || '')

// فرم ثبت نام
const form = useForm({
  name: '',
  family: '',
  mobile: mobile.value,
  image: null,
})

// ارورها
const error = ref('')

// آپلود عکس
const handleImageUpload = (e) => {
  form.image = e.target.files[0]
}

// ثبت فرم
const submitForm = () => {
  form.post(route('register-request'), {
    forceFormData: true,
    onSuccess: () => {
      router.visit(route('dashboard'))
    },
    onError: (errors) => {
      error.value = errors.message || 'خطا در ثبت نام'
    }
  })
}

// ثبت اثر انگشت
const registerFingerprint = async () => {
  try {
    // بررسی قابلیت پشتیبانی از اثر انگشت یا پلتفرم احراز هویت
    const isFingerprintAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();

    if (!isFingerprintAvailable) {
      alert('این دستگاه از اثر انگشت پشتیبانی نمی‌کند.');
      return;
    }

    const options = await fetch(route('webauthn.options'), { method: 'POST' }).then(r => r.json())
    options.challenge = Uint8Array.from(atob(options.challenge), c => c.charCodeAt(0))
    options.user.id = Uint8Array.from(atob(options.user.id), c => c.charCodeAt(0))

    const cred = await navigator.credentials.create({ publicKey: options })

    const data = {
      id: cred.id,
      rawId: btoa(String.fromCharCode(...new Uint8Array(cred.rawId))),
      response: {
        clientDataJSON: btoa(String.fromCharCode(...new Uint8Array(cred.response.clientDataJSON))),
        attestationObject: btoa(String.fromCharCode(...new Uint8Array(cred.response.attestationObject))),
      },
      type: cred.type,
    }

    await fetch(route('webauthn.verify'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    })

    alert('ثبت اثر انگشت با موفقیت انجام شد!')
  } catch (e) {
    alert('خطا در ثبت اثر انگشت')
  }
}


// برگشت به لاگین
const goBack = () => {
  router.visit(route('login'))
}

// چک کن موبایل باشه
onMounted(() => {
  if (!mobile.value) {
    router.visit(route('login'))
  }
})
</script>

<template>
  <div class="register-container">
    <h2>ثبت نام</h2>

    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div v-if="mobile">
        <input 
          v-model="form.name" 
          type="text" 
          placeholder="نام" 
          required 
        />
        <input 
          v-model="form.family" 
          type="text" 
          placeholder="نام خانوادگی" 
          required 
        />

        <!-- آپلود عکس -->
        <input 
          type="file" 
          @change="handleImageUpload"
          accept="image/*"
        />

        <button type="submit">ثبت نام</button>
      </div>

      <div v-else>
        <p>لطفاً شماره موبایل را وارد کنید</p>
        <button type="button" @click="goBack">بازگشت به صفحه ورود</button>
      </div>
    </form>

    <!-- ثبت اثر انگشت -->
    <div v-if="mobile&&isFingerprintAvailable" class="fingerprint-section">
      <h3>ثبت اثر انگشت</h3>
      <button @click="registerFingerprint">ثبت اثر انگشت</button>
    </div>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

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
    margin-top: 10px;
  }

  button:hover {
    background-color: #45a049;
  }

  .error {
    color: red;
    margin-top: 10px;
  }

  .fingerprint-section {
    margin-top: 20px;
  }

  .fingerprint-section button {
    background-color: #007bff;
  }

  .fingerprint-section button:hover {
    background-color: #0056b3;
  }
</style>
