<template>
  <Header :use-sidebar="false" :use-between="true" :show-login="true" :show-back-button="true" />
  <section class="min-h-screen px-4 py-12 dark:bg-gray-900 bg-gray-50">

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-lg overflow-hidden transition">
      <!-- <img :src="post.image"
        class="w-full h-64 object-cover object-center transition-transform duration-500 hover:scale-105" /> -->
      <div class="p-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-teal-300 mb-4">{{ post.title }}</h1>
        <p class="text-base leading-relaxed text-gray-700 dark:text-slate-300">
          {{ post.content }}
        </p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Header from '@/components/partials/Header.vue';
import { apiGet } from '@/lib/axios';

const router = useRoute();

const post = ref({});

const getPost = async () => {
  try {
    const response = await apiGet(`/api/post/${router.params.id}`)
    post.value = response.data;
  } catch (error) {
    console.error('error getPost: ', error)
  }
}


onMounted(getPost)


</script>
