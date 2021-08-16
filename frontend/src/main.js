import { createApp, h } from 'vue'

import './assets/css/global.css';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faSignOutAlt, faSignInAlt } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";


import "bootstrap/dist/css/bootstrap.min.css";
import 'bootstrap'

import App from './App.vue'
import router from './router/'

import { store } from './store';

library.add(faSignOutAlt);
library.add(faSignInAlt);

createApp(App)
  .component("font-awesome-icon", FontAwesomeIcon)
  .use(router)
  .use(store)
  .mount('#app')