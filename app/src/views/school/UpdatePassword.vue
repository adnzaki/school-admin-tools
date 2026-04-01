<script setup>
import { useSchoolStore } from '@/stores/school-store'
import { useToast } from 'primevue/usetoast'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

const { t } = useI18n()

const toast = useToast()
const store = useSchoolStore()
const showPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmNewPassword = ref(false)
const togglePasswordVisibility = (field) => {
  switch (field) {
    case 'current':
      showPassword.value = !showPassword.value
      break
    case 'new':
      showNewPassword.value = !showNewPassword.value
      break
    case 'confirm':
      showConfirmNewPassword.value = !showConfirmNewPassword.value
      break
  }
}

const onSave = ({ status, msg }) => {
  toast.removeAllGroups()
  if (status === 'error') {
    toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.incorrectForm'), life: 5000 })
  } else if (status === 'wrong_old_password') {
    toast.add({ severity: 'error', summary: t('common.error'), detail: msg, life: 6000 })
  } else {
    toast.add({ severity: 'success', summary: t('common.success'), detail: msg, life: 6000 })
  }
}

const onSaveError = (reason) => {
  toast.removeAllGroups()
  toast.add({ severity: 'error', summary: t('common.error'), detail: t('common.networkError'), life: 5000 })
  console.error(reason)
}

const updatePassword = () => {
  store.disableButton = true
  toast.add({ severity: 'info', summary: t('common.processing'), detail: t('common.saving') })
  store.updatePassword(onSave, onSaveError)
}
</script>

<template>
  <div class="card">
    <div class="flex justify-between items-center mb-6">
      <div class="font-semibold text-xl mb-4">{{ $t('school.updatePassword') }}</div>
    </div>
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-2">
        <label for="currentPassword" class="text-surface-900 dark:text-surface-0 font-medium">{{ $t('school.currentPassword') }}</label>
        <IconField>
          <InputText size="large" :type="showPassword ? 'text' : 'password'" :placeholder="$t('school.currentPassword')" class="w-full" v-model="store.passwordForm.oldPassword" />
          <InputIcon :class="['pi', showPassword ? 'pi-eye-slash' : 'pi-eye']" @click="togglePasswordVisibility('current')" />
        </IconField>
        <p class="text-red-500">{{ store.errors.oldPassword }}</p>
      </div>
      <div class="flex flex-col gap-2">
        <label for="newPassword" class="text-surface-900 dark:text-surface-0 font-medium">{{ $t('school.newPassword') }}</label>
        <IconField>
          <InputText size="large" :type="showNewPassword ? 'text' : 'password'" :placeholder="$t('school.newPassword')" class="w-full" v-model="store.passwordForm.newPassword" />
          <InputIcon :class="['pi', showNewPassword ? 'pi-eye-slash' : 'pi-eye']" @click="togglePasswordVisibility('new')" />
        </IconField>
        <p class="text-red-500">{{ store.errors.newPassword }}</p>
      </div>
      <div class="flex flex-col gap-2 mb-5">
        <label for="confirmNewPassword" class="text-surface-900 dark:text-surface-0 font-medium">{{ $t('school.confirmNewPassword') }}</label>
        <IconField>
          <InputText size="large" :type="showConfirmNewPassword ? 'text' : 'password'" :placeholder="$t('school.confirmNewPassword')" class="w-full" v-model="store.passwordForm.confirmPassword" />
          <InputIcon :class="['pi', showConfirmNewPassword ? 'pi-eye-slash' : 'pi-eye']" @click="togglePasswordVisibility('confirm')" />
        </IconField>
        <p class="text-red-500">{{ store.errors.confirmPassword }}</p>
      </div>

      <Button :label="$t('school.submitNewPassword')" :disabled="store.disableButton" @click="updatePassword" />
    </div>

  </div>
</template>
