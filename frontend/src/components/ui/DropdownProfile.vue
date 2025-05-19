<template>
  <div class="relative inline-flex">
    <button ref="trigger" class="cursor-pointer inline-flex justify-center items-center group" aria-haspopup="true"
      @click.prevent="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen" v-if="user">

      <svg class="dark:fill-gray-100" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1.2em" height="1.2em"
        viewBox="0 0 122.879 122.879" enable-background="new 0 0 122.879 122.879" xml:space="preserve">
        <g>

          <path class="st0"
            d="M0,121.42l0-19.63c10.5-4.67,42.65-13.56,44.16-26.41c0.34-2.9-6.5-13.96-8.07-19.26 c-3.36-5.35-4.56-13.85-0.89-19.5c1.46-2.25,0.84-10.44,0.84-13.53c0-30.77,53.92-30.78,53.92,0c0,3.89-0.9,11.04,1.22,14.1 c3.54,5.12,1.71,14.19-1.27,18.93c-1.91,5.57-9.18,16.11-8.56,19.26c2.31,11.74,32.13,19.63,41.52,23.8l0,22.23L0,121.42L0,121.42z" />

        </g>
      </svg>
      <div class="flex items-center truncate">
        <span
          class="truncate ml-2 text-sm font-medium text-gray-600 dark:text-gray-100 group-hover:text-gray-800 dark:group-hover:text-white">{{
            user.name }}</span>
        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500" viewBox="0 0 12 12">
          <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
        </svg>
      </div>
    </button>
    <Loading v-else />
    <transition enter-active-class="transition ease-out duration-200 transform"
      enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-out duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-show="dropdownOpen"
        class="origin-top-right z-10 absolute top-full min-w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1"
        :class="align === 'right' ? 'right-0' : 'left-0'">
        <div v-if="user" class="pt-0.5 pb-2 px-3 mb-1 border-b border-gray-200 dark:border-gray-700/60">
          <div class="font-medium text-gray-800 dark:text-gray-100">{{ user.name }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 italic">Role: {{ user.role }}</div>
        </div>
        <Loading v-else />
        <ul ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false">

          <li>
            <router-link
              class="font-medium text-sm text-slate-500 hover:text-slate-600 dark:hover:text-slate-400 flex items-center py-1 px-3"
              to="/" @click="signOut">Sign Out</router-link>
          </li>
        </ul>
      </div>
    </transition>
  </div>

</template>

<script>
import { getCurrentUser } from '@/data/User'
import { ref, onMounted, onUnmounted } from 'vue'

export default {
  name: 'DropdownProfile',
  props: ['align'],
  setup() {
    const dropdownOpen = ref(false)
    const trigger = ref(null)
    const dropdown = ref(null)
    const user = ref(null)
    const loading = ref(true)

    const signOut = () => {
      dropdownOpen.value = false
      sessionStorage.clear()
    }

    const fetchUser = async () => {
      try {
        const response = await getCurrentUser()
        if (response) {
          user.value = response
          loading.value = false
        }
      } catch (err) {
        console.error(err)
      } finally {
        loading.value = false
      }
    }

    const clickHandler = ({ target }) => {
      if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return
      dropdownOpen.value = false
    }

    const keyHandler = ({ keyCode }) => {
      if (!dropdownOpen.value || keyCode !== 27) return
      dropdownOpen.value = false
    }

    onMounted(() => {
      document.addEventListener('click', clickHandler)
      document.addEventListener('keydown', keyHandler)
      fetchUser()
    })

    onUnmounted(() => {
      document.removeEventListener('click', clickHandler)
      document.removeEventListener('keydown', keyHandler)
    })

    return {
      dropdownOpen,
      trigger,
      dropdown,
      user,
      signOut,
    }
  }
}
</script>