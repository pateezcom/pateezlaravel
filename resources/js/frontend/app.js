import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Create and mount Vue application
const app = createApp(App);
app.use(router);
app.mount('#app');

console.log('Vue application mounted');
