<script setup>
import DataTable from './DataTable.vue'
</script>

<template>
  <div class="card">
    <div class="font-semibold text-xl mb-4">{{ $t('menu.employee') }}</div>
    <DataTable />
  </div>
</template>
