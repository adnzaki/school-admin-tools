<template>
  <Dialog :header="$t('common.deleteTitle')" :visible="display" @update:visible="$emit('update:display', $event)" :breakpoints="{ '960px': '75vw' }" :style="{ width: '30vw' }" :modal="true">
    <p class="leading-normal m-0">
      {{ $t('common.deleteConfirm') }}
    </p>
    <template #footer>
      <Button :label="$t('common.buttons.delete')" @click="$emit('delete')" />
    </template>
  </Dialog>
</template>
<script setup>
const props = defineProps({
  display: Boolean
})

const emit = defineEmits(['delete', 'update:display'])
</script>
