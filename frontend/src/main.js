import { createApp } from 'vue'
import router from './router'
import App from './App.vue'

import '@/css/style.css'

// Components
import Loading from '@/components/ui/Loading.vue';
import BaseAlert from '@/components/ui/BaseAlert.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';

const app = createApp(App)

// APIs global access
app.config.globalProperties.$apiBaseUrl = import.meta.env.VITE_API_BASE_URL

// Components registry
app.component('Loading', Loading)
app.component('BaseAlert', BaseAlert)
app.component('ConfirmDialog', ConfirmDialog)

app.use(router)
app.mount('#app')