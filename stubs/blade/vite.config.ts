import { defineConfig } from 'vite'
import { resolve } from 'path'
import hot from './hot'

const config = {
  root: 'resources',
  server: {
    port: 5201
  },
  plugins: [
    hot(),
  ],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
    },
  },
  build: {
    chunkSizeWarningLimit: 500,
    manifest: true,
    emptyOutDir: true,
    outDir: '../../../public/vendor/[[name]]',
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/main.ts'),
    },
  },
}

const configProduction = {
  //
}

export default defineConfig(({ command }) => {
  if (command === 'build') {
    return Object.assign(config, configProduction)
  }

  return config
})
