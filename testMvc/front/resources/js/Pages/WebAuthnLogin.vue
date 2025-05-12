<script setup>
async function loginWithFingerprint() {
  const options = await fetch('/webauthn/login/options', { method: 'POST' }).then(r => r.json());
  options.challenge = Uint8Array.from(atob(options.challenge), c => c.charCodeAt(0));
  options.allowCredentials = options.allowCredentials.map(cred => ({
    ...cred,
    id: Uint8Array.from(atob(cred.id), c => c.charCodeAt(0))
  }));
  const assertion = await navigator.credentials.get({ publicKey: options });
  const data = {
    id: assertion.id,
    rawId: btoa(String.fromCharCode(...new Uint8Array(assertion.rawId))),
    response: {
      clientDataJSON: btoa(String.fromCharCode(...new Uint8Array(assertion.response.clientDataJSON))),
      authenticatorData: btoa(String.fromCharCode(...new Uint8Array(assertion.response.authenticatorData))),
      signature: btoa(String.fromCharCode(...new Uint8Array(assertion.response.signature))),
      userHandle: assertion.response.userHandle ? btoa(String.fromCharCode(...new Uint8Array(assertion.response.userHandle))) : null,
    },
    type: assertion.type,
  };
  await fetch('/webauthn/login/verify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
  alert('ورود موفقیت‌آمیز بود!');
  window.location.href = '/dashboard';
}
</script>

<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">ورود با اثر انگشت</h1>
    <button @click="loginWithFingerprint" class="p-2 bg-green-500 text-white rounded">ورود با اثر انگشت</button>
  </div>
</template>
