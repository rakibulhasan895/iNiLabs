import { defineStore } from 'pinia';
import api from '../api/axios';

export const useProductStore = defineStore('product', {
  state: () => ({
    list: [],
    meta: {},
  }),
  actions: {
    async fetch(page = 1) {
      const res = await api.get('/products', { params: { page } });
      this.list = res.data.data || res.data;
      this.meta = res.data.meta || {};
    },
    async create(payload) {
      const res = await api.post('/products', payload);
      return res.data;
    },
    async update(id, payload) {
      const res = await api.put(`/products/${id}`, payload);
      return res.data;
    },
    async remove(id) {
      await api.delete(`/products/${id}`);
    }
  }
});
