<template>
  <div>
    <h1>{{ isEdit ? 'Edit' : 'New' }} Product</h1>
    <form @submit.prevent="save">
      <input v-model="form.name" placeholder="Name" required />
      <input v-model="form.description" placeholder="Description" required />
      <input v-model.number="form.price" type="number" step="0.01" placeholder="Price" required />
      <input v-model.number="form.stock" type="number" placeholder="Stock" required />
      <button type="submit">Save</button>
    </form>
  </div>
</template>

<script>
import { useProductStore } from '../../stores/product';
import api from '../../api/axios';

export default {
  data() {
    return {
      form: { name: '', description: '', price: 0, stock: 0 },
      isEdit: false,
    };
  },
  async mounted() {
    const id = this.$route.params.id;
    if (id) {
      this.isEdit = true;
      const res = await api.get(`/products/${id}`);
      this.form = res.data.data || res.data;
    }
  },
  methods: {
    async save() {
      if (this.isEdit) {
        await useProductStore().update(this.$route.params.id, this.form);
      } else {
        await useProductStore().create(this.form);
      }
      this.$router.push('/products');
    }
  }
};
</script>
