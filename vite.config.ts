import { defineConfig } from "vite";
import { resolve } from "node:path";

export default defineConfig({
  plugins: [],
  build: {
    rolldownOptions: {
      input: {
        main: resolve(import.meta.dirname, "index.html"),
        product: resolve(import.meta.dirname, "product/1.html"),
        login: resolve(import.meta.dirname, "login.html"),
        register: resolve(import.meta.dirname, "register.html"),
      },
    },
  },
});
