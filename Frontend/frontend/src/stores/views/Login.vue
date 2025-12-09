<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="submit">
      <input v-model="email" placeholder="email" />
      <br>
      <input v-model="password" type="password" placeholder="password" />
      <br>
      <button>Login</button>
      <p v-if="error">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import api from '../../api/axios';
import { useAuthStore } from '../../stores/auth';

export default {
  data() {
    return { email: '', password: '', error: null };
  },
  setup() {
  },
  methods: {
    async submit() {
      try {
        const res = await api.post('/login', { email: this.email, password: this.password });
        const token = res.data.token;
        const auth = useAuthStore();
        auth.setToken(token);
        // Optionally fetch user profile if endpoint exists
        this.$router.push('/products');
      } catch (e) {
        this.error = e.response?.data?.message || 'Login failed';
      }
    }
  }
};
</script>
