// stores/sandboxApiStore.js
import { defineStore } from "pinia";
import axios from "axios";

const apiUrl =
  "http://localhost/deckbuilder_archive_spa_version_vue/api/index.php?action=";

export const useSandboxApiStore = defineStore("sandboxApi", {
  state: () => ({
    deckId: "",
    deckName: "",
    deckFormat: "",
    mainboard: [],
    sideboard: [],
    maybeboard: [],
    connectSuccess: false,
  }),
  actions: {
    async createDeck(userId, deckName, format) {
      try {
        const response = await axios.get(
          `${apiUrl}createdeck&userid=${userId}&deckname=${deckName}&format=${format}`
        );
        if (response.data) {
          this.$state.connectSuccess = true;
          console.log(this.connectSuccess);
          this.$state.deckId = response.data.deckId;
          console.log(this.deckId);
          this.$state.deckName = deckName;
          console.log(this.deckName);
          this.$state.deckFormat = format;
          console.log(this.deckFormat);
        } else {
          this.$state.deckName = deckName;
          console.log(this.deckName);
          this.$state.deckFormat = format;
          console.log(this.deckFormat);
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
    async loadSessionData(userId, deckId) {
      this.$state.connectSuccess = false;
      try {
        const response = await axios.get(
          `${apiUrl}selectdeck&userid=${userId}&deckId=${deckId}`
        );
        this.$state.connectSuccess = true;
        console.log(this.connectSuccess);
        this.$state.deckId = response.data.deckId;
        console.log(this.deckId);
        this.$state.mainboard = response.data.mainDeck;
        console.log(this.mainboard);
        this.$state.sideboard = response.data.sideDeck;
        console.log(this.sideboard);
        this.$state.maybeboard = response.data.maybeDeck;
        console.log(this.maybeboard);
      } catch (error) {
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
          this.$state.deckId = "";
          this.$state.deckName = "";
          this.$state.deckFormat = "";
          this.$state.mainboard = [];
          this.$state.sideboard = [];
          this.$state.maybeboard = [];
          this.loadSessionData(userId, deckId);
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
      quantity,
      sideBoard,
      maybeBoard
    ) {
      this.$state.connectSuccess = false;
      try {
        const response = await axios.post(
          `${apiUrl}removecard&userid=${userId}&cardid=${cardId}&deckid=${deckId}&quantity=${quantity}&sideboard=${sideBoard}&maybeboard=${maybeBoard}`
        );
        if (response.data) {
          this.$state.connectSuccess = true;
          this.$state.deckId = "";
          this.$state.deckName = "";
          this.$state.deckFormat = "";
          this.$state.mainboard = [];
          this.$state.sideboard = [];
          this.$state.maybeboard = [];
          this.loadSessionData(userId, deckId);
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
          this.$state.deckId = "";
          this.$state.deckName = "";
          this.$state.deckFormat = "";
          this.$state.mainboard = [];
          this.$state.sideboard = [];
          this.$state.maybeboard = [];
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
