<script>
import { useSandboxApiStore } from "@/stores/sandboxApiStore";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";

export default {
  name: "deck-creator",
  data() {
    return {
      sandboxApi: useSandboxApiStore(),
      userApi: useArchiveuserApiStore(),
      deckName: "",
      userID: "",
      format: "Standard",
      options: [
        "Standard",
        "Modern",
        "Pauper",
        "Frontier",
        "Commander",
        "Highlander",
        "Legacy",
        "Vintage",
      ],
    };
  },
  methods: {
    async createNewDeck() {
      console.log(this.userID);
      await this.sandboxApi.createDeck(this.userID, this.deckName, this.format);
      this.$router.push("/sand-box");
    },
    async setUserID() {
      this.userID = this.userApi.userId;
      console.log(this.userID);
      return this.userID;
    },
  },
  mounted() {
    this.setUserID();
  },
};
</script>

<template>
  <div class="container d-flex flex-column align-items-center mt-5">
    <h1>Create a New Deck</h1>
    <form @submit.prevent="createNewDeck" class="w-50 mt-3">
      <div class="form-group mb-3">
        <label for="deckName" class="form-label">Deck Name:</label>
        <input type="text" v-model="deckName" class="form-control" required />
      </div>
      <div class="form-group mb-3">
        <label for="format" class="form-label">Format:</label>
        <select v-model="format" class="form-select">
          <option v-for="fmt in options" :key="fmt">{{ fmt }}</option>
        </select>
      </div>
      <div class="form-group mt-3 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Create Deck</button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.container {
  max-width: 600px;
}
</style>
