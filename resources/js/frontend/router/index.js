import { createRouter, createWebHistory } from 'vue-router';

// Import components
import Home from '../views/Home.vue';
import About from '../views/About.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/home',
    redirect: '/'
  },
  {
    path: '/about',
    name: 'about',
    component: About
  }
];

// Create router
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
