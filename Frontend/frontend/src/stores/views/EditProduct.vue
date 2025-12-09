<template>
  <div>
    <h1>Edit Product</h1>
    <form @submit.prevent="save">
      <input v-model="product.name" placeholder="Name" />
      <input v-model.number="product.price" placeholder="Price" />
      <input v-model.number="product.stock" placeholder="Stock" />
      <input v-model="product.description" placeholder="Description" />
      <button type="submit">Update</button>
    </form>
  </div>
</template>

<script setup>
import { onMounted, reactive, toRefs } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductStore } from '../../stores/product';

const route = useRoute();
const router = useRouter();
const store = useProductStore();

const product = reactive({
  name: '',
  price: 0,
  stock: 0,
  description: '',
});

const loadProduct = async () => {
  await store.fetchOne(route.params.id);
  Object.assign(product, store.current); 
};

const save = async () => {
  await store.update(route.params.id, product);
  router.push('/products'); // go back to product list
};

onMounted(loadProduct);
</script>
