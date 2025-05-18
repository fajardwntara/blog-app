<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-teal-500 mb-4">404</h1>
      <p class="text-2xl text-gray-700 mb-4">Page Not Found</p>
      <p class="text-gray-500 mb-6">The page you are looking for does not exist.</p>
      <router-link to="/menu/post" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600 transition">
        Go to Home
      </router-link>
    </div>
  </div>
</template>