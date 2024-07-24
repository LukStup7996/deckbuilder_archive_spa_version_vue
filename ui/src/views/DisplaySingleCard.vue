<script>
import { useCardApiStore } from "../stores/cardApiStore";
import { useRoute } from "vue-router";

export default {
  name: "DisplaySingleCard",
  data() {
    return {
      displayedCard: null,
      cardApi: useCardApiStore(),
    };
  },
  computed: {
    card() {
      // Da displayedCard null sein kann, stellen wir sicher, dass wir mit einem leeren Objekt arbeiten, um Fehler zu vermeiden.
      return this.displayedCard || {};
    },
  },
  methods: {
    async fetchCardData() {
      const cardId = this.route.params.cardId;
      console.log(cardId);
      await this.cardApi.fetchCardDetails(cardId);
      const cardDetails = this.cardApi.card.length
        ? this.cardApi.card[0]
        : null;
      console.log(cardDetails);

      if (cardDetails) {
        this.displayedCard = cardDetails;
        console.log(this.displayedCard);
      } else {
        console.error(`Card with ID ${cardId} not found.`);
      }
    },
  },
  mounted() {
    this.route = useRoute(); // Zugriff auf route sicherstellen
    this.fetchCardData();
  },
};
</script>

<template>
  <div v-if="card.cardId">
    <img
      :src="require(`../assets/img/${card.cardId}.jpg`)"
      alt="card image cap"
    />
    <div>
      <h5>{{ card.cardName }}</h5>
      <p>Set: {{ card.cardId }}</p>
      <p>Mana Value: {{ card.manaValue }}</p>
      <p>Super Type: {{ card.superType }}</p>
      <p>Card Type: {{ card.cardType }}</p>
      <p>Sub Type: {{ card.subType }}</p>
      <p>Cost: {{ card.cost }}</p>
    </div>
  </div>
  <div v-else>
    <p>Card not found.</p>
  </div>
</template>

<style scoped>
/* Scoped styles here */
</style>
