import axios from 'axios'
<template>
  <v-container class="py-6">
    <v-card v-if="product">
      <v-img :src="product.image" height="300px"></v-img>
      <v-card-title>{{ product.title }}</v-card-title>
      <v-card-text>
        <p><strong>Vendor:</strong> {{ product.vendor }}</p>
        <p><strong>Price:</strong> ${{ product.price }}</p>
        <p v-html="product.body_html"></p>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="$router.back()">Back</v-btn>
      </v-card-actions>
    </v-card>

    <div v-else>
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const product = ref(null)
const API_URL = import.meta.env.VITE_API_BASE_URL

onMounted(async () => {
  try {
    const res = await axios.get(`${API_URL}/api/products/${route.params.id}`)
    product.value = res.data
  } catch (err) {
    console.error(err)
  }
})
</script>
