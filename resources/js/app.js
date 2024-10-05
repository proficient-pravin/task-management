import './bootstrap';
import '../sass/app.scss'

import Router from '@/router'
import store from '@/store'

import { createApp } from 'vue/dist/vue.esm-bundler';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';



const vuetify = createVuetify({
  components,
  directives,
  theme: { defaultTheme: 'light' },
})

const app = createApp({})
app.use(Router)
app.use(vuetify)
app.use(store)
app.use(ToastPlugin);
app.mount('#app')