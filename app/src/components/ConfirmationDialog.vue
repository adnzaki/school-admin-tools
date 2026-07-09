<!-- make it reusable for all confirmation purposes -->
<template>
  <Dialog :header="header" :visible="display" @update:visible="$emit('update:display', $event)" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <p class="leading-normal m-0">
      {{ message }}
    </p>
    <template #footer>
      <Button :label="buttonLabel" @click="$emit('action')" />
    </template>
  </Dialog>
</template>
<script setup>
const props = defineProps({
  display: Boolean,
  header: String,
  message: String,
  buttonLabel: {
    type: String,
    default: 'Yes'
  }
})

const emit = defineEmits(['action', 'update:display'])
</script>
