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
    mainDeckContents() {
      return this.displayedDeck ? this.displayedDeck.mainDeckContents : [];
    },
    sideDeckContents() {
      return this.displayedDeck ? this.displayedDeck.sideDeckContents : [];
    },
    maybeDeckContents() {
      return this.displayedDeck ? this.displayedDeck.maybeDeckContents : [];
    },
  },
  methods: {
    async fetchDeckData() {
      const deckId = this.route.params.deckId || this.deckId;
      console.log("Fetching data for deck ID:", deckId);
      await this.deckArchiveApi.fetchDeckDetails(deckId);
      const deckDetails = this.deckArchiveApi.deckContents;
      console.log("Fetched deck details:", deckDetails);
      if (deckDetails && deckDetails.deckId === deckId) {
        this.displayedDeck = deckDetails;
        console.log("Displayed deck set:", this.displayedDeck);
        console.log("Main Deck Contents:", this.displayedDeck.mainDeckContents);
        console.log("Side Deck Contents:", this.displayedDeck.sideDeckContents);
        console.log(
          "Maybe Deck Contents:",
          this.displayedDeck.maybeDeckContents
        );
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
    <div class="row">
      <div class="deck-info bg-light mb-2 p-2 text-center">
        <div class="col col-sm">
          <h5>{{ deckName }} Format: {{ deckFormat }}</h5>
        </div>
      </div>
    </div>
    <div class="main-container">
      <div id="main" class="bg-danger p-3 mb-2 flex-grow-1">
        <h6>Main Deck</h6>
        <div v-if="mainDeckContents.length">
          <div v-for="card in mainDeckContents" :key="card.cardId" class="row">
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
        <p v-else>No cards in Main Deck</p>
      </div>
    </div>
    <div class="additional-container">
      <div id="side" class="bg-primary p-3 mb-2">
        <h6>Side Deck</h6>
        <div v-if="sideDeckContents.length">
          <div v-for="card in sideDeckContents" :key="card.cardId" class="row">
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
        <p v-else>No cards in Side Deck</p>
      </div>
      <div id="maybe" class="bg-success p-3">
        <h6>Maybe Deck</h6>
        <div v-if="maybeDeckContents.length">
          <div v-for="card in maybeDeckContents" :key="card.cardId" class="row">
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
        <p v-else>No cards in Maybe Deck</p>
      </div>
    </div>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<style scoped>
.deck-info {
  height: 40px;
  width: 100%;
  align-items: flex-start;
}

.main-container {
  display: flex;
  flex-direction: row;
  gap: 10px;
}

#main {
  flex: 2;
  min-width: 250px;
  background-color: rgba(255, 0, 0, 0.1);
  border-radius: 8px;
  padding: 15px;
}

.additional-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex: 1;
  min-width: 150px;
}

#side {
  background-color: rgba(0, 0, 255, 0.1);
  border-radius: 8px;
  padding: 15px;
}

#maybe {
  background-color: rgba(0, 128, 0, 0.1);
  border-radius: 8px;
  padding: 15px;
}
</style>
