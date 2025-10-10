<script setup>
import { useHomeStore } from '@/stores/home-store'
import SchoolData from '../school/SchoolData.vue'
import Summary from './Summary.vue'

const store = useHomeStore()

store.getSummary()
</script>

<template>
  <div class="grid grid-cols-12 gap-8">
    <Summary :title="$t('menu.student')" icon="users" icon-color="blue" :number="store.totalStudents" :text-if-empty="$t('student.noActiveStudents')" :text-if-exists="$t('student.totalActiveStudents')" />
    <Summary :title="$t('menu.employee')" icon="address-book" icon-color="orange" :number="store.totalEmployees" :text-if-empty="$t('employee.noActiveEmployees')" :text-if-exists="$t('employee.totalActiveEmployees')" />
    <Summary :title="$t('menu.inLetter')" icon="file-import" icon-color="cyan" :number="store.totalIncomingLetter" :text-if-empty="$t('letterArchive.noIncomingLetter')" :text-if-exists="$t('letterArchive.archivedLetter')" />
    <Summary :title="$t('menu.outLetter')" icon="file-export" icon-color="purple" :number="store.totalOutgoingLetter" :text-if-empty="$t('letterArchive.noPrintedLetter')" :text-if-exists="$t('letterArchive.printedLetter')" />

    <div class="col-span-12 xl:col-span-12">
      <SchoolData hide-edit-button hide-header />
    </div>
  </div>
</template>
