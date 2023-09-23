import './bootstrap';

import { router } from './routes';

import { createApp } from 'vue'

import App from './App.vue'


const app = createApp(App).use(router)
app.mount('#app')
