<script>
import { useDeckArchiveApiStore } from "../stores/deckArchiveApiStore";
import { useRoute } from "vue-router";

export default {
  name: "deck-content-display",
  props: {
    deckId: { type: String, required: true },
  },
  data() {
    return {
      displayedDeck: null,
      deckArchiveApi: useDeckArchiveApiStore(),
    };
  },
  computed: {
    deckContents() {
      return this.displayedDeck ? this.displayedDeck.deckContents : [];
    },
  },
  methods: {
    async fetchDeckData() {
      const deckId = this.route.params.deckId || this.deckId;
      console.log("Fetching data for deck ID:", deckId);

      // Deckdetails über den API-Store abrufen
      await this.deckArchiveApi.fetchDeckDetails(deckId);

      // Angenommen deckArchiveApi speichert die Daten als Schlüssel-Wert-Paare
      const deckDetails = this.deckArchiveApi.deckContents;

      // Direkter Zugriff auf die benötigten Deckdaten basierend auf deckId
      if (deckDetails && deckDetails.deckId === deckId) {
        this.displayedDeck = deckDetails;
        console.log("Displayed deck set:", this.displayedDeck);
      } else {
        console.error(`Deck with ID ${deckId} not found.`);
      }
    },
  },
  mounted() {
    this.route = useRoute();
    this.fetchDeckData();
  },
};
</script>
<template>
  <div v-if="displayedDeck">
    <h5>{{ displayedDeck.deckName }}</h5>
    <p>Deck-ID: {{ displayedDeck.deckId }}</p>
    <div v-for="card in deckContents" :key="card.cardId" class="row">
      <div class="col-sm">
        <p>{{ card.cardName }}</p>
      </div>
      <div class="col-sm">
        <p>{{ card.manaValue }}</p>
      </div>
      <div class="col-sm">
        <p>{{ card.quantity }}</p>
      </div>
    </div>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<style scoped></style>
