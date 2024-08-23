<script>
import { useSandboxApiStore } from "@/stores/sandboxApiStore";
import { useCardApiStore } from "@/stores/cardApiStore";
import { useArchiveuserApiStore } from "@/stores/archiveuserApiStore";
import { useRoute } from "vue-router";

export default {
  name: "sand-box",
  props: {
    deckId: { type: String, required: true },
  },
  data() {
    return {
      cardName: "",
      cards: [],
      quantity: {},
      mainDeck: [],
      sideDeck: [],
      maybeDeck: [],
      displayedDeck: null,
      sandboxApi: useSandboxApiStore(),
      userApi: useArchiveuserApiStore(),
      cardApi: useCardApiStore(),
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
  methods: {
    async loadDeckData() {
      const deckId = this.route.params.deckId || this.deckId;
      console.log("Fetching data for deck ID:", deckId);
      await this.sandboxApi.fetchDeckData(deckId);
      const deckDetails = this.sandboxApi.deckContents;
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
    filterCardNames(cardName) {
      this.cardApi.getCardsByName(cardName).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    addCardToDeck(cardId, destination) {
      const card = this.cards.find((card) => card.cardId === cardId);
      if (card) {
        const quantity = (this.quantity[cardId] || 0) + 1;
        this.quantity[cardId] = quantity;
        this.cardApi.fetchCardDetails(cardId).then(() => {
          const cardDetails = this.cardApi.cards;
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
    this.route = useRoute();
    this.loadDeckData();
  },
};
</script>

<template>
  <div class="sandbox-container">
    <div class="row">
      <div class="deck-info bg-light mb-2 p-2 text-center">
        <div class="col col-sm">
          <h5>{{ deckName }} Format: {{ deckFormat }}</h5>
        </div>
      </div>
    </div>
    <div class="content d-flex">
      <div class="sidebar bg-secondary text-white p-3">
        <div>
          <input v-model="cardName" placeholder="Search cards" />
          <button @click="filterCardNames">Search</button>
        </div>
        <div>
          <div v-for="card in cards" :key="card.cardId" class="card-item">
            <img
              :src="require(`../assets/img/${card.cardId}.jpg`)"
              class="card-img"
              style="font-size: 8px"
            />
            <p>{{ card.cardName }}</p>
            <button @click="addCardToDeck(card.cardId, 'main')">Main</button>
            <button @click="addCardToDeck(card.cardId, 'side')">Side</button>
            <button @click="addCardToDeck(card.cardId, 'maybe')">Maybe</button>
          </div>
        </div>
      </div>
      <div class="main-container d-flex flex-column flex-grow-1">
        <div id="main" class="bg-danger p-3 mb-2 flex-grow-1">
          <h6>Main Deck</h6>
          <div v-if="mainDeckContents.length">
            <div
              v-for="card in mainDeckContents"
              :key="card.cardId"
              class="row"
            >
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
        <div class="d-flex flex-column">
          <div id="side" class="bg-primary p-3 mb-2">
            <h6>Side Deck</h6>
            <div v-if="sideDeckContents.length">
              <div
                v-for="card in sideDeckContents"
                :key="card.cardId"
                class="row"
              >
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
              <div
                v-for="card in maybeDeckContents"
                :key="card.cardId"
                class="row"
              >
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
