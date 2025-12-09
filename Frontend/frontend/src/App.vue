<template>
  <div>
    <nav class="navbar" v-if="auth.token" >
      <router-link v-if="auth.token" to="/products">Products</router-link>
      <router-link v-if="auth.token" to="/products/create">New Product</router-link>
      <button v-if="auth.token" @click="logout">Logout</button>
    </nav>
    <router-view />
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

const logout = () => {
  auth.logout();
  router.push('/');
};
</script>

<style scoped>
.navbar {
  display: flex;
  gap: 1rem;
  background-color: #42b983;
  padding: 1rem;
  color: white;
}
.navbar a {
  color: white;
  text-decoration: none;
}
.navbar button {
  background: none;
  border: 1px solid white;
  color: white;
  padding: 0.3rem 0.6rem;
  cursor: pointer;
}
</style>
