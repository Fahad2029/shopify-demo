<template>
  <v-container>
    
    <!-- Filters & Sorting -->
    <v-row class="mb-4" align="center">
      <!-- Search by title -->
      <v-col cols="12" sm="3">
        <v-text-field
          v-model="filters.search"
          label="Search by title"
          clearable
          outlined
        ></v-text-field>
      </v-col>

      <!-- Min Price -->
      <v-col cols="12" sm="2">
        <v-text-field
          v-model.number="filters.minPrice"
          type="number"
          label="Min Price"
          clearable
          outlined
        ></v-text-field>
      </v-col>

      <!-- Max Price -->
      <v-col cols="12" sm="2">
        <v-text-field
          v-model.number="filters.maxPrice"
          type="number"
          label="Max Price"
          clearable
          outlined
        ></v-text-field>
      </v-col>

      <!-- Vendor dropdown -->
      <v-col cols="12" sm="3">
        <v-select
          v-model="filters.vendor"
          :items="vendors"
          label="Filter by Vendor"
          clearable
          outlined
        ></v-select>
      </v-col>

      <!-- Sorting -->
      <v-col cols="12" sm="2">
        <v-select
          v-model="sortOption"
          :items="sortOptions"
          label="Sort by"
          outlined
          clearable
        ></v-select>
      </v-col>
    </v-row>

    <!-- Filtered & Sorted products -->
    <v-row>
      <v-col
        v-for="product in sortedProducts"
        :key="product.id"
        cols="12"
        sm="6"
        md="4"
      >
        <v-card>
          <v-img :src="product.image" height="200px"></v-img>
          <v-card-title>{{ product.title }}</v-card-title>
          <v-card-subtitle>${{ product.price }}</v-card-subtitle>
          <v-card-text>
            <small>Vendor: {{ product.vendor }}</small>
          </v-card-text>
          <v-card-actions>
            <router-link :to="`/products/${product.id}`">
              <v-btn color="primary">View Details</v-btn>
            </router-link>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])
const API_URL = import.meta.env.VITE_API_BASE_URL

// Filters
const filters = ref({
  search: '',
  minPrice: null,
  maxPrice: null,
  vendor: null,
})

// Vendor list
const vendors = ref([])

// Sorting
const sortOptions = [
  'Price: Low → High',
  'Price: High → Low',
  'Title: A → Z',
  'Title: Z → A'
]

const sortMap = {
  'Price: Low → High': 'price_asc',
  'Price: High → Low': 'price_desc',
  'Title: A → Z': 'title_asc',
  'Title: Z → A': 'title_desc'
}

const sortOption = ref(null)
// Fetch products
onMounted(async () => {
  try {
    const res = await axios.get(`${API_URL}/api/products`)
    products.value = res.data

    // Extract unique vendors
    vendors.value = [...new Set(products.value.map(p => p.vendor).filter(Boolean))]
  } catch (err) {
    console.error(err)
  }
})

// Filtered products
const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchesSearch =
      !filters.value.search ||
      p.title.toLowerCase().includes(filters.value.search.toLowerCase())
    const matchesMin =
      filters.value.minPrice === null || p.price >= filters.value.minPrice
    const matchesMax =
      filters.value.maxPrice === null || p.price <= filters.value.maxPrice
    const matchesVendor =
      !filters.value.vendor || p.vendor === filters.value.vendor

    return matchesSearch && matchesMin && matchesMax && matchesVendor
  })
})

// Sorted products
const sortedProducts = computed(() => {
  const sorted = [...filteredProducts.value]

  switch (sortMap[sortOption.value]) {
    case 'price_asc':
      sorted.sort((a, b) => a.price - b.price)
      break
    case 'price_desc':
      sorted.sort((a, b) => b.price - a.price)
      break
    case 'title_asc':
      sorted.sort((a, b) => a.title.localeCompare(b.title))
      break
    case 'title_desc':
      sorted.sort((a, b) => b.title.localeCompare(a.title))
      break
  }

  return sorted
})

</script>
