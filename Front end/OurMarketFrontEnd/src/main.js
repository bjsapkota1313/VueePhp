import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import 'sweetalert2/dist/sweetalert2.min.css'

import '@fortawesome/fontawesome-free/css/all.css'


const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
