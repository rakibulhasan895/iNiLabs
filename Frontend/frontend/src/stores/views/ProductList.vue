<template>
  <div class="p-4">

    <!-- Add/Edit Form -->
    <div class="mb-6 border p-4 rounded shadow">
      <form @submit.prevent="saveProduct">
        <input v-model="product.name" placeholder="Name" class="border p-1 w-full mb-2" />
        <input v-model.number="product.price" type="number" placeholder="Price" class="border p-1 w-full mb-2" />
        <input v-model.number="product.stock" type="number" placeholder="Stock" class="border p-1 w-full mb-2" />
        <textarea v-model="product.description" placeholder="Description" class="border p-1 w-full mb-2"></textarea>
        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
          {{ product.id ? 'Update' : 'Create' }}
        </button>
        <button v-if="product.id" @click="cancelEdit" type="button" class="ml-2 bg-gray-500 text-white px-3 py-1 rounded">Cancel</button>
      </form>
    </div>

    <!-- Product List -->
    <table class="w-full border-collapse border mb-4">
      <thead>
        <tr class="border-b">
          <th class="p-2 text-left">Name</th>
          <th class="p-2 text-left">Price</th>
          <th class="p-2 text-left">Stock</th>
          <th class="p-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in store.list" :key="p.id" class="border-b">
          <td class="p-2">{{ p.name }}</td>
          <td class="p-2">{{ p.price }}</td>
          <td class="p-2">{{ p.stock }}</td>
          <td class="p-2">
            <button @click="editProduct(p)" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</button>
            <button @click="deleteProduct(p.id)" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { defineStore, createPinia } from 'pinia';
import { createApp } from 'vue';
import axios from 'axios';

// ---------- Pinia Store ----------
const useProductStore = defineStore('product', {
  state: () => ({
    list: [],
    meta: {},
    current: null,
  }),
  actions: {
    async fetch() {
      const res = await axios.get('http://localhost:8000/api/products');
      this.list = res.data.data || res.data;
      this.meta = res.data.meta || {};
    },
    async create(payload) {
      const res = await axios.post('http://localhost:8000/api/products', payload);
      this.list.unshift(res.data);
      return res.data;
    },
    async update(id, payload) {
      const res = await axios.put(`http://localhost:8000/api/products/${id}`, payload);
      const index = this.list.findIndex(p => p.id === id);
      if (index !== -1) this.list[index] = res.data;
      if (this.current && this.current.id === id) this.current = res.data;
      return res.data;
    },
    async remove(id) {
      await axios.delete(`http://localhost:8000/api/products/${id}`);
      this.list = this.list.filter(p => p.id !== id);
    },
    async fetchOne(id) {
      const res = await axios.get(`http://localhost:8000/api/products/${id}`);
      this.current = res.data;
      return res.data;
    },
  }
});

// ---------- Component Logic ----------
const store = useProductStore();
const product = reactive({
  id: null,
  name: '',
  price: 0,
  stock: 0,
  description: '',
});

const loadProducts = async () => {
  await store.fetch();
};

// Auto-refresh every 5 seconds
setInterval(loadProducts, 5000);

const editProduct = (p) => {
  Object.assign(product, p);
};

const cancelEdit = () => {
  product.id = null;
  product.name = '';
  product.price = 0;
  product.stock = 0;
  product.description = '';
};

const saveProduct = async () => {
  if (product.id) {
    await store.update(product.id, product);
  } else {
    await store.create(product);
  }
  cancelEdit();
};

const deleteProduct = async (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    await store.remove(id);
  }
};

onMounted(loadProducts);

const app = createApp({});
const pinia = createPinia();
app.use(pinia);
</script>

<style scoped>
table th, table td {
  border: 1px solid #ddd;
}
</style>
