<script>
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";

export default {
  name: "user-login",
  data() {
    return {
      email: "",
      password: "",
      errorMessage: null,
    };
  },
  methods: {
    async loginArchiver() {
      try {
        console.log("Starting login process");
        await this.archiveUserApi.loginUser(this.email, this.password);
        if (!this.archiveUserApi.connectSuccess) {
          this.errorMessage = this.archiveUserApi.error;
        } else {
          this.errorMessage = null;
          console.log("Login successful");
          this.$router.push({ path: "/" });
        }
      } catch (error) {
        console.error("Login failed:", error);
        this.errorMessage = "An unexpected error occurred.";
      }
    },
  },
  computed: {
    archiveUserApi() {
      return useArchiveuserApiStore();
    },
  },
};
</script>

<template>
  <div>
    <form @submit.prevent="loginArchiver">
      <div class="form-group">
        <label for="email">Email address</label>
        <input
          type="email"
          class="form-control"
          id="email"
          v-model="email"
          required
        />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          v-model="password"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}
    </div>
  </div>
</template>

<style>
/* Optional: Custom styles */
</style>
