import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";
export const useDeckArchiveApiStore = defineStore("deckArchiveApi", {
  state: () => ({
    decks: [],
    ownedDecks: [],
    deckContents: [],
    connectSuccess: false,
  }),
  actions: {
    async getAllLists() {
      try {
        this.$state.decks = [];
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
    async filterByDeckName(deckName) {
      try {
        this.$state.decks = [];
        const response = await axios.get(
          apiUrl + "searchbyname&deckname=" + deckName
        );
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
    async searchByFormat(format) {
      try {
        this.$state.decks = [];
        const response = await axios.get(
          apiUrl + "searchbyformat&format=" + format
        );
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
    async getOwnedDecklists(userId) {
      try {
        this.$state.ownedDecks = [];
        const response = await axios.get(
          apiUrl + "displayowned&userid=" + userId
        );
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
    async fetchDeckDetails(deckId) {
      try {
        this.$state.deckContents = [];
        const response = await axios.get(
          apiUrl + "displaydeckcontents&deckid=" + deckId
        );
        this.$state.deckContents = response.data;
        console.log(this.$state.deckContents);
        this.$state.connectSuccess = true;
      } catch (error) {
        this.$state.connectSuccess = false;
        this.$state.deckContents = [];
        this.error =
          "We apologize we couldn't find the contents of this decklist.";
        console.log(error);
      }
    },
  },
});
