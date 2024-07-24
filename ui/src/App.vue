<script setup>
import { RouterLink } from "vue-router";
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
