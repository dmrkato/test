// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: process.env.DEV_TOOLS_ENABLED === 'true' },
  vite: {
    server: {
      allowedHosts: [process.env.BASE_URL || ''],
    }
  },
  modules: [
    '@nuxt/devtools',
  ],
})
