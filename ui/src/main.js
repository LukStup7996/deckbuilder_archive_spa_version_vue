import { createApp } from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import { createPinia } from "pinia";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js"; // Import Bootstrap JS Bundle which includes Popper
import {
  saveToken,
  getToken,
  removeToken,
  decodeToken,
} from "./utils/jwtHelper";

const app = createApp(App);
const API_BASE_URL = "http://localhost/deckbuilder_archive_spa_version_vue/api";

app.config.globalProperties.$apiBaseUrl = API_BASE_URL;
app.config.globalProperties.$jwt = {
  saveToken,
  getToken,
  removeToken,
  decodeToken,
};
app.use(createPinia());
app.use(router);

app.mount("#app");
