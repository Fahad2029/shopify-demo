<template>
  <v-container class="py-12 text-center">
    <v-row justify="center" align="center">
      <v-col cols="12" sm="6" md="4">
        <v-btn color="primary" class="mb-4" block @click="goToProducts">
          Show Products
        </v-btn>
        <v-btn color="success" block @click="syncProducts">
          Sync Products
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const API_URL = import.meta.env.VITE_API_BASE_URL

// Navigate to products list
const goToProducts = () => {
  router.push('/products')
}

// Sync products from Shopify, then navigate to products list
const syncProducts = async () => {
  try {
    await axios.get(`${API_URL}/api/shopify/fetch-products`)
    router.push('/products')
  } catch (err) {
    console.error('Error syncing products:', err)
  }
}
</script>
