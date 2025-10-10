<template>
  <div class="col-span-12 lg:col-span-6 xl:col-span-3">
    <div class="card mb-0">
      <div class="flex justify-between mb-4">
        <div>
          <span class="block text-muted-color font-medium mb-4">{{ title }}</span>
          <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">{{ number }}</div>
        </div>
        <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
          <i :class="['pi', 'pi-' + icon, `text-${iconColor}-500`, '!text-xl']"></i>
        </div>
      </div>
      <!-- <span class="text-primary font-medium">24 new </span> -->
      <span class="text-muted-color">{{ number > 0 ? textIfExists : textIfEmpty }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  icon: String,
  number: String,
  textIfEmpty: String,
  textIfExists: String,
  iconColor: String
})
</script>
