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
    };
  },
  computed: {
    sandboxApi() {
      return useSandboxApiStore();
    },
    userApi() {
      return useArchiveuserApiStore();
    },
    cardApi() {
      return useCardApiStore();
    },
    deckName() {
      return this.displayedDeck ? this.displayedDeck.deckName : "";
    },
    deckFormat() {
      return this.displayedDeck ? this.displayedDeck.deckFormat : "";
    },
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
      const deckId = this.$route.params.deckId || this.deckId;
      console.log("Fetching data for deck ID:", deckId);
      await this.sandboxApi.fetchDeckData(deckId);
      const deckDetails = this.sandboxApi.deckContents;
      console.log("Fetched deck details:", deckDetails);
      if (deckDetails && deckDetails.deckId === deckId) {
        this.displayedDeck = deckDetails;
        this.mainDeck = deckDetails.mainDeckContents || [];
        console.log(this.mainDeck);
        this.sideDeck = deckDetails.sideDeckContents || [];
        console.log(this.sideDeck);
        this.maybeDeck = deckDetails.maybeDeckContents || [];
        console.log(this.maybeDeck);
      } else {
        console.error(`Deck with ID ${deckId} not found.`);
      }
    },
    async filterCardNames(cardName) {
      await this.cardApi.getCardsByName(cardName);
      this.cards = this.cardApi.cards;
      console.log(this.cards);
    },
    async addToMain(cardId) {
      const card = this.mainDeck.find((card) => card.cardId === cardId);
      console.log(card);
      if (card) {
        const updatedQuantity = card.quantity + 1;
        card.quantity = updatedQuantity;

        try {
          await this.sandboxApi.addCardToDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            updatedQuantity,
            "No", // sideBoard
            "No" // maybeBoard
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to update main deck quantity:", error);
        }
      }
    },

    async addToSide(cardId) {
      const card = this.sideDeck.find((card) => card.cardId === cardId);
      console.log(card);

      if (card) {
        const updatedQuantity = card.quantity + 1;
        card.quantity = updatedQuantity;

        try {
          await this.sandboxApi.addCardToDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            updatedQuantity,
            "Yes", // sideBoard
            "No" // maybeBoard
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to update side deck quantity:", error);
        }
      }
    },

    async addToMaybe(cardId) {
      const card = this.maybeDeck.find((card) => card.cardId === cardId);
      console.log(cardId);

      if (card) {
        const updatedQuantity = card.quantity + 1;
        card.quantity = updatedQuantity;

        try {
          await this.sandboxApi.addCardToDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            updatedQuantity,
            "No", // sideBoard
            "Yes" // maybeBoard
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to update maybe deck quantity:", error);
        }
      }
    },
    async reduceMainByOne(cardId) {
      const card = this.mainDeck.find((card) => card.cardId === cardId);
      if (card) {
        const updatedQuantity = card.quantity - 1;
        card.quantity = updatedQuantity;

        if (updatedQuantity <= 0) {
          await this.removeCardFromMain(cardId);
        } else {
          try {
            await this.sandboxApi.addCardToDBCardsDecklists(
              this.userApi.userId,
              cardId,
              this.deckId,
              updatedQuantity,
              "No", // sideBoard
              "No" // maybeBoard
            );
            this.loadDeckData();
          } catch (error) {
            console.error("Failed to update main deck quantity:", error);
          }
        }
      }
    },

    async reduceSideByOne(cardId) {
      const card = this.sideDeck.find((card) => card.cardId === cardId);
      if (card) {
        const updatedQuantity = card.quantity - 1;
        card.quantity = updatedQuantity;

        if (updatedQuantity <= 0) {
          await this.removeCardFromSide(cardId);
        } else {
          try {
            await this.sandboxApi.addCardToDBCardsDecklists(
              this.userApi.userId,
              cardId,
              this.deckId,
              updatedQuantity,
              "Yes", // sideBoard
              "No" // maybeBoard
            );
            this.loadDeckData();
          } catch (error) {
            console.error("Failed to update side deck quantity:", error);
          }
        }
      }
    },

    async reduceMaybeByOne(cardId) {
      const card = this.maybeDeck.find((card) => card.cardId === cardId);
      if (card) {
        const updatedQuantity = card.quantity - 1;
        card.quantity = updatedQuantity;

        if (updatedQuantity <= 0) {
          await this.removeCardFromMaybe(cardId);
        } else {
          try {
            await this.sandboxApi.addCardToDBCardsDecklists(
              this.userApi.userId,
              cardId,
              this.deckId,
              updatedQuantity,
              "No", // sideBoard
              "Yes" // maybeBoard
            );
            this.loadDeckData();
          } catch (error) {
            console.error("Failed to update maybe deck quantity:", error);
          }
        }
      }
    },

    async removeCardFromMain(cardId) {
      const card = this.mainDeck.find((card) => card.cardId === cardId);
      if (card) {
        try {
          await this.sandboxApi.removeCardFromDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            "No",
            "No"
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to remove card from Main Deck.", error);
        }
      }
    },
    async removeCardFromSide(cardId) {
      const card = this.sideDeck.find((card) => card.cardId === cardId);
      if (card) {
        try {
          await this.sandboxApi.removeCardFromDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            "Yes",
            "No"
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to remove card from Side Deck.", error);
        }
      }
    },
    async removeCardFromMaybe(cardId) {
      const card = this.maybeDeck.find((card) => card.cardId === cardId);
      if (card) {
        try {
          await this.sandboxApi.removeCardFromDBCardsDecklists(
            this.userApi.userId,
            cardId,
            this.deckId,
            "No",
            "Yes"
          );
          this.loadDeckData();
        } catch (error) {
          console.error("Failed to remove card from Maybe Deck.", error);
        }
      }
    },
  },
  watch: {
    deckId: {
      immediate: true,
      handler() {
        this.loadDeckData();
      },
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
          <h5>{{ deckName }} {{ deckFormat }} ({{ mainTotal }})</h5>
        </div>
      </div>
    </div>
    <div class="content d-flex">
      <div class="sidebar bg-secondary text-white p-3">
        <div>
          <input v-model="cardName" placeholder="Search cards" />
          <button @click="filterCardNames(cardName)">Search</button>
        </div>
        <div v-if="cards.length">
          <div v-for="card in cards" :key="card.cardId" class="card-item">
            <div class="col-sm">
              <img
                :src="require(`../assets/img/${card.cardId}.jpg`)"
                class="card-img"
                style="width: 20px; height: auto"
              />
            </div>
            <div class="col-sm">
              <p>{{ card.cardName }}</p>
            </div>
            <div class="col-sm">
              <button class="btn btn-danger" @click="addToMain(card.cardId)">
                +
              </button>
            </div>
            <div class="col-sm">
              <button class="btn btn-primary" @click="addToSide(card.cardId)">
                +
              </button>
            </div>
            <div class="col-sm">
              <button class="btn btn-success" @click="addToMaybe(card.cardId)">
                +
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="main-container d-flex flex-column flex-grow-1">
        <div id="main" class="bg-danger p-3 mb-2 flex-grow-1">
          <h6>Main Deck {{ mainTotal }}</h6>
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
              <div class="col-sm">
                <button class="btn btn-info" @click="addToMain(card.cardId)">
                  +
                </button>
              </div>
              <div class="col-sm">
                <button
                  class="btn btn-info"
                  @click="reduceMainByOne(card.cardId)"
                >
                  -
                </button>
              </div>
              <div class="col-sm">
                <button
                  class="btn btn-info"
                  @click="removeCardFromMain(card.cardId)"
                >
                  x
                </button>
              </div>
            </div>
          </div>
          <p v-else>No cards in Main Deck</p>
        </div>
        <div class="d-flex flex-column">
          <div id="side" class="bg-primary p-3 mb-2">
            <h6>Side Deck {{ sideTotal }}</h6>
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
                <div class="col-sm">
                  <button class="btn btn-info" @click="addToSide(card.cardId)">
                    +
                  </button>
                </div>
                <div class="col-sm">
                  <button
                    class="btn btn-info"
                    @click="reduceSideByOne(card.cardId)"
                  >
                    -
                  </button>
                </div>
                <div class="col-sm">
                  <button
                    class="btn btn-info"
                    @click="removeCardFromSide(card.cardId)"
                  >
                    x
                  </button>
                </div>
              </div>
            </div>
            <p v-else>No cards in Side Deck</p>
          </div>
          <div id="maybe" class="bg-success p-3">
            <h6>Maybe Deck {{ maybeTotal }}</h6>
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
                <div class="col-sm">
                  <button class="btn btn-info" @click="addToMaybe(card.cardId)">
                    +
                  </button>
                </div>
                <div class="col-sm">
                  <button
                    class="btn btn-info"
                    @click="reduceMaybeByOne(card.cardId)"
                  >
                    -
                  </button>
                </div>
                <div class="col-sm">
                  <button
                    class="btn btn-info"
                    @click="removeCardFromMaybe(card.cardId)"
                  >
                    x
                  </button>
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
  flex-basis: 18.5%;
  overflow-y: auto;
  transition: transform 0.3s ease;
}

.card-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}

.card-item img {
  width: 100px;
  height: auto;
  margin-bottom: 5px;
}

.main-container {
  display: flex;
  flex-grow: 1;
  flex-direction: column;
}

.bg-danger {
  background-color: red;
}

.bg-primary {
  background-color: blue;
}

.bg-success {
  background-color: green;
}
</style>
