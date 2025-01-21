import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
      host: 'localhost',
      port: 5173,
      hmr: {
        host: 'localhost',
      },
      cors: true,
    }
  });