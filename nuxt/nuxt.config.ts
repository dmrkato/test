// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from '@tailwindcss/vite'
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: process.env.DEV_TOOLS_ENABLED === 'true' },
  css: ['~/assets/css/main.css'],
  vite: {
    server: {
      allowedHosts: [process.env.BASE_URL || ''],
    },
    plugins: [
      tailwindcss(),
    ],
  },
  modules: [
    '@nuxt/devtools',
  ],
})
