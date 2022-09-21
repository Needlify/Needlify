<template>
    <div>
        {{ currentDateTime }}
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'

  let currentDateTime = ref(new Date())

  setInterval(() => {
    currentDateTime.value = new Date()
  })
  </script>

  <style lang="scss" scoped>
  div { color: var(--primary);}
  </style>
