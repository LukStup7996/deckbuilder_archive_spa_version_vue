<script>
import { mapActions, mapState } from "pinia";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";

export default {
  data() {
    return {
      selectedOption: "account-settings",
      newUserName: "",
      newPassword: "",
      confirmPassword: "",
    };
  },
  computed: {
    ...mapState(useArchiveuserApiStore, ["userData", "error"]),
  },
  methods: {
    ...mapActions(useArchiveuserApiStore, [
      "updateUserName",
      "updateUserPassword",
      "deleteArchiver",
    ]),
    async updateName() {
      if (this.newUserName) {
        await this.updateUserName(this.confirmPassword, this.newUserName);
      }
    },
    async updatePassword() {
      if (
        this.newPassword &&
        this.newPassword === this.confirmPassword &&
        this.newPassword.length >= 12 &&
        this.newPassword.length <= 16
      ) {
        await this.updateUserPassword(this.confirmPassword, this.newPassword);
      }
    },
    async deleteAccount() {
      await this.deleteArchiver(this.confirmPassword);
      if (!this.error) {
        this.$router.push("/");
      }
    },
  },
};
</script>

<template>
  <div class="account-settings">
    <div class="sidebar">
      <ul>
        <li :class="{ active: selectedOption === 'settings' }">
          <a @click.prevent="selectedOption = 'settings'">Settings</a>
        </li>
      </ul>
    </div>
    <div class="settings-content">
      <div v-if="selectedOption === 'settings'">
        <h2>Account Settings</h2>
        <div class="form-group">
          <p>Username ändern?</p>
          <label>{{ userData.user_name }}</label>
          <input v-model="newUserName" type="text" class="form-control" />
          <input
            v-model="confirmPassword"
            type="password"
            class="form-control"
            placeholder="Password"
          />
          <button @click="updateName" class="btn btn-primary">
            Update Name
          </button>
        </div>
        <div class="form-group">
          <p>Change Password Placeholder</p>
          <input
            v-model="newPassword"
            type="password"
            class="form-control"
            placeholder="New Password"
          />
          <input
            v-model="confirmPassword"
            type="password"
            class="form-control"
            placeholder="Confirm Password"
          />
          <button @click="updatePassword" class="btn btn-primary">
            Update Password
          </button>
        </div>
        <div class="form-group">
          <p>Delete Account Placeholder</p>
          <input
            v-model="confirmPassword"
            type="password"
            class="form-control"
            placeholder="Password"
          />
          <button @click="deleteAccount" class="btn btn-danger">
            Delete Account
          </button>
        </div>
        <div v-if="error" class="alert alert-danger">{{ error }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.account-settings {
  display: flex;
}

.sidebar {
  width: 200px;
  background: #f8f9fa;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
}

.sidebar ul li {
  padding: 10px;
}

.sidebar ul li.active {
  background: #007bff;
  color: white;
}

.settings-content {
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
}

.btn {
  margin-right: 10px;
}
</style>
