<script setup>
import { RouterLink, useRouter } from "vue-router";
import { ref } from "vue";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";
import { getToken, removeToken } from "@/utils/jwtHelper";

const store = useArchiveuserApiStore();
const router = useRouter();
const isAuthenticated = ref(!!getToken());

const handleLogout = () => {
  store.logoutUser();
  removeToken();
  isAuthenticated.value = false;
  router.push("/");
};
</script>

<template>
  <header>
    <div class="wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
          <img
            alt="Archive logo"
            class="logo"
            src="@/assets/img/deckbuilder-archive.jpg"
            width="30"
            height="30"
          />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <RouterLink class="nav-link" to="/">Home</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" to="/about">About</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" to="/imprint">Imprint</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" to="/card-archive"
                >Card Archive</RouterLink
              >
            </li>
            <li class="nav-item dropdown" v-if="isAuthenticated">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Account
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <RouterLink class="dropdown-item" to="/account-settings"
                    >Account Settings</RouterLink
                  >
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a
                    class="dropdown-item"
                    href="#"
                    @click.prevent="handleLogout"
                    >Logout</a
                  >
                </li>
                <li>
                  <RouterLink class="dropdown-item" to="/deck-creator"
                    >Create Deck</RouterLink
                  >
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown" v-else>
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Account
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <RouterLink class="dropdown-item" to="/user-login"
                    >Login</RouterLink
                  >
                </li>
                <li>
                  <RouterLink class="dropdown-item" to="/account-creation"
                    >Create Account</RouterLink
                  >
                </li>
                <li>
                  <RouterLink class="dropdown-item" to="/deck-creator"
                    >Create Deck</RouterLink
                  >
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>

  <RouterView />

  <footer class="footer">
    <div class="container">
      <ul class="footer-links">
        <li>
          <RouterLink to="/imprint#contact-info">Contact</RouterLink>
        </li>
        <li>
          <RouterLink to="/imprint#legal-disc">Legal Disclaimer</RouterLink>
        </li>
        <li>
          <RouterLink to="/imprint#tos">Terms of Service</RouterLink>
        </li>
        <li>
          <RouterLink to="/imprint#dpa">Data Privacy Agreement</RouterLink>
        </li>
      </ul>
    </div>
  </footer>
</template>

<style scoped>
header {
  line-height: 1.5;
}

.logo {
  margin-right: 10px;
}

.footer {
  margin-top: 20px;
  background-color: #f8f9fa;
  padding: 20px;
  text-align: center;
}

.footer-links {
  list-style-type: none;
  padding: 0;
}

.footer-links li {
  display: inline;
  margin-right: 15px;
}
</style>
