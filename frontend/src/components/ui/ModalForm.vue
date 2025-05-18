<!-- components/BaseFormModal.vue -->
<template>
    <transition name="fade">
      <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/20">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg p-6 animate-fade-in">
  
          <h2 class="text-xs sm:text-2xl font-bold text-gray-800 dark:text-white mb-6">
            {{ title }}
          </h2>
  
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <slot name="add-form"></slot>
          </div>
  
          <div class="mt-6 flex justify-end space-x-4">
            <button @click="onCancel"
              class="cursor-pointer text-xs mx-2 px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white">
              Cancel
            </button>
            <slot name="add-button"></slot>
          </div>
  
        </div>
      </div>
    </transition>
  </template>
  
  <script setup>
  
  const props = defineProps({
    visible: Boolean,
    title: { type: String, default: 'Form' },
  })
  
  const emit = defineEmits(['update:visible'])
  
  const onCancel = () => {
    emit('update:visible', false)
  }
  
  </script>