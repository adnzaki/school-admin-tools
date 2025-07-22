<script setup>
import ButtonMenu from './ButtonMenu.vue'
import DataList from './DataList.vue'
</script>

<template>
  <div class="card">
    <div class="font-semibold text-xl mb-4">{{ $t('menu.employee') }}</div>
    <ButtonMenu />
    <DataList />
  </div>
</template>
