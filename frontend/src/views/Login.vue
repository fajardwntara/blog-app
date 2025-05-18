<template>
    <Header :use-sidebar="false" :use-between="true" :show-back-button="true" :show-login="false" />

    <BaseAlert v-model="alert.visible" :type="alert.type" :message="alert.message" :duration="2000" />

    <div class="min-h-screen flex items-center justify-center bg-white dark:bg-gray-900 px-4">
        <div
            class="w-full max-w-5xl bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
            <!-- Left: Illustration -->
            <div class="lg:w-1/2 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-8">
                <img src="@/images/login-image.png" alt="Login Illustration" class="w-full max-w-xs h-auto" />
            </div>

            <!-- Right: Login Form -->
            <div class="lg:w-1/2 p-10 flex flex-col justify-center">
                <h1 class="text-3xl font-bold text-teal-500 dark:text-teal-200">Sign In</h1>
                <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
                    Use the email and password registered by the administrator
                </p>

                <!-- Email -->
                <label class="mt-6 block text-sm font-semibold text-gray-500 dark:text-teal-300">Email</label>
                <input @keyup.enter="handleLogin" v-model="user.email" placeholder="Enter your email" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900
                 py-2 px-3 text-sm text-slate-800 dark:text-teal-100 shadow-sm
                 focus:outline-none focus:ring-2 focus:ring-teal-400 dark:focus:ring-teal-600
                 placeholder:text-gray-400 dark:placeholder:text-gray-500" />

                <!-- Password -->
                <label class="mt-4 block text-sm font-semibold text-gray-500 dark:text-teal-300">Password</label>
                <input @keyup.enter="handleLogin" v-model="user.password" type="password"
                    placeholder="Enter your password" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900
                 py-2 px-3 text-sm text-slate-800 dark:text-teal-100 shadow-sm
                 focus:outline-none focus:ring-2 focus:ring-teal-400 dark:focus:ring-teal-600
                 placeholder:text-gray-400 dark:placeholder:text-gray-500" />

                <!-- Button -->
                <button @click="handleLogin" class="mt-6 w-full bg-teal-500 text-white font-semibold py-2 px-3 rounded-lg shadow-md
                 hover:bg-teal-600
                 dark:bg-gray-800 dark:text-teal-400 dark:hover:bg-gray-700
                 transition duration-300 ease-in-out cursor-pointer
                 focus:outline-none focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-700">
                    Sign in
                </button>
            </div>
        </div>
    </div>

    <Loading v-if="loading"/>
</template>

<script setup>
import { ref, reactive } from 'vue'

import Header from '@/components/partials/Header.vue'
import { apiPost } from '@/lib/axios';
import { useRouter } from 'vue-router';

const loading = ref(false);
const router = useRouter();
const alert = reactive({
    visible: false,
    type: 'success',
    message: '',
})

const user = reactive({
    email: "",
    password: "",
})

const handleLogin = async () => {
    if (!user.email || !user.password) {
        alert.type = 'error'
        alert.message = 'Email and Password must be filled'
        alert.visible = true
        return;
    }

    loading.value = true;

    try {
        const response = await apiPost('/api/user/login', {
            "email": user.email,
            "password": user.password
        })

        if (response) {
            if (response.token) {
                sessionStorage.setItem('token', response.token)
                router.push('/menu/post')
            }
        }
    } catch (error) {
        alert.type = 'error';
        alert.message = error;
        alert.visible = true;
    } finally {
        loading.value = false;
    }


}
</script>
