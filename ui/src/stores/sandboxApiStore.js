// stores/sandboxApiStore.js
import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";

export const useSandboxApiStore = defineStore("sandboxApi", {
  state: () => ({
    deckContents: [],
    connectSuccess: false,
  }),
  actions: {
    async createDeck(userId, deckName, format) {
      console.log(userId);
      try {
        const response = await axios.get(
          `${apiUrl}createdeck&userid=${userId}&deckname=${deckName}&format=${format}`
        );
        console.log(response);
        if (response.data) {
          this.fetchDeckData(userId, response.data.deck_id);
          console.log(response.data.deck_id);
        } else {
          this.$state.connectSuccess = false;
          console.log(this.connectSuccess);
          console.error(
            "An error has occurred while trying to create a deck:",
            response.data.message
          );
        }
      } catch (error) {
        this.$state.connectSuccess = false;
        console.error(
          "An error has occurred while trying to create a deck:",
          error
        );
      }
    },
    async fetchDeckData(deckId) {
      try {
        this.$state.connectSuccess = false;
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
        console.error(
          "An error has occurred trying to load your deck data:",
          error
        );
      }
    },
    async addCardToDBCardsDecklists(
      userId,
      cardId,
      deckId,
      quantity,
      sideBoard,
      maybeBoard
    ) {
      this.$state.connectSuccess = false;
      try {
        const response = await axios.post(
          `${apiUrl}addcard&userid=${userId}&cardid=${cardId}&deckid=${deckId}&quantity=${quantity}&sideboard=${sideBoard}&maybeboard=${maybeBoard}`
        );
        if (response.data) {
          this.$state.connectSuccess = true;
          console.log("Successfully added card:", response.data);
        } else {
          console.error("Couldn't find response data:", response.data);
        }
      } catch (error) {
        console.error(
          "There has been an issue with adding your cards to the decklist.",
          error
        );
      }
    },
    async removeCardFromDBCardsDecklists(
      userId,
      cardId,
      deckId,
      sideBoard,
      maybeBoard
    ) {
      this.$state.connectSuccess = false;
      try {
        const response = await axios.post(
          `${apiUrl}removecard&userid=${userId}&cardid=${cardId}&deckid=${deckId}&sideboard=${sideBoard}&maybeboard=${maybeBoard}`
        );
        if (response.data) {
          this.$state.connectSuccess = true;
          console.log("Successfully removed card:", response.data);
        } else {
          console.error("Couldn't find response data:", response.data);
        }
      } catch (error) {
        console.error(
          "There has been an issue with removing your cards from the decklist.",
          error
        );
      }
    },
    async deleteDeck(userId, deckId) {
      this.$state.connectSuccess = false;
      try {
        const response = await axios.post(
          `${apiUrl}deletedeck&userid=${userId}&deckid=${deckId}&confirm=Yes`
        );
        if (response.data === "Successfully deleted decklist.") {
          this.$state.connectSuccess = true;
          this.$state.deckContents = [];
        } else {
          console.error("Couldn't find response data:", response.data);
        }
      } catch (error) {
        console.error(
          "An error occurred while trying to delete your decklist:",
          error
        );
      }
    },
  },
});
