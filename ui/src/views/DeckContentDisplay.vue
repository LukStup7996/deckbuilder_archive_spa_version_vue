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
      displayedDeck: [],
      deckArchiveApi: useDeckArchiveApiStore(),
    };
  },
  computed: {
    deck() {
      return this.displayedDeck || {};
    },
  },
  methods: {
    async fetchDeckData() {
      const deckId = this.route.params.deckId;
      console.log(deckId);
      await this.deckArchiveApi.fetchDeckDetails(deckId);
      const deckDetails = this.deckArchiveApi.deckContents.length
        ? this.deckArchiveApi.deckContents[0]
        : null;
      console.log(deckDetails);

      if (deckDetails) {
        this.displayedDeck = deckDetails;
        console.log(this.displayedDeck);
      } else {
        console.error(`Card with ID ${deckId} not found.`);
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
  <div>
    <h5>{{ deck.deckName }}</h5>
    <p>Deck-ID: {{ deck.deckId }}</p>
    <div v-for="card in deck" :key="card.cardId">
      <div class="row">
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
  </div>
</template>

<style scoped>
/* Scoped styles here */
</style>
