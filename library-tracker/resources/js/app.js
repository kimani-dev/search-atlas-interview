import './bootstrap';

import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// Vuetify styles + icons
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import App from './App.vue';

const vuetify = createVuetify({
  components,
  directives,
  // optional: icons
  icons: {
    defaultSet: 'mdi',
  },
});

createApp(App)
  .use(vuetify)
  .use(Vue3Toastify, {
    autoClose: 3000,
    theme: 'colored',
  })
  .mount('#app');
