<script setup>
async function registerFingerprint() {
  const options = await fetch('/webauthn/options', { method: 'POST' }).then(r => r.json());
  options.challenge = Uint8Array.from(atob(options.challenge), c => c.charCodeAt(0));
  options.user.id = Uint8Array.from(atob(options.user.id), c => c.charCodeAt(0));
  const cred = await navigator.credentials.create({ publicKey: options });
  const data = {
    id: cred.id,
    rawId: btoa(String.fromCharCode(...new Uint8Array(cred.rawId))),
    response: {
      clientDataJSON: btoa(String.fromCharCode(...new Uint8Array(cred.response.clientDataJSON))),
      attestationObject: btoa(String.fromCharCode(...new Uint8Array(cred.response.attestationObject))),
    },
    type: cred.type,
  };
  await fetch('/webauthn/verify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
  alert('ثبت اثر انگشت با موفقیت انجام شد!');
}
</script>

<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">ثبت اثر انگشت</h1>
    <button @click="registerFingerprint" class="p-2 bg-blue-500 text-white rounded">ثبت اثر انگشت</button>
  </div>
</template>
