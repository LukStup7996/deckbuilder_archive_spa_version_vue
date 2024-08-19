import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";
export const useDeckArchiveApiStore = defineStore("deckArchiveApi", {
  state: () => ({
    decks: [],
    ownedDecks: [],
    connectSuccess: false,
  }),
  actions: {
    async getAllLists() {
      try {
        const response = await axios.get(apiUrl + "searchalldecks");
        this.$state.decks = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.decks = [];
        this.error =
          "There has been an issue with connecting to the deck archives.";
        console.log(error);
      }
    },
    async filterByDeckName() {
      try {
        const response = await axios.get(apiUrl + "searchbyname");
        this.$state.decks = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.decks = [];
        this.error =
          "There has been an issue with connecting to the deck archives.";
        console.log(error);
      }
    },
    async searchByFormat() {
      try {
        const response = await axios.get(apiUrl + "searchbyformat");
        this.$state.decks = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.decks = [];
        this.error =
          "There has been an issue with connecting to the deck archives.";
        console.log(error);
      }
    },
    async getOwnedDecklists() {
      try {
        const response = await axios.get(apiUrl + "displayowned");
        this.$state.ownedDecks = response.data;
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.ownedDecks = [];
        this.error =
          "There has been an issue with connecting to the deck archives.";
        console.log(error);
      }
    },
  },
});
