<template>
  <div
    v-if="visible"
    :class="[
      'z-1000 absolute w-full text-center flex items-center justify-between gap-2 p-4 rounded-lg border text-sm font-medium shadow',
      alertClasses[type]
    ]"
    role="alert"
  >
    <slot>{{ message }}</slot>
    <button @click="close" class="text-xl font-bold focus:outline-none">&times;</button>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  type: { type: String, default: 'info' }, // success, error, etc.
  message: { type: String, default: '' },
  duration: { type: Number, default: 0 },
  modelValue: { type: Boolean, default: true }
})

const emit = defineEmits(['update:modelValue'])

const visible = ref(props.modelValue)

watch(() => props.modelValue, (val) => {
  visible.value = val
  if (val && props.duration > 0) {
    setAutoHide()
  }
})

watch(visible, (val) => {
  emit('update:modelValue', val)
})

function close() {
  visible.value = false
}

function setAutoHide() {
  setTimeout(() => {
    visible.value = false
  }, props.duration)
}

onMounted(() => {
  if (visible.value && props.duration > 0) {
    setAutoHide()
  }
})

const alertClasses = {
  success: 'bg-green-100 text-green-800 border-green-300',
  error: 'bg-red-100 text-red-800 border-red-300',
  warning: 'bg-yellow-100 text-yellow-800 border-yellow-300',
  info: 'bg-blue-100 text-blue-800 border-blue-300',
}
</script>
