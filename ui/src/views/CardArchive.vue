<!-- eslint-disable vue/return-in-computed-property -->
<script>
import { useCardApiStore } from "../stores/cardApiStore";

export default {
  name: "card-archive",
  data() {
    return {
      cards: [],
      cardApi: useCardApiStore(),
      options: [
        { text: "Card Name", value: "card-name" },
        { text: "Super Type", value: "super-type" },
        { text: "Card Type", value: "card-type" },
        { text: "Sub Type", value: "sub-type" },
        { text: "Mana Value", value: "mana-value" },
        { text: "Casting Cost", value: "cmc" },
      ],
      selectedFilter: "card-name",
      inputValue: "",
    };
  },
  computed: {
    submitFilterOptions() {
      switch (this.selectedFilter) {
        case "card-name":
          this.filterCardNames(this.inputValue);
          break;
        case "super-type":
          this.filterSuperType(this.inputValue);
          break;
        case "card-type":
          this.filterCardType(this.inputValue);
          break;
        case "sub-type":
          this.filterSubType(this.inputValue);
          break;
        case "mana-value":
          this.filterByValue(this.inputValue);
          break;
        case "cmc":
          this.filterByCost(this.inputValue);
          break;
        default:
          this.filterAllCards();
          break;
      }
    },
  },
  methods: {
    filterAllCards() {
      this.cardApi.getAllCards().then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterCardNames(cardName) {
      this.cardApi.getCardsByName(cardName).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterSuperType(superType) {
      this.cardApi.getCardsBySuper(superType).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterCardType(cardType) {
      this.cardApi.getCardsByType(cardType).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterSubType(subType) {
      this.cardApi.getCardsBySub(subType).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterByValue(manaValue) {
      this.cardApi.getByValue(manaValue).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
    filterByCost(cmc) {
      this.cardApi.getByCost(cmc).then(() => {
        this.cards = this.cardApi.cards;
      });
    },
  },
};
</script>

<template>
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <button
        class="btn btn-outline-secondary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        Filter by:
      </button>
      <ul class="dropdown-menu">
        <li v-for="option in options" :key="option.value">
          <input
            type="radio"
            class="form-check-input"
            :id="option.value"
            name="filter-options"
            v-model="selectedFilter"
            :value="option.value"
          />
          <label class="form-check-label" :for="option.value">{{
            option.text
          }}</label>
        </li>
      </ul>
    </div>
    <p>{{ selectedFilter }}</p>
    <input
      type="text"
      class="form-control"
      v-model="inputValue"
      aria-label="Text input with dropdown button"
      id="submit-filter"
    />
    <button type="button" class="btn btn-primary" @click="submitFilterOptions">
      Search
    </button>
  </div>
  <div class="container-fluid">
    <div class="col">
      <div class="container sm-8" name="card display">
        <div class="card-group" style="width: 72rem">
          <div
            v-for="card in cards"
            :key="card.cardId"
            class="card"
            style="width: 18rem"
          >
            <img
              class="card-img-top"
              :src="require(`../assets/img/${card.cardId}.jpg`)"
              alt="card image cap"
            />
            <div class="card-body">
              <h5 class="card-title">{{ card.cardName }}</h5>
              <p class="card-text">Set: {{ card.cardId }}</p>
              <p class="card-text">Mana Value: {{ card.manaValue }}</p>
              <p class="card-text">Card Type: {{ card.cardType }}</p>
              <router-link
                :to="{
                  name: 'single-card-display',
                  params: { cardId: card.cardId },
                }"
              >
                <button class="btn btn-primary">Additional Info</button>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
