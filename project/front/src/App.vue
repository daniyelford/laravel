<template>
  <component :is="Component" v-bind="props" />
</template>

<script>
export default {
  props: {
    Component: Object,
    props: Object
  }
}
</script>
