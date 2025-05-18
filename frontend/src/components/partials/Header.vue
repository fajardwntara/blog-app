<template>
  <header class="sticky top-0 z-30 bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center h-12" v-bind:class="useBetween ? 'justify-between' : 'justify-end'">

        <div class="flex" v-if="useSidebar">
          <button class="text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 lg:hidden"
            @click.stop="emit('toggle-sidebar')" aria-controls="sidebar" aria-expanded="false">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
              <rect x="4" y="5" width="16" height="2" />
              <rect x="4" y="11" width="16" height="2" />
              <rect x="4" y="17" width="16" height="2" />
            </svg>
          </button>
        </div>

        <div @click="goBack" class="flex cursor-pointer items-center space-x-2" v-if="showBackButton">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 dark:fill-white" viewBox="0 0 12 24" fill="currentColor"
            fill-rule="evenodd">
            <path d="m3.343 12l7.071 7.071L9 20.485l-7.778-7.778a1 1 0 0 1 0-1.414L9 3.515l1.414 1.414z" />
          </svg>
          <span
            class="text-sm font-semibold tracking-wide text-gray-800 dark:text-white transition-colors duration-300">
            Back
          </span>
        </div>


        <!-- Right -->
        <div class="flex items-center space-x-3">
          <ThemeToggle />
          <div class="w-px h-6 bg-gray-200 dark:bg-gray-700" />
          <div v-if="showLogin">
            <button @click="goLogin"
              class="cursor-pointer text-sm font-semibold tracking-wide text-gray-800 dark:text-white transition-colors duration-300">
              Login
            </button>
          </div>
        </div>


      </div>
    </div>
  </header>
</template>


<script setup>
import { defineProps } from 'vue'
import ThemeToggle from '@/components/ui/ThemeToggle.vue'
import { useRouter } from 'vue-router';

const props = defineProps({
  sidebarOpen: Boolean,
  variant: String,
  useSidebar: Boolean,
  useBetween: Boolean,
  showBackButton: Boolean,
  showLogin: Boolean,
})

const router = useRouter();
const goBack = () => router.back();
const goLogin = () => router.push('/login');

</script>