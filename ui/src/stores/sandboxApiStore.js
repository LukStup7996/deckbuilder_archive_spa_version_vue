// stores/sandboxApiStore.js
import { defineStore } from "pinia";
import axios from "axios";
import { saveToken, getToken, removeToken } from "@/utils/jwtHelper";

const apiUrl = "http://localhost/deckbuilder_archive/api/index.php?action=";

export const useSandboxApiStore = defineStore("sandboxApi", {
  state: () => ({
    deckId: null,
    deckName: null,
    deckFormat: null,
    mainboard: [],
    sideboard: [],
    maybeboard: [],
    connectSuccess: false,
  }),
  actions: {
    async createDeck(deckName, format) {
      try {
        removeToken();
        const response = await axios.get(
          `${apiUrl}createdeck&deckname=${deckName}&format=${format}`
        );
        if (response.data.message === "Deck created successfully.") {
          this.connectSuccess = true;
          this.deckId = response.data.deckId;
          this.deckName = deckName;
          this.deckFormat = format;
          saveToken(response.data.token);
        } else {
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
        const token = getToken();
        if (!token) throw new Error("Kein Token gefunden");
        const response = await axios.get(`${apiUrl}displaydeckcontent`, {
          headers: { Authorization: `Bearer ${token}` },
        });
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
        removeToken(); // JWT entfernen
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
