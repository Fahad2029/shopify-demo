import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
  plugins: [
    // Laravel plugin: compile app.js and app.css, enable hot reload
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    // Vue plugin
    vue(),
    // Vuetify plugin with auto-import
    vuetify({ autoImport: true }),
  ],

  server: {
    port: 5173, // Vite dev server port
    proxy: {
      // Proxy Laravel API calls to backend (avoids CORS issues)
      '/api': 'http://127.0.0.1:8080', 
    },
  },

  resolve: {
    alias: {
      '@': '/resources/js', // optional: shorthand for imports
    },
  },
})
