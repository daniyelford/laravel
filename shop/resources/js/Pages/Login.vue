<script setup>
import { ref, onMounted } from 'vue'
import { useForm ,router} from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
const form = useForm({
  mobile: '',
})
const submitForm = () => {
  form.post('/login/send-code');
}
const showFingerprintLogin = ref(false)
onMounted(async () => {
  if (window.PublicKeyCredential &&
    typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable === 'function') {
    const isAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable()
    showFingerprintLogin.value = isAvailable
  }
})
function base64UrlToBase64(base64url) {
  return base64url.replace(/-/g, '+').replace(/_/g, '/').padEnd(base64url.length + (4 - base64url.length % 4) % 4, '=');
}
async function loginWithFingerprint() {
  try {
    Inertia.post('/webauthn/login/options', {}, {
      onSuccess: async (page) => {
        const options = page.props.options
        options.challenge = Uint8Array.from(
          atob(base64UrlToBase64(options.challenge)),
          c => c.charCodeAt(0)
        )
        if (Array.isArray(options.allowCredentials)) {
          options.allowCredentials = options.allowCredentials.map(cred => ({
            ...cred,
            id: Uint8Array.from(
              atob(base64UrlToBase64(cred.id)),
              c => c.charCodeAt(0)
            )
          }))
        } else {
          options.allowCredentials = []
        }
        const assertion = await navigator.credentials.get({ publicKey: options })
        const data = {
          id: assertion.id,
          rawId: btoa(String.fromCharCode(...new Uint8Array(assertion.rawId))),
          response: {
            clientDataJSON: btoa(String.fromCharCode(...new Uint8Array(assertion.response.clientDataJSON))),
            authenticatorData: btoa(String.fromCharCode(...new Uint8Array(assertion.response.authenticatorData))),
            signature: btoa(String.fromCharCode(...new Uint8Array(assertion.response.signature))),
            userHandle: assertion.response.userHandle
              ? btoa(String.fromCharCode(...new Uint8Array(assertion.response.userHandle)))
              : null,
          },
          type: assertion.type,
        }
        Inertia.post('/webauthn/login/verify', data, {
          onSuccess: () => {
            alert('ورود موفقیت‌آمیز بود!')
            router.visit('dashboard')
          },
          onError: () => {
            alert('ورود ناموفق بود')
          }
        })
      },
      onError: () => {
        alert('خطا در دریافت گزینه‌های ورود')
      }
    })
  } catch (error) {
    console.error('خطا:', error)
    alert('ورود ناموفق بود: ' + error.message)
  }
}
</script>

<template>
  <div class="max-w-sm mx-auto mt-10 p-6 bg-white rounded-xl shadow-md space-y-4">
    <h2 class="text-2xl font-bold text-center">ورود به حساب</h2>
    <form @submit.prevent="submitForm" class="space-y-2">
      <input 
        v-model="form.mobile" 
        type="text" 
        placeholder="شماره موبایل" 
        required 
        class="w-full p-2 border rounded"
      >
      <button 
        type="submit" 
        class="w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded"
      >
        ارسال کد تایید
      </button>
      <p v-if="form.errors.mobile" class="text-red-500 text-sm">{{ form.errors.mobile }}</p>
    </form>
    <button 
      v-if="!showFingerprintLogin" 
      @click="loginWithFingerprint"
      class="w-full bg-green-500 hover:bg-green-600 text-white p-2 rounded flex items-center justify-center space-x-2"
    >
      <span>ورود با اثر انگشت</span>
    </button>
  </div>
</template>