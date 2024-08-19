<script>
import { mapActions, mapState } from "pinia";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";
import { useDeckArchiveApiStore } from "@/stores/deckArchiveApiStore";

export default {
  data() {
    return {
      selectedOption: "account-settings",
      options: [
        { text: "Account Settings", value: "account-settings" },
        { text: "Your decklists", value: "owned-deck-lists" },
      ],
      newUserName: "",
      newPassword: "",
      confirmPassword: "",
      userId: "",
      userApi: useArchiveuserApiStore(),
      ownedDecks: [],
      deckArchiveApi: useDeckArchiveApiStore(),
    };
  },
  computed: {
    ...mapState(useArchiveuserApiStore, ["userData", "error"]),
    ...mapState(useDeckArchiveApiStore, ["ownedDecks", "error"]),
  },
  methods: {
    ...mapActions(useArchiveuserApiStore, [
      "updateUserName",
      "updateUserPassword",
      "deleteArchiver",
    ]),
    ...mapActions(useDeckArchiveApiStore, ["getOwnedDecklists"]),
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
    async getDecklists() {
      this.deckArchiveApi.getOwnedDecklists(this.userId).then(() => {
        this.ownedDecks = this.deckArchiveApi.ownedDecks;
        console.log(this.ownedDecks);
      });
    },
    async setUserID() {
      this.userId = this.userApi.userId;
      console.log(this.userId);
      return this.userId;
    },
  },
  mounted() {
    this.setUserID();
    this.getDecklists();
  },
};
</script>

<template>
  <div class="account-settings">
    <div class="sidebar">
      <ul>
        <li :class="{ active: selectedOption === 'account-settings' }">
          <a @click.prevent="selectedOption = 'account-settings'">Settings</a>
        </li>
        <li :class="{ active: selectedOption === 'owned-deck-lists' }">
          <a @click.prevent="selectedOption = 'owned-deck-lists'">Your Decks</a>
        </li>
      </ul>
    </div>
    <div class="settings-content">
      <div v-if="selectedOption === 'account-settings'">
        <h2>Account Settings</h2>
        <div class="form-group">
          <p>Change Username</p>
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
          <p>Change Password</p>
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
          <p>Delete Account</p>
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
    <div class="deck-contents">
      <div v-if="selectedOption === 'owned-deck-lists'">
        <h3>Your decklists:</h3>
        <div
          v-for="deck in ownedDecks"
          :key="deck.deckId"
          class="card"
          style="width: 18rem"
        >
          <div class="card-body">
            <h5 class="card-title">{{ deck.deckName }}</h5>
            <p class="card-text">Deck-ID: {{ deck.deckId }}</p>
            <router-link
              :to="{
                name: 'deck-content-display',
                params: { deckId: deck.deckId },
              }"
            >
              <button class="btn btn-primary">Additional Info</button>
            </router-link>
          </div>
        </div>
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
