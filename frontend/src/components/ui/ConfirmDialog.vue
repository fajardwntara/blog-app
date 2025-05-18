<template>
  <transition name="fade">
    <div v-if="visible" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ title }}</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-6">{{ message }}</p>
        <div class="flex justify-end gap-3">
          <button
            class="cursor-pointer px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-white"
            @click="cancel"
          >
            Cancel
          </button>
          <button
            class="cursor-pointer px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white"
            @click="confirm"
          >
            Yes
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'

const visible = ref(false)
const title = ref('Confirm')
const message = ref('Are you sure?')

let resolvePromise

function openDialog(customTitle = 'Confirm', customMessage = 'Are you sure?') {
    title.value = customTitle
    message.value = customMessage
    visible.value = true

    return new Promise((resolve) => {
        resolvePromise = resolve
    })
}

function confirm() {
    visible.value = false
    resolvePromise(true)
}

function cancel() {
    visible.value = false
    resolvePromise(false)
}

defineExpose({ openDialog })
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
