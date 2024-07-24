<script>
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";

export default {
  name: "account-creation",
  data() {
    return {
      email: "",
      username: "",
      password: "",
      confirmPassword: "",
      errorMessage: null,
    };
  },
  methods: {
    async createAccount() {
      try {
        await this.archiveUserApi.createUser(
          this.email,
          this.username,
          this.password,
          this.confirmPassword
        );
        if (!this.archiveUserApi.connectSuccess) {
          this.errorMessage = this.archiveUserApi.error;
        } else {
          this.errorMessage = null;
          this.$router.push({ path: "/" });
        }
      } catch (error) {
        console.error("Account creation failed:", error);
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
    <form @submit.prevent="createAccount">
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
        <label for="username">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          v-model="username"
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
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input
          type="password"
          class="form-control"
          id="confirmPassword"
          v-model="confirmPassword"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary">Create Account</button>
    </form>
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}
    </div>
  </div>
</template>

<style>
/* Optional: Custom styles */
</style>
