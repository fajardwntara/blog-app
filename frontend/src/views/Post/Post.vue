<template>

  <Header :use-sidebar="false" :use-between="false" :show-login="true" :show-back-button="false" />

  <div class="min-h-screen px-4 py-8 dark:bg-gray-900">
    <div class="max-w-6xl mx-auto">

      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="(post, index) in posts" :key="index"
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg overflow-hidden transition-shadow duration-300">
          <!-- <img :src="post.image" :alt="`Image for post titled ${post.title}`"
            class="w-full h-48 object-cover transition-transform duration-500 hover:scale-105" /> -->
          <div class="p-6">
            <h2 class="text-xl font-semibold text-teal-500 dark:text-teal-200 mb-3">{{ post.title }}</h2>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ post.content }}</p>
            <button @click="goPost(post.id)"
              class="cursor-pointer mt-5 text-sm font-semibold text-white bg-teal-500 hover:bg-teal-600 dark:bg-teal-600 dark:hover:bg-teal-700 py-2 px-5 rounded-lg transition-colors duration-300">
              Read More
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

</template>

<script setup>

import { useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';
import { apiGet } from '@/lib/axios';

import Header from '@/components/partials/Header.vue';

const router = useRouter();


/* Post */
const PAGE_SIZE = 5

const loading = ref(false);
const posts = ref([]);
const postsLength = ref(1)

const getPost = async () => {
  loading.value = true
  try {
    const response = await apiGet('/api/post', { page: 1, size: 10 })
    posts.value = response.data;
    postsLength.value = Math.ceil(response.data.length / PAGE_SIZE);
  } catch (error) {
    console.error(`error getPost: `, error)
  } finally {
    loading.value = false
  }
}

const goPost = async (id) => {
  router.push(`/detail/${id}`);
}

onMounted(async () => {
  getPost();
})

</script>
