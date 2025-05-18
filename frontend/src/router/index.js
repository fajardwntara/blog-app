import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Post from '@/views/Post/Post.vue'
import PostManagement from '@/views/Menu/PostManagement/PostManagement.vue'
import NotFound from '@/views/NotFound.vue'
import PostDetail from '@/views/Post/PostDetail.vue'

const routes = [
  { path: '/', component: Post, meta: { requiresAuth: false } },
  { path: '/detail/:id', component: PostDetail, meta: { requiresAuth: false } },
  { path: '/login', component: Login, meta: { requiresAuth: false } },

  // Protected route:
  { path: '/menu/post', component: PostManagement, meta: { requiresAuth: true } },

  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = sessionStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    return next('/login')
  }

  if (to.path === '/login' && token) {
    return next('/menu/post')
  }

  next()
})

export default router
