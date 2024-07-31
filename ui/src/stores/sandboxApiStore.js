// stores/sandboxApiStore.js
import { defineStore } from "pinia";
import axios from "axios";

const apiUrl = "http://localhost/deckbuilder_archive/api/index.php?action=";

export const useSandboxApiStore = defineStore("sandboxApi", {
  state: () => ({
    deckId: null,
    deckName: "",
    deckFormat: "",
    mainboard: [],
    sideboard: [],
    maybeboard: [],
    connectSuccess: false,
  }),
  actions: {
    async createDeck(deckName, format) {
      try {
        const response = await axios.get(
          `${apiUrl}createdeck&deckname=${deckName}&format=${format}`
        );
        if (response.data.message === "Deck created successfully.") {
          this.connectSuccess = true;
          this.deckId = response.data.deckId;
          this.deckName = deckName;
          this.deckFormat = format;
        } else {
          this.deckName = deckName;
          this.deckFormat = format;
          this.connectSuccess = false;
          console.error(
            "Fehler beim Erstellen eines neuen Decks:",
            response.data.message
          );
        }
      } catch (error) {
        this.connectSuccess = false;
        console.error("Fehler beim Erstellen eines neuen Decks:", error);
      }
    },
    async loadSessionData() {
      try {
        const response = await axios.get(`${apiUrl}displaydeckcontent`);
        this.deckId = response.data.deckId;
        this.mainboard = response.data.mainDeck;
        this.sideboard = response.data.sideDeck;
        this.maybeboard = response.data.maybeDeck;
      } catch (error) {
        console.error("Fehler beim Laden der Sitzungsdaten:", error);
      }
    },
    async deleteDeck(deckId) {
      try {
        const response = await axios.post(
          `${apiUrl}deletedeck&deckid=${deckId}&confirm=Yes`
        );
        if (response.data === "Successfully deleted decklist.") {
          this.deckId = null;
          this.deckName = null;
          this.deckFormat = null;
          this.mainboard = [];
          this.sideboard = [];
          this.maybeboard = [];
        } else {
          console.error("Fehler beim Löschen des Decks:", response.data);
        }
      } catch (error) {
        console.error("Fehler beim Löschen des Decks:", error);
      }
    },
  },
});
