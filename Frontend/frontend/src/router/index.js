import { createRouter, createWebHistory } from 'vue-router';
import Login from '../stores/views/Login.vue';
import ProductList from '../stores/views/ProductList.vue';
import ProductForm from '../stores/views/ProductForm.vue';
import ProductEdit from '../stores/views/EditProduct.vue';

import { useAuthStore } from '../stores/auth';

const routes = [
  { path: '/', name: 'Login', component: Login },
  { path: '/products', name: 'ProductList', component: ProductList },
  { path: '/products/create', name: 'ProductForm', component: ProductForm },
  { path: '/products/:id/edit', name: 'EditProduct', component: ProductEdit },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  if (to.name === 'Login' && auth.token) {
    return next({ name: 'ProductList' });
  }

  const protectedRoutes = ['ProductList', 'ProductForm', 'EditProduct'];
  if (protectedRoutes.includes(to.name) && !auth.token) {
    return next({ name: 'Login' });
  }

  next();
});

export default router;

