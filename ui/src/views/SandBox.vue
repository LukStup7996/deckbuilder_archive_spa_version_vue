<script>
import { useSandboxApiStore } from "@/stores/sandboxApiStore";
import { useCardApiStore } from "@/stores/cardApiStore";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";

export default {
  name: "sand-box",
  data() {
    return {
      searchQuery: "",
      searchResults: [],
      quantity: {},
      mainDeck: [],
      sideDeck: [],
      maybeDeck: [],
      deckId: "",
      userId: "",
      sandboxApi: useSandboxApiStore(),
      userApi: useArchiveuserApiStore(),
      cardApi: useCardApiStore(),
    };
  },
  computed: {
    deckName() {
      return this.sandboxApi.deckName;
    },
    deckFormat() {
      return this.sandboxApi.deckFormat;
    },
    mainTotal() {
      return this.mainDeck.reduce(
        (total, card) => total + (card.quantity || 1),
        0
      );
    },
    sideTotal() {
      return this.sideDeck.reduce(
        (total, card) => total + (card.quantity || 1),
        0
      );
    },
    maybeTotal() {
      return this.maybeDeck.reduce(
        (total, card) => total + (card.quantity || 1),
        0
      );
    },
  },
  watch: {
    searchResults: {
      handler(newResults) {
        if (Array.isArray(newResults)) {
          newResults.forEach((card) => {
            if (!this.quantity[card.cardId]) {
              this.quantity[card.cardId] = 0;
            }
          });
        } else {
          console.error("searchResults is not an array:", newResults);
        }
      },
      immediate: true,
    },
  },
  methods: {
    async loadDeckData() {
      this.userId = this.userApi.userId;
      this.deckId = this.sandboxApi.deckId;
      this.sandboxApi.loadSessionData(this.userId, this.deckId).then(() => {
        this.mainDeck = this.sandboxApi.mainboard;
        this.sideDeck = this.sandboxApi.sideboard;
        this.maybeDeck = this.sandboxApi.maybeboard;
      });
    },
    searchCards() {
      this.cardApi.getCardsByName(this.searchQuery).then(() => {
        this.searchResults = this.cardApi.cards;
      });
    },
    addCardToDeck(cardId, destination) {
      const card = this.searchResults.find((card) => card.cardId === cardId);
      if (card) {
        const quantity = (this.quantity[cardId] || 0) + 1;
        this.quantity[cardId] = quantity;
        this.cardApi.fetchCardDetails(cardId).then(() => {
          const cardDetails = this.cardApi.card[0];
          if (!cardDetails) return;
          const cardInfo = {
            card_name: cardDetails.cardName,
            mana_value: cardDetails.manaValue,
            quantity,
          };
          if (destination === "main") {
            this.sandboxApi.mainboard.push(cardInfo);
          } else if (destination === "side") {
            this.sandboxApi.sideboard.push(cardInfo);
          } else if (destination === "maybe") {
            this.sandboxApi.maybeboard.push(cardInfo);
          }
        });
      }
    },
  },
  mounted() {
    this.loadDeckData();
  },
};
</script>

<template>
  <div class="sandbox-container">
    <div class="deck-info bg-light mb-2 p-2 text-center">
      <h2>
        {{ deckName || "Deck Name" }} Decksize: ({{ mainTotal }}) Format:
        {{ deckFormat }}
      </h2>
    </div>
    <div class="content d-flex">
      <div class="sidebar bg-secondary text-white p-3">
        <div>
          <input v-model="searchQuery" placeholder="Search cards" />
          <button @click="searchCards">Search</button>
        </div>
        <div v-if="searchResults.length">
          <div
            v-for="card in searchResults"
            :key="card.cardId"
            class="card-item"
          >
            <img :src="`../assets/img/${card.cardId}.jpg`" class="card-img" />
            <p>{{ card.cardName }}</p>
            <button @click="addCardToDeck(card.cardId, 'main')">Main</button>
            <button @click="addCardToDeck(card.cardId, 'side')">Side</button>
            <button @click="addCardToDeck(card.cardId, 'maybe')">Maybe</button>
          </div>
        </div>
      </div>
      <div class="main-container d-flex flex-column flex-grow-1">
        <div id="main" class="bg-danger p-3 mb-2 flex-grow-1">
          Mainboard: ({{ mainTotal }})
        </div>
        <div class="d-flex flex-column">
          <div id="side" class="bg-primary p-3 mb-2">
            Side Container: ({{ sideTotal }})
          </div>
          <div id="maybe" class="bg-success p-3">
            Maybe Container: ({{ maybeTotal }})
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.sandbox-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.deck-info {
  height: 40px;
  width: 100%;
}

.content {
  display: flex;
  flex-grow: 1;
}

.sidebar {
  flex-basis: 30%;
  overflow-y: auto;
  transition: transform 0.3s ease;
}

.card-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}

.card-img {
  width: 100px;
  height: auto;
  margin-bottom: 5px;
}

.main-container {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

#main {
  flex-grow: 1;
}

#side,
#maybe {
  flex: 1;
}
</style>
